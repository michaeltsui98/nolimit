<?php


class Modules_Admin_Models_SysUser extends Cola_Model {
    
	protected $_table = 'sys_user';
	
	protected $_pk = 'user_id';
	
	public function getData(){
		return $this->find();
	}
	
	/**
	 * 检查用户登录
	 * @param string $user_name
	 * @param string $password
	 * @param string $xk
	 * @return boolean|Ambigous <multitype:, boolean>
	 */
	public  function checkLogin($user_name,$password,$xk){
	    $arr = array('fileds' => 'user_id,user_name,user_realname,user_group_id,user_uid',
	             'where' => "user_name = '$user_name' and user_pass = '$password' and xk = '$xk'"
	         , 'order' => null, 'start' => -1, 'limit' => 1);
		$info = $this->find($arr);
		//如果没用则登录失败
		if(empty($info)){
			return false;
		}
		return $info;
	}
	/**
	 * 取系统用户信息
	 * @param int $page
	 * @param int $rows
	 * @param int $group_id
	 * @param string $xk
	 * 
	 */
	public  function getUserList($page,$rows,$group_id,$xk){
	    $where = "";
		if ( $group_id>0 ) {
	        $where = " and u.user_group_id = '{$group_id}'";
	    }
	    $sql = "select u.*,g.group_title 
	           from sys_user u left join sys_group g 
	           on u.user_group_id=g.group_id 
	           where u.user_gd=0 and u.xk= '$xk'
	           {$where} 
	           order by u.user_order asc";
	    //echo $sql;       
	    return $this->getListBySql($sql, $page, $rows);
	}
	
	/**
	 * 检查用户是否可用
	 * @param string $user_name
	 * @param string $xk
	 * @return boolean | int
	 */
	public  function checkUserName($user_name,$xk){
	     $status = false;
	     $status = $this->count("user_name = '$user_name' and xk = '$xk'");
	     return $status;
	}
	/**
	 * 检查用户是否是当前学科的管理员
	 * @param string $user_id
	 * @param string $xk
	 */
	public function checkAdminByUid($user_id,$xk){
		$status = false;
		$status = $this->count("user_uid = '$user_id' and xk = '$xk'");
		return $status;
	}
	
	
	
	
}

?>