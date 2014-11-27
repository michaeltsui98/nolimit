<?php
/**
 * 活动管理模块控制器   
 * @author sizzflair87430@gmail.com  2014-04-22
 */  
class Modules_Admin_Controllers_Activity  extends  Modules_Admin_Controllers_Base {
    private $_activity_manager_model = '';
    private $_activity_course_subject = '';
    private $_activity_course_subject_code = '';
    private $_activity_sync_array = '';
    private $_activity_phase_array = '';
    private $_activity_type_array = '';
    private $_activity_class_array = '';  
    private $_activity_status_array = '';
    private $_activity_grade_array = '';
    private $_activity_publisher_array = '';
    private $_activity_area_array = '';
    private $_activity_province = '';

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->_activity_manager_model = new Modules_Admin_Models_ActivityManage();
        $this->_activity_course_subject = $this->getVar('xk');
        $subject_array = Modules_Admin_Models_ActivityManage::getSubjectMap();
        if(isset($subject_array[$this->_activity_course_subject])){
            $this->_activity_course_subject_code = $subject_array[$this->_activity_course_subject];
        }
        $this->_activity_province = '_hubei';
        $this->_activity_sync_array = Modules_Admin_Models_ActivityManage::getActivitySyncMap();//活动同步性
        $this->_activity_phase_array = Modules_Admin_Models_ActivityManage::getActivityPhaseMap();//活动学段
        $this->_activity_type_array = Modules_Admin_Models_ActivityManage::getActivityTypeMap($this->_activity_course_subject_code);//活动类别
        $this->_activity_class_array = Modules_Admin_Models_ActivityManage::getActivityClassMap($this->_activity_course_subject_code);//活动类型
        $this->_activity_status_array = Modules_Admin_Models_ActivityManage::getActivityStatusMap();//活动状态
        $this->_activity_grade_array = Modules_Admin_Models_ActivityManage::getActivityGradeMap(); //活动年级
        $this->_activity_publisher_array = Modules_Admin_Models_ActivityManage::getActivityPublisherMap();//教材发行社
        $this->_activity_area_array = Modules_Admin_Models_ActivityManage::getActivityAreaMap($this->_activity_province);//活动地区
    }

    /**
     * 范本？
     */
    public  function indexAction(){
        $this->view->title = '用户管理-列表';
        $group_id = intval($this->getVar('group_id'));
        $this->view->group_id = $group_id ;
        if(!$this->request()->isAjax()){
            $layout = $this->getCurrentLayout('common.htm');
            $this->setLayout($layout);
        }

        $grouplist = Modules_Admin_Models_SysGroup::init()->getUserGroupList();
        $this->view->grouplist = $grouplist;
        $this->tpl();
    }

    /**
     * 活动列表
     */
    public function activityListAction()
    {
        if(!$this->request()->isAjax()){
            $layout = $this->getCurrentLayout('common.htm');
            $this->setLayout($layout);
        }
        $this->view->title = '活动列表';
        $this->view->add_activity_url = url($this->c, 'createActivityAction');
        $this->tpl();
    }

    /**
     * 活动列表
     * json数据输出
     */
    public function jsonAction() {
        $subject_array = Modules_Admin_Models_ActivityManage::getSubjectMap();
        if(!isset($subject_array[$this->_activity_course_subject])){
            echo 'null subject';
            return false;
        }
        $page =  $this->getVar('page',1);
        $rows =  $this->getVar('rows',20);
        $activity_list_result = $this->_activity_manager_model->getActivityList($this->_activity_course_subject_code,$page,$rows);
        if(!$activity_list_result){
            $activity_list_result = array();
        }else{
            //做一些转换
            $activity_sync_array = $this->_activity_sync_array;//活动同步性
            $activity_phase_array = $this->_activity_phase_array;//活动学段
            $activity_type_array = $this->_activity_type_array;//活动类别
            $activity_class_array = $this->_activity_class_array;//活动类型
            $activity_status_array = $this->_activity_status_array;//活动状态
            foreach($activity_list_result['rows'] as $key => $val){
                if(isset($activity_sync_array[$val['activity_info_is_sync']])){
                    $activity_list_result['rows'][$key]['activity_info_sync_label'] = $activity_sync_array[$val['activity_info_is_sync']];
                }else{
                    $activity_list_result['rows'][$key]['activity_info_sync_label'] = '未设置';
                }
                //学段
                if(isset($activity_phase_array[$val['activity_info_phase']])){
                    $activity_list_result['rows'][$key]['activity_info_phase_label'] = $activity_phase_array[$val['activity_info_phase']];
                }else{
                    $activity_list_result['rows'][$key]['activity_info_phase_label'] = '未设置';
                }
                //类别
                if(isset($activity_type_array[$val['activity_info_type']])){
                    $activity_list_result['rows'][$key]['activity_info_type_label'] = $activity_type_array[$val['activity_info_type']];
                }else{
                    $activity_list_result['rows'][$key]['activity_info_type_label'] = '未设置';
                }
                //类型
                if(isset($activity_class_array[$val['activity_info_class']])){
                    $activity_list_result['rows'][$key]['activity_info_class_label'] = $activity_class_array[$val['activity_info_class']];
                }else{
                    $activity_list_result['rows'][$key]['activity_info_class_label'] = '未设置';
                }
                //状态
                if(isset($activity_status_array[$val['activity_info_status']])){
                    $activity_list_result['rows'][$key]['activity_info_status_label'] = $activity_status_array[$val['activity_info_status']];
                }else{
                    $activity_list_result['rows'][$key]['activity_info_status_label'] = '未设置';
                }
                //活动评分
                $activity_list_result['rows'][$key]['activity_info_evaluate'] = $this->_activity_manager_model->getActivityAvgEvaluate($val['activity_info_id']);
                //时间可见
                $activity_list_result['rows'][$key]['activity_info_start_date_label'] = date('Y-m-d H:i:s',$val['activity_info_start_date']);
                //编辑组件
                $activity_id = $activity_list_result['rows'][$key]['activity_info_id'];
                $edit_activity_url =  url($this->c, 'editActivityAction');
                $activity_list_result['rows'][$key]['activity_info_edit_label'] = "<a href='javascript:;'  onclick='javascript:ui_dialog(\"{$edit_activity_url}?activity_id={$activity_id}\",this);'><span class='icon-table icon-isedit'></span>编辑</a>";
                //附件上传
                $update_activity_attachment_url =  url($this->c, 'updateAttachmentAction');
                $activity_list_result['rows'][$key]['activity_info_attachment_label'] = "<a href='javascript:;'  onclick='javascript:ui_dialog(\"{$update_activity_attachment_url}?activity_id={$activity_id}\",this,800,400);'><span class='icon-table icon-save'></span>上传预告附件</a>";
                //活动内容上传
                $update_activity_resource_url =  url($this->c, 'editResourceAction');
//                $update_activity_resource_url =  'http://dev-wenku.dodoedu.com/Upload/res/t/1';
                $activity_list_result['rows'][$key]['activity_info_resource_label'] = "<a href='javascript:;'  onclick='javascript:ui_dialog(\"{$update_activity_resource_url}?activity_id={$activity_id}\",this,800,400);'><span class='icon-table icon-save'></span>上传活动资源</a>";
                //活动成员管理
                $activity_member_list_url = url($this->c,'activityMemberListAction');
                $activity_list_result['rows'][$key]['activity_member_list_label'] = "<a href='javascript:;'  onclick='javascript:ui_dialog(\"{$activity_member_list_url}?activity_id={$activity_id}\",this,800,400);'><span class='icon-table icon-save'></span>成员管理</a>";
            }
        }
        $this->view->activity_list = $activity_list_result;
        $this->tpl();
    }


    /**
     * 添加活动页面
     */
    public function createActivityAction()
    {
        $this->view->array_subject_code = $this->_activity_course_subject_code;
        $this->view->activity_sync_array = $this->_activity_sync_array;//活动同步性
        $this->view->activity_phase_array = $this->_activity_phase_array;//活动学段
        $this->view->activity_type_array = $this->_activity_type_array;//活动类别
        $this->view->activity_class_array = $this->_activity_class_array;//活动类型
        $this->view->activity_status_array = $this->_activity_status_array;//活动状态
        $this->view->activity_grade_array = $this->_activity_grade_array;//活动年级
        $this->view->activity_publisher_array = $this->_activity_publisher_array;//教材发行商
        $this->view->activity_area_array = $this->_activity_area_array;      //活动地区
        $this->view->process_new_activity_url = url($this->c, 'processNewActivityAction');
        $this->view->update_icon_url = DODOJC.url($this->c, 'updateActivityIconAction');
        $this->view->city_url = url($this->c, 'jsonCityAction');
        $this->tpl();
    }

    /**
     * json输出城市列表
     */
    public function jsonCityAction()
    {
        $city_array = array();
        foreach($this->_activity_area_array as $key => $val){
            if($key =='420100'){
                $city_array[] = array('id'=>$key, 'text'=>$val,'selected'=>true);
            }else{
                $city_array[] = array('id'=>$key, 'text'=>$val);
            }
        }
        echo json_encode($city_array);
    }


    /**
     * 上传活动标题图片
     */
    public function updateActivityIconAction()
    {
        header("Content-type: text/html; charset=utf-8");
        $syup_debug = isset($_REQUEST['syup_debug'])?$_REQUEST['syup_debug']:null;
        if($syup_debug){
            var_dump($_REQUEST);
        }
        // 文件夹为空
        $dir = 'activity/'.$this->_activity_course_subject;
//        $dir='';
        // 普通上传
        if (count($_FILES) > 0){
            foreach($_FILES as $value){
                $file_info = Models_Attachment::init()->upload($value, $_SESSION['admin_user']['user_uid'], $dir);
                $message =  $file_info['file_path'];
            }
        }
        // 流文件上传(目前是这种)
        if(isset($_SERVER['HTTP_FILENAME']))
        {
            $filename = $_SERVER['HTTP_FILENAME'];
            $filename = urldecode($filename);
            $file = file_get_contents('php://input');
            $file_info = Models_Attachment::init()->streamUpload($filename, $file, $_SESSION['admin_user']['user_uid']);
            if ($file_info) {
                $message =  $file_info['file_path'];
            }
        }
        $this->abort($message);
    }



    /**
     * 像数据库添加新活动
     */
    public function processNewActivityAction()
    {
        $activity_data = $this->getVar('data');
        if(!isset($activity_data['activity_info_area']) OR $activity_data['activity_info_area'] == ''){
            $error_arr = array('status'=>'err','message'=>'操作失败','success_callback'=>"$.messager.show({
                title:'操作提示',
                msg:'缺少必要参数，请填写完整.',
                showType:'show'
            });");
            $this->abort($error_arr);
            return false;
        }
        //补充一些属性
        $activity_data['activity_info_subject'] =$this->_activity_course_subject_code;
        $activity_data['activity_info_visibility'] = '1';
        $activity_data['activity_info_release_date'] = time();
        $activity_data['activity_info_update_date'] = time();
        $activity_data['activity_info_start_date'] = strtotime($activity_data['activity_info_start_date']);
        $activity_data['activity_info_status'] = 1;
        $activity_data['activity_info_hits'] = 0;
        $activity_data['activity_info_weight'] = 0;
        $activity_data['activity_info_updater_id'] = $_SESSION['admin_user']['user_id'];
        $process_new_activity_result = $this->_activity_manager_model->createActivity($activity_data);
        //天加全文检索咯
        $activity_search_array = array(
                                                        'id'=>$process_new_activity_result,
                                                        'activity_info_title'=>$activity_data['activity_info_title'],
                                                        'activity_info_description'=>strip_tags($activity_data['activity_info_description']),
                                                        'activity_info_type'=>$activity_data['activity_info_type'],
                                                        'activity_info_class'=>$activity_data['activity_info_class'],
                                                        'activity_info_start_date'=>$activity_data['activity_info_start_date'],
                                                        'activity_info_area'=>$activity_data['activity_info_area'],
                                                        'activity_info_icon'=>$activity_data['activity_info_icon'],
                                                        'activity_info_evaluate'=>0,
                                                        'activity_info_member'=>0,
                                                        'activity_info_status'=>1,
                                                        'node_id'=>$activity_data['activity_info_phase'].','.$activity_data['activity_info_subject'].','.$activity_data['activity_info_publisher']
                                                );
        $this->_activity_manager_model->createActivitySearch($activity_search_array);
        //批量添加活动城市
        $city_array['city_code'] = explode(',',$activity_data['activity_info_area']);
        $city_array['activity_id'] = $process_new_activity_result;
        $insert_activity_cities_result = $this->_activity_manager_model->insertActivityCity($city_array);
        $arr = array('status'=>$process_new_activity_result,'message'=>'操作成功','success_callback'=>"ajax_flash('activity');$('#dlg').dialog('close');$.messager.show({
                title:'操作提示',
                msg:'添加活动成功.',
                showType:'show'
            });");
        $this->abort($arr);
        return false;
    }

    /**
     * 关闭新窗口
     */
    public function closeDialogAction()
    {
        $arr = array('status'=>'1','message'=>'操作成功','success_callback'=>"$('#dlg').dialog('close');");
        $this->abort($arr);
        return false;
    }


    /**
     * 编辑页面，开关都在这里(附件，和活动内容的上传另开)
     */
    public function editActivityAction()
    {
        $edit_activity_id =  $this->getVar('activity_id','');
        if($edit_activity_id == ''){
            echo 'empty activity id';
            return false;
        }
        $activity_info_result = $this->_activity_manager_model->getActivityInfoByActivityId($edit_activity_id);
        if(!$activity_info_result OR empty($activity_info_result)){
            echo "we do not have this activity";
            return false;
        }
        $this->view->array_subject_code = $this->_activity_course_subject_code;
        $this->view->activity_sync_array = $this->_activity_sync_array;//活动同步性
        $this->view->activity_phase_array = $this->_activity_phase_array;//活动学段
        $this->view->activity_type_array = $this->_activity_type_array;//活动类别
        $this->view->activity_class_array = $this->_activity_class_array;//活动类型
        $this->view->activity_status_array = $this->_activity_status_array;//活动状态
        $this->view->activity_grade_array = $this->_activity_grade_array;//活动年级
        $this->view->activity_publisher_array = $this->_activity_publisher_array;//教材发行商
        $this->view->activity_area_array = $this->_activity_area_array;//活动地区
        $this->view->activity_id = $edit_activity_id;
        $this->view->update_activity_url = url($this->c, 'updateActivityAction');
        $this->view->activity_info = $activity_info_result;
        $this->view->update_icon_url = DODOJC.url($this->c, 'updateActivityIconAction');
        $this->view->edit_city_url = url($this->c, 'jsonEditCityAction').'?activity_id='.$edit_activity_id;
        $this->tpl();
    }

    public function jsonEditCityAction()
    {
        $activity_id = $this->getVar('activity_id');
        $activity_info_result = $this->_activity_manager_model->getActivityInfoByActivityId($activity_id);
        $acitivity_city_str = $activity_info_result['activity_info_area'];
        $acitivity_city_arr = explode(',',$acitivity_city_str);
        $city_array = array();
        foreach($this->_activity_area_array as $key => $val){
            if(in_array($key,$acitivity_city_arr)){
                $city_array[] = array('id'=>$key, 'text'=>$val,'selected'=>true);
            }else{
                $city_array[] = array('id'=>$key, 'text'=>$val);
            }
        }
        echo json_encode($city_array);

    }

    /**
     * 更新活动信息
     */
    public function updateActivityAction()
    {
        $activity_data = $this->getVar('data');
        if(!isset($activity_data['activity_info_area']) OR $activity_data['activity_info_area'] == ''){
            $error_arr = array('status'=>'err','message'=>'操作失败','success_callback'=>"$.messager.show({
                title:'操作提示',
                msg:'缺少必要参数，请填写完整.',
                showType:'show'
            });");
            $this->abort($error_arr);
            return false;
        }
        $activity_id = $this->getVar('activity_id');
        $activity_data['activity_info_update_date'] = time();
        $activity_data['activity_info_start_date'] = strtotime($activity_data['activity_info_start_date']);
        $result = $this->_activity_manager_model->updateActivity($activity_id,$activity_data);
        //更新全文索引取其中一部分
