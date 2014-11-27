<?php
/**
 * 教师中心的控制器
 */
class Modules_Xl_Controllers_Teacher extends  Modules_Xl_Controllers_Base {
    private $_activity_course_subject_code = '';
    private $_activity_course_subject = '';
    private $_activity_manager_model = '';
    private $_current_controller = '';
    public $layout = '';
    private $_main_host = '';
    private $_current_user = array();
    private $_dodo_url = '';
    private $_circle_model = NULL;
    private $_teacher_info_model = NULL;
    private $_resource_model = NULL;
    private $_attachment_base_url = NULL;




    public function __construct(){
        parent::__construct();
        $this->_main_host = DODOJC;
        $this->_dodo_url = DOMAIN_NAME.'/';

        $this->layout = $this->getCurrentLayout('index.htm');
        $this->_activity_manager_model = new Modules_Admin_Models_ActivityManage();
        $this->_activity_course_subject = $this->xk;
        $subject_array = Modules_Admin_Models_ActivityManage::getSubjectMap();
        if(isset($subject_array[$this->_activity_course_subject])){
            $this->_activity_course_subject_code = $subject_array[$this->_activity_course_subject];
        }
        $this->_current_controller = explode('_',$this->c);
        $this->_current_controller = $this->_current_controller[count($this->_current_controller)-1];
//        if(!isset($_SESSION['user']['role_code'])  OR (isset($_SESSION['user']['role_code']) AND $_SESSION['user']['role_code'] !=2)){
////            $this->messagePage($this->_main_host,'未登录或不是教师用户');
//            $no_login_url = '/'.$this->xk.'/'.$this->_current_controller.'/noLogin';
//            $this->redirect($no_login_url);
//        }
        if(!isset($_SESSION['user'])){
            if(Cola_Request::isAjax()){
                $this->echoJson('login','请登录');
            }
        }

        $this->_current_user = $_SESSION['user'];

        $this->_circle_model = new Models_Circle();
        $this->_teacher_info_model = new Models_Teacher_TeacherInterface();
        $this->_resource_model = new Models_Resource();
        $this->_attachment_base_url = Models_Attachment::init()->getBaseUrl();
        $this->view->attachment_base_url = $this->_attachment_base_url;
    }

