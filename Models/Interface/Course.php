<?php

/**
 * 课程管理接口
 * @author wuya
 */
class Models_Interface_Course extends Models_Interface_Base
{

    /**
     * 年级选项
     * @param $xd 当前用户的学段属性
     * @return array
     */
    public function gradeSelect($xd)
    {
        return Models_Grade::$grade_list[$xd];
    }

    /**
     * 根据学校ID和学年，获取班级列表
     * @param int $school_id 学校ID
     * @param int $grade_code 年级
     * @return array
     */
    public function classListBySchoolAndYear($school_id, $grade_code)
    {
        $grade_info = Models_Grade::getYearByGradeCode($grade_code);
        return Models_Course_Cache::init()->classListBySchoolAndYear($school_id, $grade_info['year']);
    }

    /**
     * 课程安排列表
     * @param string $module 模块：xl或sm
     * @param string $user_id 用户ID
     * @param string $start_date 开始时间 eg:2014-09-29
     * @param string $end_date 结束时间 eg:2014-09-30
     * @param 展现形式 $format calendar（日历）或list（列表）
     * @return array
     */
    public function readCourseList($module, $user_id, $start_date, $end_date, $format = 'calendar')
    {
        $data = Models_Course_Course::init()->readCourse($module, $user_id, $start_date, $end_date, $format = 'calendar');

        //返回值封装
        $ret = array();
        foreach (array_values($data) as $key => $value) {
            //  $ret[$key] = array();
            foreach ($value as $s_key => $s_value) {
                $n_key = 'w' . $s_key;
                if (!$s_value) {
                    $s_value['class_id'] = '';
                }
                $ret[$key][$n_key] = $s_value;
            }
        }
        return $ret;
    }

    /**
     * 课程统计
     * @param string $module 模块:xl或sm
     * @param string $teacher_id 教师用户ID
     * @param string $start_date 开始时间 eg:2014-09-29
     * @param string $end_date 结束时间 eg:2014-09-30
     * @return int
     */
    public function courseStat($module, $teacher_id, $start_date, $end_date)
    {
        return Models_Course_Course::init()->courseStat($module, $teacher_id, $start_date, $end_date);
    }

    /**
     * 当前周的日期
     * @return array
     * @example array(1=>'2014-09-10',2=>'2014-09-11',3=>'2014-09-12',4=>'2014-09-13',4=>'2014-09-14',5=>'2014-09-15');
     */
    static public function dateOfCurrentWeek()
    {
        return Models_Course_Course::dateOfCurrentWeek();
    }

    /**
     * 课程信息
     * @param string $module 模块:xl或sm
     * @param int $course_id 课程ID
     * @return array 
     * @example array(
     *                'grade'=>'小学三年级',//上课班级年级
     *                'class_id'=>'325789',//班级Id
     *                'class_name'=>'火箭班',//班级名称
     *                'school_name'=>'长江数字班',//学校名称
     *                'appraise_score'=>'9.8',//评分
     *                'date'=>'2014-09-14',//日期
     *                'week'=>5//星期
     *                'remark'=>'',//备注
     *                )
     */
    public function courseInfo($module, $course_id)
    {
        $course = Models_Course_Course::init()->courseDetail($module, $course_id);

        //授课班级   
        $grade = Models_Grade::$stage_grade_list[$course[0]['course_stage']][$course[0]['course_grade']];
        $class = Models_Course_Cache::init()->classBaseInfo($course[0]['class_id'], 'class_name,class_pname');

        //课程评价
        $appraise = Models_Course_Course::init()->courseAppraise($module, $course_id);
        $appraise_score = $appraise['score'];

        //时间
        $date = $course[0]['course_date'];
        $week = $course[0]['course_week'];

        //备注
        $remark = $course[0]['course_remark'];
        return array(
            'grade' => $grade,
            'class_id' => $course[0]['class_id'],
            'class_name' => $class[0],
            'school_name' => $class[1],
            'appraise_score' => $appraise_score,
            'date' => $date,
            'week' => $week,
            'remark' => $remark
        );
    }

    /**
     * 课程记录详情
     * @param $module 模块名称 sm或xl
     * @param $course_id 课程Id 
     * @return array
     */
    public function courseRecord($module, $course_id)
    {
        $course = Models_Course_Course::init()->courseDetail($module, $course_id);
        $record = Models_DDClient::init()->getDataByApi('class/getBlogArticle', array('article_id' => $course[0]['course_record']), false);
        return array('content' => $record['data']['article_content']);
    }

