<?php

/**
 * 后台模块管理
 * 
 * @author michaeltsui98@qq.com 2014-04-17
 *
 */
class Modules_Admin_Controllers_UserGroup extends  Modules_Admin_Controllers_Base {
	
	public  function indexAction(){
	    $this->view->title = '用户组组管理-列表';
 	    if(!$this->request()->isAjax()){
	        $layout = $this->getCurrentLayout('common.htm');
	        $this->setLayout($layout);
	    }
	    $this->tpl();
	}
	/**
	 * 添加用户组
	 */
	public  function addAction(){
		$this->view->checkGroupNameUrl = url($this->c,'checkNameAction');
		$this->tpl();
	}
	
	public  function addDoAction(){
		$data = $this->getVar('data');
		$data['xk'] = XK;
		$res = Modules_Admin_Models_SysGroup::init()->insert($data);
		$this->flash_page('usergroup', $res);
		
	}
	public  function checkNameAction(){
		$name = $this->getVar('param');
        $arr = array('info'=>'名字已经被使用','status'=>'n');		
		$status = Modules_Admin_Models_SysGroup::init()->checkGroupName($name, XK);
        if(!$status){
	        $arr = array('info'=>'名字可以使用','status'=>'y');		
        }
		$this->abort($arr);
	}
	/**
	 * 编辑用户组信息
	 */
	public  function editAction(){
		$group_id = $this->getVar('group_id');
		$group = Modules_Admin_Models_SysGroup::init()->load($group_id);
		$this->view->group = $group;
		$this->view->purviewUrl = url($this->c,'purviewAction');
		$this->view->group_id = $group_id;
		$this->tpl();
	}
	/**
	 * 保存编辑用户组信息
	 */
	public  function editDoAction(){
		$group_id = $this->getVar('group_id');
		$data = $this->getVar('data');
		$res = Modules_Admin_Models_SysGroup::init()->update($group_id, $data);
		$purview = $this->getVar('purview');
		if($group_id>0){
		    Modules_Admin_Models_SysPurview::init()->delPurviewByGroupId($group_id);
		    foreach($purview as $key=>$value){
		        if($value=='on'){
		            Modules_Admin_Models_SysPurview::init()->addPurview($group_id,$key);
		        }
		    }
		}
		//$this->flash_page($table_id, $status);
		//ui_tip('', json.message, json.status, success_callback)
		$this->flash_page('usergroup', $res);
	}
	/**
	 * 设置用户组状态
	 */
	public function isOkAction(){
		$group_id = $this->get('group_id');
		$ok = $this->get('ok');
		$res  =  Modules_Admin_Models_SysGroup::init()->update($group_id, array('group_isok'=>$ok));
		$this->flash_page('usergroup', $res);
	}
	/**
	 * 排序
	 */
	public function orderAction(){
		$group_id = $this->get('group_id');
		$type = $this->get('type');
		$obj_id = $this->get('obj_id');
		$res  =  Modules_Admin_Models_SysGroup::init()->update($group_id, array('group_order'=>$obj_id));
		$this->flash_page('usergroup',$res);
	}
	/**
	 * 删除用户组
	 */
	public function delAction(){
		$group_id = $this->get('group_id');
		$res  =  Modules_Admin_Models_SysGroup::init()->delete($group_id);
		//删除用户组对应的权限
		Modules_Admin_Models_SysPurview::init()->delPurviewByGroupId($group_id);
		
		$this->flash_page('usergroup', $res);
	}
	/**
	 * json数据输出
	 */
	public function jsonAction() {
	     
	    $page =  $this->getVar('page',1);
	    $rows =  $this->getVar('rows',20);
	    $group =  Modules_Admin_Models_SysGroup::init()->getGroupList(XK, $page, $rows);
	    $this->view->group = $group;
	    $this->view->isOkUrl = url($this->c,'isOkAction');
	    $this->view->orderUrl = url($this->c,'orderAction');
	    $this->tpl();
	    
	}
	/**
	 * 权限数据输出
	 */
	public function purviewAction() {
	     
	    $page =  $this->getVar('page',1);
	    $rows =  $this->getVar('rows',20);
	    $group_id =  $this->getVar('group_id',20);
	    
	    //取模板列表
	    $module =  Modules_Admin_Models_SysModule::init()->getTreeList(XK, $page,300,"", "module_order ASC", 'cat');
	    $this->view->module = $module;
	    //取权限列表
	    $prvArr = Modules_Admin_Models_SysPurview::init()->getPurviewList($group_id);
	    $purview = array();
        if($prvArr){
        	foreach ($prvArr as $value){
        		$purview[$value['purview_module_id']] = true;
        	}
        }        	    
	    $this->view->purview = $purview;
	    $this->tpl();
	}
 
	
}