<?php

/**
 * Description of EvaluateRecord
 * 测评记录
 * @author libo
 */
class Models_Evaluate_EvaluateRecord extends Cola_Model
{

    protected $_pk = 'evaluate_record_id';
    protected $_table = 'evaluate_record';
    //测评结果
    // protected static $_evaluate_record_status = array(
    //     1 => '未完成',
    //     2 => '已完成',
    //     3 => '已过期'
    // );

    public function __construct()
    {

    }

    /**
     * 添加测评记录
     * @param array $data
     */
    public function addEvaluateRecord($data)
    {
        if (empty($data)) {
            return false;
        }
        //判断当前记录是否存在
        $count = $this->isOrNotEvaluateRecord($data['id'], $data['evaluate_record_user_id'],$data['evaluate_course_id']);
        if (empty($count)) {
            return $this->insert($data, $this->_table);
        } else {
            //判断记录表里面的状态是2(已完成),如果是已完成就不往表里面写
            $info = $this->getUserEvaluateInfoById($data['id'], $data['evaluate_record_user_id'],$data['evaluate_course_id']);
            if ($info[0]['evaluate_record_status'] == 2) {
                return false;
            }
            return $this->updateData($data['id'],$data['evaluate_course_id'], $data['evaluate_record_end_time'], $data['evaluate_record_score'], $data['evaluate_record_user_id'],$data['evaluate_record_status']);
        }
    }

    /**
     * 更新数据操作
     * @param int $id 关联id
     * @param int $course_id 课程id
     * @param string $evaluate_record_user_id 测评用户id
     * @param int $evaluate_record_end_time 测评结束时间
     * @param string $evaluate_record_score 测评的成绩
     * @param int $evaluate_record_status 状态
     */
    public function updateData($id,$course_id, $evaluate_record_end_time, $evaluate_record_score, $evaluate_record_user_id,$evaluate_record_status)
    {
        $sql = "update {$this->_table} set `evaluate_record_end_time` = {$evaluate_record_end_time},`evaluate_record_score` = '{$evaluate_record_score}',
         `evaluate_record_status` = $evaluate_record_status where `id` = $id and `evaluate_course_id` = $course_id  and  `evaluate_record_user_id` = '{$evaluate_record_user_id}'";
        return $this->sql($sql);
    }

    /**
     * 判断当前用户是否存在这条记录
     * @param int $id 关联id
     * @param int $course_id  课程id
     * @param string $evaluate_record_user_id  当前登录的用户id
     */
    public function isOrNotEvaluateRecord($id, $evaluate_record_user_id,$course_id = 0)
    {
        return $this->count("`id` = {$id} and `evaluate_record_user_id` = '{$evaluate_record_user_id}' and `evaluate_course_id` = $course_id ", $this->_table);
    }

    /**
     *  获取当前用户某个测评的状态
     * @param int $evaluate_id 关联id
     * @param int $course_id 课程id
     * @param string $evaluate_user_id 用户id
     */
    public function getUserEvaluateInfoById($id, $evaluate_user_id,$course_id = 0)
    {
        $sql = "select `evaluate_record_status` from {$this->_table} where `id` = $id and `evaluate_record_user_id` = '{$evaluate_user_id}' and `evaluate_course_id` = $course_id";
        return $this->sql($sql);
    }

    /**
     * 获取学生不同测评记录状态的数据
     * @param  string $nj 年级
     * @param int $evaluate_record_status 测评状态
     * @param string $evaluate_user_id 测评用户id
     * @param string $bb 测评版本
     * @param string $xk 科目分类
     * @param string $xd 学段
     * @param int $evaluate_classify * 所有, 1:综合 2：单元测试
     * @param int $start
     * @param int $limt
     */
    public function getDiffentClassifyRecordList($nj, $evaluate_record_status, $evaluate_user_id, $bb, $xd , $xk, $evaluate_classify,$class_id, $start, $limit)
     { 
        //未做的
        if(empty($evaluate_record_status)){
             return $this->notDoEvaluateList($nj,$class_id,$evaluate_user_id, $bb, $xd, $xk, $evaluate_classify, $start, $limit);
        }

          $wheres = '';
          $where = "`evaluate_record_status` = $evaluate_record_status and `evaluate_record_user_id` = '{$evaluate_user_id}'";
           if ($evaluate_classify != '*') {
             $wheres = " and `evaluate_classify` = $evaluate_classify ";
          } 
          
        $sql = "select `id` from evaluate where `evaluate_grade` = '{$nj}' and `evaluate_version` = '{$bb}' and `evaluate_field` = '{$xd}' and `evaluate_type_id` = '{$xk}' $wheres ";
        $evalaute_arr = $this->sql($sql);

        $ids = '';
        if(!empty($evalaute_arr)){
            foreach ($evalaute_arr as $k =>$v){
                $ids .= $v['id'] .','; 
            }
            $ids = trim($ids,','); 
            $evaluate_record_sql = "select * from {$this->_table} where id in ($ids) and $where  $wheres limit $start, $limit ";
            $evalaute_reocrd_arr = $this->sql($evaluate_record_sql);

            if(!empty($evalaute_reocrd_arr)){
                foreach ($evalaute_reocrd_arr as $key =>$value){
                     //获取测评的标题
                    $info = Models_Evaluate_Evaluate::init()->getPartFieldsById($value['id'], 'evaluate_title,time');
                    $evalaute_reocrd_arr[$key]['evaluate_title'] = $info[0]['evaluate_title'];
                    $evalaute_reocrd_arr[$key]['time'] = date("Y.m.d",$info[0]['time']);
                }
                return $evalaute_reocrd_arr;
            }
        }
        return array();
    }
    
