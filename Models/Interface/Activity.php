<?php
/**
 * 研训接口接口类
 * @author michaeltsui98@qq.com
 */
class Models_Interface_Activity extends Models_Interface_Base {

    /**
     * 取研训分类
     * @param string $insSubjectCode 学科  GS0025
     * @return Ambigous <Cola_Config, mixed, multitype:, unknown>
     */
    public  function getType($insSubjectCode) {
    	$data =  Modules_Admin_Models_ActivityManage::getActivityTypeMap($insSubjectCode);
    	$temp = array();
        foreach ($data as $k=>$v){
        	$temp[] = array('id'=>$k,'name'=>$v);
        } 
        return $temp;
    } 
    /**
     * 取研训形势
     * @param string $insSubjectCode 学科  GS0025
     * @return Ambigous <multitype:, multitype:string >
     */
    public function getClass($insSubjectCode){
    	$data =  Modules_Admin_Models_ActivityManage::init()->getActivityClassMap($insSubjectCode);
    	$temp = array();
    	foreach ($data as $k=>$v){
    	   $temp[] = array('id'=>$k,'name'=>$v);
    	}
    	return $temp;
    }
    /**
     * 获取活动列表
     * @param $inSubject          学科   varchar:    GS0024：生命安全,GS0025：心理健康
     * @param $inPage             当前页数      int > 0
     * @param $inOffset           偏移量          int > 0
     * @param $inType             活动类型     int:  空为全部 活动类型：1：基础理论 2：专业知识 3：教学技能 4：教育技能 5：自主学习
     * @param string $inStatus    活动状态     int:  空为全部   0：已关闭，1：已发布2：已开始
     * @param string $inPhase     活动学段     int:   空为全部    xd001：小学 xd002：初中 xd003：高中
     * @param string $inVisibility 活动可见性 int: 空为全部  0：私有，1：公开
     * @param string $inClass     活动类型      int: 空为全部  1、专家沙龙（视频），2、示范课（视频），3、说课PPT（视频），4、互动课堂（直播）
     * @param string $inSync      活动同步      int: 空为全部  0：不是，1：是
     * @param string $inGrade     活动年级      空为全部   varchar: 'GO003'=>'小学一年级'
     * @param string $inPublisher 发行单位      空位全部   varchar:v01人教版，v02鄂教版
     * @return array
     * ----------------------------------------------------------------------------------------
     *`activity_info_id` int(10)  '数据ID',
     *`activity_info_subject` varchar(10)  '活动科目：GS0024：生命安全,GS0025：心理健康',
     *`activity_info_type` tinyint(1)  '活动类型：1：基础理论,2：专业知识,3：教学技能,4：教育技能,5：自主学习',
     *`activity_info_is_sync` tinyint(1)  '是否是同步课程：0：不是，1：是',
     *`activity_info_visibility` tinyint(1)  '活动可见性：0：私有，1：公开',
     *`activity_info_phase` varchar(10)  '活动学段：xd001：小学 xd002：初中 xd003：高中',
     *`activity_info_class` tinyint(2)  '研修的表现形式：1、专家沙龙（视频），2、示范课（视频），3、说课PPT（视频）,4、互动课堂（直播）',
     *`activity_info_live_url` varchar(200)  '直播用到的视频连接',
     *`activity_info_release_date` int(10)  '活动的发布时间',
     *`activity_info_update_date` int(10)  '活动的最新修改时间',
     *`activity_info_start_date` int(10)  '活动正式开始的时间',
     *`activity_info_status` tinyint(1)  '活动状态:0：已关闭，1：已发布2：已开始',
     *`activity_info_title` varchar(50)  '活动的标题',
     *`activity_info_icon` varchar(100)  '活动标题图片',
     *`activity_info_updater_id` varchar(50)  '活动管理员id',
     *`activity_info_host` text  '活动主持人',多人用逗号分隔
     *`activity_info_description` text  '活动介绍',
     *`activity_info_hits` int(10)  '活动被点击次数',
     *`activity_info_publisher` varchar(10)  '教材版本:v01人教版，v02鄂教版',
     *`activity_info_grade` varchar(10)  '年级',
     *`activity_info_weight` int(5)  '权重',
     *`activity_info_area` text  '活动地区：市州代码用逗号分隔',
     *`activity_info_hit` int(10)  '活动浏览次数',
     * `member_total` int  活动总人数
     *`activity_avg_evaluate` float活动平均分
     *
     * ----------------------------------------------------------------------------------------
     */
    public function getList($inSubject,$inPage,$inOffset,$inType='',$inStatus='',$inPhase='',$inVisibility='',$inClass='',$inSync='',$inGrade='',$inPublisher='') {
    	return Models_Activity_ActivityInfo::init()
    	->getActivityList($inSubject, $inPage, $inOffset, $inType, $inStatus, $inPhase, $inVisibility, $inClass, $inSync, $inGrade, $inPublisher);
    }
    /**
     * 我参加的研训
     * @param string $subject_code  学科   varchar:    GS0024：生命安全,GS0025：心理健康  （*）
     * @param string $user_id (*)
     * @param string $page 当前页码  (*)
     * @param strng $limit  每页多少条  (*)
     * @param int $start_time  时间戳 (可空)
     * @param int $end_time  时间戳   (可空)
     * @return Ambigous <boolean, multitype>
     */
    public function getMyActivity($subject_code,$user_id,$page,$limit,$start_time="",$end_time=""){
       return  Modules_Admin_Models_ActivityManage::init()
        ->getTeacherActivityList($subject_code, $user_id, $page, $limit, $start_time, $end_time);
    }
    /**
     * 取研训详情
     * @param int $activity_id
     * @return multitype:
     */
    public function getActivityInfo($activity_id) {
        $app_info_result =   Modules_Admin_Models_ActivityManage::init()->getActivityInfoByActivityId($activity_id);
        $app_info_result['activity_avg_evaluate'] = $this->getActivityAvgEvaluate($activity_id);
        $app_info_result['member_total'] = Modules_Admin_Models_ActivityManage::init()->getActivityMemberTotal($activity_id);
        return $app_info_result;
    }