    /**
     * 未登录
     */
    public function noLoginAction()
    {
        if(isset($_SESSION['user']['role_code'])){
            $login_url = '/'.$this->xk.'/'.$this->_current_controller.'/index';
            $this->redirect($login_url);
        }
        $this->view->login_url = '/'.$this->xk.'/index';
        $this->view->resource_url = '/'.$this->xk.'/resource';
        $this->view->resource_view_url =  '/'.$this->xk.'/resource/view';
        $this->view->infomation_url =  '/'.$this->xk.'/info';
        $this->view->activity_list_more_url = '/'.$this->xk.'/activity/activityList';
        $this->view->activity_info_url = '/'.$this->xk.'/activity/activityInfo';
        $this->view->dodo_url = $this->_dodo_url;
        //获取最新活动
        $index_latest_eight_activities_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code, 1, 4, array(), array());
        if($index_latest_eight_activities_result){
            $this->view->activity_list = $index_latest_eight_activities_result['rows'];
        }else{
            $this->view->activity_list = array();
        }
        //获取推荐资源
        $recommend_resource_result = Models_Resource::init()->getRecommendResource('', $this->_activity_course_subject_code, '', 4, 1);
        $this->view->recommend_resource_list = $recommend_resource_result['data'];
//        var_dump($this->view->recommend_resource_list);
        //获取课程咨询
        $information_model = new Modules_Xl_Models_Information();
        $this->view->site_id = $information_model->getSiteId();
        $blog_list_result = $information_model->getBlogList(0,8);
        if($blog_list_result){
            $this->view->blog_list = json_to_array($blog_list_result['data']);
        }else{
            $this->view->blog_list = array();
        }
        $this->setLayout($this->layout);
        $this->tpl();
    }



    /**
     * 心理健康，教师中心
     */
    public function indexAction(){
        if(!isset($_SESSION['user']['role_code']) ){
            $no_login_url = '/'.$this->xk.'/'.$this->_current_controller.'/noLogin';
            $this->redirect($no_login_url);
        }
        $this->view->page_title = '教师中心';
        $this->_current_user['user_dodo_space_url'] = $this->_dodo_url.$this->_current_user['user_id']."/Space/index";
        $user_school_info = $this->_circle_model->getCurClassSchoolByUserId($this->_current_user['user_id']);
        $this->_current_user['user_dodo_class_url'] = $this->_dodo_url."class";
        $this->_current_user['user_dodo_school_url'] = $this->_dodo_url."school";
        $this->_current_user['user_dodo_school_name'] = $user_school_info['school_name'];
        if(isset($user_school_info['school_id'])){
            $this->_current_user['user_dodo_school_url'] = $this->_dodo_url."School_Home/index/school/".$user_school_info['school_id'];
        }
        $this->view->user_info = $this->_current_user;
        //获取当前教材版本
        $teacher_current_textbook_publisher_result = $this->_teacher_info_model->getTeacherTextbookPublisher($this->_current_user['user_id'], $this->_activity_course_subject_code);
        $publisher_array = Modules_Admin_Models_ActivityManage::getActivityPublisherMap($this->_activity_course_subject_code,$this->_current_user['xd']);
        $this->view->publisher_code = $teacher_current_textbook_publisher_result;

        $this->view->publisher_name = '尚未选择';
        if($teacher_current_textbook_publisher_result AND isset($publisher_array[$teacher_current_textbook_publisher_result])){
            $this->view->publisher_name = $publisher_array[$teacher_current_textbook_publisher_result];
        }
        if(!$teacher_current_textbook_publisher_result){
            $this->view->publisher_code = "";
        }
        unset($publisher_array['v00']);
        $this->view->publisher_json = json_encode($publisher_array);
        //获取研训的次数
        //获取当前教师活动统计
        $teacher_activity_statistics_result = $this->_activity_manager_model->getTeacherActivityStatistics($this->_activity_course_subject_code, $this->_current_user['user_id']);
        $teacher_total_activity_times = 0;
        $activity_type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap($this->_activity_course_subject_code);//活动类别
        if(!empty($teacher_activity_statistics_result)){
            foreach($teacher_activity_statistics_result as $key => $val){
                $teacher_total_activity_times += intval($val['activity_times']);
                $teacher_activity_statistics_result[$key]['activity_info_type_label'] =$activity_type_array[$val['activity_info_type']];
            }
        }
        $this->view->teacher_total_activity_times = $teacher_total_activity_times;
        //获取该科目上传资源
        $upload_resource_result = $this->_resource_model->getResourceBySearch('', '', $this->_activity_course_subject_code, '', '', '', $this->_current_user['user_id'], '', '', '');
//        var_dump($upload_resource_result);
        $this->view->upload_resource_total = $upload_resource_result['count'];
        //获取备课夹个数
        $this->view->lesson_folder_total = Models_Lesson_Folder::init()->getUserFolderCount($this->_current_user['user_id'], array('subject' => $this->_activity_course_subject_code));
        //获取本周科目课时
        $this->view->weekly_lesson_total = Models_Course_Interface::init()->courseCountOfThisWeekByTeacher($this->_current_user['user_id'], $this->xk);

        //获取我的3个链接地址
        $this->view->my_resource_url = '/'.$this->xk.'/resource/my/s/up';
        $this->view->my_prepare_lesson_url =  '/'.$this->xk.'/Lesson/record';
        $this->view->my_answer_url = '/'.$this->xk.'/Question/my';


        //获取课程提醒
        $lesson_notice_list = Models_Course_Interface::init()->courseOfThisWeekByTeacher($this->_current_user['user_id'], $this->xk);
        $later_lesson_notice_array = array();
        $today_timestamp =  strtotime(date("Y-m-d"));
        $grade_array = Modules_Admin_Models_ActivityManage::getActivityGradeMap();
        if(!empty($lesson_notice_list)){
            foreach($lesson_notice_list as $key => $val){
                foreach($val as $key2 => $val2){
                    if(strtotime($val2['course_date'])   >=  $today_timestamp){
                        if(isset($grade_array[$val2['course_grade']])){
                            $val[$key2]['grade_label'] = $grade_array[$val2['course_grade']];
                        }
                        $later_lesson_notice_array[$key] = $val;
                    }
                }
            }
        }
//        var_dump($later_lesson_notice_array);
        $this->view->lesson_notice_list = $later_lesson_notice_array;
        $this->view->lesson_url ='/'.$this->xk.'/Course/courseDetail';
        $this->view->my_activity_url = '/'.$this->xk.'/'.$this->_current_controller.'/myActivity';
        $this->view->publisher_change_ajax_url = '/'.$this->xk.'/'.$this->_current_controller.'/ajaxProcessTextbookPublisher';
        //获取面板的链接？  url ,icon , name
        //先是默认的
        $resource_url = '/'.$this->xk.'/resource';
        $activity_url = '/'.$this->xk.'/activity';
        $lesson_prepare_url =  '/'.$this->xk.'/lesson';
        $course_url = '/'.$this->xk.'/course';
        $quick_buttons_list = array(
            array('button_url'=>$resource_url, 'button_icon_class'=>'resourceSp', 'button_name'=>'找资源'),
            array('button_url'=>$activity_url, 'button_icon_class'=>'trainSp', 'button_name'=>'做研训'),
            array('button_url'=>$lesson_prepare_url, 'button_icon_class'=>'LessonReadySp', 'button_name'=>'去备课'),
            array('button_url'=>$course_url, 'button_icon_class'=>'techSp', 'button_name'=>'上课堂'),
        );
        $my_app_list_result = $this->_teacher_info_model->getMyAppList($this->_current_user['user_id'], $this->_activity_course_subject_code);
        $this->view->my_app_list = $my_app_list_result;
//        var_dump($my_app_list_result);
        //后续估计还要读,必须的，快捷方式嗨起来
        $this->view->quick_buttons_list = $quick_buttons_list;
        $this->view->sys_app_list_url = '/'.$this->xk.'/'.$this->_current_controller.'/ajaxGetSysAppList';
        $this->view->add_app_url =  '/'.$this->xk.'/'.$this->_current_controller.'/ajaxAddMyApp';
        $this->view->del_app_url =  '/'.$this->xk.'/'.$this->_current_controller.'/ajaxDeleteMyApp';
        $this->view->user_id = $this->_current_user['user_id'];
        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * ajax输出系统应用
     */
    public function ajaxGetSysAppListAction()
    {
        $sys_app_list_result = $this->_teacher_info_model->getSysAppList($this->_current_user['role_code'], $this->_activity_course_subject_code);
        $my_app_list_result = $this->_teacher_info_model->getMyAppList($this->_current_user['user_id'], $this->_activity_course_subject_code);
        if(!empty($my_app_list_result)){
            $my_app_array = array();
            foreach($my_app_list_result as $key => $val){
                $my_app_array[$val['app_id']] =  $val;
            }
        }

        if($sys_app_list_result){
            foreach($sys_app_list_result['rows'] as $key => $val){
                $sys_app_list_result['rows'][$key]['url'] = strtr($sys_app_list_result['rows'][$key]['url'],array('{$user_id}'=>$this->_current_user['user_id']));
                    if(isset($my_app_array[$val['app_id']])){
                        //如果有我选过的
                        $sys_app_list_result['rows'][$key]['my_app_id'] = $my_app_array[$val['app_id']]['id'];
                    }else{
                        $sys_app_list_result['rows'][$key]['my_app_id'] = '';
                    }
            }
        }else{
            $sys_app_list_result = array('rows'=>array(),'total'=>0);
        }
        echo json_encode($sys_app_list_result);







    }

    /**
     * AJAX添加我的应用
     */
    public function ajaxAddMyAppAction()
    {
        $app_id = $this->getVar('app_id');
        $add_result = $this->_teacher_info_model->addMyApp($this->_current_user['user_id'],$app_id);
        if($add_result > 0){
            echo json_encode(array('data'=>$add_result,  'message'=>'添加成功', 'type'=> 'success'));
        }else{
            echo json_encode(array('data'=>$add_result,  'message'=>'添加失败', 'type'=> 'error'));
        }

    }

    /**
     * AJAX删除我的应用
     */
    public function ajaxDeleteMyAppAction()
    {
        $my_app_id = $this->getVar('my_app_id');
        $del_result = $this->_teacher_info_model->deleteMyApp($my_app_id);
        if($del_result){
            echo json_encode(array('data'=>$del_result,  'message'=>'删除成功', 'type'=> 'success'));
        }else{
            echo json_encode(array('data'=>$del_result,  'message'=>'删除失败', 'type'=> 'error'));
        }

    }


    /**
     * AJAX修改教材版本
     */
    public function ajaxProcessTextbookPublisherAction()
    {
        $publisher_code = $this->getVar('publisher_code','');
        if($publisher_code != ''){
            $flag = false;
            //首先查询是否有设置科目
            $publisher_exist_result = $this->_teacher_info_model->getTeacherTextbookPublisher($this->_current_user['user_id'], $this->_activity_course_subject_code);
            if($publisher_exist_result){
                //修改
                $update_publisher_result = $this->_teacher_info_model->updateTeacherTextbook($this->_current_user['user_id'], $this->_activity_course_subject_code,$publisher_code);
                if($update_publisher_result > 0){
                    $flag = true;
                }
            }else{
                //插入
                $add_publisher_array = array(
                        'teacher_textbook_user_id'=>$this->_current_user['user_id'],
                        'teacher_textbook_subject_code'=>$this->_activity_course_subject_code,
                        'teacher_textbook_publisher_code'=>$publisher_code
                );
                $publisher_insert_result = $this->_teacher_info_model->insertTeacherTextbook($add_publisher_array);
                if($publisher_insert_result > 0){
                    $flag = true;
                }
            }
            if($flag){
                echo json_encode(array('data'=>'',  'message'=>'设置成功', 'type'=> 'success'));
            }else{
                echo json_encode(array('data'=>'',  'message'=>'设置失败或选择了相同的版本', 'type'=> 'error'));
            }
        }else{
            echo json_encode(array('data'=>'',  'message'=>'未选择版本', 'type'=> 'error'));
        }
    }

    /**
     * 我的研训首页
     */
    public function myActivityAction()
    {

        if(!isset($_SESSION['user']['role_code'])  OR (isset($_SESSION['user']['role_code']) AND $_SESSION['user']['role_code'] !=2)){
            $this->messagePage($this->_main_host,'未登录或不是教师用户');
        }
        $dd = new Models_DDClient();
//        $tokens =  $_SESSION['tokens'];
//        var_dump($tokens);
        $this->view->page_title = '我的研训';
        //教师信息
        $this->view->user_info = $this->_current_user;
        //获取当前教师活动统计,总数，类型，类别
        $teacher_activity_statistics_result = $this->_activity_manager_model->getTeacherActivityStatistics($this->_activity_course_subject_code, $this->_current_user['user_id']);
        $teacher_total_activity_times = 0;
        $activity_type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap($this->_activity_course_subject_code);//活动类别
        if(!empty($teacher_activity_statistics_result)){
            foreach($teacher_activity_statistics_result as $key => $val){
                $teacher_total_activity_times += intval($val['activity_times']);
                $teacher_activity_statistics_result[$key]['activity_info_type_label'] =$activity_type_array[$val['activity_info_type']];
            }
        }
        $this->view->teacher_total_activity_times = $teacher_total_activity_times;
        $this->view->teacher_total_activity_type = json_encode($teacher_activity_statistics_result);
        //类型
        $teacher_activity_class_statistics_result = $this->_activity_manager_model->getTeacherActivityClassStatistics($this->_activity_course_subject_code, $this->_current_user['user_id']);
        $activity_class_array = Modules_Admin_Models_ActivityManage::getActivityClassMap($this->_activity_course_subject_code);
        if(!empty($teacher_activity_class_statistics_result)){
            foreach($teacher_activity_class_statistics_result as $key => $val){
                $teacher_activity_class_statistics_result[$key]['activity_info_class_label'] =$activity_class_array[$val['activity_info_class']];
            }
        }
        $this->view->teacher_total_activity_class = json_encode($teacher_activity_class_statistics_result);
//        var_dump($this->view->teacher_total_activity_type);
//        var_dump($this->view->teacher_total_activity_class);

//        //研训足迹 -分页
//        $page = $this->getVar('page',1);
//        $offset = $this->getVar('offset',10);
//        $start_time = $this->getVar('start_time','');
//        $end_time = $this->getVar('end_time','');
//        if($start_time != ''){
//            $start_time = strtotime($start_time);
//        }
//        if($end_time != ''){
//            $end_time = strtotime($end_time);
//        }
//        $this->view->teacher_activity_list = $this->_activity_manager_model->getTeacherActivityList($this->_activity_course_subject_code, $this->_current_user['user_id'], $page, $offset, $start_time, $end_time);
//        if($this->view->teacher_activity_list ){
//            //把社区的博客地址搞进去
//            foreach($this->view->teacher_activity_list['rows'] as $key => $val){
//                if($val['activity_member_experience_blogid'] != ''  AND   $val['activity_member_experience_blogid'] != null){
//                    $this->view->teacher_activity_list['rows'][$key]['blog_url'] =$this->_dodo_url.$val['activity_member_uid'].'/Blog/view/'.$val['activity_member_experience_blogid'];
//                }
//            }
//        }
//        var_dump($this->view->teacher_activity_list);
        $this->view->post_activity_experience_url = '/'.$this->xk.'/'.$this->_current_controller.'/postActivityExperienceToDoDoEdu';
        $this->view->activity_list_more_url = '/'.$this->xk.'/activity/activityList';
        $this->view->teacher_info_url = '/'.$this->xk.'/teacher/index';
        $this->view->dodo_url = $this->_dodo_url;
        $this->view->ajax_teacher_activity_list_url = '/'.$this->xk.'/'.$this->_current_controller.'/ajaxTeacherActivityList';
        //获取我的研训左侧
        $this->_current_user['user_dodo_space_url'] = $this->_dodo_url.$this->_current_user['user_id']."/Space/index";
        $user_school_info = $this->_circle_model->getCurClassSchoolByUserId($this->_current_user['user_id']);
        $this->_current_user['user_dodo_class_url'] = $this->_dodo_url."class";
        $this->_current_user['user_dodo_school_url'] = $this->_dodo_url."school";
//        $this->_current_user['user_dodo_class_name'] = $this->_dodo_url."class";
        $this->_current_user['user_dodo_school_name'] = $user_school_info['school_name'];
//        if(isset($user_school_info['class_id'])){
//            $this->_current_user['user_dodo_class_url'] = $this->_dodo_url."class/".$user_school_info['class_id'];
//        }
        if(isset($user_school_info['school_id'])){
            $this->_current_user['user_dodo_school_url'] = $this->_dodo_url."School_Home/index/school/".$user_school_info['school_id'];
        }
        $this->view->user_info = $this->_current_user;
//        //获取研训的次数
//        //获取当前教师活动统计
//        $teacher_activity_statistics_result = $this->_activity_manager_model->getTeacherActivityStatistics($this->_activity_course_subject_code, $this->_current_user['user_id']);
//        $teacher_total_activity_times = 0;
//        $activity_type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap();//活动类别
//        if(!empty($teacher_activity_statistics_result)){
//            foreach($teacher_activity_statistics_result as $key => $val){
//                $teacher_total_activity_times += intval($val['activity_times']);
//                $teacher_activity_statistics_result[$key]['activity_info_type_label'] =$activity_type_array[$val['activity_info_type']];
//            }
//        }
//        $this->view->teacher_total_activity_times = $teacher_total_activity_times;
        //获取该科目上传资源
        $upload_resource_result = $this->_resource_model->getResourceBySearch('', '', $this->_activity_course_subject_code, '', '', '', $this->_current_user['user_id'], '', '', '');
        $this->view->upload_resource_total = $upload_resource_result['count'];
        //获取备课夹个数
        $this->view->lesson_folder_total = Models_Lesson_Folder::init()->getUserFolderCount($this->_current_user['user_id'], array('subject' => $this->_activity_course_subject_code));
        //获取本周科目课时
        $this->view->weekly_lesson_total = Models_Course_Interface::init()->courseCountOfThisWeekByTeacher($this->_current_user['user_id'], $this->xk);
        //获取我的3个链接地址
        $this->view->my_resource_url = '/'.$this->xk.'/resource/my/s/up';
        $this->view->my_prepare_lesson_url =  '/'.$this->xk.'/Lesson/record';
        $this->view->my_answer_url = '/'.$this->xk.'/Question/myAnswer';
        $this->view->js = array(
                                        "activity/act_mine.js",
                                        "common/Chart.js",
                                        "common/lhgcalendar.js",
        );
        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     *AJAX输出教师研修足迹
     */
    public function ajaxTeacherActivityListAction()
    {
        //研训足迹 -分页
        $page = $this->getVar('page',1);
        $offset = $this->getVar('offset',10);
        $start_time = $this->getVar('start_time','');
        $end_time = $this->getVar('end_time','');
        if($start_time != ''){
            $start_time = strtotime($start_time);
        }
        if($end_time != ''){
            $end_time = strtotime($end_time);
        }
        $this->view->teacher_activity_list = $this->_activity_manager_model->getTeacherActivityList($this->_activity_course_subject_code, $this->_current_user['user_id'], $page, $offset, $start_time, $end_time);
        if($this->view->teacher_activity_list ){
            //把社区的博客地址搞进去
            foreach($this->view->teacher_activity_list['rows'] as $key => $val){
                if($val['activity_member_experience_blogid'] != ''  AND   $val['activity_member_experience_blogid'] != null){
                    $this->view->teacher_activity_list['rows'][$key]['blog_url'] =$this->_dodo_url.$val['activity_member_uid'].'/Blog/view/'.$val['activity_member_experience_blogid'];
                }
            }
        }else{
            $this->view->teacher_activity_list = array('rows'=>array(), 'total'=>'0');
        }
        $this->echoJson('success','ok',$this->view->teacher_activity_list);
    }

    /**
     * 往多多社区推送日志
     */
    public function postActivityExperienceToDoDoEduAction()
    {
        $dd = new Models_DDClient();
//        $tokens =  $_SESSION['tokens'];
        $activity_id = $this->getVar('activity_id');
        $blog_title = $this->getVar('blog_title','无标题');
        $blog_content = $this->getVar('blog_content','');
        if($blog_content == ""){
            $this->echoJson('error','心得内容不能为空', array());
            exit;
        }
        $ddapi_post_blog_url = DOMAIN_NAME.'/DDApi/blog/addmyblog';
        $activity_experience_post_data = array(
//            'access_token'=>$tokens['access_token'],
            'blog_title'=>$blog_title,
            'blog_content'=>$blog_content,
        );
//        $activity_experience_info  = Cola_Com_Http::post($ddapi_post_blog_url,$activity_experience_post_data);
//        if (isset(json_decode($activity_experience_info)->errcode) and json_decode($activity_experience_info)->errcode == 7) {
//            /* 用刷新令牌之后来解决 */
//            $newAccessToken = $dd->_grantNewAccessToken($tokens['access_token'], $tokens['refresh_token']);
//            $activity_experience_info =   Cola_Com_Http::post($ddapi_post_blog_url,$activity_experience_post_data);
//        }
//        $activity_experience_info = $dd->json_to_array(json_decode($activity_experience_info, 1));
        //gai
        $activity_experience_info = $dd->getDataByApi('blog/addmyblog',$activity_experience_post_data);

        $activity_experience_blog_id =$activity_experience_info['data']['blog_id'];
        $post_activity_experience_result = $this->_activity_manager_model->postActivityExperience($activity_id, $this->_current_user['user_id'], $activity_experience_blog_id, time(), $blog_title);
//        var_dump($post_activity_experience_result);
        $activity_experience_blog_url = $this->_dodo_url.$this->_current_user['user_id'].'/Blog/view/'.$activity_experience_blog_id;
//        $this->echoJson('success','ok', $activity_experience_blog_url);
        echo json_encode(array('data'=>$activity_experience_blog_url,  'message'=>'ok', 'type'=> 'success'));
    }
}
?>