//        var_dump($activity_id,$activity_data);
        $update_activity_search_array = array(
                            'activity_info_status'=>$activity_data['activity_info_status'],
                            'activity_info_title'=>$activity_data['activity_info_title'],
                            'activity_info_icon'=>$activity_data['activity_info_icon'],
                            'activity_info_description'=>strip_tags($activity_data['activity_info_description'])
        );
        $update_activity_search_result = $this->_activity_manager_model->updateActivitySearch($activity_id,$update_activity_search_array);
//        var_dump($update_activity_search_result);
        //删除之前的
        $delete_activity_city_result = $this->_activity_manager_model->deleteActivityCityByActivityId($activity_id);
        //批量添加活动城市
        $city_array['city_code'] = explode(',',$activity_data['activity_info_area']);
        $city_array['activity_id'] = $activity_id;
        $insert_activity_cities_result = $this->_activity_manager_model->insertActivityCity($city_array);
        $arr = array('status'=>$result,'message'=>'操作成功','success_callback'=>"ajax_flash('activity');$('#dlg').dialog('close');$.messager.show({
                title:'My Title',
                msg:'修改成功.',
                showType:'show'
            });");
         $this->abort($arr);
    }



    /**
     * 上传附件页面
     */
    public function updateAttachmentAction()
    {
        $this->view->update_resource_url = DODOJC.url($this->c, 'updateResourceAction')."?activity_id=".$this->getVar('activity_id').'&resource_type=1';
        $this->view->activity_id = $this->getVar('activity_id');
        $this->view->activity_resource_url = url($this->c, 'getActivityResourceAction').'?activity_id='.$this->view->activity_id.'&resource_type=1';
        $this->view->title = "上传研训附件";
        $this->view->close_dialog_url = url($this->c, 'closeDialogAction');
        $this->view->add_attachment_url = url($this->c, 'addAttachmentAction').'?activity_id='.$this->view->activity_id.'&resource_type=1';
        $this->tpl();
    }

    /**
     * 添加资源页面
     */
    public function addAttachmentAction()
    {
        $activity_id =  $this->getVar('activity_id');
        $this->view->activity_id = $activity_id;
        $resource_type = $this->getVar('resource_type');
        $this->view->resource_type = $resource_type;
        $this->view->title = "添加研训附件";
        $this->view->process_attachment_url = url($this->c, 'processAttachmentAction');
        $this->tpl();
    }

    /**
     * 处理上传资源
     */
    public function processAttachmentAction()
    {
        $post_data = $this->getVar('data');
        //首先要从资源中心解析URL Models_Resource
        $activity_attachment_id_array = explode('/',$post_data['activity_resourse_content']);
        $activity_attachment_id = $activity_attachment_id_array[count($activity_attachment_id_array)-1];
        $activity_attachment_info_result = Models_Resource::init()->getResourceInfoById($activity_attachment_id);
        $arr = array('status'=>'0','message'=>'操作失败','success_callback'=>"$('#dlgtwo').dialog('close');$.messager.show({
                title:'操作提醒',
                msg:'添加资源失败.',
                showType:'show'
            });");
        if(isset($activity_attachment_info_result['doc_title'])){
            $post_data['activity_resourse_title'] = $activity_attachment_info_result['doc_title'];
            $post_data['activity_resourse_date'] = time();
            $post_data['activity_resourse_weight'] = 0;
            $update_activity_attachment_result = $this->_activity_manager_model->uploadActivityAttachment($post_data);
            $arr = array('status'=>$update_activity_attachment_result,'message'=>'操作成功','success_callback'=>"$('#dlgtwo').dialog('close');$.messager.show({
                title:'操作提醒',
                msg:'添加资源成功.',
                showType:'show'
            });");
        }
        $this->abort($arr);
    }


    /**
     * 上传附件页面
     */
    public function editResourceAction()
    {
        $this->view->update_resource_url = DODOJC.url($this->c, 'updateActivityResourceAction')."?activity_id=".$this->getVar('activity_id').'&resource_type=2';
        $this->view->activity_id = $this->getVar('activity_id');
        $this->view->activity_resource_url = url($this->c, 'getActivityResourceAction').'?activity_id='.$this->view->activity_id.'&resource_type=2';
        $this->view->title = "上传活动资源";
       $this->view->wenku_upload=  'http://dev-wenku.dodoedu.com/Upload/res/t/1';
        $this->view->post_to_wenku_url = url($this->c, 'saveDocToWenkuAction').'?activity_id='.$this->view->activity_id.'&resource_type=2';
        $this->view->close_dialog_url = url($this->c, 'closeDialogAction');
        //加一个从资源中心选择
        $this->view->resource_select_url = url($this->c, 'resourceSelectAction').'?activity_id='.$this->view->activity_id;
        $this->tpl();
    }

    /**
     * 从文库选择
     */
    public function resourceSelectAction()
    {
        $activity_id = $this->getVar('activity_id','');
        $this->view->activity_id = $activity_id;
//        $this->view->title = "从资源中心选择";
        $this->view->json_resource_select_url = url($this->c, 'jsonResourceSelectAction').'?activity_id='.$activity_id;
        $this->tpl();
    }

    /**
     * ajax json输出资源
     */
    public function jsonResourceSelectAction()
    {
        $activity_id = $this->getVar('activity_id','');
        $page =  $this->getVar('page',1);
        $rows =  $this->getVar('rows',20);
//        $activity_info_result['activity_info_phase']
        $activity_info_result = $this->_activity_manager_model->getActivityInfoByActivityId($activity_id);
        $resource_list = Models_Resource::init()->getResourceBySearch('','' ,$this->_activity_course_subject_code, '', '', '', $_SESSION['admin_user']['user_uid'], '5', $page, $rows);
        $resource_array = array('rows'=>array(),'total'=>0);
//        var_dump($resource_list['data']);
        if($resource_list){
            $resource_array['total'] = $resource_list['count'];
            foreach($resource_list['data'] as $key => $val){
                $add_resource_url = url($this->c, 'resourceSelectProcessAction').'?activity_id='.$activity_id.'&title='.urlencode($val['title']).'&id='.urlencode($val['id']).'&file_key='.urlencode($val['file_key']).'&page_key='.urlencode($val['page_key']);
                $resource_array['rows'][] = array(
                                                                'id'=>$val['id'],
                                                                'title'=>$val['title'],
                                                                'resource_type'=>$val['resource_type'],
                                                                'file_key'=>$val['file_key'],
                                                                'page_key'=>$val['page_key'],
                                                                'add_resource_url' => "<a href='javascript:;'    onclick='javascript:ui_qr(\"{$add_resource_url}\",this);' ><span class='icon-table icon-save'></span>添加</a>"

                );
            }
        }
//        $activity_member_list_result['rows'][$key]['delete_member_url_label'] ="<a href='javascript:;'  onclick='javascript:ui_qr(\"{$delete_member_url}\",this);'><span class='icon-table icon-isdel'></span>删除</a>";
        echo json_encode($resource_array);
    }

    /**
     * 从资源中心传入资源
     */
    public function resourceSelectProcessAction()
    {
        $activity_id = $this->getVar('activity_id');
        $resource_content = $this->getVar('id');
        $resource_title = urldecode($this->getVar('title'));
        $resource_icon = $this->getVar('page_key');
        $resource_key = $this->getVar('file_key');


        $upload_data_array = array(
            'activity_resourse_pid'=>$activity_id,
            'activity_resourse_title'=>$resource_title,
            'activity_resourse_content'=>$resource_content,
            'activity_resourse_type'=>2,
            'activity_resourse_weight'=>0,
            'activity_resourse_date'=>time(),
            'activity_resourse_icon'=>$resource_icon,
            'activity_resource_key'=>$resource_key,
        );
        //something else
        $update_activity_attachment_result = $this->_activity_manager_model->uploadActivityAttachment($upload_data_array);
        if($update_activity_attachment_result > 0){
            $arr = array('status'=>$update_activity_attachment_result,'message'=>'操作成功','success_callback'=>"ajax_flash('resource');$('#dlgtwo').dialog('close');$.messager.show({
                title:'My Title',
                msg:'添加资源成功.',
                showType:'show'
            });");
        }else{
            $arr = array('status'=>$update_activity_attachment_result,'message'=>'操作失败','success_callback'=>"ajax_flash('resource');$('#dlgtwo').dialog('close');$.messager.show({
                title:'My Title',
                msg:'添加资源失败.',
                showType:'show'
            });");
        }
        $this->abort($arr);
    }


    /**
     * 获取资源列表
     */
    public function getActivityResourceAction()
    {
        $this->view->activity_id = $this->getVar('activity_id');
        $this->view->resource_type = $this->getVar('resource_type');
        $activity_resource_result = $this->_activity_manager_model->getActivityAttachment($this->view->activity_id,$this->view->resource_type);
        //removeAttachmentAction
        if($activity_resource_result){
            foreach($activity_resource_result as $key => $val){
                $del_resource_url = url($this->c, 'removeAttachmentAction').'?resource_id='.$val['activity_resourse_id'];
                $activity_resource_result[$key]['remove_resource_url'] = "<a href='javascript:;'  onclick='javascript:ui_qr(\"{$del_resource_url}\",this);'><span class='icon-table icon-isdel'></span>删除</a>";
                $activity_resource_result[$key]['activity_resourse_date'] = date('Y-m-d H:i:s',$activity_resource_result[$key]['activity_resourse_date']);
            }
        }
        echo json_encode($activity_resource_result);
    }

    /**
     * 上传处理页面
     */
    public function updateResourceAction()
    {
        header("Content-type: text/html; charset=utf-8");
        $syup_debug = isset($_REQUEST['syup_debug'])?$_REQUEST['syup_debug']:null;
        if($syup_debug){
            var_dump($_REQUEST);
        }
        // 文件夹为空
        $dir = 'activity/'.$this->_activity_course_subject;
//        $dir='';
        // 普通上传
        if (count($_FILES) > 0){
            foreach($_FILES as $value){
                $file_info = Models_Attachment::init()->upload($value, $_SESSION['admin_user']['user_uid'], $dir);
                $message =  $file_info['file_path'];
            }
        }
        // 流文件上传(目前是这种)
        if(isset($_SERVER['HTTP_FILENAME']))
        {
            $filename = $_SERVER['HTTP_FILENAME'];
            $filename = urldecode($filename);
            $file = file_get_contents('php://input');
            $file_info = Models_Attachment::init()->streamUpload($filename, $file, $_SESSION['admin_user']['user_uid']);
            if ($file_info) {
                $message =  $file_info['file_name'];
            }
        }
            $activity_id = $this->getVar('activity_id');
            $resource_type = $this->getVar('resource_type');
            $upload_data_array = array(
                'activity_resourse_pid'=>$activity_id,
                'activity_resourse_title'=>$file_info['file_name'],
                'activity_resourse_content'=>$file_info['file_path'],
                'activity_resourse_type'=>$resource_type,
                'activity_resourse_weight'=>0,
                'activity_resourse_date'=>time(),
            );
            //something else
            $update_activity_attachment_result = $this->_activity_manager_model->uploadActivityAttachment($upload_data_array);
            $this->abort($message);
    }


    /**
     * 上传资源页面，跟文库打通
     */
    public function updateActivityResourceAction()
    {
        $edit_activity_id =  $this->getVar('activity_id','');
        $activity_info_result = $this->_activity_manager_model->getActivityInfoByActivityId($edit_activity_id);
        $file = $_FILES['Filedata']['tmp_name'];
        $size = $_FILES['Filedata']['size'];
        $ddClient = new Models_DDClient();
        $data = array(
            'app_key'=>DD_AKEY,
            'file_name'=>$_FILES['Filedata']['name'],
            'file' => '@' . $file
        );
        $url = HTTP_WENKU.'interfaces_Upload/resUpload?PHPSESSID='.session_id();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);
        curl_close($ch);
        $query = json_decode($result, 1);
        if (isset($query['status']) && $query['status']=='error') {
            throw new Exception("上传错误" . var_export($query, 1) .
                var_export($query, 1));
        }
        $data = array();
        $data['size'] = $size ;
        $data['file_path'] = key($query);
        $data['file_name'] = current($query);
        //把保存结合起来
        $edit_activity_id =  $this->getVar('activity_id','');
        $resource_type = $this->getVar('resource_type','');
        $activity_info_result = $this->_activity_manager_model->getActivityInfoByActivityId($edit_activity_id);
        $sid = session_id();
        $wenku_url = HTTP_WENKU.'Upload/resUploadPost';
        $dataWenku = array();
        $dataWenku['PHPSESSID']= $sid;
        $dataWenku['data']['doc_title'] = current($query);
        $dataWenku['data']['file'] = key($query).','.current($query);
        $dataWenku['data']['file_size'] = $size;
        $dataWenku['data']['cate_id'] = '5';
        $dataWenku['data']['xd'] = $activity_info_result['activity_info_phase'];
        $dataWenku['data']['xk'] =$activity_info_result['activity_info_subject'];
        $dataWenku['data']['bb'] =$activity_info_result['activity_info_publisher'];
        $dataWenku['is_jsonp']= '1';
        $wenku_result = Cola_Com_Http::post($wenku_url,$dataWenku);

        //下面再存资源
        $resource_result = json_decode($wenku_result,1);
        $resource_title_array = $resource_result['msg']['doc_file_name'];


        $upload_data_array = array(
            'activity_resourse_pid'=>$edit_activity_id,
//            'activity_resourse_title'=>$resource_result['msg']['doc_file_name'],
            'activity_resourse_title'=>$resource_title_array,
            'activity_resourse_content'=>$resource_result['msg']['doc_id'],
            'activity_resourse_type'=>$resource_type,
            'activity_resourse_weight'=>0,
            'activity_resourse_date'=>time(),
            'activity_resourse_icon'=>$resource_result['msg']['doc_page_key'],
            'activity_resource_key'=>$resource_result['msg']['file_key'],
        );
        //something else
        $update_activity_attachment_result = $this->_activity_manager_model->uploadActivityAttachment($upload_data_array);
        $this->abort(json_decode($wenku_result,1));
    }

    /**
     *弃用
     */
