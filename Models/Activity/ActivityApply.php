<?php
/**
 * 活动申请模型
 * @author sizzflair87430@gmail.com
 */
class Models_Activity_ActivityApply extends Cola_Model
{

    protected $_db = '_db';
    protected $_table = 'activity_apply';
    protected $_pk = 'activity_apply_id';

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
    public function readActivityApplyList($inSubjectCode,$inPage,$inOffset)
    {
        $subject_code = $this->escape($inSubjectCode);
        $page = intval($inPage) > 0 ?intval($inPage) : 1;
        $offSet = intval($inOffset) > 0 ?intval($inOffset) : 1;
        try{
            $sql = "SELECT * FROM `activity_apply` WHERE `activity_apply_subject`=".$subject_code." ORDER BY `activity_apply_date` DESC";
            return $this->getListBySql($sql, $page, $offSet);
        }catch (Exception $e){
            echo $e;
        }
    }






}