    /**
     * 获取研训的平均打分
     * @param $activity_id
     * @return mixed
     */
    public function getActivityAvgEvaluate($activity_id)
    {
        return  Modules_Admin_Models_ActivityManage::init()->getActivityAvgEvaluate($activity_id);
    }

    /**
     * 获取我对某个研训的打分
     * @param $activity_id
     * @param $user_id
     * @return mixed
     */
    public function getActivityMyEvaluate($activity_id, $user_id)
    {
        return  Modules_Admin_Models_ActivityManage::init()->getMemberEvaluate($activity_id, $user_id);
    }

    /**
     * 获取某个应用的附件列表（word ppt等）
     * @param $activity_id
     * @return mixed
     */
    public function getActivityAttachment($activity_id)
    {
        return  Modules_Admin_Models_ActivityManage::init()->getActivityAttachment($activity_id, 1);
    }

    /**
     * 获取某个应用的视频列表
     * @param $activity_id
     * @return mixed
     */
    public function getActivityVideoResource($activity_id)
    {
        return  Modules_Admin_Models_ActivityManage::init()->getActivityAttachment($activity_id, 2);
    }



    /**
     * 取研训统计
     * @param string $subject_code  GS0024：生命安全,GS0025：心理健康
     * @param string  $user_id 
     * @return multitype:
     */
    public function getStatics($subject_code,$user_id){

//        return Modules_Admin_Models_ActivityManage::init()->getTeacherActivityStatistics($subject_code, $user_id);
        $teacher_activity_statistics_result = Modules_Admin_Models_ActivityManage::init()->getTeacherActivityStatistics($subject_code, $user_id);
        $teacher_total_activity_times = 0;
        $activity_type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap($subject_code);//活动类别
        if(!empty($teacher_activity_statistics_result)){
            foreach($teacher_activity_statistics_result as $key => $val){
                $teacher_total_activity_times += intval($val['activity_times']);
                $teacher_activity_statistics_result[$key]['activity_info_type_label'] =$activity_type_array[$val['activity_info_type']];
            }
        }
        $teacher_activity_class_statistics_result = Modules_Admin_Models_ActivityManage::init()->getTeacherActivityClassStatistics($subject_code, $user_id);
        $activity_class_array = Modules_Admin_Models_ActivityManage::getActivityClassMap($subject_code);
        if(!empty($teacher_activity_class_statistics_result)){
            foreach($teacher_activity_class_statistics_result as $key => $val){
                $teacher_activity_class_statistics_result[$key]['activity_info_class_label'] =$activity_class_array[$val['activity_info_class']];
            }
        }

        $return_array = array();
        $return_array['activity_times'] = $teacher_total_activity_times;
        $return_array['activity_type_data'] = $teacher_activity_statistics_result;
        $return_array['activity_class_data'] = $teacher_activity_class_statistics_result;
        return $return_array;









    }

    /**
     * 研训打分
     * @param int $inActivityId  研训ID
     * @param string $inUserId  用户ID
     * @param int $inEvaluate  分数
     * @return Ambigous <multitype:, boolean>
     */
    public function memberEvaluate ($inActivityId, $inUserId, $inEvaluate){
        return Models_Activity_ActivityMember::init()->updateActivityMemberEvaluate($inActivityId, $inUserId, $inEvaluate);
    }

    /**
     * 参加研训
     * @param $inUserId
     * @param $inActivityId
     * @return bool
     */

