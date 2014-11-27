<?php
/**
 * 前台研训控制器   
 */
class Modules_Xl_Controllers_Activity extends  Modules_Xl_Controllers_Base {
    private $_activity_course_subject_code = '';
    private $_activity_course_subject = '';
    private $_activity_manager_model = '';
    private $_current_controller = '';
    private $_activity_news_element_id = '';    //根据项目调整
    private $_activity_news_element_type='';
    private $_current_user = array();
    private $_main_host = '';
    private $_attachment_base_url = '';
    private $_dodo_url ='';

    public function __construct(){
        parent::__construct();
        $this->_main_host = DODOJC;
        $this->layout = $this->getCurrentLayout('index.htm');
        $this->_activity_manager_model = new Modules_Admin_Models_ActivityManage();
        $this->_activity_course_subject = $this->xk;
        $subject_array = Modules_Admin_Models_ActivityManage::getSubjectMap();
        if(isset($subject_array[$this->_activity_course_subject])){
            $this->_activity_course_subject_code = $subject_array[$this->_activity_course_subject];
        }
        $this->_current_controller = explode('_',$this->c);
        $this->_current_controller = $this->_current_controller[count($this->_current_controller)-1];
        if(DODOJC == 'http://jc.dodoedu.com'){
            $this->_activity_news_element_id = 7183;
        }else{
            $this->_activity_news_element_id = 8335;
        }
        $this->_activity_news_element_type = 'Blog';
        if(isset($_SESSION['user'])){
            $this->_current_user = $_SESSION['user'];
        }
        if((isset($_SESSION['preview_role'])  AND  $_SESSION['preview_role'] != 2 ) AND (isset($_SESSION['user']['role_code']) AND $_SESSION['user']['role_code'] !=2)){
                $this->messagePage($this->_main_host,'角色不正确');
        }
        $this->_attachment_base_url = Models_Attachment::init()->getBaseUrl();
        $this->view->attachment_base_url = $this->_attachment_base_url;
        $this->_dodo_url = DOMAIN_NAME.'/';
    }

    /**
     * 研训首页
     */
    public function indexAction(){
        $this->view->page_title = '研训首页';
        //登录后的信息
        $this->view->user_info = $this->_current_user;
//        var_dump($this->view->user_info);
        if(!empty($this->_current_user)){
            //获取当前教师活动统计
            $teacher_activity_statistics_result = $this->_activity_manager_model->getTeacherActivityStatistics($this->_activity_course_subject_code, $this->_current_user['user_id']);
            $teacher_total_activity_times = 0;
            $activity_type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap($this->_activity_course_subject_code);//活动类别
            if(!empty($teacher_activity_statistics_result)){
                foreach($teacher_activity_statistics_result as $key => $val){
                    $teacher_total_activity_times += intval($val['activity_times']);
                }
            }
            $this->view->teacher_total_activity_times = $teacher_total_activity_times;
        }
        //首页数据
        $latest_activity_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,1,array(), array());
        $this->view->latest_activity_result = $latest_activity_result;
//        var_dump($latest_activity_result);
        //上右
        $today_zero_time = strtotime(date('Y-m-d',time()));
        $this->view->today_zero_time = date('Y-m-d',time());

        $later_activities_result = $this->_activity_manager_model->getLatestActivity($this->_activity_course_subject_code,$today_zero_time, 15);
        $this->view->later_five_activities_list = $later_activities_result;
        $this->view->activity_info_url =  '/'.$this->xk.'/'.$this->_current_controller.'/activityInfo';

        //中部AJAX
//        $current_publisher = $this->getVar('current_publisher','all');
        $current_phase = $this->getVar('current_phase','all');
//        $_SESSION['activity_index']['current_publisher'] = $current_publisher;
//        $_SESSION['activity_index']['current_phase'] = $current_phase;
//        $this->view->current_publisher = $_SESSION['activity_index']['current_publisher'];
//        $this->view->current_phase = $_SESSION['activity_index']['current_phase'];
        $options_array = array();
//        if($current_publisher != 'all'){
//            $options_array['activity_info_publisher'] = $current_publisher;
//        }
        if($current_phase != 'all'){
            $options_array['activity_info_phase'] = $current_phase;
        }
        $order_array = array('activity_info_start_date'=>'DESC');
        $current_page = 1;
        $current_offset = 6;
        $index_latest_eight_activities_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,$current_page,$current_offset,$options_array, $order_array);
       //尼玛的两行
        if(!$index_latest_eight_activities_result){
            $index_latest_eight_activities_result = array('rows'=>array(),'total'=>0);
        }
//        var_dump($index_latest_eight_activities_result);
        $this->view->index_latest_eight_activities_list = $index_latest_eight_activities_result['rows'];