    /**
     * 备课夹列表
     * @param string $module 模块名称 xl或sm
     * @param string $user_id 用户Id
     * @param string $xd 学段
     * @param string $grade 年级
     * @param string $edition 版本号
     * @param int $page 页码
     * @param int $num 每页显示的条数
     * @return array
     * @example array(
     *                 'total'=>5,//总条数
     *                 'rows'=>array(
     *                              0=>array(
     *                                     'id'=>113,//备课夹ID
     *                                     'user_id'=>'w36451978107373010061',//作者用户Id
     *                                     'title'=>'第三课 我要学',//标题
     *                                     'chapter_info'=>array('data'=>'第三课 你我他，像一家'),//章节描述，即课名
     *                                  ),
     *                              1=>array(
     *                                     'id'=>113,//备课夹ID
     *                                     'user_id'=>'w36451978107373010061',//作者用户Id
     *                                     'title'=>'第三课 我要学',//标题
     *                                     'chapter_info'=>array('data'=>'第三课 你我他，像一家'),//章节描述，即课名
     *                                   ),
     *                  ),
     *                )
     */
    public function lessonFolderList($module, $user_id, $xd, $grade, $edition, $page, $num)
    {
        return Models_Course_Course::init()->lessonFolderList($module, $user_id, $xd, $grade, $edition, $page, $num);
    }

