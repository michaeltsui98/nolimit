<?php

/**
 * Description of Evaluate
 * 测评管理类
 * @author libo
 */
class Models_Evaluate_Evaluate extends Cola_Model
{

    protected $_pk = 'id';
    protected $_table = 'evaluate';
    protected $_config = '';
    protected $_evaluate_unit = '';

    public function __construct()
    {
        $this->_evaluate_unit = Models_Evaluate_EvaluateUnit::init();
        $this->_config = Models_StudyResource_StudyRecordConfig::init()->config();
    }

    /**
     * 添加测评试卷
     * @param array $data
     */
    public function addEvaluate($data)
    {
        if (empty($data)) {
            return false;
        }
        //判断这条测评是否已经存在
        $count = $this->countEvaluate($data['evaluate_field'], $data['evaluate_version'], $data['evaluate_grade'], $data['evaluate_id'], $data['evaluate_classify'], $data['evaluate_type'], $data['evaluate_type_id']);
        if (!$count) {
            return $this->insert($data, $this->_table);
        }
        return true;
    }

    /**
     *  测评的统计
     * @param  int $evaluate_id  试卷id
     * @param  int $evaluate_type 0:内部,1:外部
     * @param  string $evaluate_type_id 学科
     * @return int
     */
    public function countEvaluate($xd, $bb, $nj, $evaluate_id, $evaluate_classify, $evaluate_type, $evaluate_type_id)
    {
        return $this->count("`evaluate_field` = '{$xd}' and `evaluate_version` = '{$bb}' and `evaluate_grade` = '{$nj}' and `evaluate_classify` = {$evaluate_classify} and  `evaluate_id` = {$evaluate_id} and `evaluate_type` = {$evaluate_type} and `evaluate_type_id` = '{$evaluate_type_id}' ", $this->_table);
    }

    /**
     *  编辑测评
     * @param int $id 主键id
     * @param  int $evaluate_end_time 测评结束时间
     * @return bool
     */
    public function editEvaluate($id, $evaluate_end_time)
    {
        if (empty($id)) {
            return false;
        }
        $arr = array('evaluate_end_time' => $evaluate_end_time);
        return $this->update($id, $arr);
    }

    /**
     * 取消测评
     * @param int $id 主键id
     * return bool
     */
    public function deleteEvaluate($id)
    {
        if (empty($id)) {
            return false;
        }
        return $this->delete($id);
    }

    /**
     * 获取单条数据
     * @param int $id 主键id
     */
    public function getEvaluateById($id)
    {
        if (empty($id)) {
            return false;
        }
        return $this->load($id);
    }

    /**
     * 获取某些字段的信息
     * @param int $id 主键id
     */
    public function getPartFieldsById($id, $field)
    {
        return $this->sql("select $field from {$this->_table} where `id` = $id");
    }

    /**
     * 判断教材的节点是否有测评
     * @param string $evaluate_son_id 知识节点的子节点
     */
    public function isOrNotEvaluateByNodeNode($evaluate_son_id)
    {
        return $this->count("`evaluate_son_id` = '{$evaluate_son_id}'");
    }

    /**
     * 获取测评列表(综合测评和单元测评)
     * @param string $evaluate_type_id 科目
     * @param int $evaluate_son_id 知识节点id
     * @param string $xd 学段
     * @param string $bb 版本
     * @param string $nj 年级
     * @param int $start
     * @param int $limit
     */
    public function getEvaluateListByClassify($evaluate_type_id, $evaluate_classify, $evaluate_son_id = 0, $evaluate_course_id = 0, $start, $limit, $xd = 0, $bb = 0, $nj = 0)
    {
        if (empty($evaluate_type_id) || empty($evaluate_classify)) {
            return false;
        }
        $evaluate_classify_arr = explode(",", $evaluate_classify);
        if ($evaluate_classify_arr[0] == $this->_config['_evaluate_classify']['com']) {
            if (!empty($xd) && !empty($bb) && !empty($nj)) {
                $where = "`evaluate_classify` in ($evaluate_classify)  and `evaluate_type_id` = '{$evaluate_type_id}' and `evaluate_field` = '{$xd}' and `evaluate_version` = '{$bb}' and `evaluate_grade` = '{$nj}'";
            } else {

                $where = "`evaluate_classify` in ($evaluate_classify)  and `evaluate_type_id` = '{$evaluate_type_id}'";
            }
        } else {
            if (!empty($evaluate_course_id)) {
                //获取某课程选择的测评id
                $sql = "select `id` from evaluate_unit where `evaluate_course_id` = {$evaluate_course_id} ";
                $evaluate_unit_arr = $this->sql($sql);
                $id = '';
                if (!empty($evaluate_unit_arr)) {
                    foreach ($evaluate_unit_arr as $k => $v) {
                        $id .= $v['id'] . ',';
                    }
                    $id = trim($id, ',');
                    $where = "`evaluate_classify` = " . $evaluate_classify . " and `evaluate_type_id` = '{$evaluate_type_id}' and `evaluate_son_id` = $evaluate_son_id  and `id` not in ($id)";
                } else {
                    $where = "`evaluate_classify` = " . $evaluate_classify . " and `evaluate_type_id` = '{$evaluate_type_id}' and `evaluate_son_id` = $evaluate_son_id ";
                }
            } else {
                $where = "`evaluate_classify` = " . $evaluate_classify . " and `evaluate_type_id` = '{$evaluate_type_id}' and `evaluate_son_id` = $evaluate_son_id ";
            }
        }

        $sql = "select * from {$this->_table} where $where";

        return $this->getListBySql($sql, $start, $limit);
    }

