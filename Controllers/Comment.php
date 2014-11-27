<?php
 class Controllers_Comment extends Cola_Controller
{
   protected $_comment = NULL;

   public function __construct()
   {
  	$this->_comment = new Models_Comment_Comment();
   }

  public function indexAction()
  {
 	  $this->tpl();
  } 
  /**
   * 评论列表
   * 
   */
  public function commentListAction()
  {
    //配置
    $page_config = $this->pageConfigAction();
  	$comment_type = $this->getVar("comment_type");
  	$comment_type_id = $this->getVar("comment_type_id");
    $number = $this->getVar("comment_number");
    $comment_number = empty($number)? $page_config['limit']:$comment_number;//每页多少条
  	$comment_list = $this->_comment->commentList($comment_type, $comment_type_id,$page_config['start'], $comment_number);
    if($comment_list['msg'] == 'OK'){
      $pager = new Cola_Com_Pager($page_config['p'], $page_config['limit'], $comment_list['total'], '/Comment/commentList/p/%page%/');
      $this->view->page = $pager->html();
      if(!empty($comment_list['data']) && isset($_SESSION['user'])){
        foreach($comment_list['data'] as $k =>$v){
               $comment_list['data'][$k]['del_status'] = ($_SESSION['user']['user_id'] == $v['user_id'])?1:0;
        }
      }
      $this->view->comment_list = $comment_list['data'];
    }else{
      $this->view->comment_list = array();
    }
  	$this->view->user_id = $_SESSION['user']['user_id'];
    echo $this->tpl('views/Comment/commentList.htm', NULL, TRUE);
  }


  /**
   * 添加评论
   */
 public function addCommentAction()
 {
      $comment_type = $this->getVar("comment_type");
      $comment_type_id = $this->getVar("comment_type_id");
      $comment_content = $this->getVar('comment_content');
      //检测是否登录
      if(!isset($_SESSION['user']) || empty($_SESSION['user'])){
          echo json_encode(array('type' =>'login'));exit;
       }
       //检测是否为空
     if(empty($comment_type) || empty($comment_type_id) || empty($comment_content)){
        echo json_encode(array('type' =>'error','message' =>'内容不能为空'));exit;
     }
     $status = $this->_comment->addComment($comment_type, $comment_type_id, $comment_content, $_SESSION['user']['user_id'],$_SESSION['user']['user_realname']);
     if($status){
        $html = '<li>
                    <dt class="avarter"><img src= '.$_SESSION['user']['icon'].' alt="" width="32px"></dt>
                    <dl class="name"><a target="_blank" href= '.DOMAIN_NAME.'"/'.$_SESSION['user']['user_id'].'/Space/index">'.$_SESSION['user']['user_realname'].'</a></dl>
                    <dl class="con">'.$comment_content.'</dl>
                    <dl class="time">'.date('Y-m-d H:i',time()).'</dl>
                 </li>';
       echo json_encode(array('type' =>'success','data' =>$html));exit;
     }else{
       echo json_encode(array('type' =>'error','message' =>'添加失败'));
     }
 }

 /**
  * 删除评论
  * 
  */
 public function deleteCommentAction()
 {

   $comment_id = $this->getVar("comment_id");
    if(!isset($_SESSION['user'])){
     echo json_encode(array('type' =>'login'));exit;
    }

   if(empty($comment_id)){
     echo json_encode(array('type' =>'error'));exit;
   }

   $data = $this->_comment->deleteComment($comment_id);

   if($data['data']['status']){
      echo json_encode(array('type' =>'success'));exit;
   }else{
      echo json_encode(array('type' =>'error'));
   }

 }
  /**
   * 分页配置
   * @return array
   */
  public function pageConfigAction()
  {
  	$p = $this->getVar('p',1);
  	$limit = 10;
  	$start = ($p - 1) * $limit;
  	return array(
 			'p' =>$p,
 			'start' =>$start,
 			'limit' =>$limit
  		);
  }

  /**
   * 
   * 测评的结果通过这里更新到不同的数据表里面去
   */
  public function publicUpdatedataAction()
  {
     //evaluate_param 
    // 学习资源的测评 测评试卷id_1_学习资源关联的主键id
   // 评测 测评试卷id_2_测评分类(综合:1,单元:2)_学习资源关联的主键id_课程id(综合的课程id是空的)
     $json_data = $this->getVar('data');
     if(empty($json_data)){
      return false;
     }
     $data = json_decode($json_data,TRUE);
     //print_r($data);
     $param = explode('_', $data['evaluate_param']);
     if(count($param) == 3){//学习中的测评
       $update_data = array(
          'end_time' =>$data['endtime'],
          'score' =>$data['score'],
          'user_id' =>$data['user_id'],
          'param' =>$param
        );
       Models_StudyResource_StudyRecord::init()->updateDataByUserIdAndResourceId($update_data);
     }else{
      //评测
      if($param[2] == 1){
        //综合测评,如果有日期的话要检测是否过期
        $evaluate_com_info = Models_Evaluate_Evaluate::init()->getPartFieldsById($param[3], 'evaluate_end_time');
        $end_time_arr = explode("/", $evaluate_com_info[0]['evaluate_end_time']);
        $status = time()< strtotime($end_time_arr[2] ."-".$end_time_arr[1] ."-".$end_time_arr[0])? 2:3;    
      }else{
        //单元测评,如果有日期的话要检测是否过期
        $evaluate_unit_info = Models_Evaluate_EvaluateUnit::init()->getUnitEvaluateInfo($param[3], "evaluate_end_time",$param[4]);
        $status = empty($evaluate_unit_info[0]['evaluate_end_time']) ? 2 :(time() < $evaluate_unit_info[0]['evaluate_end_time'] ? 2 : 3);        
      }
       Models_Evaluate_EvaluateRecord::init()->updateData($param[3],$param[4],$data['endtime'],$data['score'], $data['user_id'],$status);
     }
  }
  
  /**
   * 和易测评返回的结果
   * jsonp 过来的
   */
  public function heYiReturnDataAction()
  {
     $ids = $this->getVar('ids');
     $ids_arr = explode("_", $ids);
     $id = $ids_arr[0];
     $course_id = $ids_arr[1];
     $user_id = $this->getVar("userId");
     $evaluate_id = $this->getVar("questionId");
     $callback = $this->getVar("callback");
     //更新状态
    //$models = new Models_Evaluate_EvaluateRecord();
    Models_Evaluate_EvaluateRecord::init()->updateData($id,$course_id,time(),0, $user_id,2);
    echo $callback . '(' . json_encode(array('type' => "success", "message" => 1) ) . ')';
  }
}


?>