    /**
     * 设置课程
     * @param string $module 模块名称
     * @param int $class_id 班级Id
     * @param string $user_id 当前用户Id
     * @param int $sort 第几节
     * @param string $xd 学段
     * @param string $grade 年级
     * @param string $date 日期
     * @param string $lesson_folder_id 备课夹Id
     * @param type $remark 备注
     */
    public function setCourse($module, $class_id, $user_id, $sort, $xd, $grade, $date, $lesson_folder_id = '', $remark = '')
    {

        //是否关联备课夹
        $status = (!$lesson_folder_id) ? 0 : 1;

        //班级的地域信息
        $area_info = Models_DDClient::init()->getDataByApi('class/classAreaInfo', array('class_id' => $class_id));

        //市州
        $city_id = $area_info['data']['cityId'];

        //区县
        $town_id = $area_info['data']['townId'];

        //学校
        $school_id = $area_info['data']['schoolId'];

        $param = array(
            'class_id' => $class_id,
            'teacher_user_id' => $user_id,
            'course_grade' => $grade,
            'course_stage' => $xd,
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
        Models_Course_Course::init()->saveCourse($module, $param);
        return array('type' => true);
    }

    /**
     * 更新课程记录
     * @param string $module 模块名称 xl或sm
     * @param int $course_id 课程Id
     * @param string $record 课程记录内容
     * @param string $user_id 作者用户id
     * @param file $file 图片文件流
     * @return mixed  成功返回array('type'=>'success');失败返回 null
     */
    public function updateCourseRecord($module, $course_id, $record, $user_id)
    {
        return Models_Course_Course::init()->updateCourseRecord($module, $course_id, $record, $user_id,$_FILES);
    }

    /**
     * 课程评价统计
     * @param string $module 模块名称 xl或sm
     * @param int $course_id 课程Id
     * @return array
     * @example array(
     *                 'total'=>'56',//评价总数
     *                  'score'=>9.8,//课程平均评分
     *                 )
     */
    public function courseAppraiseStat($module, $course_id)
    {
        return Models_Course_Course::init()->courseAppraise($module, $course_id);
    }

    /**
     * 课程评价详情
     * @param string $module 模块名称 xl或sm
     * @param int $course_id 课程Id 
     * @param int $page 分页页数
     * @param int $num 分页每页条数 
     * @return array
     * @example array(
     *                 'data'=>array( //评价数据详情
     *                               0=>array(
     *                                       'appraise_user_id'=>'l4582212222225564654',//评价人Id
     *                                       'appraise_user_info'=>array('user_realname'=>'张三','user_id'=>'l4582212222225564654','user_avatar_160'=>'http://dev-images.dodoedu.com/image/20131358737477-42-7630-6744-160.jpg')
     *                                       'appraise_score'=>10,//评分
     *                                       'appraise_remark'=>'',//评价文字描述
     *                                       'appraise_time'=>'1402023627',//评价时间
     *                                      ),
     *      *                        1=>array(
     *                                       'appraise_user_id'=>'l4582212222225564653',//评价人Id
     *                                       'appraise_user_info'=>array('user_realname'=>'李四','user_id'=>'l4582212222225564653','user_avatar_160'=>'http://dev-images.dodoedu.com/image/20131358737477-42-7630-6744-160.jpg')
     *                                       'appraise_score'=>9,//评分
     *                                       'appraise_remark'=>'',//评价文字描述
     *                                       'appraise_time'=>'1402023627',//评价时间
     *                                      ),
     *                               ),
     *                 'count'=>2,//返回的结果条数
     *                 'limit' => $num,//评价分页每页条数
     *                 'total'=>5,//总条数
     *                 )
     */
    public function courseAppraiseDetail($module, $course_id, $page, $num)
    {
        return Models_Course_Course::init()->courseAppraiseDetail($module, $course_id, $page, $num);
    }

    /**
     * 课堂表现勋章列表
     * @param int $emotional 勋章类型:1（表扬）或2（批评） 
     * @param int $field 维度：1（品德发展），2（学业发展），3（身心发展），4（兴趣特长），5（实践能力）
     * @return array
     * @example array(
     *                 'medal_name'=>'友善自爱',//勋章名称
     *                 'medal_emotional'=>1,//表扬
     *                 'medal_field'=>3,//维度
     *                 'medal_icon'=>'http://files.dodoedu.com/photo/evaluate/54081cc2107e5.png',//勋章图标
     *           )
     */
    public function courseImpressionMedal($emotional, $field)
    {
        return Models_Course_Course::init()->medalList($emotional, $field);
    }

    /**
     * 添加课程印象
     * @param string $module 模块名称 sm或xl
     * @param int $emotional   勋章类型:1（表扬）或2（批评）
     * @param int $field  维度：1（品德发展），2（学业发展），3（身心发展），4（兴趣特长），5（实践能力）
     * @param string $student_ids 受表扬的学生Id,多个用逗号隔开
     * @param string $user_id 当前用户用户Id
     * @param int $course_id 课程Id
     * @return array
     */
    public function addImpressionForCourse($module, $emotional, $field, $medal_id, $medal_name, $student_ids, $user_id, $course_id)
    {
        $result = Models_Course_Course::init()->addImpressionForCourse($module, (string) $emotional, (string) $field, $medal_id, $medal_name, $student_ids, $user_id, $course_id);
        if ('success' == $result['type']) {
            return array('type' => true);
        } else {
            return array('type' => false);
        }
    }

    /**
     * 课堂表现详情
     * @param string $module 模块名称
     * @param string $course_id 课程Id
     * @return array
     * @example array(
     *            'data'=>array(
     *                  0=>array(
     *                        'medal_info'=>array(
     *                            'medal_name'=>'爱护环境',//勋章名称
     *                            'medal_icon'=>'http://dev-images.dodoedu.com/image/evaluate/540594482a11d.png',//勋章图标
     *                            'id'=>22,//勋章Id
     *                         ),
     *                         'students'=>array(
     *                              0=>array(
     *                                    'user_id'=>'h40611150900133510031',//用户Id
     *                                    'user_realname'=>'胡子晗',//用户姓名
     *                                    ),
     *                              1=>array(
     *                                    'user_id'=>'w40611148103214470085',//用户Id
     *                                    'user_realname'=>'文雨诗',//用户姓名
     *                                    ),
     *                         ),
     *                          'count'=>5,//获得勋章的人数
     *                 )
     *              )
     *            )
     */
    public function courseImpressionListByIds($module, $course_id)
    {
        $course = Models_Course_Course::init()->courseDetail($module, $course_id);
        $data = Models_Course_Course::init()->impressionListByIds($course[0]['course_impression']);
        $ret = array();
        $praise = isset($data[1]) ? $data[1] : array();
        foreach ($praise as $value) {
            foreach ($value['medal_info'] as $s_value) {
                $ret[$s_value['id']]['medal_info'] = $s_value;
                foreach ($value['user_info'] as $s_s_key => $s_s_value) {
                    $ret[$s_value['id']]['students'][$s_s_key] = $s_s_value;
                }
            }
        }
        foreach ($ret as $key => $value) {
            $ret[$key]['students'] = array_values($value['students']);
            $ret[$key]['count'] = count($value['students']);
        }
        return array_values($ret);
    }

    /**
     * 添加课程问答
     * @param string $module 模块名称 xl或sm
     * @param int $course_id 课程Id
     * @param string $content 问题内容
     * @param string $user_id 用户Id
     * @return array
     */
    public function addQuestionForCourse($module, $course_id, $content, $user_id)
    {
        Models_Course_Course::init()->addQuestionForCourse($module, $course_id, $content, $user_id);
        return array('type' => 'success');
    }

    /**
     * 课程问答列表
     * @param type $module 模块名称 xl或sm
     * @param type $course_id 课程Id
     * @param int $page 分页页码
     * @param int $num 每页条数
     * @return array 
     * @example array(
     *                 'data'=>array(
     *                               0=>array(
     *                                      'id'=>590,//问答id
     *                                      'user_id'=>'s35951247001862320095',//提问人用户Id
     *                                      'user_name'=>'刘杰',//提问人姓名
     *                                      'user_avatar'=>'http://dev-images.dodoedu.com/image/20131358737477-42-7630-6744-16.jpg',//人物头像
     *                                      'title'=>'老师，我听不懂啊',//问题标题
     *                                      'content'='听不懂听不懂听不懂听不懂听不懂听不懂',//问题描述
     *                                      'time'=>'2014-06-06 11:00:56',//提问时间
     *                                      'tag'=>array(
     *                                            0=>array(
     *                                                    'tag_id'=>'240',//标签ID
     *                                                    'tag_name'=>'小学',//标签名称
     *                                            ),
     *                                            1=>array(
     *                                                    'tag_id'=>'241',//标签ID
     *                                                    'tag_name'=>'心理健康',//标签名称
     *                                            )
     *                                      ),
     *                               ),                
     *                               1=>array(
     *                                      'id'=>591,//问答id
     *                                      'user_id'=>'s35951247001862320095',//提问人用户Id
     *                                      'user_name'=>'刘杰',//提问人姓名
     *                                      'user_avatar'=>'http://dev-images.dodoedu.com/image/20131358737477-42-7630-6744-16.jpg',//人物头像
     *                                      'title'=>'老师，我听不懂啊',//问题标题
     *                                      'content'='听不懂听不懂听不懂听不懂听不懂听不懂',//问题描述
     *                                      'time'=>'2014-06-06 11:00:56',//提问时间
     *                                      'answers_count'=>5,//回答条数
     * *                                    'tag'=>array(
     *                                            0=>array(
     *                                                    'tag_id'=>'240',//标签ID
     *                                                    'tag_name'=>'小学',//标签名称
     *                                            ),
     *                                            1=>array(
     *                                                    'tag_id'=>'241',//标签ID
     *                                                    'tag_name'=>'心理健康',//标签名称
     *                                            )
     *                                      ),
     *                               ),
     *                         ),
     *                 'count'=>2,//返回的结果条数
     *                 'limit'=>2,//每页的条数
     *                 'total'=>5,//总条数
     *                 )
     */
    public function questionsForCourse($module, $course_id, $page = 1, $num = 5)
    {
        $data = Models_Course_Course::init()->questionsForCourse($module, $course_id, $page, $num);
        foreach ((array) $data['data'] as $key => $value) {
            $data['data'][$key] = array(
                'id' => $value['id'],
                'user_id' => $value['user_id'],
                'user_name' => $value['user_name'],
                'user_avatar' => $value['user_image'],
                'title' => $value['FAQ_title'],
                'content' => $value['FAQ_explain'],
                'time' => $value['FAQ_time'],
                'answers_count' => $value['FAQ_answersCount'],
                'tag' => array_values($value['tag']),
            );
        }
        return $data;
    }

    /**
     * 课程对应的学生列表
     * @param $module 模块名称
     * @param $course_id 课程Id
     * @return array
     * @example array(
     *              0=>array(
     *                    'user_id'=>'l40660255506776180037',//用户Id
     *                    'user_realname'=>'刘文莉',//用户姓名
     *                    'user_avatar_16'=>'http://dev-images.dodoedu.com/shequPage/common/image/info-16.gif',//16px的头像
     *                    'user_avatar_40'=>'http://dev-images.dodoedu.com/shequPage/common/image/info-40.gif',//40px的头像
     *                    'user_avatar_64'=>'http://dev-images.dodoedu.com/shequPage/common/image/info-64.gif',//64px的头像
     *                    'user_avatar_160'=>'http://dev-images.dodoedu.com/shequPage/common/image/info-160.gif',//160px的头像
     *              )
     *           ) 
     */
    public function studentListByCourse($module, $course_id)
    {
        $course_detail = Models_Course_Course::init()->courseDetail($module, $course_id);
        $list = Models_DDClient::init()->getDataByApi('class/studentList', array('class_id' => $course_detail[0]['class_id']), false);
        return $list['data'];
    }

}