    public function joinActivity($inUserId, $inActivityId){
        $userId = $inUserId;
        $activityId = $inActivityId;
        $currentUser = $_SESSION['user'];
        $activity_manage = new Modules_Admin_Models_ActivityManage();


        if($currentUser['role_code'] != '2'){
            return false;
        }
        $dd = new Models_DDClient();
        $post_data = array(
//                'access_token'=>$tokens['access_token'],
        );
        $user_school_info_result = $dd->getDataByApi('class/myteachclasses',$post_data);
        $current_user_area_code = '';
        $current_user_area_name = '';
        $current_user_city_code = '';
        $current_user_city_name = '';
        $current_user_school_code = '';
        $current_user_school_name = '';
        if(!empty($user_school_info_result['data'])){
            $current_class_id = $user_school_info_result['data'][0]['class_id'];
            $classinfo_post_data = array(
                'class_id'=>$current_class_id,
            );
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
        $activity_info_result = $activity_manage->getActivityInfoByActivityId($activityId);
        $activity_city_array = explode(',',$activity_info_result['activity_info_area']);
        //如果学校不再这个活动范围内的话
        if(!in_array($current_user_city_code,$activity_city_array)){
                return false;
        }
        $post_member_data = array(
            'activity_member_pid'=>$activityId,
            'activity_member_uid'=>$userId,
            'activity_member_real_name'=>$currentUser['user_realname'],
            'activity_member_icon'=>$currentUser['icon'],
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
            'activity_member_phase'=>$currentUser['xd'],
            'activity_member_subject'=>$activity_info_result['activity_info_subject'],
        );
        $join_activity_result = $activity_manage->createActivityMember($post_member_data);
        //全文索引的学员人数
        $options_array = array('activity_info_id'=>$activityId);
        $activity_list_result = $activity_manage->getActivitiesByOptions($activityId,1,1,$options_array,array());
        //默认不会出错
        $activity_activity_member = $activity_list_result['rows'][0]['member_total'];
        $update_activity_search_array = array(
            'activity_info_member'=>$activity_activity_member
        );
        $update_activity_search_result = $activity_manage->updateActivitySearch($activityId,$update_activity_search_array);
        return true;
    }

    /**
     * 获取活动心得
     * @param $inActivityId
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     * ------------------------------------------
     * activity_member_uid   string 用户的社区ID
     * activity_member_experience_blogid     心得的社区博客ID
     * activity_member_experience_blogtitle 心得的博客标题
     * ------------------------------------------
     */
    public function getActivityMemberEvaluateList($inActivityId, $inPage, $inOffset)
    {
        return Modules_Admin_Models_ActivityManage::init()->getActivityMemberEvaluateList($inActivityId, $inPage, $inOffset);
    }


    /**
     * 删除我参与的研训
     */
    public function delMyActivity(){
    	 
    }

    /**
     * 获取教师中心首页左边的课程信息（取老师的课时，研训数，上传的资源数）
     * @param $inUserId
     * @param $inCourseName xl sm
     * @param $inCourseCode GS0024 GS0025
     */
    public function getTeacherIndexLeftCourseInfo($inUserId, $inCourseName, $inCourseCode)
    {
        $activity_manager_model = new Modules_Admin_Models_ActivityManage();
        $resource_model = new Models_Resource();
        //获取当前教师活动统计
        $teacher_activity_statistics_result = $activity_manager_model->getTeacherActivityStatistics($inCourseCode, $inUserId);
        $teacher_total_activity_times = 0;
        $activity_type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap($inCourseCode);//活动类别
        if(!empty($teacher_activity_statistics_result)){
            foreach($teacher_activity_statistics_result as $key => $val){
                $teacher_total_activity_times += intval($val['activity_times']);
                $teacher_activity_statistics_result[$key]['activity_info_type_label'] =$activity_type_array[$val['activity_info_type']];
            }
        }

        $return_array = array();
        $return_array['teacher_total_activity_times'] = $teacher_total_activity_times;
        //获取该科目上传资源
        $upload_resource_result = $resource_model->getResourceBySearch('', '', $inCourseCode, '', '', '', $inUserId, '', '', '');
        $return_array['upload_resource_total'] = $upload_resource_result['count'];
        //获取备课夹个数
        $return_array['lesson_folder_total'] = Models_Lesson_Folder::init()->getUserFolderCount($inUserId, array('subject' => $inCourseCode));
        //获取本周科目课时
        $return_array['weekly_lesson_total'] = Models_Course_Interface::init()->courseCountOfThisWeekByTeacher($inUserId, $inCourseName);
        return $return_array;
    }

    /**
     * 写研训心得接口
     * @param $user_id
     * @param $activity_id
     * @param $blog_title
     * @param $blog_content
     * @return bool
     */
    public function postActivityExperienceToDoDoEdu($user_id,$activity_id,$blog_title,$blog_content)
    {
        $dd = new Models_DDClient();
        if($blog_content == "" OR $blog_title == '' OR $activity_id == '' OR $user_id == ''){
            return false;
        }
        $activity_experience_post_data = array(
            'blog_title'=>$blog_title,
            'blog_content'=>$blog_content,
            'user_id'=>$user_id,
        );
        //gai
        $activity_experience_info = $dd->getDataByApi('blog/addmyblog',$activity_experience_post_data);
        $activity_experience_blog_id =$activity_experience_info['data']['blog_id'];
        $activity_manage_model = new Modules_Admin_Models_ActivityManage();
        $post_activity_experience_result = $activity_manage_model->postActivityExperience($activity_id, $user_id, $activity_experience_blog_id, time(), $blog_title);
        return true;
    }

     
}