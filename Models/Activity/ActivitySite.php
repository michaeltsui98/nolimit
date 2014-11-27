<?php
/**
 * 活动小站管理模型
 * @author sizzflair87430@gmail.com
 */
class Models_Activity_ActivitySite extends Cola_Model
{

    protected $_db = '_db';
    protected $_table = 'activity_site';
    protected $_pk = 'site_id';

    public function __construct()
    {

    }

    /**
     * 获取所有被选中的活动小站
     * @return array
     */
    public function getAllActivitySites($inSubjectCode)
    {
        $subject_code = $this->escape($inSubjectCode);
           try{
               $sql= "SELECT * FROM `activity_site` WHERE `site_activity_subject` = ".$subject_code;
               return $this->sql($sql);
           }catch (Exception $e){
               echo $e;
           }
    }


    /**
     * 获取活动小站
     * @param $inSubjectCode
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function getActivitySites($inSubjectCode, $inPage, $inOffset)
    {
        $subject_code = $this->escape($inSubjectCode);
        $page = intval($inPage);
        $offset = intval($inOffset);
        try{
            $sql = "SELECT * FROM `activity_site` WHERE `site_activity_subject`=".$subject_code." ORDER BY `site_weight` DESC";
            return $this->getListBySql($sql, $page, $offset);
        }catch (Exception $e){
            echo $e;
        }
    }

}
