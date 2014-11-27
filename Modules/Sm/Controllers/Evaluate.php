<?php

/**
 * Description of Evaluate
 * 在线测评
 * @author libo
 */
class Modules_Sm_Controllers_Evaluate extends Modules_Sm_Controllers_Base
{
    public $layout = '';
    protected $study_config = '';
    protected $_grade = NULL;
    protected $_c = NULL;
    protected $_config = NULL;
    public function __construct()
    {
        parent::__construct();
        $this->layout = $this->getCurrentLayout('index.htm');
        $this->study_config = Models_StudyResource_StudyRecordConfig::init();
          //获取年级
        $this->_grade = $this->study_config->getCurClassSchoolByUserId('grade_code');        
        if(!empty($this->user_info) && empty($this->_grade)){
             $this->messagePage('/' . $this->view->xk .'/index', '没有年级信息');
        }
        $this->_config =  $this->study_config->config();
        $this->_c = explode("_", $this->c);
    }

    /**
     * 测评
     */
    public function indexAction()
    {
        $this->user_infoAction();
        $bb = $this->getStudyResourceVersionAction();
         //版本列表
         $this->view->bb_list = Models_Resource::init()->getBbByXdAndXk($this->user_info['xd'], $this->view->xk_code);
         $this->view->bb = $bb;
        //年级
         $grade_history = array();
        $nj_list_flip =  array_flip($this->_config['nj_code']);
        // $sub_grade = substr($this->_grade_code,-1);
        //  for($i = 3;$i<= $sub_grade;$i++){
        //     $grade_code = 'GO00'.$i;
        //     $grade_history[$grade_code] = $nj_list_flip[$grade_code];
        //  }
        // $this->view->grade_history = $grade_history;
        $this->view->default_grade_code = $this->study_config->userNjToJcNj($this->_config['nj_code'][$this->_grade]);
        $this->view->default_grade = $this->_grade;
        $this->setLayout($this->layout);
        $this->view->page_title = '测评列表';
        //js
        $this->view->js = array(
            'evaluate/evaluate.js',
            'evaluate/eva_list.js'
            );
        $this->tpl();
    }

