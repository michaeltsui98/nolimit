<?php
/**
 * 教师当前的教材版本
 * @author sizzflair87430@gmail.com
 */
class Models_Teacher_TeacherInfo  extends Cola_Model
{

    protected $_db = '_db';
    protected $_table = 'teacher_textbook';
    protected $_pk = 'id';

    public function __construct()
    {

    }

    /**
     * 根据用户ID获取用户当前的教材版本
     * @param $inUserId
     * @return array
     */
    public function readTeacherTextbook($inUserId, $inSubjectCode)
    {
        $user_id = $this->escape($inUserId);
        $subject_code = $this->escape($inSubjectCode);
        try{
            $sql = "SELECT * FROM `teacher_textbook` WHERE `teacher_textbook_user_id`=".$user_id." AND `teacher_textbook_subject_code`=".$subject_code."   LIMIT 0,1";
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * 删除用户当前的教材科目
     * @param $inUserId
     * @param $inSubjectCode
     * @return array
     */
    public function deleteTeacherTextbook($inUserId, $inSubjectCode)
    {
        $user_id = $this->escape($inUserId);
        $subject_code = $this->escape($inSubjectCode);
        try{
            $sql = "DELETE FROM `teacher_textbook` WHERE `teacher_textbook_user_id` = ".$user_id." AND `teacher_textbook_subject_code`=".$subject_code;
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }

    /**
     * @param $inUserId
     * @param $inSubjectCode
     * @param $inPublisherCode
     * @return array
     */
    public function updateTeacherTextbook($inUserId, $inSubjectCode, $inPublisherCode)
    {
        $user_id = $this->escape($inUserId);
        $subject_code = $this->escape($inSubjectCode);
        $publisher_code = $this->escape($inPublisherCode);
        try{
            $sql = "UPDATE `teacher_textbook` SET `teacher_textbook_publisher_code` = ".$publisher_code." WHERE `teacher_textbook_user_id` = ".$user_id." AND `teacher_textbook_subject_code`=".$subject_code;
            return $this->sql($sql);
        }catch (Exception $e){
            echo $e;
        }
    }

}
