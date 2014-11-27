<?php

/**
 * 课程管理接口类
 *
 * @author wuya <ourzu1987@gmail.com>
 * @version 2014-5-5
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Models_Course_Interface extends Cola_Model
{

    private $_course = null;
    static public $subject_transform = array(
        'sm' => '生命安全',
        'xl' => '心理健康',
        'yw' => '语文'
    );

    public function __construct()
    {
        $this->_course = new Models_Course_Course();
    }

    /**
     * 学生的教师及学生的学段、年级
     * @param string $user_id
     * @param string $subject
     * @return array
     */
    public function teacherOfSomeStudent($user_id, $subject)
    {
        $client = new Models_DDClient();
        $result = $client->getDataByApi('class/teacherOfSomeStudent', array('user_id' => $user_id, 'subject' => $subject), false);
        return isset($result['data']) ? $result['data'] : array();
    }

    /**
     * 教师在本周的课程数统计
     * @param string $user_id 用户Id
     * @param string $subject 科目 ：sm|xl
     * @return int
     */
    public function courseCountOfThisWeekByTeacher($user_id, $subject)
    {
        $thisweek = Models_Course_Course::dateOfCurrentWeek();
        return $this->_course->courseStat($subject, $user_id, $thisweek[1]);
    }

    /**
     * 本周的课程安排
     * @param string $user_id
     * @param string $subject
     * @return array
     */
    public function courseOfThisWeekByTeacher($user_id, $subject)
    {
        $date = Models_Course_Course::dateOfCurrentWeek();

        $table = $subject . '_course';

        $query = "select `id`,`class_id`,`teacher_user_id`,`course_grade`,`course_week`,`course_date`,`course_sort`,`lesson_folder_id`,`course_status`"
                . "from `{$table}` where  `teacher_user_id`= '{$user_id}' and `course_date` between '{$date[1]}' and '{$date[5]}'";
        $course = $this->sql($query);

        $class_id_list = array();
        $ret_week = array();

        foreach ((array) $course as $value) {
            //按天分组
            $ret_week[$value['course_week']][$value['course_sort']] = $value;
            $class_id_list[$value['class_id']] = $value['class_id'];
        }

        //补充班级名称
        $client = new Models_DDClient();
        $class_list = $client->getDataByApi('class/classBaseInfoMulti', array('class_id' => $class_id_list, 'fields' => 'class_name,class_id'), false);
        $class = array();
        foreach ((array) $class_list['data'] as $value) {
            isset($value[0][1]) && $class[$value[0][1]] = $value[0][0];
        }

        foreach ((array) $ret_week as $key => $value) {
            foreach ($value as $s_key => $s_value) {
                $ret_week[$key][$s_key]['class_name'] = isset($class[$s_value['class_id']]) ? $class[$s_value['class_id']] : '';
            }
        }

        return $ret_week;
    }

    /**
     * 学生用户的本周课程统计
     * @param string $user_id
     * @param string $subject eg:'xl' or 'sm'
     * @return array
     */
    public function couserStatForStudentByCurrentWeek($user_id, $subject)
    {
        $date = Models_Course_Course::dateOfCurrentWeek();
        $current_class = Models_Course_Cache::init()->currentClassList($user_id);

        $table = $subject . '_course';

        $where = " `class_id`='{$current_class[0]['class_id']}' and  `course_date` between '{$date[1]}' and '{$date[5]}' ";
        return $this->count($where, $table);
    }

}