    /**
     * 未做的测评列表
     * @param  string  $nj               年级id
     * @param  string $class_id          班级id
     * @param  string $evaluate_user_id  用户id
     * @param  string $bb                版本 
     * @param  string $xk                学科
     * @param  string $xd                学段
     * @param  int $evaluate_classify    * 所有, 1:综合 2：单元测试
     * @param  int $start             
     * @param  int $limit             
     * @return array                    
     */
    public function notDoEvaluateList($nj,$class_id,$evaluate_user_id, $bb, $xd, $xk, $evaluate_classify, $start, $limit)
    {

        $config = Models_StudyResource_StudyRecordConfig::init()->config();
        //综合测评
        $com_evaluate_sql = "select * from evaluate where `evaluate_grade` = '{$nj}' and `evaluate_version` = '{$bb}' and `evaluate_field` = '{$xd}' and `evaluate_type_id` = '{$xk}' and `evaluate_classify` = {$config['_evaluate_classify']['com']} ";
        $com_evaluate_arr = $this->sql($com_evaluate_sql);
        if(!empty($com_evaluate_arr)){
            foreach($com_evaluate_arr as $key => $info){
                //检测是否有测评记录
                $count = $this->isOrNotEvaluateRecord($info['id'], $evaluate_user_id,0);
                if(!empty($count)){
                    unset($com_evaluate_arr[$key]);
                }
            }

        }
       //单元测评
       $unit_evaluate_sql = "select `id`,`evaluate_course_id` from evaluate_unit where `evaluate_subject_id` = '{$xk}' 
       and `evaluate_class_id` = {$class_id} and `evaluate_book_version` = '{$bb}'";
       $unit_evaluate_arr = $this->sql($unit_evaluate_sql);
       if(!empty($unit_evaluate_arr)){
          foreach($unit_evaluate_arr as $k =>$v){
             // 检测是否有测评记录
             $count = $this->isOrNotEvaluateRecord($v['id'], $evaluate_user_id,$v['evaluate_course_id']);
             if(!empty($count)){
                unset($unit_evaluate_arr[$k]);
             }else{
                //获取这条数据的信息
                $info = Models_Evaluate_Evaluate::init()->getEvaluateById($v['id']);
                $unit_evaluate_arr[$k] = $info; 
             }
          }

       }

       if($evaluate_classify == '*'){
          $data = array_merge($com_evaluate_arr,$unit_evaluate_arr);
       }else if($evaluate_classify == $config['_evaluate_classify']['com']){
           $data = $com_evaluate_arr;
       }else{
           $data = $unit_evaluate_arr;
       }
        $all_evaluate = array();
        $part_evaluate = array();
       if(empty($data)){
        return array();
       }else{
          foreach ($data as $v){
            $all_evaluate[] = $v;
          }
          
          foreach ($all_evaluate as $k =>$value){
             if( $k> $start && $k < ($start+$limit) -1){
                $part_evaluate[] = $value;
             }
          }
          return $part_evaluate;
       }
    }

    /**
     *  某条测评不同状态的测评统计
     * @param int  $id 测评关联id
     * @param int  $course_id 课程id
     */
    public function getEvalauteRecordCountById($id, $evaluate_record_status,$course_id = 0)
    {
        return $this->count("`id` = $id and `evaluate_record_status` = $evaluate_record_status", $this->_table);
    }

    /**
     * 获取参加测评的总人数
     * @param  int $id 测评关联id
     * @param  int $course_id 课程id
     * @return int
     */
    public function getAllEvaluateCountById($id,$course_id = 0)
    {
        return $this->count("`id`={$id} and `evaluate_course_id` = $course_id ", $this->_table);
    }

    /**
     *  不同科目参与测评的统计
     * @param string $evaluate_record_user_id  参与测评者id
     * @param string $evalaute_type_id 科目
     * return int
     */
    public function getEvalauteRecordCount($evaluate_record_user_id, $evalaute_type_id)
    {
        $where = "`evaluate_record_user_id` = '{$evaluate_record_user_id}'";
        $data = $this->sql("select `id` from {$this->_table} where $where");
        $id = '';
        if (!empty($data)) {
            foreach ($data as $ids) {
                $id .= $ids['id'] . ',';
            }
            $id = rtrim($id, ',');
            $count = $this->count(" `id` in ($id) and `evaluate_type_id` = '{$evalaute_type_id}'", 'evaluate');
        } else {
            $count = 0;
        }
        return $count;
    }

    /**
     * 获取当前登录用户某条测评的历史记录
     * @param string $user_id 当前登录的用户id
     * @param int $id
     * @param  int $course_id 课程id
     * @return array
     */
    public function getCurEvaluateInfo($user_id, $id,$course_id = 0)
    {
         return  $this->sql("select * from {$this->_table} where `evaluate_record_user_id` = '{$user_id}'and `id` = $id and `evaluate_course_id` = $course_id");

    }
   
}
