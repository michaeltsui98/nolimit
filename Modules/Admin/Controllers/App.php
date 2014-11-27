<?php

/**
 * 后台应用管理
 * 
 * @author michaeltsui98@qq.com 2014-05-10
 *
 */
class Modules_Admin_Controllers_App extends  Modules_Admin_Controllers_Base {
	
	public  function indexAction(){
	    $this->view->title = '教材应用管理-列表';
 	    if(!$this->request()->isAjax()){
	        $layout = $this->getCurrentLayout('common.htm');
	        $this->setLayout($layout);
	    }
	    $this->tpl();
	}
	/**
	 * 添加
	 */
	public  function addAction(){
		$this->view->checkAppNameUrl = url($this->c,'checkNameAction');
		$this->view->upload_url = DODOJC.url('Controllers_Attachment','flashUploadAction');
		$this->tpl();
	}
 	
	public  function addDoAction(){
		$data = $this->getVar('data');
		$icon_json = $this->getVar('icon');
		$icon_arr = json_decode($icon_json,1);
		if(isset($icon_arr['file_path'])){
		    $data['icon'] = $icon_arr['file_path'];
		}
		$data['xk'] = $this->getXkCode();
		
		$res = Modules_Admin_Models_SysApp::init()->insert($data);
		$this->flash_page('app', $res);
		
	}
	/**
	 * 编辑用户组信息
	 */
	public  function editAction(){
		$app_id = $this->getVar('app_id');
		$data = Modules_Admin_Models_SysApp::init()->load($app_id);
		$base_url = Models_Attachment::init()->getBaseUrl();
		$this->view->base_url = $base_url;
		$this->view->data = $data;
		$this->view->app_id = $app_id;
		$this->tpl();
	}
	/**
	 * 保存编辑用户组信息
	 */
	public  function editDoAction(){
		$app_id = $this->getVar('app_id');
		$data = $this->getVar('data');
		
		$icon_json = $this->getVar('icon');
		$icon_arr = json_decode($icon_json,1);
		if(isset($icon_arr['file_path'])){
		    $data['icon'] = $icon_arr['file_path'];
		}
		
		$res = Modules_Admin_Models_SysApp::init()->update($app_id, $data);
		$this->flash_page('app', $res);
	}
	/**
	 * 设置用户组状态
	 */
	public function isOkAction(){
		$app_id = $this->get('app_id');
		$ok = $this->get('ok');
		$res  =  Modules_Admin_Models_SysApp::init()->update($app_id, array('is_ok'=>$ok));
		$this->flash_page('app', $res);
	}
	/**
	 * 排序
	 */
	public function orderAction(){
		$app_id = $this->get('app_id');
		$type = $this->get('type');
		$obj_id = $this->get('obj_id');
		$res  =  Modules_Admin_Models_SysApp::init()->update($app_id, array('app_order'=>$obj_id));
		$this->flash_page('app',$res);
	}
	/**
	 * 删除 
	 */
	public function delAction(){
		$app_id = $this->get('app_id');
		$data = $app_info = Modules_Admin_Models_SysApp::init()->load($app_id);
		$icon_path = $data['icon'];
		//var_dump($icon_path);die;
		Models_Attachment::init()->deleteByPath($icon_path);
		
		$res  =  Modules_Admin_Models_SysApp::init()->delete($app_id);
		 
		$this->flash_page('app', $res);
	}
	/**
	 * json数据输出
	 */
	public function jsonAction() {
	     
	    $page =  $this->getVar('page',1);
	    $rows =  $this->getVar('rows',20);
	    $role_code = $this->getVar('role_code');
	    $data =  Modules_Admin_Models_SysApp::init()->getAppList($this->getXkCode(), $page, $rows,$role_code);
	    $this->view->data = $data;
	    $this->view->ats_url = Models_Attachment::init()->getBaseUrl();
	    $this->view->isOkUrl = url($this->c,'isOkAction');
	    $this->view->orderUrl = url($this->c,'orderAction');
	    $this->tpl();
	    
	}
 
 
	
}