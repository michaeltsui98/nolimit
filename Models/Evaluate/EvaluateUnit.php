<?php

/**
 * Description of EvaluateUnit
 * 单元测评
 * @author libo
 */
class Models_Evaluate_EvaluateUnit extends Cola_Model
{

    protected $_pk = 'evaluate_unit_id';
    protected $_table = 'evaluate_unit';
    protected $_config = null;
    // protected $_evaluate_status = array(
    //     'not_do'=>0,
    //     'do' => 1,
    //     'finish' =>2,
    //     'end' => 3
    // );

    public function __construct()
    {
        $this->_config = Models_StudyResource_StudyRecordConfig::init()->config();
    }

    /**
     * 添加单元测评
     * @param string $evaluate_unit_user_id 添加测评者id
     * @param int $evaluate_class_id 班级id
     * @param int $evaluate_parent_id 教材单元id
     * @param int $evaluate_son_id 教材单元的子节点id
     * @param string $id 测评id(主键id) 多个用逗号隔开
     * @param int $evaluate_end_time 结束时间 时间戳的方式
     * @param string $evaluate_subject_id 科目
     * @param int $evaluate_course_id 班级课程id
     * @param tring $evaluate_book_version  教材版本
     */
    public function addUnitEvaluate($evaluate_unit_user_id, $evaluate_class_id,$evaluate_son_id, $id, $evaluate_end_time, $evaluate_subject_id, $evaluate_course_id, $evaluate_book_version)
    {
        if (empty($evaluate_unit_user_id) || empty($evaluate_class_id)  || empty($evaluate_son_id) || empty($id)) {
            return false;
        }
        $id_arr = explode(',', $id);
        foreach ($id_arr as $ids) {
           $count = $this->isOrNOtHasUnitEvaluate($ids,$evaluate_course_id);
            if(empty($count)){
                 $data = array(
                'evaluate_unit_user_id' => $evaluate_unit_user_id,
                'evaluate_class_id' => $evaluate_class_id,
                'evaluate_son_id' => $evaluate_son_id,
                'id' => $ids,
                'evaluate_end_time' => $evaluate_end_time,
                'evaluate_subject_id' => $evaluate_subject_id,
                'time' => time(),
                'evaluate_course_id' => $evaluate_course_id,
                'evaluate_book_version' => $evaluate_book_version
            );
            $status = $this->insert($data, $this->_table);

          }
        }
        return isset($status)? $status :NULL;
    }

    /**
     * 删除单条数据
     * @param int $evaluate_unit_id 主键id
     */
    public function delEvaluateById($evaluate_unit_id)
    {
        if (empty($evaluate_unit_id)) {
            return false;
        }
        return $this->delete($evaluate_unit_id);
    }

    /**
     * 班级单元测评的统计
     * @param int $evaluate_class_id 班级id
     * @param string $evaluate_subject_id 科目
     */
    public function evaluateUnitCount($evaluate_class_id, $evaluate_subject_id)
    {
        if (empty($evaluate_class_id)) {
            return false;
        }
        return $this->count("`evaluate_class_id` = {$evaluate_class_id} and `evaluate_subject_id` = '{$evaluate_subject_id}'", $this->_table);
    }

    /**
     * 获取单元知识节点的测评列表
     * @param int $evaluate_son_id 教材子节点id
     * @param string $evaluate_subject_id 科目
     * @param int $start
     * @param int $limit
     */
    public function evaluateUnitList($evaluate_son_id, $evaluate_subject_id, $start, $limit)
    {
        $where = "`evaluate_son_id` = {$evaluate_son_id} and `evaluate_subject_id` = '{$evaluate_subject_id}'";
        $sql = "select * from {$this->_table} where $where limit $start,$limit";
        $count = $this->count($where, $this->_table);
        $data = $this->sql($sql);
        return array('count' => $count, 'data' => $data);
    }

