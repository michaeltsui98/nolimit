<?php

/**
 * 课程数据库类
 *
 * @author wuya <ourzu1987@gmail.com>
 * @version 2014-4-22
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Models_Course_Course extends Cola_Model
{

    private $_client = null;
    //科目映射
    static public $subject_config = array(
        'xl' => Models_Subject::XL,
        'sm' => Models_Subject::SM
    );
//    //班级印象选项列表
//    static public function impressionsOptions()
//    {
//        /*
//          return array(
//          3 => array(
//          75 => array(
//          'title' => '团结同学',
//          'img' => HTTP_MFS_IMG . 'glory/51e63f9437eae.png',
//          'type' => 3,
//          ),
//          74 => array(
//          'title' => '认真作业',
//          'img' => HTTP_MFS_IMG . 'glory/51e63f8a9cfa3.png',
//          'type' => 3,
//          ),
//          73 => array(
//          'title' => '认真值日',
//          'img' => HTTP_MFS_IMG . 'glory/51e63f7ed53d9.png',
//          'type' => 3,
//          ),
//          72 => array(
//          'title' => '认真听讲',
//          'img' => HTTP_MFS_IMG . 'glory/51e63f6dda100.png',
//          'type' => 3,
//          ),
//          71 => array(
//          'title' => '认真发言',
//          'img' => HTTP_MFS_IMG . 'glory/51e63f2b56c91.png',
//          'type' => 3,
//          ),
//          ),
//          4 => array(
//          76 => array(
//          'title' => '不认真发言',
//          'img' => HTTP_MFS_IMG . 'glory/51e63fc2c903b.png',
//          'type' => 4,
//          ),
//          77 => array(
//          'title' => '不认真听讲',
//          'img' => HTTP_MFS_IMG . 'glory/51e63fcb6ccee.png',
//          'type' => 4,
//          ),
//          78 => array(
//          'title' => '不认真值日',
//          'img' => HTTP_MFS_IMG . 'glory/51e63fd34fd52.png',
//          'type' => 4,
//          ),
//          79 => array(
//          'title' => '不认真作业',
//          'img' => HTTP_MFS_IMG . 'glory/51e63fdc4b719.png',
//          'type' => 4,
//          ),
//          80 => array(
//          'title' => '不团结同学',
//          'img' => HTTP_MFS_IMG . 'glory/51e63fe48b297.png',
//          'type' => 4,
//          ),
//          )
//          );
//         */
//
//        return array(
//            3 => array(
//                57 => array(
//                    'title' => '认真发言',
//                    'img' => HTTP_MFS_IMG . 'glory/51e64f6e4ebf7.png',
//                    'type' => 3,
//                ),
//                58 => array(
//                    'title' => '认真听讲',
//                    'img' => HTTP_MFS_IMG . 'glory/51e64fa142fd8.png',
//                    'type' => 3,
//                ),
//                59 => array(
//                    'title' => '认真值日',
//                    'img' => HTTP_MFS_IMG . 'glory/51e64faea25ea.png',
//                    'type' => 3,
//                ),
//                60 => array(
//                    'title' => '认真作业',
//                    'img' => HTTP_MFS_IMG . 'glory/51e64fbbf18ed.png',
//                    'type' => 3,
//                ),
//                61 => array(
//                    'title' => '团结同学',
//                    'img' => HTTP_MFS_IMG . 'glory/51e64fc904842.png',
//                    'type' => 3,
//                ),
//            ),
//            4 => array(
//                62 => array(
//                    'title' => '不认真发言',
//                    'img' => HTTP_MFS_IMG . 'glory/51e6517620838.png',
//                    'type' => 4,
//                ),
//                63 => array(
//                    'title' => '不认真听讲',
//                    'img' => HTTP_MFS_IMG . 'glory/51e65183ba8a5.png',
//                    'type' => 4,
//                ),
//                64 => array(
//                    'title' => '不认真值日',
//                    'img' => HTTP_MFS_IMG . 'glory/51e6518d62c36.png',
//                    'type' => 4,
//                ),
//                65 => array(
//                    'title' => '不认真作业',
//                    'img' => HTTP_MFS_IMG . 'glory/51e6519f6e2d4.png',
//                    'type' => 4,
//                ),
//                66 => array(
//                    'title' => '不团结同学',
//                    'img' => HTTP_MFS_IMG . 'glory/51e651aead65c.png',
//                    'type' => 4,
//                ),
//            )
//        );
//    }
    //评价种类
    public static $emotional = array(
        '1' => '表扬',
        '2' => '批评',
    );
    //评价维度
    public static $evaluate_field = array(
        '1' => '品德发展',
        '2' => '学业发展',
        '3' => '身心发展',
        '4' => '兴趣特长',
        '5' => '实践能力',
    );

    public function __construct()
    {
        $this->_client = new Models_DDClient();
    }

    /**
     * 勋章列表
     * @param int $emotional
     * @param int $evaluate_field
     * @return type
     */
    public function medalList($emotional, $evaluate_field)
    {
        $data = $this->_client->getDataByApi('class/medalListForEvaluate', array('medal_emotional' => $emotional, 'medal_field' => $evaluate_field));
        return (array) $data['data'];
    }

    /**
     * 课堂印象列表
     * @param type $evaluate_ids
     * @return type
     */
    public function impressionListByIds($evaluate_ids)
    {
        $data = $this->_client->getDataByApi('class/evaluateByIds', array('evaluate_ids' => $evaluate_ids));
        return (array) $data['data'];
    }

    /**
     * 保存课程设置
     * @param string $module eg:sm or xl
     * @param array $data
     * @return boolean || int
     */
    public function saveCourse($module, $data)
    {
        $table = $module . "_course";

        //参数格式
        $data_format = array(
            'class_id' => '',
            'teacher_user_id' => '',
            'course_grade' => '',
            'course_stage' => '',
            'course_week' => '',
            'course_date' => '',
            'course_sort' => '',
            'lesson_folder_id' => '',
            'course_lesson_folder_status' => '',
            'city_id' => '',
            'town_id' => '',
            'school_id' => '',
            'course_remark' => ''
        );

        //参数过滤
        if (!$this->checkParam($data, $data_format)) {
            return false;
        }

        $query = "select `id` from `{$table}` where `teacher_user_id`='{$data['teacher_user_id']}' and `course_date`='{$data['course_date']}'"
                . "and `course_sort`='{$data['course_sort']}'";
        $course_id = $this->db->col($query);

        if ($course_id) {

            //更新
            $where = "`teacher_user_id`='{$data['teacher_user_id']}' and `course_date`='{$data['course_date']}'"
                    . "and `course_sort`='{$data['course_sort']}'";
            unset($data['teacher_user_id']);
            unset($data['course_date']);
            unset($data['course_sort']);

            $save = $this->db->update($data, $where, $table);
        } else {
            //插入
            $save = $this->insert($data, $module . "_course");
            $course_id = $save;
        }

        //建立班级关系(若已存在关系，则新建失败)
        if ($save) {
            $subject = Models_Subject::$subject_map_for_circle[$module][$data['course_stage']];
            $this->_client->getDataByApi('class/joinClass', array('user_id' => $data['teacher_user_id'], 'role_code' => 2, 'class_id' => $data['class_id'], 'subject' => $subject), FALSE);

            return $course_id;
        } else {
            return false;
        }
    }

    /**
     * 更新课程属性
     * @param string $module
     * @param int $course_id
     * @param array $data
     */
    public function updateCourse($module, $course_id, $data)
    {
        $table = $module . "_course";

        $where = "`id`='{$course_id}'";

        return $this->db->update($data, $where, $table);
    }

    /**
     * 读取时间段内的课程
     * @param string $module 
     * @param int $class_id
     * @param string $user_id
     * @param string $start_date
     * @param string $end_date
     * @param string $format 返回数据的格式
     * @return type
     */
    public function readCourse($module, $user_id, $start_date, $end_date, $format = 'calendar')
    {
        $table = $module . '_course';
        $appraise_table = $module . '_course_appraise';

        $query = "select `id`,`class_id`,`course_remark`,`teacher_user_id`,`course_grade`,`course_week`,`course_date`,`course_sort`,`lesson_folder_id`,`course_lesson_folder_status`"
                . ", `course_appraise_status`,`course_question_status` from `{$table}` where  `teacher_user_id`= '{$user_id}' and `course_date` between '{$start_date}' and '{$end_date}'";
        $course = $this->sql($query);

        $ret_week = array();
        $ret_sort = array();
        $class_id_list = array();

        if (!empty($course)) {

            foreach ((array) $course as $value) {
                //按天分组
                $ret_week[$value['course_week']][$value['course_sort']] = $value;
                //按节次分组
                $ret_sort[$value['course_sort']][$value['course_week']] = $value;
                //班级ID
                $class_id_list[$value['class_id']] = $value['class_id'];
            }

            //每天的节数
            $ret_length = array();
            foreach ((array) $ret_week as $key => $value) {
                $ret_length[$key] = max(array_keys($value));
            }
            $max_length = (max($ret_length) < 7) ? 7 : max($ret_length);
        } else {
            $max_length = 7;
        }

        for ($sort = 1; $sort <= $max_length; $sort++) {
            for ($week = 1; $week <= 5; $week++) {
                !isset($ret_sort[$sort][$week]) && $ret_sort[$sort][$week] = array();
            }
        }

        //补充班级名称
        $class_list = Models_Course_Cache::init()->classBaseInfoMulti($class_id_list, 'class_name,class_id');
        $class = array();
        foreach ((array) $class_list as $value) {
            isset($value[0][1]) && $class[$value[0][1]] = $value[0][0];
        }

        switch ($format) {

            case 'calendar':

                //日历格式返回结果
                ksort($ret_sort);
                foreach ($ret_sort as $key => $value) {
                    foreach ($value as $s_key => $s_value) {
                        if (!empty($s_value)) {
                            $ret_sort[$key][$s_key]['class_name'] = isset($class[$s_value['class_id']]) ? $class[$s_value['class_id']] : '';
                        }
                    }
                    ksort($ret_sort[$key]);
                }
                return $ret_sort;
                break;

            case 'list':
                ksort($ret_week);
                foreach ($ret_week as $key => $value) {
                    foreach ($value as $s_key => $s_value) {
                        if (!empty($s_value)) {
                            $ret_week[$key][$s_key]['class_name'] = isset($class[$s_value['class_id']]) ? $class[$s_value['class_id']] : '';

                            //评价总数
                            $where = "`course_id`='{$s_value['id']}' ";
                            $count = $this->count($where, $appraise_table);
                            $ret_week[$key][$s_key]['appraise_count'] = $count;

                            //问答
                            $param = array(
                                'app_obj_id' => (int) $s_value['id'],
                                'subject' => self::$subject_config[$module],
                                'app_obj_type' => 'course',
                            );
                            $count = Models_Question_Interfaces::init()->getAllAskCount($param);
                            $ret_week[$key][$s_key]['question_count'] = $count['data'];

                            //备课夹标题
                            $folder_info = Models_Lesson_Folder::init()->load($s_value['lesson_folder_id']);
                            $ret_week[$key][$s_key]['lesson_folder_title'] = $folder_info['title'];
                        }
                    }
                    ksort($ret_week[$key]);
                }
                return $ret_week;
                break;
            default :
                return array();
        }
    }

    /**
     * 学生的课程记录
     * @param string $module
     * @param string $student_id
     * @param string $start_date
     * @param string $end_date
     * @param array $format
     */
    public function courseListByStudent($module, $student_id, $start_date, $end_date, $parent_id = null)
    {
        $class_list = Models_Course_Cache::init()->currentClassList($student_id);
        $class_id = isset($class_list[0]['class_id']) ? $class_list[0]['class_id'] : '';

        $table = $module . '_course';

        $query = "select `id`,`class_id`,`teacher_user_id`,`course_grade`,`course_week`,`course_date`,`course_sort`,`lesson_folder_id`,`course_lesson_folder_status`"
                . "from `{$table}` where  `class_id`= '{$class_id}' and `course_date` between '{$start_date}' and '{$end_date}' order by `course_date`ASC,`course_sort` ASC ";
        $course = $this->sql($query);
        foreach ((array) $course as $key => $value) {

            //是否评价
            $appraise_user = ($parent_id == null) ? $student_id : $parent_id;
            $course[$key]['is_appraise'] = $this->checkAppraise($module, $value['id'], $appraise_user);

            //备课夹信息
            $lesson_folder_info = $this->lessonFolderInfo($value['lesson_folder_id']);
            $course[$key]['chapter_title'] = $lesson_folder_info['chapter_title'];
            $course[$key]['node_title'] = $lesson_folder_info['node_title'];

            //评分情况
            $course[$key]['appraise'] = $this->appraiseInfoForCourse($module, $value['id']);

            //任课老师信息
            $user_info = Models_Course_Cache::init()->getUserInfo($value['teacher_user_id']);
            $course[$key]['teacher_user_name'] = $user_info['data']['user_realname'];
        }

        return $course;
    }

    /**
     * 判断当前用户对课程有无评价
     * @param string $module
     * @param int $course_id
     * @param string $user_id
     * @return boolean
     */
    private function checkAppraise($module, $course_id, $user_id)
    {
        $table = $module . "_course_appraise";
        $where = " `appraise_user_id`='{$user_id}' and  `course_id`='{$course_id}' ";

        $count = $this->count($where, $table);
        return ($count > 0);
    }

    /**
     * 备课夹详情
     * @param int $lesson_folder_id
     * @return array
     */
    public function lessonFolderInfoDb($lesson_folder_id)
    {
        $folder_info = Models_Lesson_Folder::init()->load($lesson_folder_id);

        //章节和知识节点详情
        $resource_model = new Models_Resource();
        $chapter_title = $resource_model->getUnitTitleById($folder_info['chapter']);
        $node_title = $resource_model->getUnitTitleById($folder_info['node']);
        $folder_info['chapter_title'] = isset($chapter_title['data']) ? $chapter_title['data'] : '';
        $folder_info['node_title'] = isset($node_title['data']) ? $node_title['data'] : '';

        return $folder_info;
    }

    /**
     * 备课夹详情(缓存)
     * @param int $lesson_folder_id
     * @return array
     */
    public function lessonFolderInfo($lesson_folder_id)
    {
        $rs = array($lesson_folder_id);
        return $this->cached('lessonFolderInfoDb', $rs, 3600);
    }

    /**
     * 课程评价
     * @param string $module
     * @param int $course_id
     */
    public function appraiseInfoForCourse($module, $course_id)
    {
        //表名
        $table = $module . "_course_appraise";

        //评价总分数
        $sum = $this->db->col("select SUM(`appraise_score`) as `sum` from `{$table}` where `course_id`='{$course_id}'");

        //评价总数
        $where = "`course_id`='{$course_id}' ";
        $count = $this->count($where, $table);

        return array(
            'count' => $count,
            'sum' => $sum,
            'average' => $count ? round(($sum / $count), 1) : 0
        );
    }

    /**
     * 当前周的日期
     * @return array
     */
    static public function dateOfCurrentWeek()
    {
        $date = array();
        $week = date('w');
        for ($i = 1; $i <= 5; $i++) {
            $date[$i] = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d') - $week + $i, date('Y')));
        }
        return $date;
    }

    /**
     * 某一周的日期
     * @param int $theweek
     * @return array
     */
    static public function dateOfSomeWeek($weekstart, $diff)
    {
        $timestamp = strtotime($weekstart . $diff . ' week');

        for ($i = 1; $i <= 5; $i++) {
            $date[$i] = date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp), date('d', $timestamp) - 1 + $i, date('Y', $timestamp)));
        }
        return $date;
    }

    /**
     * 当前月份的日期
     * @return tarray
     */
    static public function dataOfSomeMonth($themonth, $diff = 0)
    {
        $themonth = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($themonth)), 1, date('Y', strtotime($themonth))));
        $timestamp = strtotime($themonth . $diff . ' month');
        $first_day = date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
        $last_day = date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp) + 1, 0, date('Y', $timestamp)));
        return array('first_day' => $first_day, 'last_day' => $last_day);
    }

    /**
     * 某月的第一周
     * @param int $themonth
     * @return array
     */
    static public function firstweekOfSomeMonth($themonth, $diff)
    {
        $themonth = date('Y-m-d', mktime(0, 0, 0, date('m', strtotime($themonth)), 1, date('Y', strtotime($themonth))));

        $timestamp = strtotime($themonth . $diff . ' month');
        $firstday = mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp));

        $diff = date('w', $firstday) - 1;
        //周六、日则从下周一开始
        date('w', $firstday) > 5 && $diff = date('w', $firstday) - 8;

        for ($i = 1; $i <= 5; $i++) {
            $date[$i] = date('Y-m-d', mktime(0, 0, 0, date('m', $timestamp), - $diff + $i, date('Y', $timestamp)));
        }
        return $date;
    }

    /**
     * 课程记录详情
     * @param string $module
     * @param int $course_id
     * @return array
     */
    public function courseDetail($module, $course_id)
    {
        $table = $module . '_course';
        $query = "select `class_id`,`course_remark`,`teacher_user_id`,`course_week`,`course_date`,`course_sort`,`course_record`,`lesson_folder_id`,`course_lesson_folder_status`,`course_grade`,`course_stage`"
                . ", `course_impression` from `{$table}`  where `id`='{$course_id}' limit 0,1";
        $record = $this->sql($query);

        return $record;
    }

    /**
     * 更新课程记录
     * @param string $module
     * @param int $course_id
     * @param int $record
     * @return array
     */
    public function updateCourseRecord($module, $course_id, $record, $user_id, $file = null)
    {
        $course_detail = $this->courseDetail($module, $course_id);
        $subject_name = Models_Subject::$subject_list[self::$subject_config[$module]];
        $title = "《" . $subject_name . "》课程记录";
        $class_id = $course_detail[0]['class_id'];

        //对图片附件进行处理
        if (!empty($_FILES)) {
            $result = Models_Attachment::init()->uploadToDodo('class', $class_id);
            foreach ((array) $result as $key => $value) {
                $record .= '<img src="' . $value['path'] . '" />';
            } 
            
        }
        $client = new Models_DDClient();
        if ($course_detail[0]['course_record']) {

            //更新课程记录
            $client->getDataByApi('class/editClassArticle', array('article_id' => $course_detail[0]['course_record'], 'class_id' => $class_id, 'content' => $record, 'title' => $title, 'attachment' => $file), false);

            return array('type' => 'success');
        } else {

            //课程记录所在的博客分类
            $sorts_ret = $client->getDataByApi('class/blogSortsByTypeAndClassId', array('class_id' => $class_id, 'type' => $module), false);

            if (isset($sorts_ret['data'][0]['class_id']) && $sorts_ret['data'][0]['class_id']) {

                $sort_id = $sorts_ret['data'][0]['class_id'];
            } else {

                //新建课程记录对应的班级博客分类
                $sort_add = $client->getDataByApi('class/addBlogSort', array('class_id' => $class_id, 'sort_name' => '课程记录', 'sort_type' => $module, 'user_id' => $user_id), false);
                $sort_id = $sort_add['data']['data'];
            }

            //新增课程记录
            $add = $client->getDataByApi('class/addClassArticle', array('class_id' => $class_id, 'sort_id' => $sort_id, 'content' => $record, 'title' => $title, 'user_id' => $user_id, 'attachment' => $file), false);

            if (isset($add['data']['status']) && $add['data']['status']) {

                $table = $module . '_course';

                $record_id = $add['data']['article_id'];

                $data = array(
                    'course_record' => $record_id
                );
                //更新
                $where = "`id`='{$course_id}' ";

                $this->db->update($data, $where, $table);

                return array('type' => 'success');
            }
        }
    }

    /**
     * 检查备课夹是否能修改
     * @param string $subject_id 科目ID
     * @param int $lesson_folder_id 备课夹ID
     * @return boolean
     */
    public function lessonFolderCheck($subject_id, $lesson_folder_id)
    {
        $course_table_prefix = array(Models_Subject::SM => 'sm', Models_Subject::XL => 'xl',);
        $table = $course_table_prefix[$subject_id] . '_course';
        $date = date('Y-m-d');
        $query = "select count(1) as count from `{$table}` where `course_lesson_folder_status`=1 and `lesson_folder_id`='{$lesson_folder_id}'"
                . "and `course_date`<='{$date}' ";
        $count = $this->sql($query);
        return $count[0]['count'] > 0;
    }

    /**
     * 通过班级ID获取班级的学段和年级
     * @param int $class_id
     * @return array
     */
    public function gradeAndStageByClassId($class_id)
    {
        $class_info = Models_Course_Cache::init()->classBaseInfo($class_id, 'class_type,class_year');
        $xd_ret = array('1' => Models_Stage::XX, '2' => Models_Stage::CZ, '3' => Models_Stage::GZ);
        $grade = (Models_Grade::getGradeCodeByYear($xd_ret[$class_info[0]], $class_info[1]));
        $stage = $xd_ret[$class_info['data'][0]];
        return array('grade' => $grade, 'stage' => $stage);
    }

    /**
     * 检查参数
     * @param array $param
     * @param array $param_format
     * @return boolean
     */
    protected function checkParam($param, $param_format)
    {
        if (array_diff_key($param, $param_format) !== array() || array_diff_key($param_format, $param) !== array()) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 发表评论
     * @param string $module
     * @param int $course_id
     * @param int $score
     * @param string $remark
     * @param string $user_id
     * @param int $role
     * @return array
     */
    public function addAppraise($module, $course_id, $score, $remark, $user_id, $role)
    {
        //表名
        $table = $module . "_course_appraise";

        //不能重复评价
        $court = $this->count("`course_id`='{$course_id}' and `appraise_user_id`='{$user_id}'", $table);
        if ($court) {
            return false;
        }

        //参数
        $param = array(
            'course_id' => $course_id,
            'appraise_score' => $score,
            'appraise_remark' => $remark,
            'appraise_user_id' => $user_id,
            'appraise_role' => $role,
            'appraise_time' => time()
        );
        //插入评价记录
        if ($this->insert($param, $module . "_course_appraise")) {
            //更新课程状态
            $this->db->update(array('course_appraise_status' => 1), "`id`='{$course_id}' ", $module . "_course");
            return true;
        } else {
            return false;
        }
    }

    /**
     * 班级印象详情
     * @param string $impression_ids
     * @return array
     */
    public function impressionByIds($impression_ids)
    {
        $client = new Models_DDClient();
        return $client->getDataByApi('class/impressionListByIds', array('impression_ids' => $impression_ids), false);
    }

    /**
     * 课程统计
     * @param string $module
     * @param string $teacher_id
     * @param string $start_time
     * @return int
     */
    public function courseStat($module, $teacher_id, $start_time = NULL, $end_time = NULL)
    {
        //表名
        $table = $module . "_course";

        $where = "`teacher_user_id`='{$teacher_id}' ";

        if ($start_time && $end_time) {
            $where.=" and `course_date` between '{$start_time}' and '{$end_time}'";
        }

        //时间段的课程统计
        $court = $this->count($where, $table);

        return $court;
    }

    /**
     * 课程评价
     * @param string $module
     * @param int $course_id
     * @return array
     */
    public function courseAppraise($module, $course_id)
    {
        //表名
        $table = $module . "_course_appraise";

        //评价总分数
        $sum = $this->db()->row("select SUM(`appraise_score`) as `sum` from `{$table}` where `course_id`='{$course_id}'");

        //评价总数
        $where = "`course_id`='{$course_id}' ";
        $count = $this->count($where, $table);
        return array(
            'total' => $count,
            'score' => $count ? round(($sum['sum'] / $count), 1) : 0,
        );
    }

    /**
     * 课程评价详情
     * @param string $module
     * @param int $course_id
     * @param int $page
     * @return array
     */
    public function courseAppraiseDetail($module, $course_id, $page = 1, $num = 5)
    {
        //表名
        $table = $module . "_course_appraise";

        //评价详情
        $start = ($page - 1) * $num;
        $total = $this->count("`course_id`='{$course_id}'", $table);
        $list = $this->sql("select  `appraise_user_id`,`appraise_score`,`appraise_remark`,`appraise_time` from `{$table}` where `course_id`='{$course_id}' limit {$start},{$num}");
        foreach ((array) $list as $key => $value) {
            $user_info = Models_Course_Cache::init()->getUserInfo($value['appraise_user_id']);
            $list[$key]['appraise_user_info'] = (array) $user_info['data'];
        }
        return array(
            'data' => $list,
            'count' => count($list),
            'limit' => $num,
            'total' => $total,
        );
    }

    /**
     * 增加课程问答
     * @param string $module
     * @param int $course_id
     * @param string $title
     * @return array
     */
    public function addQuestionForCourse($module, $course_id, $title, $user_id)
    {
        $course_detail = $this->courseDetail($module, $course_id);

        $base_data = array(
            'Section' => $course_detail[0]['course_stage'],
            'Grade' => $course_detail[0]['course_grade'],
            'Subject' => self::$subject_config[$module],
            'app_obj_id' => (int) $course_id,
        );

        $data = array(
            'question_title' => $title,
            'app_obj_type' => 'course',
            'user_id' => $user_id,
            'num' => 5,
        );

        //插入问答记录
        $question_model = new Models_Question_Interfaces();
        if ($question_model->baseData($base_data)->addQuestion($data)) {
            //更新课程状态
            $this->db->update(array('course_question_status' => 1), "`id`='{$course_id}' ", $module . "_course");
            return true;
        } else {
            return false;
        }
    }

    /**
     * 课程相关的问答
     * @param string $module
     * @param int $course_id
     * @param int $page
     * @param int $num
     */
    public function questionsForCourse($module, $course_id, $page = 1, $num = 5)
    {
        $param = array(
            'app_obj_id' => (int) $course_id,
            'p' => (int) $page,
            'num' => (int) $num,
            'subject' => self::$subject_config[$module],
            'app_obj_type' => 'course',
            'get_tag' => 1
        );

        $question_model = new Models_Question_Interfaces();
        $questions = $question_model->getQuestion($param);
        $total = $questions['count'];
        unset($questions['count']);

        return array('data' => $questions, 'count' => count($questions), 'limit' => 5, 'total' => $total);
    }

    /**
     * 课程相关知识节点的单元测评选项
     * @param string $module
     * @param int $course_id
     * @return array
     */
    public function evaluateOptionsForCourse($module, $course_id)
    {
        $course_detail = $this->courseDetail($module, $course_id);
        $folder = Models_Lesson_Folder::init()->load($course_detail[0]['lesson_folder_id']);

        if (!isset($folder['chapter'])) {
            return array();
        }
        //知识单元或知识节点
        $knowledge = ('xl' == $module) ? $folder['chapter'] : $folder['node'];
        if (!$knowledge) {
            return array();
        }
        return Models_Evaluate_Evaluate::init()->getEvaluateListByClassify(self::$subject_config[$module], 2, (int) $knowledge, $course_id, 0, 10);
    }

    /**
     * 新增课程评测
     * @param string $module
     * @param string $user_id
     * @param int $course_id
     * @param string $evaluate_ids
     * @return boolean
     */
    public function addEvaluateForCourse($module, $user_id, $course_id, $evaluate_ids)
    {
        $course_detail = $this->courseDetail($module, $course_id);
        $folder = Models_Lesson_Folder::init()->load($course_detail[0]['lesson_folder_id']);

        if (!isset($folder['chapter'])) {
            return false;
        }
        //知识单元或知识节点
        $knowledge = ('xl' == $module) ? $folder['chapter'] : $folder['node'];
        if (!$knowledge) {
            return false;
        }

        $add = Models_Evaluate_EvaluateUnit::init()->addUnitEvaluate($user_id, $course_detail[0]['class_id'], $knowledge, $evaluate_ids, 0, self::$subject_config[$module], $course_id, $folder['edition']);
        if ($add) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 课程的评测列表
     * @param string $module
     * @param int $course_id
     * @param int $page
     * @return array
     */
    public function evaluateListByCourse($module, $course_id, $page = 1)
    {
        $course_detail = $this->courseDetail($module, $course_id);
        $folder = Models_Lesson_Folder::init()->load($course_detail[0]['lesson_folder_id']);
        $list = Models_Evaluate_EvaluateUnit::init()->getUnitEvaluateListByCourseId($course_id, self::$subject_config[$module], $folder['edition'], $course_detail[0]['class_id'], ($page - 1) * 5, 5);
        return array('data' => $list['data'], 'count' => count($list['data']), 'limit' => 5);
    }

    /**
     * 备课夹列表
     * @param string $module
     * @param string $user_id
     * @param string $stage
     * @param string $grade
     * @param string $edition
     * @param int $page
     * @return array
     */
    public function lessonFolderList($module, $user_id, $stage, $grade, $edition, $page, $num = 5)
    {
        $folder_model = new Models_Lesson_Folder();
        $extend_conditions = array();

        //版本
        self::$subject_config[$module] && $extend_conditions['subject'] = self::$subject_config[$module];

        //学段
        $stage && $extend_conditions['stage'] = $stage;

        //年级
        $grade && $extend_conditions['grade'] = $grade;

        //版本
        $edition && $extend_conditions['edition'] = $edition;

        $list = $folder_model->getUserFolderList($user_id, ($page - 1) * $num, $num, $extend_conditions);


        return $list;
    }

    /**
     * 添加课程印象
     * @param string $module
     * @param int $type
     * @param int $icon_id
     * @param string $student_ids
     * @param string $user_id
     * @param int $course_id
     * @return boolean
     */
    public function addImpressionForCourse($module, $emotional, $field, $medal_id, $medal_name, $student_ids, $user_id, $course_id)
    {
        $course_detail = $this->courseDetail($module, $course_id);

        $param = array(
            'emotional' => $emotional,
            'field' => $field,
            'medal_id' => $medal_id,
            'medal_name' => $medal_name,
            'user_id' => $student_ids,
            'class_id' => $course_detail[0]['class_id'],
            'post_user_id' => $user_id,
            'notify' => 1,
        );

        //调用接口添加印象
        $result = $this->_client->getDataByApi('class/evaluatePost', $param);
        if ($result['data']['status'] && $result['data']['data']) {
            //合并历史印象
            if ($course_detail[0]['course_impression']) {
                $course_detail[0]['course_impression'].=',' . $result['data']['data'];
            } else {
                $course_detail[0]['course_impression'] = $result['data']['data'];
            }
        }

        //更新课程的印象关联字段
        $data = array('course_impression' => $course_detail[0]['course_impression']);
        $where = "`id`='{$course_id}'";
        $table = $module . '_course';
        $this->db->update($data, $where, $table);

        return array('type' => 'success');
    }

    /**
     * 课程评价统计(市州)
     */
    public function statForAppraiseByCity($module)
    {
        //课程主表
        $course_table = $module . "_course";
        //评价表
        $appraise_table = $module . "_course_appraise";

        //分市统计
        $query = "select SUM(t1.`appraise_score`) as `sum` ,count(t2.`id`) as count,t2.city_id,t2.`course_stage` from `{$appraise_table}` t1,`{$course_table}` t2 where t1.`course_id`=t2.`id` "
                . "group by t2.`city_id`,t2.`course_stage`";

        $data = $this->sql($query);

        //计算平均分
        $score_list = array();
        $allowed_stage = array_keys(Models_Stage::$stage_list);
        foreach ((array) $data as $value) {
            if ($value['city_id'] && in_array($value['course_stage'], $allowed_stage)) {
                $score_list[$value['city_id']][$value['course_stage']]['score'] = $value['count'] ? round($value['sum'] / $value['count'], 1) : 0;
                $score_list[$value['city_id']][$value['course_stage']]['count'] = $value['count'];
            }
        }

        //全省市州列表        
        $city_list = Models_Course_Cache::init()->cityList();

        //市州列表添加评分字段
        foreach ((array) $city_list as $key => $value) {
            $city_list[$key]['xd001_count'] = isset($score_list[$value['code']]['xd001']['count']) ? $score_list[$value['code']]['xd001']['count'] : 0;
            $city_list[$key]['xd001_score'] = isset($score_list[$value['code']]['xd001']['score']) ? $score_list[$value['code']]['xd001']['score'] : 0;
            $city_list[$key]['xd002_count'] = isset($score_list[$value['code']]['xd002']['count']) ? $score_list[$value['code']]['xd002']['count'] : 0;
            $city_list[$key]['xd002_score'] = isset($score_list[$value['code']]['xd002']['score']) ? $score_list[$value['code']]['xd002']['score'] : 0;
            $city_list[$key]['xd003_count'] = isset($score_list[$value['code']]['xd003']['count']) ? $score_list[$value['code']]['xd003']['count'] : 0;
            $city_list[$key]['xd003_score'] = isset($score_list[$value['code']]['xd003']['score']) ? $score_list[$value['code']]['xd003']['score'] : 0;
        }
        return array_values($city_list);
    }

    /**
     * 开课情况统计
     * @param string $module
     * @return array
     */
    public function statForCourseCountByCity($module)
    {
        //课程主表
        $course_table = $module . "_course";

        $allowed_stage = array_keys(Models_Stage::$stage_list);

        //课程总数统计
        $query_total = "select count(`id`) as count,`city_id`,`course_stage` from `{$course_table}` "
                . "group by `city_id`,`course_stage`";

        $data = $this->sql($query_total);
        $stat_total = array();
        foreach ((array) $data as $value) {
            if ($value['city_id'] && in_array($value['course_stage'], $allowed_stage)) {
                $stat_total[$value['city_id']][$value['course_stage']] = $value['count'];
            }
        }

        //备课统计
        $query_lesson_folder = "select count(`id`) as count,`city_id`,`course_stage` from `{$course_table}` where `course_lesson_folder_status`=1 "
                . "group by `city_id`,`course_stage`";
        $data = $this->sql($query_lesson_folder);
        $stat_lesson_folder = array();
        foreach ((array) $data as $value) {
            if ($value['city_id'] && in_array($value['course_stage'], $allowed_stage)) {
                $stat_lesson_folder[$value['city_id']][$value['course_stage']] = $value['count'];
            }
        }


        //课程记录统计
        $query_record = "select count(`id`) as count,`city_id`,`course_stage` from `{$course_table}` where `course_record` is not null "
                . "group by `city_id`,`course_stage`";
        $data = $this->sql($query_record);
        $stat_record = array();
        foreach ((array) $data as $value) {
            if ($value['city_id'] && in_array($value['course_stage'], $allowed_stage)) {
                $stat_record[$value['city_id']][$value['course_stage']] = $value['count'];
            }
        }

        //全省市州列表
        $city_list = Models_Course_Cache::init()->cityList();

        foreach ($city_list as $key => $value) {
            $city_list[$key]['xd001_total'] = isset($stat_total[$value['code']]['xd001']) ? $stat_total[$value['code']]['xd001'] : 0;
            $city_list[$key]['xd001_lesson_folder'] = isset($stat_lesson_folder[$value['code']]['xd001']) ? $stat_lesson_folder[$value['code']]['xd001'] : 0;
            $city_list[$key]['xd001_record'] = isset($stat_record[$value['code']]['xd001']) ? $stat_record[$value['code']]['xd001'] : 0;
            $city_list[$key]['xd002_total'] = isset($stat_total[$value['code']]['xd002']) ? $stat_total[$value['code']]['xd002'] : 0;
            $city_list[$key]['xd002_lesson_folder'] = isset($stat_lesson_folder[$value['code']]['xd002']) ? $stat_lesson_folder[$value['code']]['xd002'] : 0;
            $city_list[$key]['xd002_record'] = isset($stat_record[$value['code']]['xd002']) ? $stat_record[$value['code']]['xd002'] : 0;
            $city_list[$key]['xd003_total'] = isset($stat_total[$value['code']]['xd003']) ? $stat_total[$value['code']]['xd003'] : 0;
            $city_list[$key]['xd003_lesson_folder'] = isset($stat_lesson_folder[$value['code']]['xd003']) ? $stat_lesson_folder[$value['code']]['xd003'] : 0;
            $city_list[$key]['xd003_record'] = isset($stat_record[$value['code']]['xd003']) ? $stat_record[$value['code']]['xd003'] : 0;
        }

        return array_values($city_list);
    }

    /**
     * 开课率统计
     * @param string $module
     * @return array
     */
    public function statForTeachByCity($module)
    {
        $cache_model = new Models_Course_Cache();

        //城市列表
        $city_list = $cache_model->cityList();

        //学校统计列表
        $school_stat = $cache_model->schoolStatByCityDb();

        //教师统计列表
        $teacher_stat = $cache_model->teacherStatBySubjectByCity($module);

        foreach ($city_list as $key => $value) {
            $city_list[$key]['xd001_teacher'] = $teacher_stat[$value['code']]['xd001'];
            $city_list[$key]['xd001_school'] = $school_stat[$value['code']][1];
            $city_list[$key]['xd001_percent'] = $school_stat[$value['code']][1] ? (round($teacher_stat[$value['code']]['xd001'] / $school_stat[$value['code']][1], 4) * 100) . "%" : "0%";
            $city_list[$key]['xd002_teacher'] = $teacher_stat[$value['code']]['xd002'];
            $city_list[$key]['xd002_school'] = $school_stat[$value['code']][2];
            $city_list[$key]['xd002_percent'] = $school_stat[$value['code']][2] ? (round($teacher_stat[$value['code']]['xd002'] / $school_stat[$value['code']][2], 4) * 100) . "%" : "0%";
            $city_list[$key]['xd003_teacher'] = $teacher_stat[$value['code']]['xd003'];
            $city_list[$key]['xd003_school'] = $school_stat[$value['code']][3];
            $city_list[$key]['xd003_percent'] = $school_stat[$value['code']][3] ? (round($teacher_stat[$value['code']]['xd003'] / $school_stat[$value['code']][3], 4) * 100) . "%" : "0%";
        }
        return $city_list;
    }

}
