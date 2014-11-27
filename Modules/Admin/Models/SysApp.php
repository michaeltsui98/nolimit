<?php


class Modules_Admin_Models_SysApp extends Cola_Model {
    
	protected $_table = 'sys_app';
	
	protected $_pk = 'app_id';
	 
	/**
	 * get dataGrid data
	 * @param string $xk
	 * @param int $page
	 * @param int $limit
	 * @return Ambigous <boolean, multitype:Ambigous, multitype:unknown Ambigous <multitype:, boolean> >
	 */
	public function getAppList($xk,$page,$limit,$role_code=''){
	    $where = '';
	    if($role_code){
	    	$where .= " and role_code = $role_code";
	    }
	    $sql = "select * from {$this->_table} where  xk = '$xk' $where order by app_order asc";
		return $this->getListBySql($sql, $page, $limit);
	}
	/**
	 * 取非移动端的app
	 * @param string $xk
	 * @param int $page
	 * @param int $limit
	 * @param string $role_code
	 * @return Ambigous <boolean, multitype:Ambigous, multitype:, multitype:unknown Ambigous <multitype:, boolean> >
	 */
	public function getAppByNoMobile($xk,$page,$limit,$role_code=''){
	    $where = '';
	    if($role_code){
	    	$where .= " and role_code = $role_code";
	    }
	    $where .= " and is_mobile !=1 and is_ok = 1 ";
	    $sql = "select * from {$this->_table} where  xk = '$xk' $where order by app_order asc";
		return $this->getListBySql($sql, $page, $limit);
	}
	/**
	 * 取手机端的应用
	 * @param string $xk
	 * @param int $page
	 * @param int $limit
	 * @param string $role_code
	 * @return Ambigous <boolean, multitype:Ambigous, multitype:, multitype:unknown Ambigous <multitype:, boolean> >
	 */
	public function getMobileAppList($xk,$page,$limit,$role_code=''){
	    $where = '  and is_mobile = 1 ';
	    if($role_code){
	    	$where .= " and role_code = $role_code";
	    }
		$sql = "select * from {$this->_table} where  xk = '$xk' $where order by app_order asc";
		
		return $this->getListBySql($sql, $page, $limit);
	}
	/**
	 * 检查组名
	 * @param string $name
	 * @param string $xk
	 * @return number | boolean
	 */
	public function checkAppName($name, $xk){
		$res = false;
		$res = $this->count("name = '$name' and xk = '$xk'");
		return $res;
	}
	
}

?>