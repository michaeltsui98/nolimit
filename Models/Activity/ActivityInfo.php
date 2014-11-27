<?php
/**
 * 活动信息管理数据模型
 * @author sizzflair87430@gmail.com
 */
class Models_Activity_ActivityInfo extends Cola_Model
{

    protected $_db = '_db';
    protected $_table = 'activity_info';
    protected $_pk = 'activity_info_id';

    public function __construct()
    {

    }

    /**
     * 获取活动列表
     * @param $inSubject                  学科   varchar:    GS0024：生命安全,GS0025：心理健康
     * @param $inPage                       当前页数      int > 0
     * @param $inOffset                      偏移量          int > 0
     * @param $inType                        活动类型     int:  空为全部 活动类型：1：基础理论 2：专业知识 3：教学技能 4：教育技能 5：自主学习
     * @param string $inStatus        活动状态     int:  空为全部   0：已关闭，1：已发布2：已开始
     * @param string $inPhase        活动学段     int:   空为全部    xd001：小学 xd002：初中 xd003：高中
     * @param string $inVisibility  活动可见性 int: 空为全部  0：私有，1：公开
     * @param string $inClass         活动类型      int: 空为全部  1、专家沙龙（视频），2、示范课（视频），3、说课PPT（视频），4、互动课堂（直播）
     * @param string $inSync          活动同步      int: 空为全部  0：不是，1：是
     * @param string $inGrade        活动年级      空为全部   varchar: 'GO003'=>'小学一年级'
     * @param string $inPublisher  发行单位      空位全部   varchar:v01人教版，v02鄂教版
     * todo 后续还要修改
     * @return array
     */
    public function getActivityList($inSubject,$inPage,$inOffset,$inType='',$inStatus='',$inPhase='',$inVisibility='',$inClass='',$inSync='',$inGrade='',$inPublisher='')
    {
            $subject = $this->escape($inSubject);
            $page = intval($inPage) > 0 ?intval($inPage) : 1;
            $offSet = intval($inOffset) > 0 ?intval($inOffset) : 1;
            $whereStr = '';
            if($inType != ''){
                $whereStr .= " AND `activity_info_type`=".intval($inType)." ";
            }
            if($inStatus != ''){
                $whereStr .= " AND `activity_info_status`=".intval($inStatus)." ";
            }
            if($inPhase != ''){
                $whereStr .= " AND `activity_info_phase`=".$this->escape($inPhase)." ";
            }
            if($inVisibility != ''){
                $whereStr .= " AND `activity_info_visibility`=".intval($inVisibility)." ";
            }
            if($inClass != ''){
                $whereStr .= " AND `activity_info_class`=".intval($inClass)." ";
            }
            if($inSync != ''){
                $whereStr .= " AND `activity_info_is_sync`=".intval($inSync)." ";
            }
            if($inGrade != ''){
                $whereStr .= " AND `activity_info_grade`=".$this->escape($inGrade)." ";
            }
            if($inPublisher != ''){
                $whereStr .= " AND `activity_info_publisher`=".$this->escape($inPublisher)." ";
            }

            try{
                $sql = "SELECT `activity_info`.*,COUNT(`activity_member`.`activity_member_uid`) as `member_total`, IF(AVG(`activity_member`.`activity_member_evaluate`) IS NULL,0, ROUND(AVG(`activity_member`.`activity_member_evaluate`),1)) as `activity_avg_evaluate`  FROM `activity_info` LEFT JOIN `activity_member`
                            ON `activity_info`.`activity_info_id`=`activity_member`.`activity_member_pid`
                            WHERE  `activity_info`.`activity_info_status`>0   AND `activity_info`.`activity_info_subject` = ".$subject." ".$whereStr." GROUP BY `activity_info`.`activity_info_id` ORDER BY `activity_info`.`activity_info_start_date` DESC";
                return $this->getListBySql($sql, $page, $offSet);
            }catch (Exception $e){
                echo $e;
            }
    }


