<?php
/**
 * 活动申请模型
 * @author sizzflair87430@gmail.com
 */
class Models_Activity_ActivityCity extends Cola_Model
{

    protected $_db = '_db';
    protected $_table = 'activity_city';
    protected $_pk = 'activity_city_id';

    public function __construct()
    {

    }

    /**
     * 批量创建活动地区
     * @param array $inCityArray
     * @return array|int
     */
    public function createActivityCity(array $inCityArray)
    {
        if(empty($inCityArray['city_code'])){
            return 0;
        }
        $city_values_str = '';
        foreach($inCityArray['city_code'] as $key => $val){
            $city_values_str .= " (".$inCityArray['activity_id'].",'".$val."'),";
        }
        $city_values_str = substr($city_values_str, 0, -1);
        try{
            $sql = "INSERT INTO `activity_city`(`activity_city_pid`,`activity_city_code`)
                            VALUES  ".$city_values_str;
            $result = $this->sql($sql);
            return $result;
        }catch (Exception $e){
            echo $e;
        }
    }


    /**
     * 删除某个活动ID下面的城市编码
     * @param $inActivityId
     * @return array
     */
    public function deleteActivityCity($inActivityId)
    {
        $activity_id = intval($inActivityId);
        try{
            $sql = "DELETE FROM `activity_city` WHERE `activity_city_pid` = ".$activity_id;
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }




}