//    public function saveDocToWenkuAction()
//    {
//        $edit_activity_id =  $this->getVar('activity_id','');
//        $resource_type = $this->getVar('resource_type','');
//        $activity_info_result = $this->_activity_manager_model->getActivityInfoByActivityId($edit_activity_id);
//        $sid = session_id();
//        $wenku_url = HTTP_WENKU.'Upload/resUploadPost';
//        $data = array();
//        $data['PHPSESSID']= $sid;
//        $data['data'] = $this->getVar('data');
//        $data['data']['cate_id'] = '5';
//        $data['data']['xd'] = $activity_info_result['activity_info_phase'];
//        $data['data']['xk'] =$activity_info_result['activity_info_subject'];
//        $data['is_jsonp']= '1';
//        $result = Cola_Com_Http::post($wenku_url,$data);
//    }

    /**
     *    删除活动
      */
    public function removeAttachmentAction()
    {
        $resource_id = $this->getVar('resource_id');
        $remove_resource_result = $this->_activity_manager_model->removeResource($resource_id);
        $msg = "删除失败";
        if($remove_resource_result){
            $msg = "删除成功";
        }
        $arr = array('status'=>$remove_resource_result,'message'=>$msg,'success_callback'=>"ajax_flash('resource');$.messager.show({
                title:'notice',
                msg:'".$msg."',
                showType:'show'
            });");
        $this->abort($arr);
    }

    /**
     *   活动学员管理
      */
    public function activityMemberListAction()
    {
        $activity_id =  $this->getVar('activity_id','');

        $this->view->title="活动成员管理";
        $this->view->get_member_list_url = url($this->c, 'jsonActivityMemberListAction').'?activity_id='.$activity_id;
        $this->tpl();

    }

    /**
     * 输出成员列表
     */
    public function jsonActivityMemberListAction()
    {
        $activity_id =  $this->getVar('activity_id','');
        $page =  $this->getVar('page',1);
        $rows =  $this->getVar('rows',20);
        $activity_member_list_result = $this->_activity_manager_model->getActivityMemberList($activity_id, $page, $rows);

        if($activity_member_list_result){
            foreach($activity_member_list_result['rows'] as $key => $val){
                $delete_member_url = url($this->c, 'removeActivityMemberAction').'?member_id='.$val['activity_member_id'].'&activity_id='.$activity_id;
                $activity_member_list_result['rows'][$key]['delete_member_url_label'] ="<a href='javascript:;'  onclick='javascript:ui_qr(\"{$delete_member_url}\",this);'><span class='icon-table icon-isdel'></span>删除</a>";
                $activity_member_list_result['rows'][$key]['activity_member_date'] = date('Y-m-d H:i:s',$val['activity_member_date']);
            }
        }
        echo json_encode($activity_member_list_result);

    }

    /**
     * 删除学员
     */
    public function removeActivityMemberAction()
    {
        $member_id = $this->getVar('member_id');
        $activity_id = $this->getVar('activity_id');
        $remove_member_result = $this->_activity_manager_model->deleteActivityMember($member_id);
        $msg = "删除失败";
        if($remove_member_result){
            $msg = "删除成功";
        }
        $arr = array('status'=>$remove_member_result,'message'=>$msg,'success_callback'=>"ajax_flash('member');$.messager.show({
                title:'notice',
                msg:'".$msg."',
                showType:'show'
            });");

        //减少全文索引的学员人数
        $options_array = array('activity_info_id'=>$activity_id);
        $activity_list_result = $this->_activity_manager_model->getActivitiesByOptions($this->_activity_course_subject_code,1,1,$options_array,array());
        //默认不会出错
        $activity_activity_member = $activity_list_result['rows'][0]['member_total'];
        $update_activity_search_array = array(
            'activity_info_member'=>$activity_activity_member,
            'activity_info_evaluate'=>$activity_list_result['rows'][0]['activity_avg_evaluate']
        );
        $update_activity_search_result = $this->_activity_manager_model->updateActivitySearch($activity_id,$update_activity_search_array);
        $this->abort($arr);
    }



    //活动申请管理
    public function applyListAction()
    {
        $this->view->title = "活动申请管理";
        $this->view->json_apply_list_url = url($this->c, 'jsonApplyListAction');
        $this->tpl();
    }

    /**
     * 输出活动管理
     */
    public function jsonApplyListAction()
    {
        $page =  $this->getVar('page',1);
        $rows =  $this->getVar('rows',20);
        $apply_list_result = $this->_activity_manager_model->getActivityApplyList( $this->_activity_course_subject_code,$page,$rows);
        if(!empty($apply_list_result)){
            $activity_phase_array = $this->_activity_phase_array;//活动学段
            $activity_type_array = $this->_activity_type_array;//活动类别
            $activity_class_array = $this->_activity_class_array;//活动类型
            foreach($apply_list_result['rows'] as $key => $val){
                //学段
                if(isset($activity_phase_array[$val['activity_apply_phase']])){
                    $apply_list_result['rows'][$key]['activity_apply_phase_label'] = $activity_phase_array[$val['activity_apply_phase']];
                }else{
                    $apply_list_result['rows'][$key]['activity_apply_phase_label'] = '未设置';
                }
                //类别
                if(isset($activity_type_array[$val['activity_apply_type']])){
                    $apply_list_result['rows'][$key]['activity_apply_type_label'] = $activity_type_array[$val['activity_apply_type']];
                }else{
                    $apply_list_result['rows'][$key]['activity_apply_type_label'] = '未设置';
                }
                //类型
                if(isset($activity_class_array[$val['activity_apply_class']])){
                    $apply_list_result['rows'][$key]['activity_apply_class_label'] = $activity_class_array[$val['activity_apply_class']];
                }else{
                    $apply_list_result['rows'][$key]['activity_apply_class_label'] = '未设置';
                }
            }
        }
        echo json_encode($apply_list_result);
    }

    /**
     * 研训统计
     */
    public function activityStatisticsAction()
    {
        $this->view->title="活动统计";
        $this->view->json_acitivty_statistics_url = url($this->c, 'jsonActivityStatisticsAction');
        $this->tpl();
    }

    /**
     * json输出活动统计列表（目前是模拟数据）
     */
    public function jsonActivityStatisticsAction()
    {
        $start_date = strtotime($this->getVar('start_date',''));
        $end_date = strtotime($this->getVar('end_date',''));
        $activity_statistics_result = $this->_activity_manager_model->getActivityStatistics($this->_activity_course_subject_code,$start_date, $end_date);
        echo json_encode($activity_statistics_result);
    }

    /**
     * 研训成员统计
     */
    public function activityMemberStatisticsAction()
    {
        $this->view->title = "研训成员统计";
        $this->view->json_acitivty_member_statistics_url = url($this->c, 'jsonActivityMemberStatisticsAction');
        $this->view->phase_array = $this->_activity_phase_array;//活动学段
        $this->view->city_array =  $this->_activity_area_array;
        $this->view->activity_county_url = url($this->c, 'jsonActivityCountyAction');
        $this->tpl();
    }

    /**
     * 获取研训成员统计
     */
    public function jsonActivityMemberStatisticsAction()
    {
        $page=  $this->getVar('page',1);
        $offset=$this->getVar('rows',20);
        $city_code = $this->getVar('city_list','');
        $county_code = $this->getVar('county_list','400000');
        $school_phase = $this->getVar('phase_list','');
        $start_time = strtotime($this->getVar('start_date',''));
        $end_time = strtotime($this->getVar('end_date',''));

        $_SESSION['activity_admins']['current_city_code'] = '';
        $_SESSION['activity_admins']['current_county_code'] = '';
        $_SESSION['activity_admins']['current_phase'] = '';
        $_SESSION['activity_admins']['start_date'] = '';
        $_SESSION['activity_admins']['end_date'] = '';

        if($city_code != ''){
            $_SESSION['activity_admins']['current_city_code'] = $city_code;
        }else{
            $city_code = $_SESSION['activity_admins']['current_city_code'];
        }
        if($county_code != ''){
            $_SESSION['activity_admins']['current_county_code'] = $county_code;
        }else{
            $county_code = $_SESSION['activity_admins']['current_county_code'];
        }
        if($school_phase != ''){
            $_SESSION['activity_admins']['current_phase'] = $school_phase;
        }else{
            $school_phase = $_SESSION['activity_admins']['current_phase'];
        }
        if($start_time != ''){
            $_SESSION['activity_admins']['start_date'] = $start_time;
        }else{
            $start_time = $_SESSION['activity_admins']['start_date'];
        }
        if($end_time != ''){
            $_SESSION['activity_admins']['end_date'] = $end_time;
        }else{
            $end_time = $_SESSION['activity_admins']['end_date'];
        }

        if($school_phase == 'xd000'){
            $school_phase  =  '' ;
        }
        $area_code = '';
        if($county_code != '400000'){
            $area_type = 1;
            $area_code = $county_code;
        }else{
            $area_type = 2;
            $area_code = $city_code;
        }

        $activity_member_statistics_result = $this->_activity_manager_model->getActivityMemberStatistics($this->_activity_course_subject_code, $area_code, $area_type, $school_phase,$page,$offset, $start_time, $end_time);
        $first_load = $this->getVar('first_load','0');
        if($first_load == '0'){
            echo json_encode(array());
        }else{
            if($activity_member_statistics_result){
                echo json_encode($activity_member_statistics_result);
            }else{
                $null_array = array(
                    'rows'=>array(array('activity_member_real_name'=>'', 'activity_member_area_name'=>'', 'activity_member_school_name'=> '','activity_member_times'=>'')),
                    'total'=>1
                );
                echo json_encode($null_array);
            }
        }




    }

    /**
     * json输出活动区县
     */
    public function jsonActivityCountyAction()
    {
        $city_code = $this->getVar('city_code');
        $county_array = $this->_activity_manager_model->getCountyMap($city_code);
        echo  json_encode($county_array);
    }

    /**
     * 研训分析（目前是模拟数据）
     */
    public function activityAnalysisAction()
    {
        $this->view->title="研训分析";
        $this->view->activity_analysis_url = url($this->c, 'jsonActivityAnalysisAction');
        $current_year = date('Y',time());
        $current_month = date('m',time());
        $this->view->current_year = $current_year;
        $this->view->current_month = $current_month;


        $this->tpl();
    }

    /**
     * 输出三个报表
     */
    public function jsonActivityAnalysisAction()
    {
        $selected_year = $this->getVar('selected_year','');
        $selected_month = $this->getVar('selected_month','');
        $next_month = intval($selected_month)+1;
        $next_year = intval($selected_year)+1;

        //获取本月的起止和全年的起止
        $current_month_start_date = strtotime($selected_year.'-'.$selected_month);
//        $start_month = date('Y-m-d H:i:s',$current_month_start_date);
        $current_month_end_date = strtotime($selected_year.'-'.$next_month);
//        $end_month = date('Y-m-d  H:i:s',$current_month_end_date);
        $current_year_start_date = strtotime($selected_year.'-01');
//        $start_year = date('Y-m-d  H:i:s',$current_year_start_date);
        $current_year_end_date = strtotime($next_year.'-01');
//        $end_year = date('Y-m-d  H:i:s',$current_year_end_date);


        $teacher_phase_array = array();
        $teacher_phase_number_array = array();
        $teacher_type_array = array();
        $teacher_rate_array = array();
        //算教师构成，不GROUP BY UID   OK
        $teacher_phase_result = $this->_activity_manager_model->getTeacherPhaseAnalysis($this->_activity_course_subject_code, $current_month_start_date, $current_month_end_date);
        if(!empty($teacher_phase_result)){
            foreach($teacher_phase_result as $key => $val){
                if(!isset($teacher_phase_array[$val['activity_member_phase']])){
                    $teacher_phase_array[$val['activity_member_phase']] = 0;
                }
                $teacher_phase_array[$val['activity_member_phase']] +=1;
            }
            foreach($teacher_phase_array as $key => $val){
                $teacher_phase_number_array[$key]['teacher_number']=$val;
                $teacher_phase_number_array[$key]['teacher_phase']=$key;
            }
            $teacher_phase_number_array = array_values($teacher_phase_number_array);



        }
        //算研训TYPE 构成
//        $activity_type_map = $this->_activity_type_array;
        $activity_type_analysis_result = $this->_activity_manager_model->getActivityTypeAnalysis($this->_activity_course_subject_code, $current_month_start_date, $current_month_end_date);
        $teacher_type_array = $activity_type_analysis_result;
        if(!empty($teacher_type_array)){
            foreach($teacher_type_array as $key => $val){
                    if(isset($this->_activity_type_array[$val['activity_info_type']])){
                        $teacher_type_array[$key]['activity_info_type_name'] = $this->_activity_type_array[$val['activity_info_type']];
                    }
            }
        }

        //最麻烦的月率和年率对比
        $month_rate_result = $this->_activity_manager_model->getActivityStatistics($this->_activity_course_subject_code, $current_month_start_date, $current_month_end_date);
        $teacher_rate_array['month'] = $month_rate_result['rows'];
        foreach($teacher_rate_array['month'] as $key => $val){
            $teacher_rate_array['month'][$key]['total_rate'] = ($val['xd001_percent']+$val['xd002_percent']+$val['xd003_percent'])/3;
        }
        //年对比
        $year_rate_result = $this->_activity_manager_model->getActivityStatistics($this->_activity_course_subject_code, $current_year_start_date, $current_year_end_date);
        $teacher_rate_array['year'] = $year_rate_result['rows'];
        foreach($teacher_rate_array['year'] as $key => $val){
            $teacher_rate_array['year'][$key]['total_rate'] = ($val['xd001_percent']+$val['xd002_percent']+$val['xd003_percent'])/3;
        }
        //汇总
        echo json_encode(array('teacher_phase_analysis'=>$teacher_phase_number_array, 'activity_type_analysis'=>$teacher_type_array, 'activity_rate_analysis'=>$teacher_rate_array, 'debug'=>'0'));
    }



    /**
     * 小站后台拉取
     */
    public function activitySiteListAction()
    {
        $this->view->title="活动小站抓取";
        $this->view->json_acitivity_site_url = url($this->c, 'jsonActivitySiteAction');
        $this->tpl();
    }

    /**
     * json输出所有从社区抓取过来的活动小站
     */
    public function jsonActivitySiteAction()
    {
        $activity_site_list_result = $this->_activity_manager_model->getActivitySites(7, 0, 999999, 145);
        $this->view->activity_site_list = $activity_site_list_result['data']['data'];
        $activity_site_array = array();
        foreach( $activity_site_list_result['data']['data'] as $key => $val){
            $activity_site_array['rows'][] = $val;
        }
        $activity_site_array['total'] = count($activity_site_list_result['data']['data']);
        //添加一个加入和踢出的按钮
        $select_site_array = array();
        $select_site_list = $this->_activity_manager_model->getSelectedSites( $this->_activity_course_subject_code);
        if(!empty($select_site_list)){
            foreach($select_site_list as $key => $val){
                $select_site_array[] = $val['site_id'];
            }
        }
        //上面还要操作一下
        $add_site_url = url($this->c, 'processActivitySiteAction');
        $delete_site_url = url($this->c, 'deleteActivitySiteAction');
        foreach($activity_site_array['rows'] as $key => $val){
            $val['site_describe'] = urlencode($val['site_describe']);
            if(in_array($val['site_id'], $select_site_array)){
                $activity_site_array['rows'][$key]['site_option_label'] = "<a href='javascript:;' onclick='javascript:ui_ajax(\"{$delete_site_url}?site_id={$val['site_id']}\",this);'><span class='icon-table icon-isjz'></span>解除</a>";
            }else{
                $activity_site_array['rows'][$key]['site_option_label'] = "<a href='javascript:;' onclick='javascript:ui_ajax(\"{$add_site_url}?site_id={$val['site_id']}&site_name={$val['site_name']}&site_logo={$val['site_logo']}&site_describe={$val['site_describe']}&site_join={$val['site_join']}&site_activity_subject={$this->_activity_course_subject_code}\",this);'><span class='icon-table icon-isok'></span>加入</a>";
            }
        }
        echo json_encode($activity_site_array);
    }


    /**
     * 处理活动小站
     */
    public function processActivitySiteAction()
    {
        $insert_array = array(
            'site_id'=>$this->getVar('site_id'),
            'site_name'=>$this->getVar('site_name'),
            'site_logo'=>$this->getVar('site_logo'),
            'site_describe'=>$this->getVar('site_describe'),
            'site_join'=>$this->getVar('site_join'),
            'site_activity_subject'=>$this->getVar('site_activity_subject')
        );

        $insert_result = $this->_activity_manager_model->createActivitySite($insert_array);

        if($insert_result){
            $arr = array('status'=>$insert_result,'message'=>'操作成功','success_callback'=>"ajax_flash('activitySite');$.messager.show({
                title:'操作提示',
                msg:'添加成功.',
                showType:'show'
            });");
        }else{
            $arr = array('status'=>$insert_result,'message'=>'操作失败','success_callback'=>"ajax_flash('activitySite');$.messager.show({
                title:'操作提示',
                msg:'添加失败.',
                showType:'show'
            });");
        }
        $this->abort($arr);

    }

    /**
     * 删除活动小站
     */
    public function deleteActivitySiteAction()
    {
        $site_id = $this->getVar('site_id');
        $delete_result = $this->_activity_manager_model->deleteActivitySite($site_id);
        if($delete_result){
            $arr = array('status'=>$delete_result,'message'=>'操作成功','success_callback'=>"ajax_flash('activitySite');$.messager.show({
                title:'操作提示',
                msg:'删除成功.',
                showType:'show'
            });");
        }else{
            $arr = array('status'=>$delete_result,'message'=>'失败','success_callback'=>"ajax_flash('activitySite');$.messager.show({
                title:'操作提示',
                msg:'删除失败.',
                showType:'show'
            });");
        }
        $this->abort($arr);
    }






}