    /**
     *  获取某个班级某个知识节点的测评列表(后台用的)
     * @param int $evaluate_son_id 知识节点
     * @param int $evaluate_class_id 班级id
     * @param string $evaluate_subject_id 科目id
     */
    public function getClassEvaluateUnit($evaluate_son_id, $evaluate_class_id, $evaluate_subject_id)
    {
        $sql = "select `evaluate_id`,`evaluate_title` from evaluate where `id` in (select `id` from evaluate_unit where `evaluate_son_id` = {$evaluate_son_id}
            and `evaluate_class_id` = {$evaluate_class_id} and `evaluate_subject_id` = '{$evaluate_subject_id}')";
        return $this->sql($sql);
    }

    /**
     *  获取某班级某知识节点的测评列表
     * @param int $evaluate_son_id 知识节点
     * @param int $evaluate_class_id 班级id
     * @param string $evaluate_subject_id 科目
     * @param int $start
     * @param int $limit
     */
    public function getClassEvaluateUnitList($evaluate_son_id, $evaluate_class_id, $evaluate_subject_id, $start, $limit)
    {
        $where = "`evaluate_son_id` = {$evaluate_son_id} and `evaluate_class_id` = {$evaluate_class_id} and `evaluate_subject_id` = '{$evaluate_subject_id}'";
        $sql = "select * from {$this->_table} where $where limit $start,$limit";
        $data = $this->sql($sql);
        $count = $this->count($where, $this->_table);
        if (!empty($count)) {
            foreach ($data as $k => $v) {
                $evaluate_info = Models_Evaluate_Evaluate::init()->getEvaluateById($v['id']);
                $data[$k]['evaluate_id'] = $evaluate_info['evaluate_id'];
                $data[$k]['evaluate_title'] = $evaluate_info['evaluate_title'];
            }
        }
        return array('count' => $count, 'data' => $data);
    }

    /**
     *  获取班级总的测评列表
     * @param string $xk 学科
     * @param int $class_id 班级id
     * @param string $bb 版本
     * @param int $start
     * @param int $limit
     * @return array
     */
    public function getUserUnitEvaluateListByClassify($class_id, $xk, $bb, $start, $limit)
    {
        $where = "`evaluate_class_id` = {$class_id} and `evaluate_subject_id` = '{$xk}'  and `evaluate_book_version` = '{$bb}'";
        $sql = "select * from {$this->_table} where $where limit $start,$limit";
        $count = $this->count($where, $this->_table);
        $data = $this->sql($sql);
        if (!empty($data)) {
            foreach ($data as $key => $v) {
                $data[$key]['evaluate_count'] = Models_Evaluate_EvaluateRecord::init()->getAllEvaluateCountById($v['id'],$v['evaluate_course_id']);
                //判断当前测评用户是否已完成
                $info =  Models_Evaluate_EvaluateRecord::init()->getUserEvaluateInfoById($v['id'], $_SESSION['user']['user_id'],$v['evaluate_course_id']);
                if(empty($info)){
                    $data[$key]['evaluate_status'] = $this->_config['_evaluate_status']['not_do'];
                }else{
                     if($info[0]['evaluate_record_status'] !=$this->_config['_evaluate_status']['finish']){
                    $data[$key]['evaluate_status'] = (empty($v['evaluate_end_time'])) ? $this->_config['_evaluate_status']['do'] : (time() < $v['evaluate_end_time'] ? $this->_config['_evaluate_status']['do'] : $this->_config['_evaluate_status']['end']);
                }else{
                    $data[$key]['evaluate_status'] = $this->_config['_evaluate_status']['finish'];
                }
            }
                //测评的标题等信息
                $evaluate = Models_Evaluate_Evaluate::init()->getPartFieldsById($v['id'], 'evaluate_title,evaluate_id,evaluate_type');
                $data[$key]['evaluate_title'] = $evaluate[0]['evaluate_title'];
                $data[$key]['evaluate_id'] = $evaluate[0]['evaluate_id'];
                $data[$key]['evaluate_end_time'] = empty($v['evaluate_end_time']) ? 0 : date("Y-m-d", $v['evaluate_end_time']);
                $data[$key]['evaluate_type'] = $evaluate[0]['evaluate_type'];
            }
        }
        return array('count' => $count, 'data' => $data);
    }
    
    /**
     * 获取 某个班级的某个知识节点的课程是否添加了这条测评
     * @param int $evaluate_course_id 课程id
     * @param int $id 关联id
    
     */
    public function isOrNOtHasUnitEvaluate($id,$evaluate_course_id)
    {
        return $this->count(" `id` = $id and `evaluate_course_id` = $evaluate_course_id ", $this->_table);
    }

    /**
     * 知识节点测评的统计
     * @param int $evaluate_son_id 知识节点
     */
    public function isOrNotHasUnitEvaluateByNodeId($evaluate_son_id, $evaluate_subject_id)
    {
        $data = $this->sql("select distinct `id` from {$this->_table} where  `evaluate_son_id` = $evaluate_son_id and `evaluate_subject_id` = '{$evaluate_subject_id}'");
        return count($data);
    }

    /**
     * 判断当前知识节点的某条测评是否过期
     * @param  int $evaluate_id 测评id
     * @param  int $course_id 课程id
     */
    public function evaluateIsOrNotDueByEvaluateId($evaluate_id,$course_id)
    {
        $evaluate = $this->sql("select `id` from evaluate where `evaluate_id` = {$evaluate_id}");
        return $this->sql("select `evaluate_end_time` from {$this->_table} where `id` = {$evaluate[0]['id']} and `evaluate_course_id` = $course_id");
    }
    /**
     * 获取单元测评某条测评的信息
     * @param  int $id  关联id
     * @param  int $course_id 课程id
     * @param  string $field 字段
     * @return array
     */
    public function getUnitEvaluateInfo($id,$field = "*",$course_id)
    {
        return $this->sql("select $field from {$this->_table} where `id` = $id and `evaluate_course_id` = $course_id");
    }
    /**
     * 通过课程id获取单元测评列表
     * @param  int  $course_id 课程id
     * @param  string  $xk 学科
     * @param  string  $bb 版本
     * @param  int     $class_id
     * @return array
     */
    public function getUnitEvaluateListByCourseId($course_id,$xk, $bb,$class_id,$start,$limit)
    {
        $where = "`evaluate_course_id` = $course_id and `evaluate_subject_id` = '{$xk}' and `evaluate_book_version` = '{$bb}'";
        $sql = "select * from {$this->_table} where $where limit $start,$limit";
        $count = $this->count($where,$this->_table);
        $data = $this->sql($sql);
        if(!empty($data)){
            foreach($data as $key =>$v){
                //测评的标题等信息
                $evaluate = Models_Evaluate_Evaluate::init()->getPartFieldsById($v['id'], 'evaluate_title,evaluate_id');
                $data[$key]['evaluate_title'] = $evaluate[0]['evaluate_title'];
                $data[$key]['evaluate_id'] = $evaluate[0]['evaluate_id'];
                //获取测评的状态和当前班级参加的人数
                $data[$key]['evaluate_count'] = Models_Evaluate_EvaluateRecord::init()->getAllEvaluateCountById($v['id'],$course_id);
                $info = Models_Evaluate_EvaluateRecord::init()->getCurEvaluateInfo($_SESSION['user_id']['user_id'], $v['id'],$course_id);
                $data[$key]['evaluate_status'] = (empty($info) || $info[0]['evaluate_record_status'] == 1)?0:1; // 0:未完成,1:完成
            }
        }
        return array('count' =>$count,'data'=>$data);
    }
}
