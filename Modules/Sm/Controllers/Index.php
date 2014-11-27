<?php

class Modules_Sm_Controllers_Index extends  Modules_Sm_Controllers_Base {
	
	public $layout = '';
	
	public function __construct(){
	    parent::__construct();
	    $this->layout = $this->getCurrentLayout('index.htm');
	}
	
	/**
	 * 登录前
	 */
	public function indexAction(){
	 
		$this->view->title = '生命安全首页';
        $xk = $this->xk;
        $this->view->hidden_header = true;
        $this->view->css = array('login.css');
        $this->view->js = array('login/login_index.js');
        $this->setLayout($this->layout);
       // var_dump($this->user_info);
	if(empty($this->user_info)==false){
		    
		    if((int)$this->user_info['role_code']===4){
		        $this->redirect("/sign/exit?refer=/{$xk}/index");
		    } 
		    //登录后的页面
		 	$class_list =  Models_Circle::init()->getClassList($this->user_info['user_id']);
		 	$this->view->class_list = $class_list;
		    $is_admin = Modules_Admin_Models_SysUser::init()->checkAdminByUid($this->user_info['user_id'],$xk);
		    $this->view->is_admin = $is_admin;
		    $this->view->role_code = $this->user_info['role_code'];
		    $this->view->user_role = Cola::getConfig('_userRole');
		    
		    $role_info = Helper_Breadcrumb::getInstance($this->xk)->role_item_maps[$this->view->role_code];
		    $this->view->role_url = "/{$this->xk}/{$role_info['uri']}";
		    //$this->tpl("Modules/{$this->view->ucxk}/Views/Index/role.htm");
			//die;
		}else{
		 	$ref = isset($_SESSION['refer'])?$_SESSION['refer']:'/'.$xk.'/index/go';
    		
    		$dd = new Models_DDClient();
     		$tokens =  $dd->getToken();
     		 
    		$this->view->access_tokens = $tokens['access_token'];
    		$this->view->appKey = DD_AKEY;
    		$this->view->callBackUrl = DD_CALLBACK_URL;
    		$this->view->ref = $ref;
		}
		
		$this->tpl();
	}
	public  function  goAction(){
		$role_code = $this->user_info['role_code'];
		$xk = $this->xk;
		if($role_code==1){
			$this->redirect('/'.$xk.'/student');
		}elseif($role_code==2){
			$this->redirect('/'.$xk.'/teacher');
		}elseif($role_code==3){
			$this->redirect('/'.$xk.'/parent');
		}else{
			$this->redirect('/'.$xk.'/index');
		}
	}
    /**
     * 登录页面
     */
	public function loginAction(){
	    $ref = isset($_SESSION['refer'])?$_SESSION['refer']:'/'.$this->xk.'/index/go';
	    
	    $dd = new Models_DDClient();
	    $tokens =  $dd->getToken();
	    $this->view->access_tokens = $tokens['access_token'];
	    $this->view->appKey = DD_AKEY;
	    $this->view->callBackUrl = DD_CALLBACK_URL;
	    $this->view->ref = $ref;
		$this->tpl();
	}
	/**
	 * 保存浏览者角色信息
	 */
	public  function perviewAction(){
		$role = $this->getVar('role');
		$to = $this->getVar('to');
		if(!$role){
			return false;
		}
		Cola_Response::cookie('perview_role_'.$this->xk,$role,time()+3600*5,'/','.dodoedu.com');
		$this->redirect($to);
	}
	
	 
}

?>