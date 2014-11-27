<?php

/**
 * Description of StudyRecord
 * 微视频,电子教材的学习记录表
 * @author libo
 */
class Models_StudyResource_StudyRecord extends Cola_Model
{
    protected $_db = '_db';
    protected $_table = 'study_record';
    protected $_pk = 'study_record_id';
    protected $_study_record_config = array(
        1 => '未学完',
        2 => '学习完'
    );
    //展示图标的个数
    protected $_study_pic_number = 5;

    public function __construct()
    {

    }

    /**
     * 添加学习记录
     * @param type $data
     * return bool
     */
    public function addStudyRecord($data, $resource_type = null)
    {
        //检测资源的类型
        $study_resource_type = Models_StudyResource_StudyResource::init()->getStudyResourceByResourceId($data['resource_id']);
        //没有学习完的可以往学习记录表里面写数据
        $study_record_info = $this->isOrNotFinishResourceId($data);
        $study_record_status = empty($study_record_info) ? 0 : $study_record_info[0]['study_record_status'];
        //测评的不用加多条记录
        if($study_resource_type[0]['study_resource_type_id'] == 100){
            //判断表里面是否有数据
            if(empty($study_record_status)){
                return $this->insert($data, $this->_table);
            }
        }else{
             if ($study_record_status != 2 ) {
               return $this->insert($data, $this->_table);
            
            } else if ($study_record_status == 2 && $resource_type == 'isVideo') { //状态为2时,不往表里面写数据,但是会更新学习的时间
            //$this->updateRecordTime($data['study_record_time'], $study_record_info[0]['study_record_id']);
            $this->updateRecordTime("`study_record_time` = '{$data['study_record_time']}'",$study_record_info[0]['study_record_id']);
           } 
       }
    }

    /**
     * 更新时间
     * @param 更新的数据 $update_data
     * @return int
     */
    public function updateRecordTime($update_data,$study_record_id)
    {
        return $this->sql("update {$this->_table} set $update_data where `study_record_id` = $study_record_id");
    }

    /**
     * 判断这条学习资源是否已经完成
     * @param array $data
     */
    public function isOrNotFinishResourceId($data)
    {
        $where = "`resource_id` = {$data['resource_id']} and `study_record_user_id` = '{$data['study_record_user_id']}' and `study_resource_subject_type` = '{$data['study_resource_subject_type']}'";
        $study_record_info = $this->sql("select `study_record_status`,`study_record_id` from {$this->_table} where $where order by time desc limit 0,1");
        return $study_record_info;
    }

    /**
     * 获取某个资源的最后一条记录
     * @param  int $resource_id 关联id
     * @param string $study_record_user_id 学习用户id
     * @param string $study_resource_subject_type 科目
     */
    public function getResourceRecordById($resource_id, $study_record_user_id, $study_resource_subject_type)
    {
        return $this->sql("select * from {$this->_table} where `resource_id` = $resource_id  and `study_record_user_id` = '{$study_record_user_id}' and `study_resource_subject_type` = '{$study_resource_subject_type}' order by time desc");
    }

    /**
     * 通过学习资源删除学习记录表对应的学习记录
     * @param int $study_resource_id 学习资源id
     * return bool
     */
    public function deleteStudyRecord($study_resource_id)
    {
        return $this->delete($study_resource_id, 'study_resource_id');
    }

