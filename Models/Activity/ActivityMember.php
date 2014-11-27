<?php
/**
 * 活动资源管理模型
 * @author sizzflair87430@gmail.com
 */
class Models_Activity_ActivityMember extends Cola_Model
{

    protected $_db = '_db';
    protected $_table = 'activity_member';
    protected $_pk = 'activity_member_id';

    public function __construct()
    {

    }

    /**
     * 获取活动成员列表
     * @param $inActivityId
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function readActivityMemberList($inActivityId,$inPage,$inOffset)
    {
        $activity_id = intval($inActivityId);
        $page = intval($inPage) > 0 ?intval($inPage) : 1;
        $offSet = intval($inOffset) > 0 ?intval($inOffset) : 1;
        try{
            $sql = "SELECT * FROM `activity_member` WHERE `activity_member_pid` = ".$activity_id."   ORDER BY `activity_member_date` DESC";
            return $this->getListBySql($sql, $page, $offSet);
        }catch (Exception $e){
            echo $e;
        }
    }

    public function readActivityMemberTotal($inActivityId)
    {
        $activity_id = intval($inActivityId);
        $sql = "SELECT COUNT(*) AS `member_total` FROM `activity_member` WHERE `activity_member_pid` = ".$activity_id;
        $result = $this->sql($sql);
        return $result;
    }




    /**
     * 获取平均分
     * @param $inActivityId
     * @return array
     */
    public function readActivityAvgEvaluate($inActivityId)
    {
        $activity_id = intval($inActivityId);
        try{
            $sql = "SELECT ROUND(AVG(`activity_member_evaluate`),1) as `activity_avg_evalute`  FROM `activity_member` WHERE   `activity_member_pid` = ".$activity_id;
            $result = $this->sql($sql);
            //不是null就设置缓存
            return $result;
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 获取研训达人
     * @param $inSubjectCode
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function readActivityMemberByHot($inSubjectCode,$inPage,$inOffset)
    {
        $subject_code = $this->escape($inSubjectCode);
        $page = intval($inPage);
        $offset = intval($inOffset);
        try{
            $sql = "SELECT * FROM `activity_member` WHERE `activity_member_subject` =".$subject_code."  GROUP BY `activity_member_uid` ORDER BY COUNT(*) DESC ";
            return $this->getListBySql($sql,$page,$offset);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 取得用户对活动的评价
     * @param $inActivityId
     * @param $inUserId
     * @return array
     */
    public function readActivityUserEvaluate($inActivityId, $inUserId)
    {
        $activity_id = intval($inActivityId);
        $user_id = $this->escape($inUserId);
        try{
            $sql = "SELECT * FROM `activity_member` WHERE `activity_member_pid`=".$activity_id." AND `activity_member_uid` = ".$user_id;
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 用户对研训活动打分
     * @param $inActivityId
     * @param $inUserId
     * @param $inEvaluate
     * @return array
     */
    public function updateActivityMemberEvaluate($inActivityId, $inUserId, $inEvaluate)
    {
        $activity_id = intval($inActivityId);
        $user_id = $this->escape($inUserId);
        $evaluate = intval($inEvaluate);
        try{
            $sql = "UPDATE `activity_member` SET `activity_member_evaluate` = ".$evaluate." WHERE `activity_member_pid` = ".$activity_id." AND `activity_member_uid` = ".$user_id;
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 获取当前教师用户研训统计
     * @param $inSubjectCode
     * @param $inUserId
     * @return array
     */
    public function readTeacherActivityStatistics($inSubjectCode, $inUserId)
    {
        $subject_code = $this->escape($inSubjectCode);
        $user_id = $this->escape($inUserId);
        try{
            $sql = "SELECT COUNT(*) as `activity_times`,`activity_info`.`activity_info_type` as `activity_info_type`  FROM `activity_member` INNER JOIN `activity_info`
                            ON `activity_member`.`activity_member_pid` = `activity_info`.`activity_info_id`
                            WHERE `activity_member_uid` =   ".$user_id."  AND `activity_member_subject` =  ".$subject_code."
                            GROUP  BY `activity_info`.`activity_info_type`";
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }


    /**
     * 活动类型统计
     * @param $inSubjectCode
     * @param $inUserId
     * @return array
     */
    public function readTeacherActivityClassStatistics($inSubjectCode, $inUserId)
    {
        $subject_code = $this->escape($inSubjectCode);
        $user_id = $this->escape($inUserId);
        try{
            $sql = "SELECT COUNT(*) as `activity_times`,`activity_info`.`activity_info_class` as `activity_info_class`  FROM `activity_member` INNER JOIN `activity_info`
                            ON `activity_member`.`activity_member_pid` = `activity_info`.`activity_info_id`
                            WHERE `activity_member_uid` =   ".$user_id."  AND `activity_member_subject` =  ".$subject_code."
                            GROUP  BY `activity_info`.`activity_info_class`";
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }




    /**
     * 分页获取教师研修列表
     * @param $inSubjectCode
     * @param $inUserId
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function readTeacherActivityList($inSubjectCode, $inUserId, $inPage, $inOffset, $inStartTime, $inEndTime)
    {
        $subject_code = $this->escape($inSubjectCode);
        $user_id = $this->escape($inUserId);
        $page = intval($inPage)==0 ? 1 : intval($inPage);
        $offset = intval($inOffset);
        $startTime = intval($inStartTime);
        $endTime = intval($inEndTime);
        $startTimeQueryStr = "";
        if($startTime !== 0){
            $startTimeQueryStr = "  AND `activity_info`.`activity_info_start_date` >= ".$startTime." ";
        }
        $endTimeQueryStr = "";
        if($endTime !==0){
            $endTimeQueryStr = "  AND `activity_info`.`activity_info_start_date` <= ".$endTime." ";
        }

        try{
            $sql = "SELECT `activity_info`.*,COUNT(`activity_member`.`activity_member_uid`) as `member_total`, IF(AVG(`activity_member`.`activity_member_evaluate`) IS NULL,0, ROUND(AVG(`activity_member`.`activity_member_evaluate`),1)) as `activity_avg_evaluate`  FROM `activity_info` LEFT JOIN `activity_member`
                            ON `activity_member`.`activity_member_pid` = `activity_info`.`activity_info_id`
                            WHERE `activity_member_uid` =   ".$user_id."  AND `activity_member_subject` =  ".$subject_code."  ".$startTimeQueryStr."  ".$endTimeQueryStr."
                            GROUP BY `activity_info`.`activity_info_id`
                            ORDER BY `activity_info`.`activity_info_start_date` DESC";
            return $this->getListBySql($sql, $page, $offset);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 上传活动心得
     * @param $inActivityId
     * @param $inUserId
     * @param $inBlogId
     * @param $inBlogDate
     * @return array
     */
    public function updateActivityExperience($inActivityId, $inUserId, $inBlogId, $inBlogDate, $inBlogTitle)
    {
        $activity_id = intval($inActivityId);
        $user_id = $this->escape($inUserId);
        $blog_id = $this->escape($inBlogId);
        $blog_date = intval($inBlogDate);
        $blog_title = $this->escape($inBlogTitle);

        try{
            $sql = "UPDATE `activity_member` SET `activity_member_experience_blogid`=".$blog_id.",`activity_member_experience_date`=".$blog_date.", `activity_member_experience_blogtitle`=".$blog_title."
                            WHERE `activity_member_pid`=".$activity_id." AND `activity_member_uid`=".$user_id;
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * @param $inSubjectCode
     * @param $inAreaCode
     * @param $inAreaCodeType 1:区县  2:市州
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function readActivityMemberStatistics($inSubjectCode, $inAreaCode, $inAreaCodeType, $inPhase, $inPage, $inOffset, $inStart_time, $inEnd_time)
    {
        $subject_code = $this->escape($inSubjectCode);
        $area_code = $this->escape($inAreaCode);
        $area_code_type = $inAreaCodeType;
        $start_time = intval($inStart_time);
        $end_time = intval($inEnd_time);
        if($end_time === 0){
            $end_time = time();
        }

        $area_query_option = "";
        if($area_code_type == 1){
            //区县
            $area_query_option = "  AND `activity_member_area_code` =   ".$area_code." ";
        }else{
            //统一市州
            $area_query_option = "  AND   LEFT(`activity_member_area_code`,4)  =   ".substr($area_code, 0, 5)."'   ";
        }
        $phase_option = "";
        if(!$inPhase == '' ){
            $phase = $this->escape($inPhase);
            $phase_option = "  AND `activity_member_phase`=".$phase." ";
        }
        $page = intval($inPage)>1? intval($inPage):1;
        $offset = intval($inOffset);
        try{
            $sql = "SELECT *, COUNT(*) as `activity_member_times` FROM `activity_member` WHERE `activity_member_subject`=".$subject_code."   ".$area_query_option."  ".$phase_option."  AND `activity_member_date`>= ".$start_time."  AND `activity_member_date` <= ".$end_time."
                            GROUP BY `activity_member_uid`
                            ORDER BY `activity_member_times` DESC";
            return $this->getListBySql($sql, $page, $offset);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 根据ID获取研训心得
     * @param $inActivityId
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function getActivityMemberEvaluateList($inActivityId, $inPage, $inOffset)
    {
        $activity_id = intval($inActivityId);
        $page = intval($inPage);
        $offset = intval($inOffset);
        try{
            $sql = "SELECT * FROM `activity_member` WHERE `activity_member_pid` = ".$activity_id." AND `activity_member_experience_blogid` != '' ORDER BY `activity_member_experience_date` DESC ";
            return $this->getListBySql($sql, $page, $offset);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 活动统计
     * @param $inSubjectCode
     * @param $inStartDate
     * @param $inEndDate
     * @return array
     */
    public function readActivityStatistics($inSubjectCode, $inStartDate, $inEndDate)
    {
        $subject_code = $this->escape($inSubjectCode);
        $start_time = intval($inStartDate);
        $end_time = intval($inEndDate);
        if($end_time === 0){
            $end_time = time();
        }
        try{
            $sql = "SELECT * FROM `activity_member` WHERE `activity_member_subject`=".$subject_code."  AND `activity_member_date`>=".$start_time."  AND `activity_member_date`<=".$end_time."  AND  `activity_member_phase` !='xd000'  ";
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }




    }




}