    /**
     * 获取从今天开始的往后的五条数据
     * @param $inSubject
     * @param $inStartDate
     * @param $inOffset
     * @return array
     */
    public function readLatestActivities($inSubject,$inStartDate,$inOffset)
    {
        $subject = $this->escape($inSubject);
        $startDate = intval($inStartDate);
        $offset = intval($inOffset);
        try{
            $sql = "SELECT * FROM `activity_info` WHERE `activity_info_status`>0   AND  `activity_info_subject` = ".$subject." AND `activity_info_start_date` >= ".$startDate." ORDER BY `activity_info_start_date` ASC LIMIT 0,".$offset;

            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 根据条件取得活动的分页信息
     * @param $inSubject
     * @param $inPage
     * @param $inOffset
     * @param array $inSelectOptions
     * @param array $inOrderOptions
     * @return bool|multitype
     */
    public function readActivitiesByOptions($inSubject,$inPage,$inOffset, array $inSelectOptions, array $inOrderOptions)
    {
        $subject  = $this->escape($inSubject);
        $page = intval($inPage);
        $offset = intval($inOffset);
        $select_options = $inSelectOptions;
        $order_options = $inOrderOptions;

        $select_options_sql_str = "";
        if(!empty($select_options)){
            foreach($select_options as $key => $val){
                $select_options_sql_str .= " AND `".$key."`='".$val."'  ";
            }
        }

        $order_options_sql_str = "";
        if(!empty($order_options)){
            $order_options_sql_str = "  ORDER BY  ";
            foreach($order_options as $key =>$val){
                $order_options_sql_str .= " ".$key." ".$val.',';
            }
            $order_options_sql_str = substr($order_options_sql_str, 0, -1).' ';
        }else{
            $order_options_sql_str = "  ORDER BY  `activity_info_start_date` DESC   ";
        }
        try{
            $sql = "SELECT `activity_info`.*,COUNT(`activity_member`.`activity_member_uid`) as `member_total`, IF(AVG(`activity_member`.`activity_member_evaluate`) IS NULL,0, ROUND(AVG(`activity_member`.`activity_member_evaluate`),1)) as `activity_avg_evaluate`  FROM `activity_info` LEFT JOIN `activity_member`
                            ON `activity_info`.`activity_info_id`=`activity_member`.`activity_member_pid`
                             WHERE  `activity_info_status`>0   AND `activity_info_subject`=".$subject."    ".$select_options_sql_str.'  GROUP BY `activity_info`.`activity_info_id`  '.$order_options_sql_str;
            return $this->getListBySql($sql, $page, $offset);
        }catch (Exception $e){
            echo $e;
        }
    }

    public function readActivityTimeStatistics($inSubjectCode, $inStartDate, $inEndDate)
    {
        $subject_code = $this->escape($inSubjectCode);
        $start_time = intval($inStartDate);
        $end_time = intval($inEndDate);
        if($end_time === 0){
            $end_time = time();
        }
        try{
            $sql = "SELECT * FROM `activity_info` WHERE `activity_info_subject`=".$subject_code."  AND `activity_info_start_date`>=".$start_time."  AND `activity_info_start_date` <=".$end_time ;
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     *
     * @param $inSubjectCode
     * @param $inStartDate
     * @param $inEndDate
     * @return array
     */
    public function readActivityTypeAnalysis($inSubjectCode, $inStartDate, $inEndDate)
    {
        $subject_code = $this->escape($inSubjectCode);
        $start_time = intval($inStartDate);
        $end_time = intval($inEndDate);
        if($end_time === 0){
            $end_time = time();
        }
        try{
            $sql = "SELECT `activity_info_type`,COUNT(*) as `activity_info_type_times` FROM `activity_info` WHERE `activity_info_subject`=".$subject_code."  AND `activity_info_start_date`>=".$start_time." AND `activity_info_start_date`<=".$end_time." AND `activity_info_type`!=0 GROUP BY `activity_info_type`;";
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 增加活动的点击数
     * @param $inActivityId 研训ID
     * @return array  点击数增加的结果，影响的行数
     */
    public function updateActivityHit($inActivityId)
    {
        $activityId = intval($inActivityId);
        try{
            $sql = "UPDATE `activity_info` SET `activity_info_hit`=`activity_info_hit`+1 WHERE `activity_info_id`= ".$activityId;
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }


}
