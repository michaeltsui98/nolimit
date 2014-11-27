<?php


/**
 * Description of StudyResource
 *  心理 家长模块
 * @author libo
 */
 class Modules_Sm_Controllers_Parent extends Modules_Sm_Controllers_Base
 {

 	public $layout = '';
    public $study_config = '';
    protected $_course = '';
    protected $_user_app = NULL;
    protected $_study_record = '';
    protected $_defalut_xd = 'xd001';

 	public function __Construct()
 	{
 		parent::__Construct();
 		$this->layout = $this->getCurrentLayout('index.htm');
        $this->study_config = Models_StudyResource_StudyRecordConfig::init();
        $this->_course = new Modules_Sm_Controllers_Course();
        $this->_user_app = new Models_UserApp();
        $this->_study_record = Models_StudyResource_StudyRecord::init();
 	}


     public function recommendAction()
     {
        if(!isset($this->user_info) || empty($this->user_info)){
           //推荐的学习包
         $recommend_study_resource = Models_StudyResource_StudyResource::init()->recommendStudyResource($this->view->xk_code,6);
         $this->view->recommend_study_resource = $recommend_study_resource;
         //推荐的问答
         $model_question = new Models_Question_Interfaces();
         $recommend_question = $model_question->getRecommend(array('num' =>6,'subject' => $this->view->xk_code));
         unset($recommend_question['count']);
         $this->view->recommend_question = $recommend_question; 
        //获取课程咨询
        $information_model = new Modules_Sm_Models_Information();
        $this->view->site_id = $information_model->getSiteId();
        $blog_list_result = $information_model->getBlogList(0,6);
        if($blog_list_result){
            $this->view->blog_list = Models_StudyResource_StudyRecordConfig::init()->object2array($blog_list_result['data']);
        }else{
            $this->view->blog_list = array();
        } 
        $this->setlayout($this->layout);
        $this->tpl();
        }else{
            $this->redirect('/' . $this->view->xk . '/Parent/index');
        }  
     }


   /**
   * 首页
   * @return
   */
 	public function indexAction()
 	{
       $this->user_infoAction();
       $this->setlayout($this->layout);
       $my_app = Models_UserApp::init()->getMyAppList($this->user_info['user_id'], $this->view->xk_code); //获取当前用户的应用
       $this->view->my_app = $my_app;
       //url前缀
       $this->view->url_prefix = DODOJC . '/' .$this->view->xk .'/'; 
       //快捷面板
       $this->view->default_app = $this->defaultAppAction();
      //获取应用,添加应用和删除应用的地址
      $this->view->sys_app_list_url = '/'.$this->view->xk.'/Parent/ajaxGetSysAppList';
      $this->view->add_app_url =  '/'.$this->view->xk.'/Parent/ajaxAddMyApp';
      $this->view->del_app_url =  '/'.$this->view->xk.'/Parent/ajaxDeleteMyApp';
      $this->view->user_id = $this->user_info['user_id'];
      $this->tpl();
 	}

     /**
      * 默认的应用
      * @return array
    */
    public function defaultAppAction()
    {
       return  array(
         0 => array('name' => '有问题', 'url' => 'question/index', 'icon' => 'icon-FAQsSp'),
         );
    }
    
    /**
      * 应用列表
      * @return 
    */
   public function ajaxGetSysAppListAction()
   {
    //获取我的应用列表
    $app_list = $this->_user_app->getSysAppList($this->user_info['role_code'],$this->view->xk_code,1,999999);
    //我的应用列表
    $my_app_list = $this->_user_app->getMyAppList($this->user_info['user_id'],$this->view->xk_code);
    $my_app_arr = array();
    if(!empty($my_app_list)){
        foreach ($my_app_list as $my_app){
            $my_app_arr[$my_app['app_id']] = $my_app;
        }
    }
      if(!empty($app_list)){
            foreach($app_list['rows'] as $key => $val){
                    if(isset($my_app_arr[$val['app_id']])){
                        //如果有我选过的
                        $app_list['rows'][$key]['my_app_id'] = $my_app_arr[$val['app_id']]['id'];
                    }else{
                        $app_list['rows'][$key]['my_app_id'] = '';
                    }
            }
             echo json_encode($app_list);
        }
        echo json_encode(array('rows' =>array()));
       
   }

    /**
     *添加应用
     * @return 
    */
    public function ajaxAddMyAppAction()
   {
       $app_id = $this->getVar('app_id');
        $add_result = $this->_user_app->addMyApp($this->user_info['user_id'],$app_id);
        if($add_result > 0){
            echo json_encode(array('data'=>$add_result,  'message'=>'添加成功', 'type'=> 'success'));
        }else{
            echo json_encode(array('data'=>$add_result,  'message'=>'添加失败', 'type'=> 'error'));
        }
   }

    /**
     * 删除应用
     * @return 
    */
     public function ajaxDeleteMyAppAction()
    {
       $my_app_id = $this->getVar('my_app_id');
        $del_result = $this->_user_app->delMyAppById($my_app_id);
        if($del_result){
            echo json_encode(array('data'=>$del_result,  'message'=>'删除成功', 'type'=> 'success'));
        }else{
            echo json_encode(array('data'=>$del_result,  'message'=>'删除失败', 'type'=> 'error'));
        }
    }

    /**
     * 学习的历史记录
     */
    public function studyHistoryCountAction()
    {
         //获取孩子的学段
         //$child_id = $this->publicInfoAction('child_id');
         //$xd = $this->study_config->getCurClassSchoolByUserId('xd',$child_id);
         $info = $this->publicInfoAction('xd_code');
         $xd = empty($info) ?$this->_defalut_xd:$info;
         $bb_list = $this->getChildJcbbAction($xd);
         $this->view->bb_list = $bb_list;
         $this->setLayout($this->layout);
         //年级
         // $grade_info = $this->gradeHistoryAction();   
         // $this->view->grade_history = $grade_info['grade_history'];
         // $this->view->default_grade = $grade_info['grade_code'];
         //js
         $this->view->js = array(
          "parent/parent_history.js"
          );
         $this->tpl();
    }
 	
    /**
     * 版本获取年级
     * @return
     */
    public function getNjByBbAction()
    {
        $info = $this->publicInfoAction('xd_code');
        $xd = substr(empty($info) ? $this->_defalut_xd : $info, -1);
        $bb = $this->post("bb");
        $info = Models_StudyResource_StudyRecordConfig::init()->getJcNj($xd, $this->view->xk_code, $bb);
        if (empty($info)) {
            echo json_encode(array());
        } else {
            echo json_encode($info);
        }
    }
     
      /**
       * 历史年级
       * @return  array
       */
      public function gradeHistoryAction()
      {
         $grade_descript = $this->publicInfoAction('grade_code');
         $config = Models_StudyResource_StudyRecordConfig::init()->config();
         $nj_list_flip =  array_flip($config['nj_code']);
         $grade_code = NULL;
         foreach ($nj_list_flip as $k =>$grade){
            if($grade == $grade_descript){
                $grade_code = $k;
            } 
         }
         $sub_grade = substr($grade_code,-1);
         for($i = 3;$i<= $sub_grade;$i++){
            $grade_codes = 'GO00'.$i;
            $grade_history[$grade_codes] = $nj_list_flip[$grade_codes];
         }
         return array('grade_code' =>$grade_code,'grade_history' =>$grade_history);
      }
      

     /**
     * 孩子学习的历史记录加载的数据
     */
    public function studyHistoryMoreAjaxAction()
    {
         //获取孩子的信息
         $child_info = $this->publicInfoAction();
         $page_config = $this->study_config->pageConfig();
         $default_version = $this->post("bb");
         $grade_code = $this->post("grade");
         $study_history = Models_StudyResource_StudyRecord::init()->getStudyRecordList($child_info['child_id'], $this->view->xk_code, empty($child_info['xd_code']) ? $this->_defalut_xd : $child_info['xd_code'], $default_version, $grade_code, $page_config['start'], $page_config['limit']); 
        if (!empty($study_history)) {
            foreach ($study_history as $key => $v) {
                $info = Models_Resource::init()->getUnit(empty($child_info['xd_code']) ? $this->_defalut_xd : $child_info['xd_code'], $this->view->xk_code, $default_version, $grade_code); 
                if (!empty($info)) {
                   // $study_history[$key]['parent_node_title'] = $this->study_config->getNodeTitleByNodeId($info, $v['study_resource_grade_node']);//需要调整
                    $study_history[$key]['son_node_title'] = $this->study_config->getNodeTitleByNodeId($info, $v['study_grade_node_node']);
                }
            }
            $this->view->history = $study_history;
            echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($study_history), 'data' => $this->tpl('Modules/' . $this->view->ucxk . '/Views/Parent/studyHistoryMoreAjax.htm', NULL, TRUE)));
        } else {
            echo json_encode(array('limit' => $page_config['limit'], 'every_page' => 0, 'data' => NULL));
        }
    }

    /**
     * 课程
     */
    public function courseAction()
    {
        $this->_course->indexAction();
    }

    public function  courseDetailAction()
    {

      $this->_course->courseDetailAction();
    }
    
    public function courseAppraiseListAction()
    {
        $this->_course->appraiseListAction();
    }
 
   public function courseEvaluateListAction()
   {
        $this->_course->evaluateListAction();
   }

    public function courseQuestionListAction()
    {
        $this->_course->questionListAction();
    }
   
   public function courseAddAppraiseAction()
   {
    $this->_course->addAppraiseAction();
   }

   public function courseAddQuestionForCourseAction()
   {
     $this->_course->addQuestionForCourseAction();
   }
   
     /**
     * 通过知识结构的方式获取历史记录
     */
    
    public function studyHistoryMoreAjaxByNodeAction()
    {
         //获取孩子的信息
        $child_info = $this->publicInfoAction();
        //获取用户选择的教材版本
        $default_version = $this->post("bb");
        $grade_node = $this->post("grade");
        $page_config = $this->study_config->pageConfig();
        //知识单元节点
        $info = Models_Resource::init()->getUnit(empty($child_info['xd_code']) ? $this->_defalut_xd : $child_info['xd_code'], $this->view->xk_code, $default_version, $grade_node); //现在是死的,后面要换成参数的
        $node = $this->study_config->build_tree($info); //节点树
        //$node = $this->study_config->getNodePage($node_tree, $page_config['start'], $page_config['limit']);
        if (!empty($node)) {
            foreach ($node as $key => $value) {
                if (isset($value['child'])) {
                    foreach ($value['child'] as $k => $v) {
                        $info = $this->_study_record->getStudyResourceCount($child_info['child_id'], $this->view->xk_code, empty($child_info['xd_code']) ?$this->_defalut_xd : $child_info['xd_code'], $default_version, $grade_node, $v['id']);
                        $value['child'][$k]['pic'] = $info['pic'];
                        $value['child'][$k]['descript'] = $info['descript'];
                    }
                    $node[$key] = $value;
                } else {
                    $info = $this->_study_record->getStudyResourceCount($this->user_info['user_id'], $this->view->xk_code, empty($child_info['xd_code']) ?$this->_defalut_xd : $child_info['xd_code'], $default_version, $grade_node, $value['id']);
                    $node[$key]['pic'] = $info['pic'];
                    $node[$key]['descript'] = $info['descript'];
                }
            }
            $this->view->start = $page_config['start'];
            $this->view->node = $node;
            echo json_encode(array('data' => $this->tpl("Modules/" . $this->view->ucxk . "/Views/Parent/studyHistoryMoreAjaxByNode.htm", NULL, TRUE)));
        } else {
            echo json_encode(array('data' => NULL));
        }
    }

 /**
     * 测评的历史记录
     */
    public function evaluateHistoryAction()
    {
        $this->user_infoAction();
         //获取孩子的学段
         $child_id = $this->publicInfoAction('child_id');
         $class_id = $this->study_config->getCurClassSchoolByUserId('class_id',$child_id);
         if(empty($class_id)){
          //没有加入班级
          $this->redirect("/" .$this->view->xk ."/Parent");
         }else{
          $info = $this->publicInfoAction('xd_code');
          $xd = empty($info) ?$this->_defalut_xd:$info;  
         $bb_list = $this->getChildJcbbAction($xd);
         $this->view->bb_list = $bb_list;
         }
        //测评状态的配置
        $this->view->evaluate_status = $this->evaluateStatusAction();
        //测评的配置
        $this->view->evaluate_config = $this->evaluateConfigAction();
        $this->view->page_title = '测评历史记录';
        //年级
        $grade_info = $this->gradeHistoryAction();
        //$this->view->grade_history = $grade_info['grade_history'];
        $this->view->default_grade_code = $this->study_config->userNjToJcNj($grade_info['grade_code']);
        $this->view->default_grade = $grade_info['grade_history'][$grade_info['grade_code']];
        //js
        $this->view->js = array(
          'parent/parent_historyRecord.js'
          );
        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * 测评历史记录ajax
     * @return 
     */
    public function evaluateHistoryAjaxAction()
    {
         //孩子的信息
        $child_info = $this->publicInfoAction();
        $nj = $this->post('nj'); //年级
        $bb = $this->post('bb'); //版本
        $page_config = $this->study_config->pageConfig();
        $evaluate_type = $this->post('evaluate_type','all_evaluate'); //类型(所有,综合,单元)
        $config = $this->study_config->config();
        switch($evaluate_type){
            case 'all_evaluate':
                $evaluate_classify = '*';
            break;
            case 'com_evaluate':
                $evaluate_classify = $config['_evaluate_classify']['com']; 
            break;
            case 'unit_evaluate':
               $evaluate_classify = $config['_evaluate_classify']['unit'];
            break;
        }

        $evaluate_record_status = $this->post('evaluate_record_status',1);

        //获取用户的班级
        $class_id = $this->study_config->getCurClassSchoolByUserId('class_id',$child_info['child_id']);
        $data = Models_Evaluate_EvaluateRecord::init()->getDiffentClassifyRecordList($nj, $evaluate_record_status, $child_info['child_id'], $bb, $child_info['xd_code'], $this->view->xk_code, $evaluate_classify,$class_id,$page_config['start'], $page_config['limit']);
        $this->view->data = $data;
        if(!empty($data)){
          echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($data), 'data' => $this->tpl('Modules/'.$this->view->ucxk.'/Views/Parent/evaluateHistoryAjax.htm', NULL, TRUE)));  
      }else{
          echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($data), 'data' => NULL));
      }
        
    }


    /**
     * 公共左侧
     * 
     */
    
    public function  publicStuInfoAction()
    {
    	//获取孩子的用户id
    	$stu_id = $this->publicInfoAction('child_id');
    	//获取孩子的基本信息
    	$models = new Models_DDClient();
    	$stu_info = $models->searchUserInfo($stu_id);
    	$user = array(
              'user_id' =>$this->user_info['user_id'],
              'user_id_info' =>$stu_id,
              'user_realname' =>$this->user_info['user_realname'],
              'user_level' =>$this->user_info['user_level'],
              'icon' =>$this->user_info['icon'],
    		);

             $this->view->child_id = $stu_info['user_id'];
            $this->view->child_realname = $stu_info['real_name'];
            $this->view->child_level = $stu_info['level'];
            $this->view->child_icon = $stu_info['icon'];
    	   $this->view->info =  Models_StudyResource_StudyRecordConfig::init()->publicStuInfo($user, $this->view->xk_code);
    	  $this->tpl('Modules/' . $this->view->ucxk . '/Views/Parent/publicStuInfo.htm');
    }

    /**
     * 公共的信息
     * @return array
     */
    public function publicInfoAction($param = NULL)
    {
         //获取孩子的信息
         $child_id = Models_StudyResource_StudyRecordConfig::init()->getChildrenByParentId($this->user_info['user_id']);
         $xd = $this->study_config->getCurClassSchoolByUserId('xd',$child_id);
         $config = $this->study_config->config();
         $xd_code = $config['xd_code'][$xd];
         $grade_code =  $this->study_config->getCurClassSchoolByUserId('grade_code',$child_id);
         $arr = array(
            'child_id' => $child_id,
            'xd_code' => $xd_code,
            'grade_code' => $grade_code
          );
         return empty($param) ?$arr:$arr[$param];
    }
    
  
   /**
   * 获取家长对应的孩子的教材版本
   * @param  string $xd_code  学段
   * @return array
   */
  public function getChildJcbbAction($xd_code)
  {
    return  Models_Resource::init()->getBbByXdAndXk($xd_code, $this->view->xk_code); 
  }
 	/**
 	 * 检测登录
 	 * @return 
 	 */
    public function user_infoAction()
    {
    	if(!isset($this->user_info) || empty($this->user_info)){
    		$this->redirect('/' . $this->view->xk . '/Parent/recommend');
    	}
    }

    /**
     * 测评状态的配置
     */
    public function evaluateStatusAction()
    {
        return array(
            2 => '完成',
            1 => '未完成',
            0 => '未做',
            3 => '已过期'
        );
    }

    /**
     * 测评记录类型的配置
     * @return array
     */
    public function evaluateConfigAction()
    {
        return array(
            'all_evaluate' => '所有测评',
            'com_evaluate' => '综合测评',
            'unit_evaluate' => '单元测评'
        );
    }
 }

?>