<?php

 /**
  * 用户权限模型
  * @author michael 2014-04-21
  *
  */
 class Modules_Admin_Models_SysPurview extends Cola_Model {
    
	protected $_table = 'sys_purview';
	
	protected $_pk = 'purview_id';
	 
	/**
	 * 取组的权限列表
	 * @param int $group_id
	 */
	public function getPurviewList($group_id){
		$sql = "select * from {$this->_table} where purview_group_id = '$group_id'";
		return $this->sql($sql);
	}
	
	/**
	 * 删除组权限
	 * @param int $group_id
	 */
	public function delPurviewByGroupId($group_id){
		$sql = "delete  from {$this->_table} where purview_group_id = '$group_id'";
		return $this->sql($sql);
	}
	/**
	 * 添加权限
	 * @param int $group_id
	 * @param int $module_id
	 */
	public function addPurview($group_id,$module_id){
		return $this->insert(array('purview_group_id'=>$group_id,'purview_module_id'=>$module_id));
	}
	
}

?>