//        var_dump($index_latest_eight_activities_result);
        $this->view->publisher_array = Modules_Admin_Models_ActivityManage::getActivityPublisherMap();
        $this->view->type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap($this->_activity_course_subject_code);

        //获取活动小站列表
        $activity_sites_list_result = $this->_activity_manager_model->getIndexActivitySites($this->_activity_course_subject_code, 1, 20);
        if($activity_sites_list_result){
            $this->view->activity_sites_list = $activity_sites_list_result['rows'];
        }else{
            $this->view->activity_sites_list = array();
        }
//        var_dump($this->view->activity_sites_list);

        //获取研训资讯
        $activity_news_list_result = $this->_activity_manager_model->getActivityNews($this->_activity_news_element_id, $this->_activity_news_element_type, 10);
        $this->view->activity_news_list = $activity_news_list_result['data'];
//        var_dump($this->view->activity_news_list);

        //获取研训达人
        $hot_teacher_page = 1;
        $hot_teacher_offset = 20;
        $hot_teacher_list_result = $this->_activity_manager_model->getHotTeachers($this->_activity_course_subject_code,$hot_teacher_page,$hot_teacher_offset);
        if($hot_teacher_list_result){
            $this->view->hot_teacher_list = $hot_teacher_list_result['rows'];
        }else{
            $this->view->hot_teacher_list = array();
        }
        $this->view->activity_index_url = '/'.$this->xk.'/'.$this->_current_controller.'/index';
        $this->view->teacher_center_url = '/'.$this->xk.'/teacher/index';
        $this->view->teacher_activity_url = '/'.$this->xk.'/teacher/myActivity';
        $this->view->ajax_activity_list_url = '/'.$this->xk.'/'.$this->_current_controller.'/ajaxIndexActivityList';
        $this->view->activity_list_more_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityList';
        $this->view->activity_info_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityInfo';
        $this->view->activity_calender_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityCalendar';
        $this->view->dodo_url = $this->_dodo_url;
        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * 研训日历
     */
    public function activityCalendarAction()
    {
        $this->view->page_title = '研训日历';
        $this->view->user_info = $this->_current_user;
        $this->view->activity_list_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityList';
        $this->view->activity_apply_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityApply';
        $this->view->activity_info_url =  '/'.$this->xk.'/'.$this->_current_controller.'/activityInfo';
        $this->view->teacher_center_url = '/'.$this->xk.'/Teacher/index';
        $this->view->activity_index_url = '/'.$this->xk.'/'.$this->_current_controller.'/index';
//        //最新最热
//        $latest_activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,5,array(), array());
//        if($latest_activity_list_result){
//            $this->view->latest_activity_list = $latest_activity_list_result['rows'];
//        }else{
//            $this->view->latest_activity_list = array();
//        }
//        $hotest_activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,5,array(), array('AVG(`activity_member`.`activity_member_evaluate`)'=>'DESC'));
//        if($hotest_activity_list_result){
//            $this->view->hotest_activity_list = $hotest_activity_list_result['rows'];
//        }else{
//            $this->view->hotest_activity_list = array();
//        }
//        //获取研训达人
//        $hot_teacher_page = 1;
//        $hot_teacher_offset = 5;
//        $hot_teacher_list_result = $this->_activity_manager_model->getHotTeachers($this->_activity_course_subject_code,$hot_teacher_page,$hot_teacher_offset);
//        if($hot_teacher_list_result){
//            $this->view->hot_teacher_list = $hot_teacher_list_result['rows'];
//        }else{
//            $this->view->hot_teacher_list = array();
//        }
        $this->view->current_year = date('Y',time());
        $this->view->current_month = date('m',time());        
        $this->view->activity_info_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityInfo';
        $this->view->dodo_url = $this->_dodo_url;
        $this->view->ajax_activity_calendar_url = '/'.$this->xk.'/'.$this->_current_controller.'/ajaxActivityCalendar';
        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * AJAX输出研训日历
     */
    public function ajaxActivityCalendarAction()
    {
        //右侧
        $current_year = $this->getVar('data_year');
        $current_month =$this->getVar('data_month');
        if(intval($current_month)+1 >12){
            $next_month = 1;
            $next_year = intval($current_year)+1;
        }else{
            $next_month = intval($current_month)+1;
            $next_year = $current_year;
        }
        $current_month_stamp = strtotime($current_year.'-'.$current_month);
        $next_month_stamp = strtotime($next_year.'-'.$next_month);
        $activity_result = $this->_activity_manager_model->getActivityCalender($this->_activity_course_subject_code, $current_month_stamp, $next_month_stamp);
        $return_array = array('rows'=>array(),'total'=>0);
        if(!empty($activity_result)){
            foreach($activity_result as $key => $val){
                $activity_result[$key]['start_date_label'] = date('j',$val['activity_info_start_date']);
            }
            $return_array['rows'] = $activity_result;
            $return_array['total'] = count($activity_result);
        }
        $this->echoJson('success','ok',$return_array);
    }

    /**
     * 首页AJAX通过学段获取活动
     */
    public function ajaxIndexActivityListAction()
    {
        $current_phase = $this->getVar('current_phase','all');
        $options_array = array();
        if($current_phase != 'all'){
            $options_array['activity_info_phase'] = $current_phase;
        }
        $activity_info_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityInfo';
        $order_array = array('activity_info_start_date'=>'DESC');
        $current_page = 1;
        $current_offset = 6;
        $index_latest_eight_activities_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,$current_page,$current_offset,$options_array, $order_array);
        if(!$index_latest_eight_activities_result){
            $index_latest_eight_activities_result = array('rows'=>array(),'total'=>0);
        }
        $publisher_array = Modules_Admin_Models_ActivityManage::getActivityPublisherMap();
        $type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap($this->_activity_course_subject_code);
        $activity_list_html_str ='';
        if (!empty($index_latest_eight_activities_result['rows'])){
                $list_total =  count($index_latest_eight_activities_result['rows']);
                    foreach($index_latest_eight_activities_result['rows'] as $key =>$val){
                        $i = $key%3;
                       if ($i ===0){
                             $activity_list_html_str .= "<div class='row f-mt10'>";
                        }
                        if ($i === 2 OR $key+1 ===  $list_total ){
                                 $activity_list_html_str .= "<div class='teachStudySection clearfix  f-fl'>
                                     <div class='studyCover f-fl f-mr10'>
                                         <!--<div class='hotest'>最新</div>-->
                                         <a href=".$activity_info_url."?activity_id=".$val['activity_info_id']."  target='_blank'>
                                            <img src='".$this->_attachment_base_url.$val['activity_info_icon']."' alt='' width='130px' height='80px'/>
                                         </a>
                                     </div>
                                     <ul class='studyDesc f-fl'>
                                         <li class='title'><a href=".$activity_info_url."?activity_id=".$val['activity_info_id']."  target='_blank'>".$val['activity_info_title']."</a></li>";
                                       $host_array = explode(',',$val['activity_info_host']);
                                        $activity_list_html_str .= "<li class='jioner'><i class='icon-jioner'></i>";
                                         foreach($host_array as  $host_key=>$host_val){
                                            $activity_list_html_str .=$host_val.'&nbsp';
                                         }
                                         $activity_list_html_str .= "</li><li class='intro'><i class='icon-intro'></i>".$type_array[$val['activity_info_type']]."</li>
                                         <li class='data'><i class='icon-play'></i>已有".$val['member_total']."人参与</li>
                                     </ul>
                                     <div class='f-cb'></div>
                                 </div>
                                 <div class='f-cb'></div>
                            </div>";
                        }else{
                            $activity_list_html_str .= "<div class='teachStudySection clearfix  f-fl f-mr10'>
                                     <div class='studyCover f-fl f-mr10'>
                                         <!--<div class='hotest'>最新</div>-->
                                         <a href=".$activity_info_url."?activity_id=".$val['activity_info_id']."  target='_blank'>
                                            <img src='".$this->_attachment_base_url.$val['activity_info_icon']."' alt='' width='130px' height='80px'/>
                                         </a>
                                     </div>
                                     <ul class='studyDesc f-fl'>
                                         <li class='title'><a href=".$activity_info_url."?activity_id=".$val['activity_info_id']."  target='_blank'>".$val['activity_info_title']."</a></li>";
                            $host_array = explode(',',$val['activity_info_host']);
                            $activity_list_html_str.="<li class='jioner'><i class='icon-jioner'></i>";
                            foreach($host_array as  $host_key=>$host_val){
                                $activity_list_html_str .=$host_val.'&nbsp';
                            }
                            $activity_list_html_str .= "</li><li class='intro'><i class='icon-intro'></i>".$type_array[$val['activity_info_type']]."</li>
                                         <li class='data'><i class='icon-play'></i>已有".$val['member_total']."人参与</li>
                                     </ul>
                                     <div class='f-cb'></div>
                                 </div>";
                        }
                    }
         }
        echo json_encode(array('data'=>$activity_list_html_str,  'message'=>' ok', 'type'=> 'success'));
//        $this->echoJson('success','ok',$activity_list_html_str);
    }

    /**
     * 列表页面
     */

    /**
     * 活动列表页面
     */
    public function activityListAction()
    {
        $this->view->page_title = '更多研训';
        //获取左边的活动列表
        $this->view->user_info = $this->_current_user;
        $current_type = $this->getVar('current_type','all');
        $current_class = $this->getVar('current_class','all');
        $current_phase = $this->getVar('current_phase','all');
        $current_publisher = $this->getVar('current_publisher','all');
        $current_order = $this->getVar('current_order','activity_info_start_date');
        $_SESSION['activity_index']['current_type'] = $current_type;
        $_SESSION['activity_index']['current_class']  = $current_class;
        $_SESSION['activity_index']['current_phase']  = $current_phase;
        $_SESSION['activity_index']['current_order']  = $current_order;
        $_SESSION['activity_index']['current_publisher']  = $current_publisher;
        $this->view->current_type = $_SESSION['activity_index']['current_type'] ;
        $this->view->current_class = $_SESSION['activity_index']['current_class'];
        $this->view->current_phase = $_SESSION['activity_index']['current_phase'];
        $this->view->current_order = $_SESSION['activity_index']['current_order'];
        $this->view->current_publisher = $_SESSION['activity_index']['current_publisher'];
        $options_array = array();
        if($current_type != 'all'){
            $options_array['activity_info_type'] = $current_type;
        }
        if($current_class != 'all'){
            $options_array['activity_info_class'] = $current_class;
        }
        if($current_phase != 'all'){
            $options_array['activity_info_phase'] = $current_phase;
        }
        if($current_publisher != 'all'){
            $options_array['activity_info_publisher'] = $current_publisher;
        }
        $order_array = array($current_order=>' DESC');
        $current_page = $this->getVar('page',1);
        $current_offset = 10;
        $activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,$current_page,$current_offset,$options_array, $order_array);
        if(!$activity_list_result){
            $activity_list_result = array('rows'=>array(),'total'=>0);
        }
        $this->view->activity_list = $activity_list_result['rows'];
