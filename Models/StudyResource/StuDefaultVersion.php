<?php

/**
 * Description of stuDefaultVersion
 *
 * @author libo
 */
class Models_StudyResource_StuDefaultVersion extends Cola_Model
{

    protected $_db = '_db';
    protected $_pk = 'id';
    protected $_table = 'stu_default_version';

    public function __construct()
    {

    }

    /**
     *  添加默认的学习版本
     * @param array $data
     */
    public function addDefultVersion($data)
    {
        if (empty($data)) {
            return false;
        }
        //判断是否已经添加了版本
        $status = $this->isOrNotAddDefaultVersion($data['sel_user_id'], $data['study_type_id']);
        if (empty($status)) {
            return  $this->insert($data, $this->_table);
        } else {
            return $this->updateDefaultVersion($data['sel_user_id'], $data['sel_study_version'], $data['study_type_id']);
        }
    }

    /**
     * 判断当前用户是否已经添加了默认版本
     * @param string $sel_user_id 用户id
     * @param string $study_type_id GS0025:心理, GS0024:生命科学
     */
    public function isOrNotAddDefaultVersion($sel_user_id, $study_type_id)
    {
        if (empty($sel_user_id) || empty($study_type_id)) {
            return false;
        }
        return $this->count("`sel_user_id` = '{$sel_user_id}' and `study_type_id` = '{$study_type_id}'", $this->_table);
    }

    /**
     * 更新学习课程的版本
     * @param string $sel_user_id 用户id
     * @param string $sel_study_version 默认的版本id
     * @param string $study_type_id GS0025:心理, GS0024:生命科学
     */
    public function updateDefaultVersion($sel_user_id, $sel_study_version, $study_type_id)
    {
        if (empty($sel_user_id) || empty($sel_study_version) || empty($study_type_id)) {
            return false;
        }
        $update = "update {$this->_table} set `sel_study_version` = '{$sel_study_version}' where `sel_user_id` = '{$sel_user_id}' and `study_type_id` = '{$study_type_id}'";
        return $this->sql($update);
    }

    /**
     *  获取默认的课程版本
     * @param string $sel_user_id 用户id
     * @param string $study_type_id GS0025:心理, GS0024:生命科学
     */
    public function getDefaultVersion($sel_user_id, $study_type_id)
    {
        if (empty($sel_user_id) || empty($study_type_id)) {
            return false;
        }
        $sql = "select `sel_study_version` from {$this->_table} where `sel_user_id` = '{$sel_user_id}' and `study_type_id` = '{$study_type_id}'";
        return $this->sql($sql);
    }

}
