<?php


class Modules_Admin_Models_SysGroup extends Cola_Model {
    
	protected $_table = 'sys_group';
	
	protected $_pk = 'group_id';
	
	public function getData(){
		return $this->find();
	}
	/**
	 * 获取用户组表列
	 * @return Ambigous <multitype:, boolean>
	 */
	public function getUserGroupList(){
		$xk = XK;
		return $this->find(array('fileds' => 'group_id,group_title', 
				'where' => "xk='{$xk}' and group_isok=1", 'order' => 'group_order asc'));
	}
	/**
	 * get dataGrid data
	 * @param string $xk
	 * @param int $page
	 * @param int $limit
	 * @return Ambigous <boolean, multitype:Ambigous, multitype:unknown Ambigous <multitype:, boolean> >
	 */
	public function getGroupList($xk,$page,$limit){
		$sql = "select * from {$this->_table} where   xk = '$xk' order by group_order asc";
		return $this->getListBySql($sql, $page, $limit);
	}
	/**
	 * 检查组名
	 * @param string $name
	 * @param string $xk
	 * @return number | boolean
	 */
	public function checkGroupName($name, $xk){
		$res = false;
		$res = $this->count("group_title = '$name' and xk = '$xk'");
		return $res;
	}
	
}

?>