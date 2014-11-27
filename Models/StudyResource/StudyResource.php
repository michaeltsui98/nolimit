<?php

/**
 * Description of StudyResource
 *  微视频,电子教材类
 * @author libo
 */
class Models_StudyResource_StudyResource extends Cola_Model
{

    protected $_table = 'study_resource';
    protected $_pk = 'resource_id';

    /**
     * 添加学习资源(电子教材,微视频等)
     * @param array $data
     * return bool
     */
    public function addStudyResource($data)
    {
        if (empty($data)) {
            return false;
        }
        $count = $this->issetStudyResourceId($data['study_resource_id'], $data['study_grade_node_node'], $data['study_resource_subject_type']);
        if (empty($count)) {
            return $this->insert($data, $this->_table);
        } else {
            return 0;
        }
    }

    /**
     * 删除学习资源(电子教材,微视频等)
     * @param int $id 主键id
     */
    public function deleteStudyResourceById($id)
    {
        if (empty($id)) {
            return false;
        }
        return $this->delete($id);
    }

    /**
     * 获取学生不同科目对应的年级,教材版本,知识节点,课程的学习资源列表
     * @param string $study_grade_node_node 知识节点id
     * @param string $study_resource_type_id 资源类型
     * @param string $study_resource_subject_type 科目
     */
    public function getAllStudyResource($study_grade_node_node, $study_resource_type_id, $study_resource_subject_type)
    {
        if (empty($study_grade_node_node) || empty($study_resource_type_id) || empty($study_resource_subject_type)) {
            return false;
        }
        $where = "  `study_grade_node_node` = '{$study_grade_node_node}' and `study_resource_type_id` = '{$study_resource_type_id}' and `study_resource_subject_type` = '{$study_resource_subject_type}'";
        $sql = "select * from {$this->_table} where  $where";
        $count = $this->count($where, $this->_table);
        $data = $this->sql($sql);
        return array('count' => $count, 'data' => $data);
    }

    /**
     *
     * @param string $study_resource_subject_type 科目
     * @param int  $study_resource_type_id 资源分类
     * @param int $study_grade_node_node 知识节点id
     * @param int $start
     * @param int $limit
     * @return type
     */
    public function getAllSelectStudyResource($study_resource_subject_type, $study_resource_type_id, $study_grade_node_node, $start, $limit)
    {
        $where = "`study_resource_subject_type` = '{$study_resource_subject_type}' and `study_resource_type_id` = {$study_resource_type_id}  and `study_grade_node_node` = {$study_grade_node_node}";
        $sql = "select * from {$this->_table} where $where";
        return $this->getListBySql($sql, $start, $limit);
    }

    /**
     * 获取学生不同科目对应年级教材版本,知识节点的学习类型列表
     * @param string $study_grade_node_node 知识节点
     * @param string $study_resource_subject_type 科目
     */
    public function getStudyResourceType($study_grade_node_node, $study_resource_subject_type)
    {
        if (empty($study_grade_node_node) || empty($study_resource_subject_type)) {
            return false;
        }
        $where = " `study_grade_node_node` = {$study_grade_node_node} and `study_resource_subject_type` = '{$study_resource_subject_type}'";
        $sql = "select distinct `study_resource_type_id` from {$this->_table} where $where";
        return $this->sql($sql);
    }

    /**
     * 获取单条学习资源信息
     * @param int $id 主键id
     * return array
     */
    public function getStudyResourceByResourceId($id)
    {
        if (empty($id)) {
            return false;
        }
        return $this->sql("select * from {$this->_table} where `resource_id` = $id");
    }

    /**
     * 获取单条学习资源信息
     * @param int $study_resource_id  学习资源id
     * return array
     */
    public function getStudyResourceByStudyResourceId($study_resource_id)
    {
        if (empty($study_resource_id)) {
            return false;
        }
        return $this->sql("select * from {$this->_table} where `study_resource_id` = $study_resource_id ");
    }

    /**
     *  判断当前这条资源记录是否存在
     * @param int $study_resource_id  资源id
     * @param int $study_grade_node_node 节点id
     * @param string $xk 学科
     */
    public function issetStudyResourceId($study_resource_id, $study_grade_node_node = 0, $xk)
    {
        if (empty($study_resource_id)) {
            return false;
        }
        return $this->count("`study_resource_id`= $study_resource_id and `study_grade_node_node` = $study_grade_node_node  and `study_resource_subject_type` = '{$xk}'");
    }

    /**
     * 判断当前这条资源记录是否存在
     * @param int $study_resource_id  资源id
     * return int
     */
    public function countByResourceId($study_resource_id)
    {
        if (empty($study_resource_id)) {
            return false;
        }
        return $this->count("`study_resource_id`= $study_resource_id ");
    }

    /**
     * 判断用户是否有这条学习资源
     * @param int $study_resource_id 资源id
     * @param string $study_resource_subject_type 科目id
     * @param string $study_resource_field 学段
     * @param string $study_resource_grade 年级
     */
    public function userIssetResource($study_resource_id, $study_resource_subject_type, $study_resource_field, $study_resource_grade)
    {
        $where = "`study_resource_id` = {$study_resource_id} and `study_resource_subject_type` = '{$study_resource_subject_type}' and `study_resource_field` = '{$study_resource_field}' and  `study_resource_grade` = '{$study_resource_grade}'";
        return $this->count($where, $this->_table);
    }

    /**
     * 获取第一个学习的资源
     * @param int $study_grade_node_node 知识节点id
     * @param string $study_resource_subject_type 科目
     * @param int $node_id 年级的主键id
     */
    public function getFirstOneEvaluate($study_grade_node_node, $study_resource_subject_type, $node_id)
    {
        //获取某个年级的测评类型的主键id,$node_id:年级id
        $info = Models_StudyResource_StudyType::init()->getStudyTypeIdByGradeNodeId($node_id);
        $id = '';
        if (empty($info)) {
            $where = " `study_grade_node_node` = $study_grade_node_node  and `study_resource_subject_type` = '{$study_resource_subject_type}'";
        } else {
            foreach ($info as $v) {
                $id .= $v['id'] . ',';
            }
            $id = rtrim($id, ',');
            $where = " `study_grade_node_node` = $study_grade_node_node  and `study_resource_subject_type` = '{$study_resource_subject_type}' and `study_resource_type_id` not in ($id)";
        }
        return $this->sql("select * from {$this->_table} where $where limit 0,1");
    }

    /**
     * 获取某个知识节点不同类型的学习资源列表
     * @param  int $study_grade_node_node 知识节点id
     * @param string $study_resource_subject_type 科目
     * @param int $study_resource_type_id  学习资源类型id
     * return array
     */
    public function getDifferentResourceTypeList($study_grade_node_node, $study_resource_subject_type, $study_resource_type_id)
    {
        $where = " `study_grade_node_node` = $study_grade_node_node  and `study_resource_subject_type` = '{$study_resource_subject_type}' and `study_resource_type_id` = {$study_resource_type_id}";
        return $this->sql("select * from {$this->_table} where $where ");
    }

    /**
     * 推荐的学习资源(测评作业不推荐)
     * @param  string $xk 学科
     * @param  int $limit
     * @return array
     */
    public function recommendStudyResource($xk, $limit)
    {
        $sql = "select * from {$this->_table} where `study_resource_subject_type` = '{$xk}' and `study_resource_type_id` != 100 order by time desc limit 0,$limit";
        return $this->sql($sql);
    }

}
