<?php

/**
 * 后台用户管理
 * 
 * @author michaeltsui98@qq.com 2014-04-17
 *
 */
class Modules_Admin_Controllers_User extends  Modules_Admin_Controllers_Base {
	
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
	    $this->view->js = array('script/Chart.js');
	    $this->tpl();
	}
	/**
	 * 添加用户
	 */
	public  function addAction(){
		
		$grouplist = Modules_Admin_Models_SysGroup::init()->getUserGroupList();
		$this->view->grouplist = $grouplist;
		$this->view->check_username_url = url($this->c, 'checkUserNameAction');
		$this->tpl();
	}
	public  function addDoAction(){
		$data = $this->getVar('data');
		$data['xk'] = XK;
		$res = Modules_Admin_Models_SysUser::init()->insert($data);
		//$arr = array('status'=>$res,'message'=>'操作成功','success_callback'=>"ajax_flash('user');");
		//$this->abort($arr);
		$this->flash_page('user', $res);
		
	}
	public  function checkUserNameAction(){
		$user_name = $this->getVar('param');
        $arr = array('info'=>'用户名已经被使用','status'=>'n');		
		$status = Modules_Admin_Models_SysUser::init()->checkUserName($user_name, XK);
        if(!$status){
	        $arr = array('info'=>'用户名可以使用','status'=>'y');		
        }
		$this->abort($arr);
	}
	/**
	 * 编辑用户信息
	 */
	public  function editAction(){
		$grouplist = Modules_Admin_Models_SysGroup::init()->getUserGroupList();
		$this->view->grouplist = $grouplist;
		$user_id = $this->getVar('user_id');
		$user = Modules_Admin_Models_SysUser::init()->load($user_id);
		$this->view->user = $user;
		$this->tpl();
	}
	/**
	 * 保存编辑用户信息
	 */
	public  function editDoAction(){
		$user_id = $this->getVar('user_id');
		$data = $this->getVar('data');
		$res = Modules_Admin_Models_SysUser::init()->update($user_id, $data);
		/* $arr = array('status'=>$res,'message'=>'操作成功','success_callback'=>"ajax_flash('user');");
		$this->abort($arr); */
		$this->flash_page('user', $res);
	}
	/**
	 * 设置用户状态
	 */
	public function isOkAction(){
		$user_id = $this->get('user_id');
		$ok = $this->get('ok');
		$res  =  Modules_Admin_Models_SysUser::init()->update($user_id, array('user_isok'=>$ok));
		$arr = array('status'=>$res,'message'=>'操作成功','success_callback'=>"ajax_flash('user');");
		$this->abort($arr);
	}
	/**
	 * 排序
	 */
	public function orderAction(){
		$user_id = $this->get('user_id');
		$type = $this->get('type');
		$obj_id = $this->get('obj_id');
		
		$res  =  Modules_Admin_Models_SysUser::init()->update($user_id, array('user_order'=>$obj_id));
		$arr = array('status'=>$res,'message'=>'操作成功','success_callback'=>"ajax_flash('user');");
		$this->abort($arr);
	}
	/**
	 * 删除用户
	 */
	public function delAction(){
		$user_id = $this->get('user_id');
		$res  =  Modules_Admin_Models_SysUser::init()->delete($user_id);
		$arr = array('status'=>$res,'message'=>'操作成功','success_callback'=>"ajax_flash('user');");
		$this->abort($arr);
	}
	/**
	 * json数据输出
	 */
	public function jsonAction() {
	    $group_id = intval($this->getVar('group_id'));
	    $page =  $this->getVar('page',1);
	    $rows =  $this->getVar('rows',20);
	    $user =  Modules_Admin_Models_SysUser::init()->getUserList($page, $rows, $group_id, XK);
	    $this->view->user = $user;
	    $this->view->isOkUrl = url($this->c,'isOkAction');
	    $this->view->orderUrl = url($this->c,'orderAction');
	    $this->tpl();
	    
	}
 
	
}