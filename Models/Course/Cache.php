<?php

/**
 * 课程缓存类
 *
 * @author wuya <ourzu1987@gmail.com>
 * @version 2014-5-28
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Models_Course_Cache extends Cola_Model
{

    private $_client = null;

    public function __construct()
    {
        $this->_client = new Models_DDClient();
    }

    /**
     * 批量获取班级基础信息
     * @param string $class_id_list
     * @param string $fields
     * @return array
     */
    public function classBaseInfoMultiDb($class_id_list, $fields)
    {
        $data = $this->_client->getDataByApi('class/classBaseInfoMulti', array('class_id' => $class_id_list, 'fields' => $fields));
        return (array) $data['data'];
    }

    /**
     * 批量获取班级基础信息(缓存)
     * @param string $class_id_list
     * @param string $fields
     * @return array
     */
    public function classBaseInfoMulti($class_id_list, $fields)
    {
        $rs = array($class_id_list, $fields);
        return $this->cached('classBaseInfoMultiDb', $rs, 3600);
    }

    /**
     * 班级基本信息
     * @param int $class_id
     * @param string $fields
     * @return array
     */
    public function classBaseInfoDb($class_id, $fields)
    {
        $data = $this->_client->getDataByApi('class/classBaseInfo', array('class_id' => $class_id, 'fields' => $fields));
        return (array) $data['data'];
    }

    /**
     * 班级基本信息(缓存)
     * @param int $class_id
     * @param string $fields
     * @return array
     */
    public function classBaseInfo($class_id, $fields)
    {
        $rs = array($class_id, $fields);
        return $this->cached('classBaseInfoDb', $rs, 3600);
    }

    /**
     * 当前班级列表
     * @param string $user_id
     * @return array
     */
    public function currentClassListDb($user_id)
    {
        $class_list = $this->_client->getDataByApi('class/myclasslistinfo', array('user_id' => $user_id));
        return $class_list['data'];
    }

    /**
     * 当前班级列表(缓存)
     * @param string $user_id
     * @return array
     */
    public function currentClassList($user_id)
    {
        $rs = array($user_id);
        return $this->cached('currentClassListDb', $rs, 3600);
    }

    /**
     * 分市州和学段的学校数统计
     * @return array
     */
    public function schoolStatByCityDb()
    {
        $cirle = Cola_Com_WebServices::factory(Cola::getconfig('_webServicesCircle'));
        $stat = $$cirle->StatisticsSchByCity("420000");
        return $stat[0];
    }

    /**
     * 分市州和学段的学校数统计(缓存)
     * @return type
     */
    public function schoolStatByCity()
    {
        $rs = array();
        return $this->cached('schoolStatByCityDb', $rs, 86400);
    }

    /**
     * 分市州和学段的教师数量统计
     * @param string $module
     * @return array 
     */
    public function teacherStatBySubjectByCityDb($module)
    {
        $subject_ret = Models_Subject::$subject_map_for_circle[$module];
        $subject_ids = '';
        foreach ($subject_ret as $value) {
            $subject_ids.=',' . $value;
        }
        $subject_ids = substr($subject_ids, 1);

        $cirle = Cola_Com_WebServices::factory(Cola::getconfig('_webServicesCircle'));
        $data = $cirle->StatisticsSubByCity("420000", (string) $subject_ids);

        $subject_ret_flip = array_flip($subject_ret);
        $stat = array();
        foreach ((array) $data[0] as $key => $value) {
            foreach ($value as $s_key => $s_value) {
                $stat[$key][$subject_ret_flip[$s_key]] = $s_value;
            }
        }
        return $stat;
    }

    /**
     * 分市州和学段的教师数量统计(缓存)
     * @param string $module
     * @return array 
     */
    public function teacherStatBySubjectByCity($module)
    {
        $rs = array($module);
        return $this->cached("teacherStatBySubjectByCityDb", $rs, 3600);
    }

    /**
     * 城市列表
     * @return array
     */
    public function cityListDb()
    {
        //全省市州列表
        $cirle = Cola_Com_WebServices::factory(Cola::getconfig('_webServicesCircle'));
        $city_list = $cirle->getAreaById('420000');
        return $city_list;
    }

    /**
     * 城市列表（缓存）
     */
    public function cityList()
    {
        $rs = array();
        return $this->cached('cityListDb', $rs, 86400);
    }

    /**
     * 根据学校ID和年份获取班级列表
     * @param int $school_id
     * @param int $year
     * @return array
     */
    public function classListBySchoolAndYearDb($school_id, $year, $xd)
    {
        $class_list = $this->_client->getDataByApi('class/getClassListBySchoolYear', array('school_id' => $school_id, 'school_year' => $year, 'school_xd' => $xd));
        return $class_list;
    }

    /**
     * 根据学校ID和年份获取班级列表(缓存)
     * @param int $school_id
     * @param int $year
     * @return array
     */
    public function classListBySchoolAndYear($school_id, $year, $xd)
    {
        $rs = array($school_id, $year, $xd);
        return $this->cached('classListBySchoolAndYearDb', $rs, 3600);
    }

    /**
     * 获取用户信息
     * @param string $user_id
     * @return array
     */
    public function getUserInfoDb($user_id)
    {
        $user_info = $this->_client->getDataByApi('user/getBaseUserAvatar', array('user_id' => $user_id));
        return $user_info;
    }

    /**
     * 获取用户信息（缓存）
     * @param string $user_id
     * @return array
     */
    public function getUserInfo($user_id)
    {
        $rs = array($user_id);
        return $this->cached('getUserInfoDb', $rs, 86400);
    }

}
