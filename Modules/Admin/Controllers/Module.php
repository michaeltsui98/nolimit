<?php

/**
 * 后台模块管理
 * 
 * @author michaeltsui98@qq.com 2014-04-17
 *
 */
class Modules_Admin_Controllers_Module extends  Modules_Admin_Controllers_Base {
	
	public  function indexAction(){
		
	    $this->view->title = '后台模块管理-列表';
 	    if(!$this->request()->isAjax()){
	        $layout = $this->getCurrentLayout('common.htm');
	        $this->setLayout($layout);
	    }
	    $this->tpl();
	}
	/**
	 * 添加模块
	 */
	public  function addAction(){
		$this->view->check_module_url = url($this->c, 'checkModuleTitleAction');
		$this->view->jsonTreeUrl = url($this->c, 'jsonTreeAction');
		$this->tpl();
	}
	/**
	 * 保存模块信息
	 */
	public  function addDoAction(){
		$data = $this->getVar('data');
		$data['xk'] = XK;
		$data['module_type'] = 1;
		$res = Modules_Admin_Models_SysModule::init()->insert($data);
		$this->flash_page('module', $res,null,'treegrid');
		
	}
	/**
	 * 检查模块名称
	 */
	public  function checkModuleTitleAction(){
		$user_name = $this->getVar('param');
        $arr = array('info'=>'模块名已经被使用','status'=>'n');		
		$status = Modules_Admin_Models_SysModule::init()->checkModuleTitleAction($user_name, XK);
        if(!$status){
	        $arr = array('info'=>'模块名可以使用','status'=>'y');		
        }
		$this->abort($arr);
	}
	/**
	 * 编辑模块信息
	 */
	public  function editAction(){
		 
		$module_id = $this->getVar('module_id');
		$module = Modules_Admin_Models_SysModule::init()->load($module_id);
		$this->view->module = $module;
		$this->view->jsonTreeUrl = url($this->c, 'jsonTreeAction');
		$this->tpl();
	}
	/**
	 * 保存编辑模块信息
	 */
	public  function editDoAction(){
		$module_id = $this->getVar('module_id');
		$data = $this->getVar('data');
		$data['xk'] = XK;
		$res = Modules_Admin_Models_SysModule::init()->update($module_id, $data);
		$this->flash_page('module', $res,null,'treegrid');
	}
	/**
	 * 设置模块状态
	 */
	public function isOkAction(){
		$module_id = $this->getVar('module_id');
		$ok = $this->get('ok');
		$res  =  Modules_Admin_Models_SysModule::init()->update($module_id, array('module_isok'=>$ok));
		$this->flash_page('module', $res,null,'treegrid');
	}
	/**
	 * 排序
	 */
	public function orderAction(){
		$module_id = $this->getVar('module_id');
		$obj_id = $this->get('obj_id');
		$res  =  Modules_Admin_Models_SysModule::init()->update($module_id, array('module_order'=>$obj_id));
		$this->flash_page('module', $res,null,'treegrid');
	}
	/**
	 * 删除模块
	 */
	public function delAction(){
		$module_id = $this->getVar('module_id');
		$res  =  Modules_Admin_Models_SysModule::init()->delete($module_id);
		$this->flash_page('module', $res,null,'treegrid');
	}
	/**
	 * json数据输出
	 */
	public function jsonAction() {
	    $module_id = intval($this->getVar('id',0));
	    $page =  $this->getVar('page',1);
	    $rows =  $this->getVar('rows',20);
	    $module =  Modules_Admin_Models_SysModule::init()->getModuleList($module_id, XK);
	    $sub_module =  Modules_Admin_Models_SysModule::init()->getSubModuleCount(XK);
	    $this->view->module = $module;
	    $this->view->sub_module = $sub_module;
	    $this->view->isOkUrl = url($this->c,'isOkAction');
	    $this->view->orderUrl = url($this->c,'orderAction');
	    $this->tpl();
	}
	/**
	 * 权限数据输出
	 */
	public function jsonTreeAction() {
	    $module_id = intval($this->getVar('id',0));
	    $module =  Modules_Admin_Models_SysModule::init()->getModuleList($module_id, XK);
	    $sub_module =  Modules_Admin_Models_SysModule::init()->getSubModuleCount(XK);
	    $this->view->module = $module;
	    $this->view->id = $module_id;
	    $this->view->sub_module = $sub_module;
	     
	    $this->tpl();
	}
 
	
}