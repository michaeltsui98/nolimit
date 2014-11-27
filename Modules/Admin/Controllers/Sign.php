<?php

/**
 * 管理后台
 * @author michael  2014-05-08
 *
 */

class Modules_Admin_Controllers_Sign extends  Controllers_Base {
	
    /**
     * 后台登录页面
     */
	public  function indexAction(){
		$this->view->xk = $this->getVar("xk",'xl');
	    $this->tpl();
	}
	
	public function loginAction(){
		$user_name = $this->post('user_name');
		$password = $this->post('password');
		$xk = $this->post('xk');
		 $res = Modules_Admin_Models_SysUser::init()->checkLogin($user_name, $password, $xk);
		/*if(!isset($this->user_info['user_id'])){
		    $this->messagePage('/admin/sign','管理员用户必须先到前台登录');
		}*/
		if($res){ 
		    //当前登录用户
		    /* $is_admin = Modules_Admin_Models_SysUser::init()->checkAdminByUid($this->user_info['user_id'],$xk);
		    if(!$is_admin){
		    	$this->messagePage('/admin/sign','登录账号不是管理员用户');
		    	exit;
		    } */
		    $_SESSION['admin_user'] = $res[0];
		    $this->redirect(BASE_PATH.'Admin/Index/index/xk/'.$xk);
			
		} 
		
		$this->messagePage('/admin/sign/index/xk/'.$xk,'用户名或密码错误');
		//var_dump($user_name,$password);
	}
	/**
	 * 退出后台
	 */
	public function exitAction(){
		unset($_SESSION['admin_user']);
		$xk = $this->getVar('xk');
		$this->redirect(BASE_PATH.'Admin/Sign/index/xk/'.$xk);
	}
	
}