    /**
     * 综合测评
     */
    public function compositeEvaluateAction()
    {
        $config = $this->study_config->config();
        $nj = $this->post('nj');
        $bb = $this->post('bb');
        $evaluate_classify = $this->post("evaluate_type");
        $page_config = $this->study_config->pageConfig();
        $data = Models_Evaluate_Evaluate::init()->getUserEvaluateListByClassify($this->view->xk_code, $evaluate_classify, $this->user_info['xd'], $nj, $bb, $page_config['start'], $page_config['limit']);
        $this->view->data = $data['data'];
        //分类
        $this->view->evaluate_classify = $config['_evaluate_classify']['com'];
        $this->view->type = $config['_evaluate_type']['evaluate'];
        //测评跳转的地址
        $this->view->url = '/'.$this->view->xk .'/'.end($this->_c) .'/doEvaluate';
        if(!empty($data['data'])){
            echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($data['data']), 'data' => $this->tpl('Modules/'.$this->view->ucxk.'/Views/Evaluate/compositeEvaluate.htm', NULL, TRUE)));
        }else{
            echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($data['data']), 'data' =>null));
        }
        
    }

    /**
     * 单元测评
     */
    public function unitEvaluateAction()
    {
        $config = $this->study_config->config();
       // $date = $this->post('date', 'week');
        $nj = $this->post('nj');
        $bb = $this->post('bb');
        //获取班级id
        $class_id = $this->study_config->getCurClassSchoolByUserId('class_id');
       // $class_id = 337607;//假的数据
        $page_config = $this->study_config->pageConfig();
        if(!empty($class_id)){
           $data = Models_Evaluate_EvaluateUnit::init()->getUserUnitEvaluateListByClassify($class_id, $this->view->xk_code, $bb, $page_config['start'], $page_config['limit']); 
       }else{
           $data = array('count' =>0,'data' =>array()); 
       }
        //分类
        $this->view->evaluate_classify = $config['_evaluate_classify']['unit'];
        $this->view->type = $config['_evaluate_type']['evaluate'];
        //测评跳转的地址
        $this->view->url = '/'.$this->view->xk .'/'.end($this->_c) .'/doEvaluate';
        $this->view->data = $data['data'];
        if(!empty($data['data'])){
          echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($data['data']), 'data' => $this->tpl('Modules/'.$this->view->ucxk.'/Views/Evaluate/unitEvaluate.htm', NULL, TRUE)));  
      }else{
        echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($data['data']), 'data' =>null));
      }
        
    }

    /**
     * 测评的跳转方法
     */
    public function doEvaluateAction()
    {
        $this->loginAction();
        $config = $this->study_config->config();
        $evaluate_classify = $this->post("evaluate_classify",'');//综合测评和单元测评
        $evaluate_id = $this->post("evaluate_id"); //测评id
       // echo $evaluate_id;
        $id = $this->post("id");//主键id
        //学习测评:study_evaluate 单元测评:evaluate
        $type = $this->post('types');//学习资源测评还是这里的测评
        $evaluate_course_id = $this->post('course_id',0);//课程id 
         //测评种类
        if($type == $config['_evaluate_type']['study_evaluate']){
            //学习资源里面的测评,判断当前学习资源的状态
            $info = Models_StudyResource_StudyRecord::init()->getUserStudyStatus($this->user_info['user_id'],$id);    
            if($info != $config['_evaluate_status']['finish']){//没有完成了测评
               //检测记录表里面是否存在测评的记录,不存在的话就初始化状态到学习记录表里面去
                $this->addStudyRecordAction($id);
              }
         }else{
            if ($evaluate_classify == 1) {
                //综合测评
                $info = Models_Evaluate_Evaluate::init()->getPartFieldsById($id, 'evaluate_end_time');
                $evaluate_end_time = explode('/', $info[0]['evaluate_end_time']);
                $evaluate_record_status = time() < (strtotime($evaluate_end_time[2] . "-" . $evaluate_end_time[0] . "-" . $evaluate_end_time[1])+ 24*2600 -1) ? 1 : 3;
                //添加测评记录
                Models_Evaluate_EvaluateRecord::init()->addEvaluateRecord($this->evaluateRecordDataAction($id, $evaluate_id, $evaluate_record_status, $evaluate_classify));              
            } else {
                //单元测评
                $info = Models_Evaluate_EvaluateUnit::init()->getUnitEvaluateInfo($id, 'evaluate_end_time',$evaluate_course_id);

                //有可能单元测评没有截止日期
                $evaluate_record_status = empty($info[0]['evaluate_end_time'])?1:(time() < $info[0]['evaluate_end_time'] ? 1 : 3);
                //获取班级id
                $class_id = $this->study_config->getCurClassSchoolByUserId('class_id'); 
                //$class_id = 337607;//假的数据       
                //添加测评记录
                Models_Evaluate_EvaluateRecord::init()->addEvaluateRecord($this->evaluateRecordDataAction($id, $evaluate_id, $evaluate_record_status, $evaluate_classify, $class_id,$evaluate_course_id));
             }
        }
             //从学习记录表里面获取学习的状态
             $record_info = Models_Evaluate_EvaluateRecord::init()->getCurEvaluateInfo($this->user_info['user_id'], $id,$evaluate_course_id);
            //过期的话
            if($record_info[0]['evaluate_record_status'] == $config['_evaluate_status']['end']){
                return false;
            }
           //构造参数以作跳转的参数使用
            $param = ($type == $config['_evaluate_type']['study_evaluate'])? $evaluate_id ."_". $config['_evaluate_type']['study_evaluate'] . '_' . $id : $evaluate_id ."_". $config['_evaluate_type']['evaluate'] ."_" . $evaluate_classify ."_" .$id ."_".$evaluate_course_id;
            $_SESSION['evaluate_param'] = $param;
            $url = HTTP_XUE ."test.htm?id=".$evaluate_id;
            //echo $url;
            echo json_encode(array('type' =>'success','data' =>$url));
        
 }
     /**
     * 测评的历史记录
     */
    public function evaluateHistoryAction()
    {
        $this->user_infoAction();
        //版本
        $bb = $this->getStudyResourceVersionAction();
         //版本列表
         $this->view->bb_list = Models_Resource::init()->getBbByXdAndXk($this->user_info['xd'], $this->view->xk_code);
         $this->view->bb = $bb;
        //测评状态的配置
        $this->view->evaluate_status = $this->evaluateStatusAction();
        //测评的配置
        $this->view->evaluate_config = $this->evaluateConfigAction();
        $this->view->page_title = '测评历史记录';
        //年级
        $grade_history = array();
        $nj_list_flip =  array_flip($this->_config['nj_code']);
        // $sub_grade = substr($this->_grade_code,-1);
        //  for($i = 3;$i<= $sub_grade;$i++){
        //     $grade_code = 'GO00'.$i;
        //     $grade_history[$grade_code] = $nj_list_flip[$grade_code];
        //  }
        // $this->view->grade_history = $grade_history;
        $this->view->default_grade_code = $this->study_config->userNjToJcNj($this->_config['nj_code'][$this->_grade]);
        $this->view->default_grade = $this->_grade;
        //js
        $this->view->js = array(
            'evaluate/eva_historyRecord.js'
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
        $nj = $this->post('nj'); //年级
        $bb = $this->post('bb'); //版本
        //$date = $this->post('date','week'); //时间
       // extract(Models_StudyResource_StudyRecordConfig::init()->dateConfig($date));
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
        $class_id = $this->study_config->getCurClassSchoolByUserId('class_id');
        $data = Models_Evaluate_EvaluateRecord::init()->getDiffentClassifyRecordList($nj, $evaluate_record_status, $this->user_info['user_id'], $bb, $this->user_info['xd'], $this->view->xk_code, $evaluate_classify,$class_id,$page_config['start'], $page_config['limit']);
        $this->view->data = $data;
        if(!empty($data)){
           echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($data), 'data' => $this->tpl('Modules/'.$this->view->ucxk.'/Views/Evaluate/evaluateHistoryAjax.htm', NULL, TRUE))); 
       }else{
          echo json_encode(array('limit' => $page_config['limit'], 'every_page' => count($data), 'data' => null));
       }
        
    }

    

    /**
     * 测评记录数据的封装
     * @param  int $id  关联id
     * @param  int $evaluate_id  测评id
     * @param  int $evaluate_record_status 测评状态
     * @param  int $evaluate_classify 测评分类id
     * @param  int $course_id 课程id
     * @return  array
     */
    public function evaluateRecordDataAction($id, $evaluate_id, $evaluate_record_status, $evaluate_classify, $class_id = NULL,$course_id = 0)
    {
        return array(
            'id' => $id,
            'evaluate_id' => $evaluate_id,
            'evaluate_record_user_id' => $this->user_info['user_id'],
            'evaluate_record_status' => $evaluate_record_status,
            'evaluate_record_end_time' => time(),
            'evaluate_record_score' => '',
            'evaluate_subject' => $this->view->xk_code,
            'evaluate_classify' => $evaluate_classify,
            'evaluate_class_id' =>$class_id,
            'evaluate_course_id' =>$course_id
        );
    }

    /**
     * 学习资源测评添加记录
     * @param int $id 主键id
     */
    public function addStudyRecordAction($id)
    {
        //在学习资源记录表里面初始化学习状态
            $data = array(
                'study_record_user_id' => $this->user_info['user_id'],
                'resource_id' => $id,
                'study_record_status' => 1,
                'study_record_time' => 0,
                'study_resource_subject_type' => $this->view->xk_code,
                'time' => time(),
                'study_score' => 0
            );
            Models_StudyResource_StudyRecord::init()->addStudyRecord($data);
    }
    /**
     * 日期的配置
     * @return array
     */
    public function timeRangeAction()
    {
        return array(
            'week' => "一周",
            'month' => '一月'
        );
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

    /**
     * 获取版本
     * @return bool or string
     */
    public function getStudyResourceVersionAction()
    {
        $version = Models_StudyResource_StuDefaultVersion::init()->getDefaultVersion($this->user_info['user_id'], $this->view->xk_code);
        if (empty($version)) {
            $this->redirect("/" . $this->view->xk . "/Study/index");
        } else {
            return $version[0]['sel_study_version'];
        }
    }

    /**
     * 推荐的页面 
     */
    public function recommendAction()
    {
        $this->setLayout($this->layout);
        $this->tpl();
    }

    /**
     * 检测是否登录
     */
    public function user_infoAction()
    {

        if (!isset($this->user_info) || empty($this->user_info)) {
            $this->redirect('/' . $this->view->xk . '/Evaluate/recommend');
        }
    }

    /**
    * 未登录弹出登陆框
    */
   public function loginAction()
   {

    if(!isset($this->user_info) || empty($this->user_info)){
      Cola_Controller::echoJson('login', '请登录');
    }

   }
   
}
