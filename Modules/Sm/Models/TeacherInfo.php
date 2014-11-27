<?php
/**
 * 心理健康咨询资讯相关模型
 */
class Modules_Sm_Models_TeacherInfo  extends Cola_Model {
    private $_teacher_info_model = "";
    private $_user_app_model = "";
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->_teacher_info_model = new Models_Teacher_TeacherInfo();
        $this->_user_app_model= new Models_UserApp();
    }

    /**
     * 添加用户的当前教材版本
     * @param array $inTextbookData
     * @return bool
     */
    public function insertTeacherTextbook(array $inTextbookData)
    {
        return $this->_teacher_info_model->insert($inTextbookData);
    }

    /**
     * 根据用户ID和课程学科编号获取当前用户的教材版本
     * @param $inUserId
     * @param $inSubjectCode     GS0024生命安全  GS0025心理健康
     * @return bool   尚未设置:false(bool) 已经设置:版本编号(string)
     */
    public function getTeacherTextbookPublisher($inUserId, $inSubjectCode)
    {
        $teacher_textbook_result = $this->_teacher_info_model->readTeacherTextbook($inUserId, $inSubjectCode);
        if(empty($teacher_textbook_result)){
            return false;
        }else{
            return $teacher_textbook_result[0]['teacher_textbook_publisher_code'];
        }
    }

    /**
     * 修改教师当前教材版本
     * @param $inUserId
     * @param $inSubjectCode
     * @param $inPublisherCode
     * @return array
     */
    public function updateTeacherTextbook($inUserId, $inSubjectCode,$inPublisherCode)
    {
        return $this->_teacher_info_model->updateTeacherTextbook($inUserId, $inSubjectCode,$inPublisherCode);
    }

    /**
     * 删除用户的当前教材版本
     * @param $inUserId
     * @param $inSubjectCode
     * @return array
     */
    public function deleteTeacherTextbook($inUserId, $inSubjectCode)
    {
        return $this->_teacher_info_model->deleteTeacherTextbook($inUserId, $inSubjectCode);
    }

    /**
     * 获取我的 应用
     * @param $user_id
     * @param $xk_code
     * @return 为空就是empty array
     */
    public function getMyAppList($user_id,$xk_code)
    {
        return $this->_user_app_model->getMyAppList($user_id,$xk_code);
    }

    /**
     * 获取系统该学科应用列表
     * @param $role
     * @param $xk_code
     * @return mixed
     */
    public function getSysAppList($role,$xk_code)
    {
        return $this->_user_app_model->getSysAppList($role,$xk_code,1,999999);
    }

    /**
     * 添加我的应用
     * @param $user_id
     * @param $app_id
     * @return bool
     */
    public function addMyApp($user_id,$app_id)
    {
        return $this->_user_app_model->addMyApp($user_id,$app_id);
    }

    /**
     * 删除我的应用
     * @param $id
     * @return Ambigous
     */
    public function deleteMyApp($id)
    {
        return $this->_user_app_model->delMyAppById($id);
    }

}