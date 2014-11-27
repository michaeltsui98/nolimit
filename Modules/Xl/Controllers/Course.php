<?php

/**
 *
 * 课程设置控制器（心理）
 * @author wuya
 * @version 2014-04-22
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Modules_Xl_Controllers_Course extends Modules_Xl_Controllers_Base
{

    protected $_course = null;
    protected $_client = null;
    protected $_cache_model = null;

    const MODULE = 'xl';

    public function __construct()
    {
        parent::__construct();

        $this->_course = new Models_Course_Course();

        $this->layout = $this->getCurrentLayout('index.htm');

        $this->_client = new Models_DDClient();

        $this->_cache_model = new Models_Course_Cache();
        $this->checkAccount();
    }

    /**
     * 为确认关系的教师用户不能使用课程管理功能
     */
    public function checkAccount()
    {
        if (!empty($this->user_info) && !$this->user_info['school_id']) {
            $this->messagePage(DOMAIN_NAME . '/school', '您暂时无法使用该功能~<br>请先建立您的学校所属关系');
        }
        if ($this->user_info['user_status'] == 6 && $this->user_info['role_code'] == 2) {
            $this->messagePage(DOMAIN_ACCOUNT . '/userVerify/verify', '您暂时无法使用该功能~<br>请先确认与所在学校的所属关系');
        }
    }

    /**
     * 保存课程设置
     */
    public function saveCourseAction()
    {
        //班级ID
        $class_id = $this->getVar('class_id');

        //班级ID不能为空
        if (!$class_id) {
            $this->echoJson("error", "请选择上课班级!");
        }

        //节次、排序
        $sort = $this->getVar('sort');
        if (!$sort) {
            $this->echoJson("error", "请选择上课班级!");
        }

        //班级的地域信息
        $area_info = $this->_client->getDataByApi('class/classAreaInfo', array('class_id' => $class_id));

        //市州
        $city_id = $area_info['data']['cityId'];

        //区县
        $town_id = $area_info['data']['townId'];

        //学校
        $school_id = $area_info['data']['schoolId'];

        //学段
        $stage = $this->user_info['xd'];

        //年级
        $grade = $this->getVar('grade');

        //日期
        $date = $this->getVar('date');

        //备课夹ID
        $lesson_folder_id = $this->getVar('lesson_folder_id');

        //是否关联备课夹
        $status = (!$lesson_folder_id) ? 0 : 1;

        //备注
        $remark = $this->getVar('remark');
        $param = array(
            'class_id' => $class_id,
            'teacher_user_id' => $this->user_info['user_id'],
            'course_grade' => $grade,
            'course_stage' => $stage,
            'course_week' => date('w', strtotime($date)),
            'course_date' => $date,
            'course_sort' => $sort,
            'lesson_folder_id' => $lesson_folder_id,
            'course_lesson_folder_status' => $status,
            'city_id' => $city_id,
            'town_id' => $town_id,
            'school_id' => $school_id,
            'course_remark' => $remark,
        );
        $course_id = $this->_course->saveCourse(self::MODULE, $param);
        $this->echoJson("success", "保存成功!", array('course_id' => $course_id));
    }

    public function testAction()
    {
        // $result = $this->_course->impressionListByIds('51,50');
        $modele = new Models_Interface_Course();
        $result = $modele->questionsForCourse('xl', '217');
        print_R($result);
        echo json_encode($result);
    }

    /**
     * 更新备课夹
     */
    public function saveLessonFolderAction()
    {
        $course_id = $this->getVar('course_id');
        $lesson_folder_id = $this->getVar('folder_id');
        $data = array(
            'lesson_folder_id' => $lesson_folder_id,
            'course_lesson_folder_status' => (!$lesson_folder_id) ? 0 : 1
        );
        $this->_course->updateCourse(self::MODULE, $course_id, $data);
        $this->echoJson("success", "设置成功!");
    }

    /**
     * 课程首页
     */
    public function indexAction()
    {
        $role_code = $this->user_info['role_code'];
        switch ($role_code) {
            case 1:
                $this->indexForStudent();
                break;
            case 2:
                $this->indexForTeacher();
                break;
            case 3:
                $this->indexForParent();
                break;
            default :
                $this->indexForNoLogin();
        }
    }

    /**
     * 未登录页面
     */
    public function indexForNoLogin()
    {
        //角色代码    
        $role_code = Cola_Request::cookie('perview_role_' . $this->xk);
        switch ($role_code) {
            case 1:
                $this->view->page_title = '课堂';
                break;
            case 2:
                $this->view->page_title = '课程';
                break;
            case 3:
                $this->view->page_title = '课堂';
                break;
        }
        $this->view->role_code = $role_code;
        $this->setLayout($this->layout);
        $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/indexForNoLogin');
    }

    /**
     * 学生课堂记录
     */
    public function indexForStudent()
    {

        $this->view->page_title = '课堂记录';
        $user_id = $this->user_info['user_id'];

        $date = Models_Course_Course::dataOfSomeMonth(date('Y-m'));
        $ret = $this->_course->courseListByStudent(self::MODULE, $user_id, $date['first_day'], $date['last_day']);
        $this->view->course = $ret;
        $this->setLayout($this->layout);
        $this->view->js = array(
            'course/index_student.js',
        );
        $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/indexForStudent');
    }

    /**
     * 家长视角的学生课堂记录
     */
    public function indexForParent()
    {
        $this->view->page_title = '课堂记录';
        $user_id = $this->user_info['user_id'];
        $child_id = Models_StudyResource_StudyRecordConfig::init()->getChildrenByParentId($user_id);

        $date = Models_Course_Course::dataOfSomeMonth(date('Y-m'));
        $ret = $this->_course->courseListByStudent(self::MODULE, $child_id, $date['first_day'], $date['last_day'], $user_id);
        $this->view->course = $ret;
        $this->setLayout($this->layout);
        $this->view->js = array(
            'course/index_student.js',
        );
        $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/indexForParent');
    }

    /**
     * 教师课表
     */
    public function indexForTeacher()
    {
        $this->view->page_title = '课程安排';
        $user_id = $this->user_info['user_id'];
        $date = Models_Course_Course::dateOfCurrentWeek();
        $ret = $this->_course->readCourse(self::MODULE, $user_id, $date[1], $date[5]);
        $this->view->thisweek = date('W');
        $this->view->date = $date;
        $this->view->course = $ret;

        //全部课程统计
        $stat_all = $this->_course->courseStat(self::MODULE, $this->user_info['user_id']);
        $this->view->stat_all = $stat_all;

        //本周课程统计
        $thisweek = Models_Course_Course::dateOfCurrentWeek();
        $stat_week = $this->_course->courseStat(self::MODULE, $this->user_info['user_id'], $thisweek[1], $thisweek[5]);
        $this->view->stat_week = $stat_week;

        //当日课程统计
        $stat_day = $this->_course->courseStat(self::MODULE, $this->user_info['user_id'], date('Y-m-d'), date('Y-m-d'));
        $this->view->stat_day = $stat_day;

        //所任教班级列表
        $this->view->class_list = $this->_cache_model->currentClassList($this->user_info['user_id']);

        $this->setLayout($this->layout);
        $this->view->js = array(
            'course/course_index.js',
        );
        $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/indexForTeacher');
    }

    /**
     * 课程设置页面
     */
    public function courseSettingAction()
    {
        $type = $this->getVar('type', 'all');

        //年级选项
        $this->view->grade_select = Models_Grade::gradesSelect($this->user_info['xd'], '', array(), array('id' => 'grade'));

        $grade_code = array_keys(Models_Grade::$stage_grade_list[$this->user_info['xd']]);

        $grade_info = Models_Grade::getYearByGradeCode($grade_code[0]);

        $currentClass = $this->_cache_model->currentClassList($this->user_info['user_id']);

        $school_id = $currentClass[0]['school_id'];

        !$school_id && $school_id = $this->user_info['school_id'];

        $xd = Models_Stage::$xd_to_sqxd_map[$this->user_info['xd']];
        $class_list = $this->_cache_model->classListBySchoolAndYear($school_id, $grade_info['year'], $xd);

        $this->view->class_list = $class_list['data'];
        $this->view->type = $type;

        $this->view->version = Models_Resource::init()->getBbByXdAndXk($this->user_info['xd'], Models_Course_Course::$subject_config[self::MODULE]);

        $this->tpl();
    }

    /**
     * 设置备课夹
     */
    public function lessonFolderSettingAction()
    {
        $course_id = $this->getVar('course_id');
        //获取课程的学段和年级信息
        $course_detail = $this->_course->courseDetail(self::MODULE, $course_id);
        $this->view->stage = $course_detail[0]['course_stage'];
        $this->view->grade = $course_detail[0]['course_grade'];
        $this->view->type = "lessonFolder";
        $this->view->version = Models_Resource::init()->getBbByXdAndXk($this->user_info['xd'], Models_Course_Course::$subject_config[self::MODULE]);
        $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/courseSetting');
    }

    /**
     * 备课夹列表
     */
    public function lessonFolderListAction()
    {
        $stage = $this->user_info['xd'];
        $grade = $this->getVar('grade');
        $edition = $this->getVar('edition');
        $page = $this->getVar('p', 1);
        $list = $this->_course->lessonFolderList(self::MODULE, $this->user_info['user_id'], $stage, $grade, $edition, $page);
        // $list2 = Models_Resource::init()->getResourceBySearch(NULL, $stage, Models_Course_Course::$subject_config[self::MODULE], $edition, $grade, NULL, $this->user_info['user_id'], 8, $page, 5);
        $this->echoJson("success", "获取成功!", $list);
    }

    /**
     * 班级列表
     */
    public function classListAction()
    {
        $grade_code = $this->getVar('grade');
        $grade_info = Models_Grade::getYearByGradeCode($grade_code);

        $currentClass = $this->_cache_model->currentClassList($this->user_info['user_id']);
        $school_id = $currentClass[0]['school_id'];
        !$school_id && $school_id = $this->user_info['school_id'];

        $xd = Models_Stage::$xd_to_sqxd_map[$this->user_info['xd']];
        $class_list = Models_Course_Cache::init()->classListBySchoolAndYear($school_id, $grade_info['year'], $xd);

        $this->echoJson('success', '获取成功！', $class_list['data']);
    }

    /**
     * 课程安排接口
     */
    public function courseHtmlApiAction()
    {
        $date = Models_Course_Course::dateOfCurrentWeek();
        $ret = $this->_course->readCourse(self::MODULE, $this->user_info['user_id'], $date[1], $date[5]);
        $this->view->course = $ret;
        $date = Models_Course_Course::dateOfCurrentWeek();
        $this->view->date = $date;
        $this->tpl();
    }

    /**
     * 课程安排
     */
    public function courseListAction()
    {
        $format = $this->getVar('format');
        $start_date = $this->getVar('start');
        $timestamp = strtotime($start_date);

        $date = array();
        for ($i = 1; $i <= 5; $i++) {
            $date[$i] = date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp) - 1 + $i, date('Y', $timestamp)));
        }

        $course = $this->_course->readCourse(self::MODULE, $this->user_info['user_id'], $date[1], $date[5], $format);

        $this->view->course = $course;
        $this->view->theweek = date('W', strtotime($date[5]));
        $this->view->date = $date;


        if ('calendar' == $format) {
            $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/courseByCalendar');
        } else {
            $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/courseByList');
        }
    }

    /**
     * 某一周的课程安排
     */
    public function courseByWeekAction()
    {
        $user_id = $this->user_info['user_id'];
        $weekstart = $this->post('weekstart');
        $diff = $this->post('diff');
        $format = $this->post('format');
        $date = Models_Course_Course::dateOfSomeWeek($weekstart, $diff);
        $ret = $this->_course->readCourse(self::MODULE, $user_id, $date[1], $date[5], $format);

        $this->view->theweek = date('W', strtotime($date[5]));
        $this->view->date = $date;
        $this->view->course = $ret;

        if ('calendar' == $format) {
            $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/courseByCalendar');
        } else {
            $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/courseByList');
        }
    }

    /**
     * 某一周的课程安排
     */
    public function courseByMonthAction()
    {
        $user_id = $this->user_info['user_id'];
        $themonth = $this->post('themonth');
        $diff = $this->post('diff');
        $format = $this->post('format');
        $date = Models_Course_Course::firstweekOfSomeMonth($themonth, $diff);

        $ret = $this->_course->readCourse(self::MODULE, $user_id, $date[1], $date[5], $format);

        $this->view->theweek = date('W', strtotime($date[5]));
        $this->view->date = $date;
        $this->view->course = $ret;

        if ('calendar' == $format) {
            $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/courseByCalendar');
        } else {
            $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/courseByList');
        }
    }

    /**
     * 学生的每月课程计划或记录
     */
    public function courseForStudentByMonthAction()
    {
        $themonth = $this->post('themonth');
        $diff = $this->post('diff', 0);

        $user_id = $this->user_info['user_id'];
        $date = Models_Course_Course::dataOfSomeMonth($themonth, $diff);
        $ret = $this->_course->courseListByStudent(self::MODULE, $user_id, $date['first_day'], $date['last_day']);
        $this->view->role_code = $this->user_info['role_code'];
        $this->view->course = $ret;
        $this->view->themonth = $themonth;
        $this->view->themonth = date("Y-m", strtotime($date['first_day']));
        $this->tpl();
    }

    /**
     * 课程详情
     */
    public function courseDetailAction()
    {
        $role_code = $this->user_info['role_code'];

        switch ($role_code) {
            case 1:
                $this->courseDetailForStudent();
                break;
            case 2:
                $this->courseDetailForTeacher();
                break;
            case 3:
                $this->courseDetailForStudent();
                break;
            default :
                $this->indexForNoLogin();
        }
    }

    /**
     * 课程详情(教师)
     */
    public function courseDetailForTeacher()
    {
        $this->view->page_title = '课程详情';
        $course_id = $this->getVar('course_id');
        $this->view->course_id = $course_id;
        $course = $this->_course->courseDetail(self::MODULE, $course_id);
        $this->view->course = $course;

        //备课夹
        $lesson_folder_detail = Models_Lesson_Folder::init()->getPreviewResourceHtml($course[0]['lesson_folder_id'], 5);
        $this->view->lesson_folder_detail = $lesson_folder_detail;
        $lesson_folder = Models_Lesson_Folder::init()->load($course[0]['lesson_folder_id']);
        $this->view->lesson_folder = $lesson_folder;

        //课程记录
        $record_content = '';
        if ($course[0]['course_record']) {
            $record = $this->_client->getDataByApi('class/getBlogArticle', array('article_id' => $course[0]['course_record']), false);
            $record_content = $record['data']['article_content'];
        }
        $this->view->record = $record_content;

        //课程评价
        $appraise = $this->_course->courseAppraise(self::MODULE, $course_id);
        $this->view->appraise = $appraise;

        //班级成员数
        $class_num = $this->_client->getDataByApi('class/classMemberNum', array('class_id' => $course[0]['class_id']));
        $this->view->student_num = isset($class_num['data']['student']) ? $class_num['data']['student'] : 0;

        //授课班级   
        $this->view->grade = Models_Grade::$stage_grade_list[$course[0]['course_stage']][$course[0]['course_grade']];
        $this->view->class = $this->_cache_model->classBaseInfo($course[0]['class_id'], 'class_id,class_name');

        $this->setLayout($this->layout);
        $this->view->css = array(
            "popups.css",
        );
        $this->view->js = array(
            "course/circle.js",
            "evaluate/eva_list.js",
            "course/course_detail.js"
        );
        $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/courseDetailForTeacher');
    }

    /**
     * 课程详情（学生）
     */
    public function courseDetailForStudent()
    {
        $this->view->page_title = '课程详情';
        $course_id = $this->getVar('course_id');
        $this->view->course_id = $course_id;
        $course = $this->_course->courseDetail(self::MODULE, $course_id);
        $this->view->course = $course;

        //备课夹
        $lesson_folder_detail = Models_Lesson_Folder::init()->getPreviewResourceHtml($course[0]['lesson_folder_id']);
        $this->view->lesson_folder_detail = $lesson_folder_detail;
        $lesson_folder = Models_Lesson_Folder::init()->load($course[0]['lesson_folder_id']);
        $this->view->lesson_folder = $lesson_folder;

        //课程记录
        $record_content = '';
        if ($course[0]['course_record']) {
            $record = $this->_client->getDataByApi('class/getBlogArticle', array('article_id' => $course[0]['course_record']), false);
            $record_content = $record['data']['article_content'];
        }
        $this->view->record = $record_content;
        $this->view->role_code = $this->user_info['role_code'];

        //课程评价
        $appraise = $this->_course->courseAppraise(self::MODULE, $course_id);
        $this->view->appraise = $appraise;

        //授课班级   
        $this->view->grade = Models_Grade::$stage_grade_list[$course[0]['course_stage']][$course[0]['course_grade']];
        $this->view->class = $this->_cache_model->classBaseInfo($course[0]['class_id'], 'class_id,class_name');

        $this->setLayout($this->layout);
        if ($this->user_info['role_code'] == 1) {
            $this->view->js = array(
                "course/index_student.js",
                "course/course_detail.js",
            );
        } else if ($this->user_info['role_code'] == 3) {
            $this->view->js = array(
                "course/index_student.js",
                "course/course_parent.js",
            );
        }
        $this->tpl('Modules/' . ucfirst(self::MODULE) . '/Views/Course/courseDetailForStudent');
    }

    /**
     * 印象勋章
     */
    public function impressionMedalAction()
    {
        $emotional = $this->getVar('emotional');
        $field = $this->getVar('field');
        $result = $this->_course->medalList($emotional, $field);
        $this->echoJson("success", "获取成功!", $result);
    }

    /**
     * 课程印象
     */
    public function impressionListAction()
    {
        $course_id = $this->getVar('course_id');
        $course = $this->_course->courseDetail(self::MODULE, $course_id);
        $list = $this->_course->impressionListByIds($course[0]['course_impression']);
        $data_format = array(
            'by' => isset($list[1]) ? $list[1] : null,
            'pp' => isset($list[2]) ? $list[2] : null,
        );
        $this->echoJson('success', '获取成功!', array('data' => $data_format));
    }

    /**
     * 课程评价列表
     */
    public function appraiseListAction()
    {
        $course_id = $this->getVar('course_id');
        $page = $this->getVar("p", 1);
        $list = $this->_course->courseAppraiseDetail(self::MODULE, $course_id, $page);
        $this->echoJson("success", "获取成功!", $list);
    }

    /**
     * 课程问答列表
     */
    public function questionListAction()
    {
        $course_id = $this->getVar('course_id');
        $page = $this->getVar("p", 1);
        $list = $this->_course->questionsForCourse(self::MODULE, $course_id, $page);
        $this->echoJson("success", "获取成功!", $list);
    }

    /**
     * 课程评测列表
     */
    public function evaluateListAction()
    {
        $course_id = $this->getVar('course_id', 148);
        $page = $this->getVar("p", 1);
        $list = $this->_course->evaluateListByCourse(self::MODULE, $course_id, $page);
        $this->echoJson("success", "获取成功!", $list);
    }

    /**
     * 更新课程记录
     */
    public function recordUpdateAction()
    {
        $record = $this->post('content');
        $course_id = $this->post('course_id');
        $result = $this->_course->updateCourseRecord(self::MODULE, $course_id, $record, $this->user_info['user_id']);

        if ('success' == $result['type']) {
            $this->echoJson('success', '编辑成功！', array());
        } else {
            $this->echoJson('error', '编辑失败！', array());
        }
    }

    /**
     * 新增课程评价
     */
    public function addAppraiseAction()
    {
        $course_id = $this->post('course_id');
        $score = $this->post('score', 0);
        $remark = $this->post('remark');
        $user_id = $_SESSION['user']['user_id'];
        $role = $_SESSION['user']['role_code'];
        $add = $this->_course->addAppraise(self::MODULE, $course_id, $score, $remark, $user_id, $role);
        if ($add) {
            $this->echoJson('success', '添加成功！');
        } else {
            $this->echoJson('error', '您已经提交过评价！');
        }
    }

    /**
     * 发表课程相关问题
     */
    public function addQuestionForCourseAction()
    {
        $course_id = $this->getVar('course_id');
        $question = $this->getVar('content');
        $user_id = $this->user_info['user_id'];
        $result = $this->_course->addQuestionForCourse(self::MODULE, $course_id, $question, $user_id);
        if ($result) {
            $this->echoJson('success', '添加成功！', array());
        } else {
            $this->echoJson('error', '添加失败！', array());
        }
    }

    /**
     * 课程相关知识节点的单元测评选项
     */
    public function evaluateOptionsForCourseAction()
    {
        $course_id = $this->getVar('course_id');
        $options = $this->_course->evaluateOptionsForCourse(self::MODULE, $course_id);
        !$options && $options = array();
        $this->echoJson('success', '获取成功！', (array) $options);
    }

    /**
     * 添加课程评测
     */
    public function addEvaluateForCourseAction()
    {
        $course_id = $this->getVar('course_id');
        $evaluate_ids = $this->getVar('evaluate_ids');
        $result = $this->_course->addEvaluateForCourse(self::MODULE, $this->user_info['user_id'], $course_id, $evaluate_ids);
        if ($result) {
            $this->echoJson('success', '添加成功！', array());
        } else {
            $this->echoJson('error', '添加失败！', array());
        }
    }

    /**
     * 课程对应的学生列表
     */
    public function studentListByCourseAction()
    {
        $course_id = $this->getVar('course_id');
        $course_detail = $this->_course->courseDetail(self::MODULE, $course_id);
        $list = $this->_client->getDataByApi('class/studentList', array('class_id' => $course_detail[0]['class_id']), false);
        $data = $list['data'] ? $list['data'] : array();
        $this->echoJson('success', '获取成功！', $data);
    }

    /**
     * 添加课程印象
     */
    public function addImpressionForCourseAction()
    {
        $emotional = $this->post('emotional');
        $field = $this->post('field');
        $medal_id = $this->post('medal_id');
        $medal_name = $this->post('medal_name');
        $student_ids = $this->post('user_id');
        $course_id = $this->post('course_id');

        $result = $this->_course->addImpressionForCourse(self::MODULE, $emotional, $field, $medal_id, $medal_name, $student_ids, $this->user_info['user_id'], $course_id);
        if ('success' == $result['type']) {
            $this->echoJson('success', '添加成功！', array());
        } else {
            $this->echoJson('error', '添加失败！', array());
        }
    }

}