    /**
     * 学习的历史记录
     * @param string $study_record_user_id 用户id
     * @param string $study_resource_subject_type 科目
     * @param string $study_resource_field 学段
     * @param  string $study_resource_version 教材版本
     * @param string $study_resource_grade 年级
     * @param int $start
     * @param int $limit
     */
    public function getStudyRecordList($study_record_user_id, $study_resource_subject_type, $study_resource_field, $study_resource_version, $study_resource_grade, $start, $limit)
    {
        //获取学习id
        $where = "`study_resource_subject_type` = '{$study_resource_subject_type}' and `study_resource_field` = '{$study_resource_field}' and `study_resource_version` = '{$study_resource_version}' and `study_resource_grade` = '{$study_resource_grade}'";
        $data = $this->sql("select `resource_id` from study_resource where $where");
        $resource_id = '';
        if (!empty($data)) {
            foreach ($data as $value) {
                $resource_id .= $value['resource_id'] . ',';
            }
            $resource_id = rtrim($resource_id, ',');
            $study_record_where = "`resource_id` in ({$resource_id}) and `study_record_user_id` = '{$study_record_user_id}' and `study_record_status` != 0 limit $start , $limit";
            $record_data = $this->sql("select * from {$this->_table} where $study_record_where");
            if (!empty($record_data)) {
                foreach ($record_data as $k => $m) {
                    //获取节点
                    $data = $this->sql("select `study_grade_node_node`,`study_resource_title` from study_resource where `resource_id` = {$m['resource_id']}");
                    $record_data[$k]['study_grade_node_node'] = $data[0]['study_grade_node_node'];
                    $record_data[$k]['study_resource_title'] =  $data[0]['study_resource_title'];
                    $record_data[$k]['study_resource_title_small'] =  Cola_View::truncate($data[0]['study_resource_title'],12);
                }
            } else {
                $record_data = array();
            }
        } else {
            $record_data = array();
        }
        return $record_data;
    }

    /**
     * 统计知识节点的学习情况
     * @param string $study_resource_subject_type 科目
     * @param string $study_resource_field 学段
     * @param string $study_resource_version 版本
     * @param string $study_resource_grade 年级
     * @param int $study_grade_node_node 知识节点
     */
    public function getStudyResourceCount($study_record_user_id, $study_resource_subject_type, $study_resource_field, $study_resource_version, $study_resource_grade, $study_grade_node_node)
    { 
        $where = " `study_resource_subject_type` = '{$study_resource_subject_type}' and `study_resource_field` = '{$study_resource_field}' and `study_resource_version` = '{$study_resource_version}' and `study_resource_grade` = '{$study_resource_grade}' and  `study_grade_node_node` = $study_grade_node_node";
        $data = $this->sql("select `resource_id` from study_resource where $where");
        $not_study_arr = array();
        $now_study_arr = array();
        $resource_id = array();
        $resource_id_count = 0;
        if (!empty($data)) {
            foreach ($data as $v) {
                $resource_id[] = $v['resource_id'];
            }
            $resource_id_count = count($resource_id);
            foreach ($resource_id as $resource_ids) {
                //获取最后一条记录
                $data = $this->sql("select `study_record_status`,`time` from {$this->_table} where `study_record_user_id` = '{$study_record_user_id}' and `resource_id` = {$resource_ids} order by time desc");
                if (empty($data)) {
                    $not_study_arr[] = 0;
                } else if (!empty($data) && $data[0]['study_record_status'] == 1) {
                    $now_study_arr[] = $data[0]['time'];
                }
            }
            if (count($not_study_arr) == $resource_id_count) {
                return array('pic' => 0, 'descript' => "尚未学习");
            } else if (count($now_study_arr) > 0 && count($now_study_arr) < $resource_id_count) {
                $pic = ceil((count($now_study_arr) / $resource_id_count) * $this->_study_pic_number);
                return array('pic' => $pic, 'descript' => date("Y-m-d", end($now_study_arr)));
            } else {
                return array('pic' => $this->_study_pic_number, 'descript' => '完成学习');
            }
        }
        return array('pic' => NULL, 'descript' => "无学习资源");
    }

