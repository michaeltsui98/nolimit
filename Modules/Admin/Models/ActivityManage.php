<?php
/**
 * 活动管理后台业务逻辑模型
 *@author sizzflair87430@gmail.com
 */
class Modules_Admin_Models_ActivityManage  extends Cola_Model {
    private $_activity_model = '';
    private $_activity_resource_model = '';
    private $_activity_member_model = '';
    private $_activity_apply_model = '';
    private $_activity_city_model = '';
    private $_activity_site_model = '';
    private $_activity_search_model = '';
    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->_activity_model = new Models_Activity_ActivityInfo();
        $this->_activity_resource_model = new Models_Activity_ActivityResource();
        $this->_activity_member_model = new Models_Activity_ActivityMember();
        $this->_activity_apply_model = new Models_Activity_ActivityApply();
        $this->_activity_city_model = new Models_Activity_ActivityCity();
        $this->_activity_site_model = new Models_Activity_ActivitySite();
        $this->_activity_search_model = new Models_Activity_ActivitySearch();
    }

    /**
     * 按照条件获取活动列表
     * @param $inSubject                  学科   varchar:    GS0024：生命安全,GS0025：心理健康
     * @param $inPage                       当前页数      int > 0
     * @param $inOffset                      偏移量          int > 0
     * @param $inType                        活动类型     int:  空为全部 活动类型：1：基础理论 2：专业知识 3：教学技能 4：教育技能 5：自主学习
     * @param string $inStatus        活动状态     int:  空为全部   0：已关闭，1：已发布2：已开始
     * @param string $inPhase        活动学段     int:   空为全部    xd001：小学 xd002：初中 xd003：高中
     * @param string $inVisibility  活动可见性 int: 空为全部  0：私有，1：公开
     * @param string $inClass         活动类型      int: 空为全部  1、专家沙龙（视频），2、示范课（视频），3、说课PPT（视频），4、互动课堂（直播）
     * @param string $inSync          活动同步      int: 空为全部  0：不是，1：是
     * @param string $inGrade        活动年级      空为全部   varchar: 'GO003'=>'小学一年级'
     * @param string $inPublisher  发行单位      空位全部   varchar:v01人教版，v02鄂教版
     * todo 现在都变成EAPI了
     * @return array
     */
    public function getActivityList($inSubject,$inPage,$inOffset,$inType='',$inStatus='',$inPhase='',$inVisibility='',$inClass='',$inSync='',$inGrade='',$inPublisher='')
    {
//        $result = $this->_activity_model->getActivityList($inSubject,$inPage,$inOffset,$inType,$inStatus='',$inPhase='',$inVisibility='',$inClass='',$inSync='',$inGrade='',$inPublisher='');
//        return $result;
        $objectId = '';
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityList/'.$objectId;
        $requestData = array(
                                    'client_key'=>$eapiHelper->getClientKey(),
                                    'subject'=>$inSubject,
                                    'page'=>$inPage,
                                    'offset'=>$inOffset,
                                    'type'=>$inType,
                                    'status'=>$inStatus,
                                    'phase'=>$inPhase,
                                    'visibility'=>$inVisibility,
                                    'class'=>$inClass,
                                    'sync'=>$inSync,
                                    'grade'=>$inGrade,
                                    'publisher'=>$inPublisher
        );
        $result = $eapiHelper->EApiRequest('GET', $requestURI, $objectId, $requestData);
        return $result['data'];
        
        
        
    }

    /**
     * 插入
     * @param $inData
     * @return bool
     */
    public function createActivity($inData)
    {
//        $result = $this->_activity_model->insert($inData);
        $objectId = '';
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityInfo/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $result = $eapiHelper->EApiRequest('POST', $requestURI, $objectId, $inData);
        return $result['data'];
    }

    //全文检索
    public function createActivitySearch($inData)
    {
        $result = Models_Activity_ActivitySearch::init()->search()->addIndex($inData);
        return $result;
    }

    /**
     * 更新全文索引
     * @param $inActivityId
     * @param $inActivityData
     * @return mixed
     */
    public function updateActivitySearch($inActivityId, $inActivityData)
    {
        $result = Models_Activity_ActivitySearch::init()->search()->editIndex($inActivityId,$inActivityData);
        return $result;
    }



    public function getActivitySearch($inKey,$inSubjectCode,$order_field,$asc,$page,$page_size)
    {
        $query = " * {$inKey}   AND node_id:{$inSubjectCode}";
        $resources = Helper_Search::init(Cola::getConfig('_activity_search'))->indexQuery($query,false,$order_field,$asc,false,$page,$page_size,true);
        return $resources;
    }




    /**
     * 查询单条活动记录
     * @param $inActivityId
     * @return array
     */
    public function getActivityInfoByActivityId($inActivityId)
    {
//        $result = $this->_activity_model->load($inActivityId);
        $objectId = $inActivityId;
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityInfo/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $result = $eapiHelper->EApiRequest('GET', $requestURI, $objectId, $inData); 
        return $result['data'];
    }






    /**
     * 更新活动信息
     * @param $inActivityId
     * @param array $inData
     * @return bool
     */
    public function updateActivity($inActivityId, array $inData)
    {
//        $result = $this->_activity_model->update($inActivityId,$inData);
        $objectId = $inActivityId;
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityInfo/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $result = $eapiHelper->EApiRequest('PUT', $requestURI, $objectId, $inData); 
        return $result['data'];
    }


    /*activity management go here*/
    /**
     * 活动预告上传附件
     * @param array $inData
     * @return bool
     */
    public function uploadActivityAttachment(array $inData)
    {
//        $result = $this->_activity_resource_model->insert($inData);
        $objectId = '';
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityAttachment/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $result = $eapiHelper->EApiRequest('POST', $requestURI, $objectId, $inData); 
        return $result['data'];
    }

    /**
     * 获取附件
     * @param $inActivityId
     * @param $inResourceType
     * @return array
     */
    public function getActivityAttachment($inActivityId,$inResourceType)
    {
//        $result = $this->_activity_resource_model->getActivityResourceList($inActivityId,$inResourceType);
        $objectId = $inActivityId;
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityAttachment/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $inData['resource_type'] = $inResourceType;
        $result = $eapiHelper->EApiRequest('GET', $requestURI, $objectId, $inData); 
        return $result['data'];
    }


    /**
     * 删除资源
     * @param $inResourceId
     * @return bool
     */
    public function removeResource($inResourceId)
    {
//        $result = $this->_activity_resource_model->delete($inResourceId);
        $objectId = $inResourceId;
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityAttachment/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $result = $eapiHelper->EApiRequest('DELETE', $requestURI, $objectId, $inData);         
        return $result['data'];
    }

    /**
     * 获取活动成员列表
     * @param $inActivityId
     * @param $inPage
     * @param $inOffset 
     * @return bool|multitype
     */
    public function getActivityMemberList($inActivityId,$inPage,$inOffset)
    {
//        $result = $this->_activity_member_model->readActivityMemberList($inActivityId,$inPage,$inOffset);
        $objectId = $inActivityId;
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityMemberList/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $inData['page'] = $inPage;
        $inData['offset'] = $inOffset;
        $result = $eapiHelper->EApiRequest('GET', $requestURI, $objectId, $inData);
        return $result['data'];
    }

    public function getActivityMemberTotal($inActivityId)
    {
//        $result = $this->_activity_member_model->readActivityMemberTotal($inActivityId);
        $objectId = $inActivityId;
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityMemberList/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $inData['page'] = 1;
        $inData['offset'] = 9999999;
        $result = $eapiHelper->EApiRequest('GET', $requestURI, $objectId, $inData);        
        return intval($result['data']['total']);
    }


    /**
     * 删除活动学员
     * @param $inMemberId
     * @return bool
     */
    public function deleteActivityMember($inMemberId)
    {
//        $result = $this->_activity_member_model->delete($inMemberId);
        $objectId = $inMemberId;
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityMember/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $result = $eapiHelper->EApiRequest('DELETE', $requestURI, $objectId, $inData);            
        return $result['data'];
    }

    /**
     * 获取活动平均分
     * @param $inActivityId
     * @return float
     */
    public function getActivityAvgEvaluate($inActivityId)
    {
//        $result = $this->_activity_member_model->readActivityAvgEvaluate($inActivityId);
        $objectId = $inActivityId;
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityEvaluate/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $result = $eapiHelper->EApiRequest('GET', $requestURI, $objectId, $inData);  
//        return floatval($result['data'][0]['activity_avg_evalute']); 
        return floatval($result['data']); 
    }

    /**
     * 获取活动申请列表
     */
    public function getActivityApplyList($inSubjectId,$inPage,$inOffset)
    {
//        $result = $this->_activity_apply_model->readActivityApplyList($inSubjectId,$inPage,$inOffset);
        $objectId = '';
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityApplyList/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $inData['subject_id'] = $inSubjectId;
        $inData['page'] = $inPage;
        $inData['offset'] = $inOffset;
        $result = $eapiHelper->EApiRequest('GET', $requestURI, $objectId, $inData); 
        return $result['data'];
    }


    /**statistics go here**/
    /**
     * 获取活动统计
     * @param $inStartDateStamp
     * @param $inEndDateStamp
     * @param $inSubjectCode
     * @return array
     */
    public function getActivityStatistics($inSubjectCode, $inStartDateStamp, $inEndDateStamp)
    {
//        $activity_statistics_array = array();
//        //现在是DEMO
//        $activity_statistics_demo_array['rows'] = array(
//            array('city_name'=>'武汉','xd001_percent'=>'90','xd001_times'=>'100','xd002_percent'=>'70','xd002_times'=>'77','xd003_percent'=>'66','xd003_times'=>'55'),
//            array('city_name'=>'黄石','xd001_percent'=>'89','xd001_times'=>'99','xd002_percent'=>'72','xd002_times'=>'76','xd003_percent'=>'55','xd003_times'=>'54'),
//            array('city_name'=>'宜昌','xd001_percent'=>'88','xd001_times'=>'98','xd002_percent'=>'55','xd002_times'=>'54','xd003_percent'=>'77','xd003_times'=>'32'),
//            array('city_name'=>'咸宁','xd001_percent'=>'87','xd001_times'=>'97','xd002_percent'=>'67','xd002_times'=>'54','xd003_percent'=>'33','xd003_times'=>'21'),
//            array('city_name'=>'恩施','xd001_percent'=>'86','xd001_times'=>'96','xd002_percent'=>'77','xd002_times'=>'34','xd003_percent'=>'34','xd003_times'=>'33'),
//            array('city_name'=>'鄂州','xd001_percent'=>'85','xd001_times'=>'95','xd002_percent'=>'88','xd002_times'=>'54','xd003_percent'=>'56','xd003_times'=>'15'),
//            array('city_name'=>'十堰','xd001_percent'=>'84','xd001_times'=>'94','xd002_percent'=>'96','xd002_times'=>'44','xd003_percent'=>'87','xd003_times'=>'8'),
//            array('city_name'=>'荆门','xd001_percent'=>'83','xd001_times'=>'93','xd002_percent'=>'76','xd002_times'=>'44','xd003_percent'=>'22','xd003_times'=>'22'),
//            array('city_name'=>'襄樊','xd001_percent'=>'82','xd001_times'=>'92','xd002_percent'=>'87','xd002_times'=>'33','xd003_percent'=>'15','xd003_times'=>'8'),
//            array('city_name'=>'潜江','xd001_percent'=>'81','xd001_times'=>'91','xd002_percent'=>'67','xd002_times'=>'21','xd003_percent'=>'45','xd003_times'=>'23'),
//            array('city_name'=>'随州','xd001_percent'=>'80','xd001_times'=>'90','xd002_percent'=>'87','xd002_times'=>'15','xd003_percent'=>'54','xd003_times'=>'77'),
//            array('city_name'=>'仙桃','xd001_percent'=>'79','xd001_times'=>'89','xd002_percent'=>'85','xd002_times'=>'25','xd003_percent'=>'66','xd003_times'=>'3'),
//            array('city_name'=>'孝感','xd001_percent'=>'78','xd001_times'=>'88','xd002_percent'=>'57','xd002_times'=>'65','xd003_percent'=>'55','xd003_times'=>'73'),
//            array('city_name'=>'天门','xd001_percent'=>'77','xd001_times'=>'87','xd002_percent'=>'97','xd002_times'=>'45','xd003_percent'=>'44','xd003_times'=>'2'),
//            array('city_name'=>'荆州','xd001_percent'=>'76','xd001_times'=>'86','xd002_percent'=>'45','xd002_times'=>'45','xd003_percent'=>'33','xd003_times'=>'77'),
//            array('city_name'=>'黄冈','xd001_percent'=>'75','xd001_times'=>'85','xd002_percent'=>'76','xd002_times'=>'76','xd003_percent'=>'22','xd003_times'=>'54'),
//        );
//        $activity_statistics_demo_array['total'] = '17';
////        $activity_statistics_array = $activity_statistics_demo_array;
//       $activity_statistics_array_result = $this->_activity_member_model->readActivityStatistics($inSubjectCode, $inStartDateStamp, $inEndDateStamp);
//
//        //先算活动次数todo
//        $activity_times_result = $this->_activity_model->readActivityTimeStatistics($inSubjectCode, $inStartDateStamp, $inEndDateStamp);
//        $activity_statistics_array = $activity_times_result;
//        $result_array = array();
//        $city_array = $this::getActivityAreaMap('_hubei');
//        //读圈子啦
//        $subject_array = array(
//            'GS0024'=>'222,323,425',
//            'GS0025'=>'221,322,424',
//        );
//        $sub_subject_array = array(
//            '222'=>'xd001',
//            '232'=>'xd002',
//            '425'=>'xd003',
//            '221'=>'xd001',
//            '323'=>'xd002',
//            '424'=>'xd003',
//        );
//        $current_subject_circle_str = $subject_array[$inSubjectCode];
//        //这里读圈子
//        $circle_mode = Cola_Com_WebServices::factory(Cola::getconfig('_webServicesCircle'));
//        $circle_teacher_result = $circle_mode->StatisticsSubByCity("420000", (string) $current_subject_circle_str);
//        $circle_teacher_array = array();
//        foreach($circle_teacher_result[0] as $key => $val){
//            foreach($val as $key2=> $val2){
//                $circle_teacher_array[$key][$sub_subject_array[$key2]] = $val2;
//            }
//        }
//        if(!empty($activity_statistics_array)){
//            foreach($activity_statistics_array as $key => $val){
//                $activity_city_array = explode(',',$val['activity_info_area']);
//                foreach($activity_city_array as $key2 => $val2){
//                       if(!isset($result_array[$val2])){
//                           $result_array[$val2] = array(
//                                                        'xd001_times'=>0,'xd001_teacher'=>0, 'xd001_total'=>1,'xd001_percent'=>0,
//                                                        'xd002_times'=>0,'xd002_teacher'=>0, 'xd002_total'=>1,'xd002_percent'=>0,
//                                                        'xd003_times'=>0,'xd003_teacher'=>0, 'xd003_total'=>1,'xd003_percent'=>0,
//                                                        'city_name'=>$city_array[$val2]
//                                        );
//                       }
//                       if($val['activity_info_phase'] == 'xd001'){
//                            $result_array[$val2]['xd001_times'] +=1;
//                       }else if($val['activity_info_phase'] == 'xd002'){
//                           $result_array[$val2]['xd002_times'] +=1;
//                       }else if($val['activity_info_phase'] == 'xd003'){
//                           $result_array[$val2]['xd003_times'] +=1;
//                       }
//                    //都有逻辑
//                    if(isset($circle_teacher_array[$val2])){
//                        $result_array[$val2]['xd001_total'] = $circle_teacher_array[$val2]['xd001'];
//                        $result_array[$val2]['xd002_total'] = $circle_teacher_array[$val2]['xd002'];
//                        $result_array[$val2]['xd003_total'] = $circle_teacher_array[$val2]['xd003'];
//                    }
//                }
//                //都有逻辑
//            }
//        }
//        //组合一下
//        foreach($activity_statistics_array_result as $key => $val){
//            if(isset($result_array[$val['activity_member_city_code']])){
//                $result_array[$val['activity_member_city_code']][$val['activity_member_phase'].'_teacher'] +=1;
//            }
//        }
//        //最后循环算率，方法内一共四次循环
//        foreach($result_array as $key => $val){
//            if($val['xd001_times'] > 0 AND  $val['xd001_total'] > 0){
//                $result_array[$key]['xd001_percent'] = $val['xd001_teacher']/($val['xd001_total']*$val['xd001_times']);
//            }
//            if($val['xd002_times'] > 0  AND  $val['xd002_total'] > 0 ){
//                $result_array[$key]['xd002_percent'] = $val['xd002_teacher']/($val['xd002_total']*$val['xd002_times']);
//            }
//            if($val['xd003_times'] > 0  AND  $val['xd003_total'] > 0){
//                $result_array[$key]['xd003_percent'] = $val['xd003_teacher']/($val['xd003_total']*$val['xd003_times']);
//            }
//        }
//        $return_array = array('rows'=>array_values($result_array), 'total'=>count($result_array));
        $objectId = '';
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityStatistics/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $inData['subject_id'] = $inSubjectCode;
        $inData['start_date_stamp'] = 0;
        $inData['end_date_stamp'] = 0;
        $result = $eapiHelper->EApiRequest('GET', $requestURI, $objectId, $inData);
        return $result['data'];
    }

    /**
     * 获取研训学员统计
     * @param $inSubjectCode
     * @param $inAreaCode
     * @param $inAreaCodeType
     * @param $inPhase
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function getActivityMemberStatistics($inSubjectCode, $inAreaCode, $inAreaCodeType, $inPhase, $inPage, $inOffset, $start_time, $end_time)
    {
//        $activity_member_statistics_array = array();
//        $activity_member_statistics_demo_array['rows'] = array(
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学1','activity_member_times'=>'10'),
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学2','activity_member_times'=>'9'),
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学3','activity_member_times'=>'8'),
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学4','activity_member_times'=>'7'),
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学5','activity_member_times'=>'6'),
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学6','activity_member_times'=>'5'),
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学7','activity_member_times'=>'4'),
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学8','activity_member_times'=>'3'),
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学9','activity_member_times'=>'2'),
//            array('activity_member_real_name'=>'教师1','activity_member_area_name'=>'武汉市','activity_member_school_name'=>'小学10','activity_member_times'=>'1'),
//        );
//        $activity_member_statistics_demo_array['total']='10';
//        $activity_member_statistics_array = $activity_member_statistics_demo_array;
//        if($inAreaCode == ''){
//            $inAreaCode = '420100';
//        }
//        return $this->_activity_member_model->readActivityMemberStatistics($inSubjectCode, $inAreaCode, $inAreaCodeType, $inPhase, $inPage, $inOffset, $start_time, $end_time);
        $objectId = '';
        $eapiHelper = new Helper_Eapi();
        $requestURI = $eapiHelper->getEapiUrl().'activityMemberStatistics/'.$objectId;
        $inData['client_key'] = $eapiHelper->getClientKey();
        $inData['subject_id'] = $inSubjectCode;
        $inData['area_code'] = $inAreaCode;
        $inData['area_code_type'] = $inAreaCodeType;
        $inData['phase'] = $inPhase;
        $inData['page'] = $inPage;
        $inData['offset'] = $inOffset;
        $inData['start_time'] = $start_time;
        $inData['end_time'] = $end_time;
        $result = $eapiHelper->EApiRequest('GET', $requestURI, $objectId, $inData);
        return $result['data'];
        
    }

    //教师分析
    /**
     * 获取教师学段
     * @param $inSubjectCode
     * @param $inStartDate
     * @param $inEndDate
     * @return array
     */
    public function getTeacherPhaseAnalysis($inSubjectCode, $inStartDate, $inEndDate)
    {
        return $this->_activity_member_model->readActivityStatistics($inSubjectCode, $inStartDate, $inEndDate);
    }

    /**
     * 获取研训类别分析
     * @param $inSubjectCode
     * @param $inStartDate
     * @param $inEndDate
     * @return array
     */
    public function getActivityTypeAnalysis($inSubjectCode, $inStartDate, $inEndDate)
    {
        return $this->_activity_model->readActivityTypeAnalysis($inSubjectCode, $inStartDate, $inEndDate);
    }



    /**
     * 批量添加活动城市编码
     * @param $inCityData
     * @return array|int
     */
    public function insertActivityCity($inCityData)
    {
        return $this->_activity_city_model->createActivityCity($inCityData);
    }

    /**
     *根据活动ID删除活动地区
     * @param $inActivityId
     * @return array
     */
    public function deleteActivityCityByActivityId($inActivityId)
    {
        return $this->_activity_city_model->deleteActivityCity($inActivityId);
    }


    /**
     * 获取小站列表
     * @param $inSiteType
     * @param int $inStart
     * @param int $inOffset
     * @return mixed|string
     */
    public function getActivitySites($inSiteType=7, $inStart=0, $inOffset=8,$inParseLogo= 145)
    {
        $dodo_api_url = DOMAIN_NAME.'/DDApi/site/getsitelistbytype';
        $dd = new Models_DDClient();
//        $tokens =  $_SESSION['tokens'];
        $post_data = array(
//            'access_token'=>$tokens['access_token'],
            'site_type'=>$inSiteType,
            'start'=>$inStart,
            'limit'=>$inOffset,
            '$parse_logo'=>$inParseLogo
        );
//        $infoData =   Cola_Com_Http::post($dodo_api_url,$post_data);
//
//        if (isset(json_decode($infoData)->errcode) and json_decode($infoData)->errcode == 7) {
//            /* 用刷新令牌之后来解决 */
//            $newAccessToken = $dd->_grantNewAccessToken($tokens['access_token'], $tokens['refresh_token']);
//            $infoData =   Cola_Com_Http::post($dodo_api_url,$post_data);
//        }

//        return $dd->json_to_array(json_decode($infoData, 1));
        $result = $dd->getDataByApi('site/getsitelistbytype',$post_data);
        return$result;
    }




    /**
     * 根据学科获取已经确定的活动小站列表
     * @param $inSubjectCode
     * @return array
     */
    public function getSelectedSites($inSubjectCode){
        return $this->_activity_site_model->getAllActivitySites($inSubjectCode);
    }

    /**
     * 添加活动小站
     * @param array $inDataArray
     * @return bool
     */
    public function createActivitySite(array $inDataArray)
    {
        return $this->_activity_site_model->insert($inDataArray);
    }

    /**
     * 删除活动小站
     * @param $inSiteId
     * @return bool
     */
    public function deleteActivitySite($inSiteId)
    {
        return $this->_activity_site_model->delete($inSiteId);
    }







    /***外部的都走这里**/
    /**
     * 返回从今天开始以后的5个活动
     * @param $inSubject
     * @param $inStartDate
     * @param $inOffset
     * @return array
     */
    public function getLatestActivity($inSubject,$inStartDate,$inOffset)
    {
            $result = $this->_activity_model->readLatestActivities($inSubject,$inStartDate,$inOffset);
            return $result;
    }

    /**
     * 根据条件取得活动分页列表
     * @param $inSubject
     * @param $inPage
     * @param $inOffset
     * @param array $inSelectOptions
     * @param array $inOrderOptions
     * @return bool|multitype
     */
    public function getActivitiesByOptions($inSubject,$inPage,$inOffset, array $inSelectOptions, array $inOrderOptions)
    {
        return $this->_activity_model->readActivitiesByOptions($inSubject,$inPage,$inOffset, $inSelectOptions, $inOrderOptions);
    }

    /**
     *  获取首页研训小站
     * @param $inSubjectCode
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function getIndexActivitySites($inSubjectCode, $inPage, $inOffset)
   {
        return $this->_activity_site_model->getActivitySites($inSubjectCode, $inPage, $inOffset);
   }


    /**
     * 获取教研咨询
     * @param $inElementId
     * @param $inElementType
     * @param $inLimit
     */
    public function getActivityNews($inElementId, $inElementType, $inLimit)
    {
        $dodo_api_url = DOMAIN_NAME.'/DDApi/site/getsiteelementdata';
        $dd = new Models_DDClient();
//        $tokens =  $_SESSION['tokens'];
        $post_data = array(
//            'access_token'=>$tokens['access_token'],
            'element_id'=>$inElementId,
            'element_type'=>$inElementType,
            'limit'=>$inLimit
        );
//        $infoData =   Cola_Com_Http::post($dodo_api_url,$post_data);
//        if (isset(json_decode($infoData)->errcode) and json_decode($infoData)->errcode == 7) {
//            /* 用刷新令牌之后来解决 */
//            $newAccessToken = $dd->_grantNewAccessToken($tokens['access_token'], $tokens['refresh_token']);
//            $infoData =   Cola_Com_Http::post($dodo_api_url,$post_data);
//        }
//        return $dd->json_to_array(json_decode($infoData, 1));
        $result = $dd->getDataByApi('site/getsiteelementdata',$post_data);
        return $result;
    }

    /**
     * 获取研训达人
     * @param $inSubject
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function getHotTeachers($inSubject,$inPage,$inOffset)
    {
        return $this->_activity_member_model->readActivityMemberByHot($inSubject,$inPage,$inOffset);
    }

    /**
     * 获取用户对当前活动的评价
     * @param $inActivityId
     * @param $inUserId
     * @return array
     */
    public function getMemberEvaluate($inActivityId, $inUserId)
    {
        return $this->_activity_member_model->readActivityUserEvaluate($inActivityId, $inUserId);
    }

    /**
     * 用户打分
     * @param $inActivityId
     * @param $inUserId
     * @param $inEvaluate
     * @return array
     */
    public function updateMemberEvaluate($inActivityId, $inUserId, $inEvaluate)
    {
        $evaluate = intval($inEvaluate)>5?5:intval($inEvaluate);
        return $this->_activity_member_model->updateActivityMemberEvaluate($inActivityId, $inUserId, $evaluate);
    }


    /**
     * 添加活动成员
     * @param array $inMemberData
     * @return bool
     */
    public function createActivityMember(array $inMemberData)
    {
            return $this->_activity_member_model->insert($inMemberData);
    }

    /**
     * 添加活动申请
     * @param array $inApplyData
     * @return bool
     */
    public function createActivityApply(array $inApplyData)
    {
        return $this->_activity_apply_model->insert($inApplyData);
    }

    /**
     * 获取当前教师的活动统计
     * @param $inSubjectCode
     * @param $inUserId
     * @return array
     */
    public function getTeacherActivityStatistics($inSubjectCode, $inUserId)
    {
        return $this->_activity_member_model->readTeacherActivityStatistics($inSubjectCode, $inUserId);
    }

    /**
     * 获取当前教师的活动统计
     * @param $inSubjectCode
     * @param $inUserId
     * @return array
     */
    public function getTeacherActivityClassStatistics($inSubjectCode, $inUserId)
    {
        return $this->_activity_member_model->readTeacherActivityClassStatistics($inSubjectCode, $inUserId);
    }

    /**
     * 分页获取教师研训列表
     * @param $inSubjectCode
     * @param $inUserId
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function getTeacherActivityList($inSubjectCode, $inUserId, $inPage, $inOffset, $inStartTime="", $inEndTime="'")
    {
        return $this->_activity_member_model->readTeacherActivityList($inSubjectCode, $inUserId, $inPage, $inOffset, $inStartTime, $inEndTime);
    }

    /**
     * 上传活动心得
     * @param $inActivityId
     * @param $inUserId
     * @param $inBlogId
     * @param $inBlogDate
     * @return array
     */
    public function postActivityExperience($inActivityId, $inUserId, $inBlogId, $inBlogDate, $inBlogTitle)
    {
        return $this->_activity_member_model->updateActivityExperience($inActivityId, $inUserId, $inBlogId, $inBlogDate, $inBlogTitle);
    }

    /**
     * 获取活动心得
     * @param $inActivityId
     * @param $inPage
     * @param $inOffset
     * @return bool|multitype
     */
    public function getActivityMemberEvaluateList($inActivityId, $inPage, $inOffset)
    {
        return $this->_activity_member_model->getActivityMemberEvaluateList($inActivityId, $inPage, $inOffset);
    }


    /**
     * 取得研训日历
     * @param $inSubjectCode
     * @param $inStartStamp
     * @param $inEndStamp
     */
    public function getActivityCalender($inSubjectCode, $inStartStamp, $inEndStamp)
    {
        return $this->_activity_model->readActivityTimeStatistics($inSubjectCode, $inStartStamp, $inEndStamp);
    }

    /**
     * 增加研训的点击数
     * @param $inActivityId 研训ID
     * @return array 影响的行数
     */
    public function increaseActivityHit($inActivityId)
    {
        return $this->_activity_model->updateActivityHit($inActivityId);
    }



    /***static attributes go here**/
    /**
     * 返回学科映射
     * 映射可能还有很多
     * @static
     * @return array
     */
    static public function getSubjectMap()
    {
        $subject_array = array(
                                    'sm'=>'GS0024',
                                    'xl'=>'GS0025',
        );
        return $subject_array;
    }

    /**
     * 返回活动课程同步性映射
     * @static
     * @return array
     */
    static public function getActivitySyncMap()
    {
        $sync_array = array(
                                '0'=>'否',
                                '1'=>'是',
        );
        return $sync_array;
    }

    /**
     * 返回活动类别
     * @static
     * @return array
     * todo gai
     */
    static public function getActivityTypeMap($inSubjectCode = 'GS0025')
    {
        $type_array = array(
                        'GS0024'=>array(
                            '0'=>'类别不限',
                            '1'=>'通识性培训',
                            '2'=>'教材教法',
                            '3'=>'知识技能',
                            '4'=>'实践体验',
                            '5'=>'专项培训',
                        ),
                        'GS0025'=>array(
                            '0'=>'类别不限',
                            '1'=>'通识性培训',
                            '2'=>'教材教法',
                            '3'=>'知识技能',
                            '4'=>'实践体验',
                            '5'=>'专项培训',
                        )
        );
        return $type_array[$inSubjectCode];
    }

    /**
     * 获取活动类型
     * @static
     * @return array
     */
    static public function getActivityClassMap($inSubjectCode = 'GS0025')
    {
        $class_array = array(
            'GS0024'=>array(
                            '0'=>'类型不限',
                            '1'=>'安全沙龙',
                            '2'=>'示范课',
                            '3'=>'视频讨论',
                            '4'=>'互动课堂',
                            '5'=>'专家讲座',
                ),
            'GS0025'=>array(
                            '0'=>'类型不限',
                            '1'=>'心理沙龙',
                            '2'=>'示范课',
                            '3'=>'视频讨论',
                            '4'=>'互动课堂',
                            '5'=>'专家讲座',
                ),
        );
        return $class_array[$inSubjectCode];
    }

    /**
     * 获取活动状态
     * @static
     * @return array
     */
    static public function getActivityStatusMap()
   {
       $status_map = array(
                        '0'=>'已关闭',
                        '1'=>'已发布',
                        '2'=>'已公开',
       );
       return $status_map;
   }

    /**
     * 获取年级数组
     * @static
     * @return array
     */
    static public function getActivityGradeMap()
    {
        $grade_map = array(
            'GO000'=>'年级不限',
            'GO003'=>'小学一年级',
            'GO004'=>'小学二年级',
            'GO005'=>'小学三年级',
            'GO006'=>'小学四年级',
            'GO007'=>'小学五年级',
            'GO008'=>'小学六年级',
            'GO009'=>'初中一年级',
            'GO0010'=>'初中二年级',
            'GO0011'=>'初中三年级',
            'GO0012'=>'高中一年级',
            'GO0013'=>'高中二年级',
            'GO0014'=>'高中三年级',
        );
        return $grade_map;
    }

    /**
     * 获取活动教材发行版本
     * @static
     *@return array
     */
    static public function getActivityPublisherMap($inSubjectCode='GS0024', $inPhaseCode='xd001')
    {
        if($inPhaseCode == ''){
            $inPhaseCode='xd001';
        }
        $publisher_map = array(
                'v00'=>'版本不限',
                'v11'=>'鄂科版',
                'v01'=>'人教版',
                'v02'=>'鄂教版',
        );
        $resource_publisher_map = Models_Resource::init()->getBbByXdAndXk($inPhaseCode, $inSubjectCode) ;
//        var_dump($resource_publisher_map);
        if(!empty($resource_publisher_map)){
            $publisher_map = array();
            foreach($resource_publisher_map as $key => $val){
                $publisher_map[$val['code']] = $val['name'];
            }
        }
        return $publisher_map;
    }

    /**
     * 获取学段映射
     * @static
     * @return array
     */
    static public function getActivityPhaseMap()
    {
        $phase_map = array('xd000'=>'学段不限','xd001'=>'小学','xd002'=>'初中','xd003'=>'高中');
        return $phase_map;
    }

    /**
     * 获取是编码映射数组
     * @static
     * @param $inProvince  '_hb'：湖北省
     * @return array
     */
    static public function getActivityAreaMap($inProvince)
    {
        $area_map = array(
                '_hubei'=>array(
                    '420100'=>'武汉',
                    '420500'=>'宜昌',
                    '422800'=>'恩施土家族苗族自治州',
                    '420200'=>'黄石市',
                    '429004'=>'仙桃市',

                    '420900'=>'孝感市',
                    '420600'=>'襄阳市',
                    '429601'=>'天门市',
                    '429501'=>'潜江市',
                    '429701'=>'神农架林区',

                    '420300'=>'十堰市',
                    '425800'=>'江汉油田教育实业集团',
                    '420800'=>'荆门市',
                    '429100'=>'随州市',
                    '420700'=>'鄂州市',
                    '421000'=>'荆州市',
                    '421200'=>'咸宁市',
                    '421100'=>'黄冈市',
                ),
        );
        if(isset($area_map[$inProvince])){
            return $area_map[$inProvince];
        }else{
            return array();
        }
    }

    static public   function getCountyMap($inCityCode)
     {
        $county_map = array(
            '420100'=>array(
                                                '400000'=>'全市',
                                                '420108'=>'武汉经济技术开发区',
                                                '420111'=>'洪山区',
                                                '420107'=>'青山区',
                                                '420112'=>'东西湖区',
                                                '420116'=>'黄陂区',
                                                '420109'=>'东湖开发区',
                                                '420114'=>'蔡甸区',
                                                '420101'=>'武汉市直',
                                                '420103'=>'江汉区',
                                                '420104'=>'桥口区',
                                                '420117'=>'新洲区',
                                                '420105'=>'汉阳区',
                                                '420113'=>'汉南区',
                                                '420102'=>'江岸区',
                                                '420106'=>'武昌区',
                                                '420115'=>'江夏区',
),
            '420500'=>array(
                '400000'=>'全市',
                                                '420581'=>'宜都市',
                                                '420505'=>'猇亭区',
                                                '420503'=>'伍家岗区',
                                                '420506'=>'夷陵区',
                                                '420527'=>'秭归县',
                                                '420526'=>'兴山县',
                                                '420525'=>'远安县',
                                                '420502'=>'西陵区',
                                                '420583'=>'枝江市',
                                                '420501'=>'宜昌市直',
                                                '420582'=>'当阳市',
                                                '420528'=>'长阳土家族自治县',
                                                '420529'=>'五峰县',
                                                '420504'=>'点军区',
                                                '420507'=>'宜昌高新区',
            ),
            '422800'=>array(
                '400000'=>'全市',
                                            '422801'=>'恩施市',
                                            '422802' =>'利川市',
                                            '422822'=>'建始县',
                                            '422823'=>'巴东县',
                                            '422825'=>'宣恩县',
                                            '422826'=>'咸丰县',
                                            '422827'=>'来凤县',
                                            '422828'=>'鹤峰县',

            ),
            '420200'=>array(
                '400000'=>'全市',
               ' 420205'=>'铁山区',
                '420203'=>'石灰窑区',
                '420222'=>'阳新县',
                '420281'=>'大冶市',
                '420202'=>'黄石港区',
                '420204'=>'下陆区',
                '420206'=>'黄石开发区',
),
            '429004'=>array(
                '400000'=>'全市',
                '420904'=>'仙桃市'
            ),

            '420900'=>array(
                '400000'=>'全市',
                '420901'=>'孝感市直',
                '420923'=>'云梦县',
                '420981'=>'应城市',
                '420982'=>'安陆市',
                '420922'=>'大悟县',
                '420902'=>'孝南区',
                '420921'=>'孝昌县',
                '420984'=>'汉川市',
                '420903'=>'孝感市经济开发区',

            ),
            '420600'=>array(
                '400000'=>'全市',
                '420626'=>'保康县',
                '420606'=>'樊城区',
                '420624'=>'南漳县',
                '420602'=>'襄城区',
                '420684'=> '宜城市',
                '420604'=> '高新区',
                '420625'=>'谷城县',
                '420682'=>'老河口市',
                '420621'=>'襄阳区',
                '420683'=>'枣阳市',
                '420603'=>'汽产区',
            ),
            '429601'=>array(
                '400000'=>'全市',
                '429601'=>'天门市',
            ),
            '429501'=>array(
                '400000'=>'全市',
                '429501'=>'潜江市',
            ),
            '429701'=>array(
                '400000'=>'全市',
                '429701'=>'神农架林区',
            ),

            '420300'=>array(
                '400000'=>'全市',
                '420322'=>'郧西县',
                '420301'=>'十堰市直',
                '420302'=>'茅箭区',
                '420303'=>'张湾区',
               '420304' =>'高新区',
                '420381'=>'丹江口市',
               '420325' =>'房县',
               '420324' =>'竹溪县',
               '420321' =>'郧县',
                '420323'=>'竹山县',
                '420305'=>'武当山',
               '420306' =>'东风分局',
            ),
            '425800'=>array(
                '400000'=>'全市',
                '425800' => '江汉油田教育实业集团',
            ),
            '420800'=>array(
                '400000'=>'全市',
                '420881'=>'钟祥市',
                '420822'=>'沙洋县',
                '420802'=>'东宝区',
                '420801' =>'荆门市直',
                '420804'=>'屈家岭管理区',
                '420803'=>'掇刀区教委',
                '420821' =>'京山县',
            ),

            '429100'=>array(
                '400000'=>'全市',
                '429101'=>'随州市曾都区',
                '429102'=>'随州市广水市',
                '429103'=>'随州市直',
                '429109'=>'随县',
            ),

            '420700'=>array(
                '400000'=>'全市',
                '420705'=>'街道办事处',
                '420706'=>'葛店开发区',
                '420704'=>'鄂城区',
                '420703'=>'华容区',
                '420701'=>'鄂州市直',
                '420702'=>'梁子湖区',
                '420707'=>'鄂州市经济开发区'

            ),
            '421000'=>array(
                '400000'=>'全市',
                '421003'=>'荆州区',
                '421083'=>'洪湖市',
                '421004'=>'荆州开发区',
                '421023'=>'监利县',
                '421024'=>'江陵县',
                '421081'=>'石首市',
                '421001'=>'荆州市直',
                '421087'=>'松滋市',
               '421022' =>'荆州公安县',
                '421002'=>'沙市区',
            ),
            '421200'=>array(
                '400000'=>'全市',
                '421223' =>'崇阳县',
               '421224' =>'通山县',
                '421201'=>'咸宁市直',
               '421202' =>'咸安区',
               '421222' =>'通城县',
                '421221'=>'嘉鱼县',
               '421281' =>'赤壁市',
            ),
            '421100'=>array(
                '400000'=>'全市',
                '421129'=>'龙感湖区',
                '421102'=>'黄州区',
                '421123'=>'罗田县',
                '421124'=>'英山县',
                '421182'=>'武穴市',
                '421181'=>'麻城市',
                '421125'=>'浠水县',
                '421122'=>'红安县',
                '421101'=>'黄冈市直',
                '421126'=>'蕲春县',
                '421121'=>'团风县',
                '421127'=>'黄梅县',
                '421103'=>'黄冈市经济开发区',
            ),
        );
         if(isset($county_map[$inCityCode])){
             return $county_map[$inCityCode];
         }else{
             return array();
         }
     }







}

?>