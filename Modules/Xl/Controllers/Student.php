<?php

/**
 * Description of StudentCenter
 * 学生个人中心
 * @author libo
 */
class Modules_Xl_Controllers_Student extends Modules_Xl_Controllers_Base
{

    public $layout = '';
    protected $_user_app = NULL;
    //protected $_request_url = 'http://dev-xue.dodoedu.com/test.htm';
    protected $_request_url = 'http://xue.dodoedu.com/test.htm';
    public function __construct()
    {
        parent::__construct();
        $this->layout = $this->getCurrentLayout('index.htm');
        $this->_user_app = new Models_UserApp();
    }

    /**
     * 首页
     */
    public function indexAction()
    {
        $this->user_infoAction();
        $this->view->page_title = '学习个人中心';
        $this->setLayout($this->layout);
        $my_app = Models_UserApp::init()->getMyAppList($this->user_info['user_id'], $this->view->xk_code); //获取当前用户的应用
        $this->view->my_app = $my_app;
        //url前缀
        $this->view->url_prefix = DODOJC . '/' .$this->view->xk .'/'; 
        //最近学习
        // $this->view->recent_study = Models_OnlineCourseRecord_OnlineCourseRecord::init()->getPartStudyRecord($this->user_info['user_id'], $this->_xk, 'GO003', 3);
        //快捷面板
         $this->view->default_app = $this->defaultAppAction();
        //获取应用,添加应用和删除应用的地址
        $this->view->sys_app_list_url = '/'.$this->view->xk.'/Student/ajaxGetSysAppList';
        $this->view->add_app_url =  '/'.$this->view->xk.'/Student/ajaxAddMyApp';
        $this->view->del_app_url =  '/'.$this->view->xk.'/Student/ajaxDeleteMyApp';
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
        0 => array('name' => '看视频', 'url' => 'Study/studyDetail', 'icon' => 'icon-videoSp'),
        1 => array('name' => '做测评', 'url' => 'evaluate/index', 'icon' => 'icon-testSp'),
        2 => array('name' => '有问题', 'url' => 'question/index', 'icon' => 'icon-FAQsSp'),
        3 => array('name' => '玩游戏', 'url' => 'game/index',     'icon' => 'icon-gameSp')
    );
 }
 
 /**
  * 应用列表
  * @return 
  */
 public function ajaxGetSysAppListAction()
 {
    //获取我的应用列表
    $app_list = $this->_user_app->getAppByNoMobile($this->user_info['role_code'],$this->view->xk_code,1,999999);
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
        }else{
             echo json_encode(array('rows' =>array()));
        }
 }
 /**
  * 添加应用
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
    * 修改版本的ajax
    * @return
    */
   public function ajaxProcessTextbookPublisherAction()
   {
        $default_version = (string) $this->getVar('publisher_code');
        if (empty($default_version) || empty($this->user_info['user_id'])) {
            echo json_encode(array('type' => 'error', 'message' => '未选择版本'));
        }
        $data = array(
            'sel_user_id' => $this->user_info['user_id'],
            'sel_study_version' => $default_version,
            'study_type_id' => $this->view->xk_code,
            'time' => time()
        );
        $status = Models_StudyResource_StuDefaultVersion::init()->addDefultVersion($data);
        if ($status) {
            echo json_encode(array('type' => 'success', 'message' => '添加默认版本成功', 'data' => ''));
        } else {
            echo json_encode(array('type' => 'error', 'message' => '设置失败或选择了相同的版本'));
        }  
   }

    /**
     * 推荐的页面 
     */
    public function recommendAction()
    {
      if(!isset($this->user_info) || empty($this->user_info)){
         //推荐的学习包
         $recommend_study_resource = Models_StudyResource_StudyResource::init()->recommendStudyResource($this->view->xk_code,8);
         $this->view->recommend_study_resource = $recommend_study_resource;
         //推荐的评测
         $recommend_evaluate = Models_Evaluate_Evaluate::init()->recommendEvaluate($this->view->xk_code,8);
         $this->view->recommend_evaluate = $recommend_evaluate;
         //推荐的问答
         $model_question = new Models_Question_Interfaces();
         $recommend_question = $model_question->getRecommend(array('num' =>8,'subject' => $this->view->xk_code));
         unset($recommend_question['count']);
         $this->view->recommend_question = $recommend_question;
         //测评的url地址
         $this->view->request_url = HTTP_XUE .'test.htm';
         $this->setLayout($this->layout);
         $this->tpl();
      }else{
        $this->redirect('/' . $this->view->xk . '/Student/index');
      }
    }

     /**
     * 检测是否登录
     */
    public function user_infoAction()
    {
      
        if (!isset($this->user_info) || empty($this->user_info)) {
            $this->redirect('/' . $this->view->xk . '/Student/recommend');
        }
    }
}