    /**
     * 不同学习记录的统计
     * @param string $study_record_user_id 学习资源者id
     * @param int $study_resource_type_id 资源类型
     * @param string $study_resource_subject_type 科目
     * return int
     */
    public function getDiffStudyRecordCount($study_record_user_id, $study_resource_subject_type, $study_resource_type_id)
    {
        $where = " `study_record_user_id` = '{$study_record_user_id}'";
        $sql = "select `resource_id` from {$this->_table} where $where ";
        $resource_ids = '';
        $resource_id_arr = $this->sql($sql);
        if (!empty($resource_id_arr)) {
            foreach ($resource_id_arr as $id) {
                $resource_ids .= $id['resource_id'] . ",";
            }
            $resource_ids = rtrim($resource_ids, ',');
            $count = $this->count(" `resource_id` in ({$resource_ids}) and `study_resource_type_id` = $study_resource_type_id  and `study_resource_subject_type` = '{$study_resource_subject_type}' ", 'study_resource');
        } else {
            $count = 0;
        }
        return $count;
    }

    /**
     * 参加知识节点学习人数的统计
     * @param string $field 学段
     * @param string $grade 年级
     * @param string $bb 版本
     * @param int $son_id  知识节点id
     * @param string $study_resource_subject_type 科目
     */
    public function getRecordCount($field, $grade, $bb, $son_id, $study_resource_subject_type)
    {
        $where = "`study_resource_field` = '{$field}' and `study_resource_grade` = '{$grade}' and `study_resource_version` = '{$bb}' and `study_grade_node_node` = $son_id  and `study_resource_subject_type` = '{$study_resource_subject_type}'";
        $resource_id_arr = $this->sql("select `resource_id` from study_resource where $where ");
        $count = 0;
        $resource_id = '';
        if (!empty($resource_id_arr)) {
            foreach ($resource_id_arr as $id) {
                $resource_id .= $id['resource_id'] . ',';
            }
            $resource_id = rtrim($resource_id, ',');
            $data = $this->sql("select distinct `study_record_user_id` from {$this->_table} where `resource_id` in ($resource_id)");
            $count = count($data);
        }
        return $count;
    }

    /**
     * 获取某条资源学习的统计
     * @param int $resource_id 学习资源表关联id
     */
    public function getOneStudyResourceStudyCountByResourceId($resource_id)
    {
        $data = $this->sql("select distinct study_record_user_id from {$this->_table} where `resource_id` = {$resource_id}");
        return empty($data) ? 0 : count($data[0]);
    }

    /**
     *
     * @param int $id 主键id
     * @return type
     */
    public function getStudyRecordById($id)
    {
        return $this->load($id);
    }

    /**
     *  通过主键id 更新数据
     * @param int $id 主键id
     * @param array $data
     * return bool
     */
    public function updateDataById($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     *  更新数据(测评作业通过这个方法进行更新)
     * @param  array $update_data
     * @return  
     */
    public function updateDataByUserIdAndResourceId($update_data)
    {
        $sql = " update {$this->_table} set `study_record_status` = 2 ,`time`= {$update_data['endtime']} ,`study_score` = {$update_data['score']} 
                    where `study_record_user_id` = '{$update_data['user_id']}' and `resource_id` = {$update_data['param'][2]} ";
        $this->sql($sql);             
    }

    /**
     *  获取当前用户学习的状态
     * @param  string $user_id  用户id
     * @param  int $resource_id 学习资源关联id
     * @return int 
     */
    public function getUserStudyStatus($user_id,$resource_id)
    {
        $where = "`study_record_user_id` = '{$user_id}' and `resource_id` = {$resource_id} order by time desc limit 0,1";
        $data = $this->sql("select `study_record_status` from {$this->_table} where $where");
        return empty($data) ? 0 : $data[0]['study_record_status'];
    } 

    /**
     * 获取学习的某些用户
     * @param  $xk 学科
     * @param  int $limit 
     * @return array
     */
    public function getPartUserStudy($xk,$limit)
    {
        $sql = "select distinct `study_record_user_id` from {$this->_table} where `study_resource_subject_type` = '{$xk}' limit $limit ";
        return $this->sql($sql);
    }
}