//        var_dump($this->view->activity_list );


        $this->view->activity_list_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityList';
        $this->view->activity_apply_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityApply';
        $this->view->activity_info_url =  '/'.$this->xk.'/'.$this->_current_controller.'/activityInfo';
        $this->view->teacher_center_url = '/'.$this->xk.'/Teacher/index';
        $this->view->activity_index_url = '/'.$this->xk.'/'.$this->_current_controller.'/index';
        //最新最热
        $latest_activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,5,array(), array());
        if($latest_activity_list_result){
            $this->view->latest_activity_list = $latest_activity_list_result['rows'];
        }else{
            $this->view->latest_activity_list = array();
        }

//        $hotest_activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,5,array(), array('AVG(`activity_member`.`activity_member_evaluate`)'=>'DESC'));
        $hotest_activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,5,array(), array('`member_total`'=>'DESC'));
        if($hotest_activity_list_result){
            $this->view->hotest_activity_list = $hotest_activity_list_result['rows'];
        }else{
            $this->view->hotest_activity_list = array();
        }

        //获取研训达人
        $hot_teacher_page = 1;
        $hot_teacher_offset = 5;
        $hot_teacher_list_result = $this->_activity_manager_model->getHotTeachers($this->_activity_course_subject_code,$hot_teacher_page,$hot_teacher_offset);
        if($hot_teacher_list_result){
            $this->view->hot_teacher_list = $hot_teacher_list_result['rows'];
        }else{
            $this->view->hot_teacher_list = array();
        }
        $this->view->activity_info_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityInfo';
        $this->view->dodo_url = $this->_dodo_url;
        $this->view->ajax_activity_url = '/'.$this->xk.'/'.$this->_current_controller.'/ajaxActivityMoreList';
        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * 活动列表页面AJAX输出活动列表
     */
    public function ajaxActivityMoreListAction()
    {
        $current_type = $this->getVar('current_type','all');
        $current_class = $this->getVar('current_class','all');
        $current_phase = $this->getVar('current_phase','all');
        $current_order = $this->getVar('current_order','activity_info_start_date');
        $current_page = $this->getVar('p',1);
        $current_offset = $this->getVar('offset',6);

        $options_array = array();
        if($current_type != 'all'){
            $options_array['activity_info_type'] = $current_type;
        }
        if($current_class != 'all'){
            $options_array['activity_info_class'] = $current_class;
        }
        if($current_phase != 'all'){
            $options_array['activity_info_phase'] = $current_phase;
        }
        $order_array = array();
        if($current_order =="start_date"){
            $order_array['activity_info_start_date'] = 'DESC';
        }else if($current_order =="hot"){
            $order_array['AVG(`activity_member`.`activity_member_evaluate`)'] = 'DESC';
        }else{
            $order_array['activity_info_start_date'] = 'DESC';
        }
        $activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,$current_page,$current_offset,$options_array, $order_array);
        if(!$activity_list_result){
            $activity_list_result = array('rows'=>array(),'total'=>0);
        }else{
            foreach($activity_list_result['rows'] as $key =>$val){
                $activity_list_result['rows'][$key]['activity_info_description'] = strip_tags($val['activity_info_description']);
            }
        }
        $this->echoJson('success','ok',$activity_list_result);
    }

    /**
     * 活动申请页面
     */
    public function activityApplyAction()
    {
        if(!isset($this->_current_user['user_id'])){
            $this->messagePage($this->_main_host,'请先登录之后再申请');
        }
        $this->view->page_title = "申请研训";

        $this->view->publisher_list = Modules_Admin_Models_ActivityManage::getActivityPublisherMap();
        $this->view->phase_array = Modules_Admin_Models_ActivityManage::getActivityPhaseMap();//活动学段
        $this->view->type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap($this->_activity_course_subject_code);//活动类别
        $this->view->class_array = Modules_Admin_Models_ActivityManage::getActivityClassMap($this->_activity_course_subject_code);//活动类型
        unset($this->view->publisher_list['v00']);
        unset($this->view->phase_array['xd000']);
        unset($this->view->type_array['0']);
        unset($this->view->class_array['0']);
        $this->view->process_activity_apply_url =  '/'.$this->xk.'/'.$this->_current_controller.'/processActivityApply';
        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * 处理研训申请
     */
    public function processActivityApplyAction()
    {
        if(!isset($this->_current_user['user_id'])){
            $this->messagePage($this->_main_host,'请先登录之后再重新申请。');
        }
        $activity_apply_data = $this->getVar('data');
        //错误处理
        if($activity_apply_data['activity_apply_title'] == '' OR $activity_apply_data['activity_apply_contact'] == ''){
            $this->echoJson('error','请将必填信息填写完整后再提交。',array());
        }

//        $this->echoJson('success','ok',$activity_list_result);


        $activity_apply_data['activity_apply_subject'] = $this->_activity_course_subject_code;
        $activity_apply_data['activity_apply_proposer_uid'] = $this->_current_user['user_id'];
        $activity_apply_data['activity_apply_proposer_name'] = $this->_current_user['user_realname'];
        $activity_apply_data['activity_apply_proposer_school'] = $this->_current_user['school_name'];
        $activity_apply_data['activity_apply_status'] = 1;
        $activity_apply_data['activity_apply_date']  = time();
        $activity_apply_result = $this->_activity_manager_model->createActivityApply($activity_apply_data);
        if($activity_apply_result){
            $this->echoJson('success','ok',array());
        }else{
            $this->echoJson('error','申请失败，请重新申请或联系管理员。',array());
        }
    }



    /**
     * 活动详情页面
     * 有些操作需要登录
     * 活动未公开的时候:只有参加研训的人可以观看和打分，已经登录却没有参加的人需要参加之后才可以观看，
     * 活动公开之后，登录或者不登录的用户均可观看，但是不能再点击参加，已经参加的用户可以打分
     * 只有登录之后的用户才可以留言
     */
    public function activityInfoAction()
    {
        //获取学校信息
        $ddapi_url = DOMAIN_NAME.'/DDApi/class/myteachclasses';
        $dd = new Models_DDClient();
//        $tokens =  $_SESSION['tokens'];
        $post_data = array(
//            'access_token'=>$tokens['access_token'],
        );

//        $user_school_info_result = Cola_Com_Http::post($ddapi_url,$post_data);
////            $user_school_info_result = $dd->getDataByApi('user/myteachclasses',$post_data,1);
//        if (isset(json_decode($user_school_info_result)->errcode) and json_decode($user_school_info_result)->errcode == 7) {
//            /* 用刷新令牌之后来解决 */
//            $newAccessToken = $dd->_grantNewAccessToken($tokens['access_token'], $tokens['refresh_token']);
//            $user_school_info_result =   Cola_Com_Http::post($ddapi_url,$post_data);
//        }
//        $user_school_info_result = $dd->json_to_array(json_decode($user_school_info_result, 1));
        //gai

        $this->view->page_title = "研训详情";
        $activity_id = $this->getVar('activity_id');
        //取得活动基本信息，活动平均分，当前用户是否打分,活动的附件，视频资源
        $activity_info_result = $this->_activity_manager_model->getActivityInfoByActivityId($activity_id);
        $activity_city_array = explode(',',$activity_info_result['activity_info_area']);
        //如果学校不再这个活动范围内的话
        $this->view->could_join = false;

        $activity_avg_evaluate_result = $this->_activity_manager_model->getActivityAvgEvaluate($activity_id);
        if(isset($this->_current_user['user_id'])){
            $user_school_info_result = $dd->getDataByApi('class/myteachclasses',$post_data);
            $current_user_area_code = '';
            $current_user_area_name = '';
            $current_user_city_code = '';
            $current_user_city_name = '';
            $current_user_school_code = '';
            $current_user_school_name = '';
            if(!empty($user_school_info_result['data'])){
                $current_class_id = $user_school_info_result['data'][0]['class_id'];
                $ddapi_classinfo_url = DOMAIN_NAME.'/DDApi/class/classinfo';
                $classinfo_post_data = array(
//                'access_token'=>$tokens['access_token'],
                    'class_id'=>$current_class_id,
                );
//            $my_class_school_info  = Cola_Com_Http::post($ddapi_classinfo_url,$classinfo_post_data);
//            if (isset(json_decode($my_class_school_info)->errcode) and json_decode($my_class_school_info)->errcode == 7) {
//                /* 用刷新令牌之后来解决 */
//                $newAccessToken = $dd->_grantNewAccessToken($tokens['access_token'], $tokens['refresh_token']);
//                $my_class_school_info =   Cola_Com_Http::post($ddapi_classinfo_url,$classinfo_post_data);
//            }
//            $my_class_school_info = $dd->json_to_array(json_decode($my_class_school_info, 1));
                $my_class_school_info = $dd->getDataByApi('class/classinfo',$classinfo_post_data);
                if(!empty($my_class_school_info['data'])){
                    $current_user_area_code = $my_class_school_info['data']['school_county_code'];
                    $current_user_area_name = $my_class_school_info['data']['school_county_name'];
                    $current_user_city_code = $my_class_school_info['data']['school_city_code'];
                    $current_user_city_name = $my_class_school_info['data']['school_city_name'];
                    $current_user_school_code = $my_class_school_info['data']['school_id'];
                    $current_user_school_name = $my_class_school_info['data']['school_name'];
                }
            }
            if(in_array($current_user_city_code,$activity_city_array)){
                $this->view->could_join = true;
            }
            $activity_user_evaluate_result = $this->_activity_manager_model->getMemberEvaluate($activity_id, $this->_current_user['user_id']);
        }else{
            $activity_user_evaluate_result = -1;
        }

        $this->view->activity_info = $activity_info_result;
        $this->view->activity_avg_evaluate = $activity_avg_evaluate_result;
        $this->view->activity_user_evaluate = $activity_user_evaluate_result;
        //获取研训附件
        $activity_attachment_list_result = $this->_activity_manager_model->getActivityAttachment($activity_id, 1);
        //获取研训视频资源（主要）
        $activity_resource_list_result = $this->_activity_manager_model->getActivityAttachment($activity_id, 2);
        $this->view->activity_attachment_list = $activity_attachment_list_result;
//        var_dump($this->view->activity_attachment_list);
        if(!empty($activity_resource_list_result)){
            foreach($activity_resource_list_result as $key => $val){
                $resource_file_type_array = explode('.',$val['activity_resourse_title']);
                $activity_resource_list_result[$key]['activity_resourse_file_type'] = $resource_file_type_array[1];
            }
        }
        $this->view->activity_resource_list = $activity_resource_list_result;
//        var_dump($this->view->activity_resource_list);
        //活动成员心得
        $activity_member_evaluate_list_result = $this->_activity_manager_model->getActivityMemberEvaluateList($activity_id, 1, 10);
        if($activity_member_evaluate_list_result){
            $this->view->activity_member_evaluate_list = $activity_member_evaluate_list_result['rows'];
        }else{
            $this->view->activity_member_evaluate_list = array();
        }
//        var_dump($this->view->activity_member_evaluate_list);
        //获取活动小站列表
        $activity_sites_list_result = $this->_activity_manager_model->getIndexActivitySites($this->_activity_course_subject_code, 1, 20);
        if($activity_sites_list_result){
            $this->view->activity_sites_list = $activity_sites_list_result['rows'];
        }else{
            $this->view->activity_sites_list = array();
        }

        //增加点击数
        $this->_activity_manager_model->increaseActivityHit($activity_id);


        $this->view->user_id = isset($this->_current_user['user_id'])?$this->_current_user['user_id']:'';
        $this->view->activity_attachment_down_url =  '/'.$this->xk.'/'.$this->_current_controller.'/downloadAttachment';
        $this->view->activity_evaluate_url = '/'.$this->xk.'/'.$this->_current_controller.'/memberEvaluate';
        $this->view->join_activity_url = '/'.$this->xk.'/'.$this->_current_controller.'/joinActivity';
        $this->view->activity_index_url = '/'.$this->xk.'/'.$this->_current_controller.'/index';
        $this->view->teacher_info_url = '/'.$this->xk.'/teacher/index';
        $this->view->dodo_url = $this->_dodo_url;
        $this->setLayout($this->layout);
        $this->tpl();


    }

    /**
     * 文件下载
     */
    public function downloadAttachmentAction() {
        $file_path = $this->get('file_path');
        $file_name = $this->get('file_name');
        Models_Attachment::init()->download($file_path, $file_name);
    }


    /**
     * 打分
     */
    public function memberEvaluateAction()
    {
        $activity_id = $this->getVar('activity_member_pid');
        $activity_member_uid = $this->getVar('activity_member_uid');
        $activity_member_evaluate = $this->getVar('activity_member_evaluate');
        $member_evaluate_result = $this->_activity_manager_model->updateMemberEvaluate($activity_id, $activity_member_uid, $activity_member_evaluate);

        //修改全文检索里面的分数
        $options_array = array('activity_info_id'=>$activity_id);
        $activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,1,$options_array,array());
        //默认不会出错
        $activity_avg_evaluate = $activity_list_result['rows'][0]['activity_avg_evaluate'];
        $update_activity_search_array = array(
            'activity_info_evaluate'=>$activity_avg_evaluate
        );
        $update_activity_search_result = $this->_activity_manager_model->updateActivitySearch($activity_id,$update_activity_search_array);
//        var_dump($update_activity_search_result);
//        echo $member_evaluate_result;
        $this->echoJson('success','评分成功',array());
    }

    /**
     * 参加研训
     */
    public function joinActivityAction()
    {
        if(isset($this->_current_user['user_id'])){
            //登录了可以加
            //但是非教师用户不可以
            if($this->_current_user['role_code'] != '2'){
                $this->messagePage($this->_main_host,'只有教师用户可以参加研训');
            }
            //获取学校信息
            $ddapi_url = DOMAIN_NAME.'/DDApi/class/myteachclasses';
            $dd = new Models_DDClient();
//            $tokens =  $_SESSION['tokens'];
            $post_data = array(
//                'access_token'=>$tokens['access_token'],
            );

//            $user_school_info_result = Cola_Com_Http::post($ddapi_url,$post_data);
////            $user_school_info_result = $dd->getDataByApi('user/myteachclasses',$post_data,1);
//            if (isset(json_decode($user_school_info_result)->errcode) and json_decode($user_school_info_result)->errcode == 7) {
//                /* 用刷新令牌之后来解决 */
//                $newAccessToken = $dd->_grantNewAccessToken($tokens['access_token'], $tokens['refresh_token']);
//                $user_school_info_result =   Cola_Com_Http::post($ddapi_url,$post_data);
//            }
//            $user_school_info_result = $dd->json_to_array(json_decode($user_school_info_result, 1));
            $user_school_info_result = $dd->getDataByApi('class/myteachclasses',$post_data);





            $current_user_area_code = '';
            $current_user_area_name = '';
            $current_user_city_code = '';
            $current_user_city_name = '';
            $current_user_school_code = '';
            $current_user_school_name = '';
            if(!empty($user_school_info_result['data'])){
                $current_class_id = $user_school_info_result['data'][0]['class_id'];
                $ddapi_classinfo_url = DOMAIN_NAME.'/DDApi/class/classinfo';
                $classinfo_post_data = array(
//                    'access_token'=>$tokens['access_token'],
                    'class_id'=>$current_class_id,
                );
//                $my_class_school_info  = Cola_Com_Http::post($ddapi_classinfo_url,$classinfo_post_data);
//                if (isset(json_decode($my_class_school_info)->errcode) and json_decode($my_class_school_info)->errcode == 7) {
//                    /* 用刷新令牌之后来解决 */
//                    $newAccessToken = $dd->_grantNewAccessToken($tokens['access_token'], $tokens['refresh_token']);
//                    $my_class_school_info =   Cola_Com_Http::post($ddapi_classinfo_url,$classinfo_post_data);
//                }
//                $my_class_school_info = $dd->json_to_array(json_decode($my_class_school_info, 1));
                $my_class_school_info = $dd->getDataByApi('class/classinfo',$classinfo_post_data);
                if(!empty($my_class_school_info['data'])){
                    $current_user_area_code = $my_class_school_info['data']['school_county_code'];
                    $current_user_area_name = $my_class_school_info['data']['school_county_name'];
                    $current_user_city_code = $my_class_school_info['data']['school_city_code'];
                    $current_user_city_name = $my_class_school_info['data']['school_city_name'];
                    $current_user_school_code = $my_class_school_info['data']['school_id'];
                    $current_user_school_name = $my_class_school_info['data']['school_name'];
                }
            }
            $activity_info_result = $this->_activity_manager_model->getActivityInfoByActivityId($this->getVar('activity_id'));
            $activity_city_array = explode(',',$activity_info_result['activity_info_area']);
            //如果学校不再这个活动范围内的话
            if(!in_array($current_user_city_code,$activity_city_array)){
                echo json_encode('0');
                exit;
            }
            $post_member_data = array(
                'activity_member_pid'=>$this->getVar('activity_id'),
                'activity_member_uid'=>$this->_current_user['user_id'],
                'activity_member_real_name'=>$this->_current_user['user_realname'],
                'activity_member_icon'=>$this->_current_user['icon'],
                'activity_member_area_code'=>$current_user_area_code,
                'activity_member_area_name'=>$current_user_area_name,
                'activity_member_city_code'=>$current_user_city_code,
                'activity_member_city_name'=>$current_user_city_name,
                'activity_member_school_code'=>$current_user_school_code,
                'activity_member_school_name'=>$current_user_school_name,
                'activity_member_evaluate'=>0,
                'activity_member_date'=>time(),
                'activity_member_experience_blogid'=>'',
                'activity_member_experience_date'=>0,
                'activity_member_phase'=>$this->_current_user['xd'],
                'activity_member_subject'=>$this->_activity_course_subject_code,
            );
            $join_activity_result = $this->_activity_manager_model->createActivityMember($post_member_data);
            //全文索引的学员人数
            $options_array = array('activity_info_id'=>$this->getVar('activity_id'));
            $activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,1,$options_array,array());
            //默认不会出错
            $activity_activity_member = $activity_list_result['rows'][0]['member_total'];
            $update_activity_search_array = array(
                'activity_info_member'=>$activity_activity_member
            );
            $update_activity_search_result = $this->_activity_manager_model->updateActivitySearch($this->getVar('activity_id'),$update_activity_search_array);
            $this->echoJson('success','参加研训成功',array());
        }else{
            //没登录，要先登录
//            $this->messagePage($this->_main_host,'请先登录后再参加研训');
            $this->echoJson('error','请先登录后再参加研训',array());
        }
    }

    /**
     * 检索
     */
    public function searchAction(){
        $search_key = $this->getVar('key');
        $page = $this->getVar('p',1);
        $page_size = 6;
        //排序条件，默认按时间倒序
        $current_order = $this->getVar('current_order','activity_info_start_date');
        if($current_order =="start_date"){
            $order_field = 'activity_info_start_date';
        }else if($current_order =="hot"){
            $order_field = 'activity_info_evaluate';
        }else{
            $order_field = 'activity_info_start_date';
        }
        $asc = false;
        $activity_search_result = $this->_activity_manager_model->getActivitySearch($search_key,$this->_activity_course_subject_code,$order_field,$asc,$page,$page_size);
        Helper_Search::init(Cola::getConfig('_activity_search'))->flushLog();
        $activity_search_array = array('rows'=>array(), 'total'=>0);
        if($activity_search_result){
            $activity_search_array['rows'] = $activity_search_result['data'];
            $activity_search_array['total'] = $activity_search_result['count'];
        }
        $this->view->activity_list = $activity_search_array['rows'];

        //从列表页面8过来的
        $this->view->activity_list_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityList';
        $this->view->activity_apply_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityApply';
        $this->view->activity_info_url =  '/'.$this->xk.'/'.$this->_current_controller.'/activityInfo';
        $this->view->teacher_center_url = '/'.$this->xk.'/Teacher/index';
        $this->view->activity_index_url = '/'.$this->xk.'/'.$this->_current_controller.'/index';
        //最新最热
        $latest_activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,5,array(), array());
        if($latest_activity_list_result){
            $this->view->latest_activity_list = $latest_activity_list_result['rows'];
        }else{
            $this->view->latest_activity_list = array();
        }
        $hotest_activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,5,array(), array('AVG(`activity_member`.`activity_member_evaluate`)'=>'DESC'));
        if($hotest_activity_list_result){
            $this->view->hotest_activity_list = $hotest_activity_list_result['rows'];
        }else{
            $this->view->hotest_activity_list = array();
        }
        //获取研训达人
        $hot_teacher_page = 1;
        $hot_teacher_offset = 5;
        $hot_teacher_list_result = $this->_activity_manager_model->getHotTeachers($this->_activity_course_subject_code,$hot_teacher_page,$hot_teacher_offset);
        if($hot_teacher_list_result){
            $this->view->hot_teacher_list = $hot_teacher_list_result['rows'];
        }else{
            $this->view->hot_teacher_list = array();
        }
        $this->view->activity_info_url = '/'.$this->xk.'/'.$this->_current_controller.'/activityInfo';
        $this->view->ajax_activity_url = '/'.$this->xk.'/'.$this->_current_controller.'/ajaxActivitySearch';
        $this->view->search_key = $search_key;
        $this->view->dodo_url = $this->_dodo_url;
        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * ajax输出研训搜索结果
     */
    public function ajaxActivitySearchAction()
    {
        $search_key = $this->getVar('key');
        $page = $this->getVar('p',1);
        $page_size = 6;
        //排序条件，默认按时间倒序
        $current_order = $this->getVar('current_order','activity_info_start_date');
        if($current_order =="start_date"){
            $order_field = 'activity_info_start_date';
        }else if($current_order =="hot"){
            $order_field = 'activity_info_evaluate';
        }else{
            $order_field = 'activity_info_start_date';
        }
        $asc = false;
        $activity_search_result = $this->_activity_manager_model->getActivitySearch($search_key,$this->_activity_course_subject_code,$order_field,$asc,$page,$page_size);
        Helper_Search::init(Cola::getConfig('_activity_search'))->flushLog();
        $activity_search_array = array('rows'=>array(), 'total'=>0);
        if($activity_search_result){
            foreach($activity_search_result['data'] as $key => $val){
                if($val['activity_info_status'] === '0'){
                    $activity_search_result['count'] -= 1;
                    continue;
                }
                $activity_search_array['rows'][$key]['id'] = $val['id'];
                $activity_search_array['rows'][$key]['activity_info_title'] = $val['activity_info_title'];
                $activity_search_array['rows'][$key]['activity_info_status'] = $val['activity_info_status'];
                $activity_search_array['rows'][$key]['activity_info_icon'] = $val['activity_info_icon'];
                $activity_search_array['rows'][$key]['activity_info_start_date'] = $val['activity_info_start_date'];
                $activity_search_array['rows'][$key]['activity_info_evaluate'] = $val['activity_info_evaluate'];
                $activity_search_array['rows'][$key]['activity_info_member'] = $val['activity_info_member'];
                $activity_search_array['rows'][$key]['activity_info_description'] = $val['activity_info_description'];
            }
            $activity_search_array['total'] = $activity_search_result['count'];
        }
        $this->echoJson('success','ok', $activity_search_array);
    }
}
?>