    /**
     * 获取不同分类的测评数据(综合和单元,前台取单元的不能调用这个接口)
     * @param string $evaluate_type_id 科目分类
     * @param int $evaluate_classify 测评分类
     * @param string $evaluate_field 学段
     * @param string $evaluate_grade 年级
     * @param string $evaluate_version 测评版本
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getUserEvaluateListByClassify($evaluate_type_id, $evaluate_classify, $evaluate_field, $evaluate_grade, $evaluate_version, $start, $limit)
    {
        $where = "`evaluate_type_id` = '{$evaluate_type_id}' and `evaluate_classify` = $evaluate_classify and `evaluate_version` = '{$evaluate_version}'
         and `evaluate_field` = '{$evaluate_field}' and `evaluate_grade` = '{$evaluate_grade}'";
        $sql = "select * from {$this->_table} where $where limit $start,$limit";
        $data = $this->sql($sql);
        $count = $this->count($where, $this->_table);
        if (!empty($count)) {
            foreach ($data as $key => $v) {
                $date_arr = explode("/", $v['evaluate_end_time']);
                $data[$key]['evaluate_end_time'] = empty($v['evaluate_end_time']) ? 0 : $date_arr[2] . "-" . $date_arr[0] . "-" . $date_arr[1];
                $data[$key]['evaluate_count'] = Models_Evaluate_EvaluateRecord::init()->getAllEvaluateCountById($v['id']);
                $time = Models_StudyResource_StudyRecordConfig::init()->dateToTime($v['evaluate_end_time']);
                //判断当前测评用户是否已完成
                $info = Models_Evaluate_EvaluateRecord::init()->getUserEvaluateInfoById($v['id'], $_SESSION['user']['user_id']);
                if (empty($info)) {
                    //如果为空还要检测是否过期了
                    $end_time = Models_StudyResource_StudyRecordConfig::init()->dateToTime($v['evaluate_end_time']);
                    if (empty($end_time)) {
                        $data[$key]['evaluate_status'] = $this->_config['_evaluate_status']['not_do'];
                    } else {
                        $data[$key]['evaluate_status'] = (time() < $end_time) ? $this->_config['_evaluate_status']['not_do'] : $this->_config['_evaluate_status']['end'];
                    }
                } else {
                    if ($info[0]['evaluate_record_status'] != $this->_config['_evaluate_status']['finish']) {
                        $data[$key]['evaluate_status'] = (empty($time)) ? $this->_config['_evaluate_status']['do'] : (time() < $time ? $this->_config['_evaluate_status']['do'] : $this->_config['_evaluate_status']['end']);
                    } else {
                        $data[$key]['evaluate_status'] = $this->_config['_evaluate_status']['finish'];
                    }
                }
            }
        }
        return array('count' => $count, 'data' => $data);
    }

    /**
     * 批量获取测评列表
     * @param string $ids 批量的主键id
     * @param  string $nj 年级
     * @param string $evaluate_version 版本
     * @param int $start
     * @param int $limit
     */
    // public function batchGetEvaluateList($ids,$nj, $evaluate_version, $start, $limit)
    // {
    //     $where = "`id` in ($ids) and `evaluate_version` = '{$evaluate_version}' and `evaluate_grade` = '{$nj}'";
    //     $sql = "select `evaluate_title`,`id`,`time` from {$this->_table} where $where  limit $start,$limit";
    //     $count = $this->count($where, $this->_table);
    //     $data = $this->sql($sql);
    //     return array('count' => $count, 'data' => $data);
    // }

    /**
     * 获取某条综合测评的结束时间
     * @param  int $evaluate_id
     * @return string
     */
    public function getEvaluateEndTimeByEvaluateId($evaluate_id)
    {
        $sql = "select `evaluate_end_time` from  evaluate where `evaluate_id` = $evaluate_id";
        $data = $this->sql($sql);
        return $data[0]['evaluate_end_time']; //date格式
    }

    /**
     *  推荐的测评列表
     * @param  string $xk    学科
     * @param   int   $limit
     * @return  array
     */
    public function recommendEvaluate($xk, $limit)
    {
        $sql = "select * from {$this->_table} where `evaluate_type_id` = '{$xk}' order by time desc limit 0,$limit";
        return $this->sql($sql);
    }

}
