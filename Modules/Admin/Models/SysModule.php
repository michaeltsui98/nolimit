<?php

/**
 * @author michael
 *
 */
class Modules_Admin_Models_SysModule extends Cola_Model {
    
	protected $_table = 'sys_module';
	
	protected $_pk = 'module_id';
	
	
	/**
	 * 取后用户的菜单
	 * @param int $group_id
	 * @param string $xk
	 * @return Ambigous <multitype:, boolean>
	 */
	public function getMenu($group_id,$xk){
	    if(!$xk){
	    	return false;
	    }
	    if($group_id==0){
	        $sql = "SELECT * FROM sys_module where module_isok=1 and xk='{$xk}' ORDER BY module_order asc";
	    }else{
	        $sql = "SELECT * FROM sys_module m 
	        LEFT JOIN sys_purview p on m.module_id = p.purview_module_id 
	        where m.module_isok=1 and p.purview_group_id={$group_id} 
	        and m.xk = '{$xk}'
	        ORDER BY m.module_order asc";
	    }
	    return $this->sql($sql);
	}
	/**
	 * 获取模块列表，及子模块列表
	 * @param int $module_id
	 * @param string $xk
	 */
	public function getModuleList($module_pid,$xk){
		 
		$where = "and module_pid = '$module_pid' ";	
		$sql = "SELECT * FROM `sys_module` where xk= '$xk' $where order by module_order asc";
		return $this->sql($sql);
	}
	/**
	 * 子模块的统计
	 */
	public function getSubModuleCount($xk){
		$sql = "SELECT b.module_pid,count(*) c from sys_module b 
			where b.xk='$xk'  and  b.module_pid in(
			select a.module_id from sys_module a where a.xk = '$xk'
			 )
			group by b.module_pid
			 ";
	  $data = $this->sql($sql);
	  $arr = array();
	  foreach ($data as $v){
	  	$arr[$v['module_pid']] = $v['c'];
	  }	
	  return $arr;
	}
	/**
	 * 检查模块标题是否存在
	 * @param string $title
	 * @param string $xk
	 * @return bool | int
	 */
	public function checkModuleTitleAction($title,$xk){
		$res = false;
		$res = $this->count("module_title = '$title' and xk= '$xk'");
		return $res;
	}
	
	public function getTreeList($xk, $page,$rows,$where, $order, $type){
	    $sql = "select * from {$this->_table}  where xk='$xk' $where  order by  $order";
	    return $this->getListBySql($sql, $page, $rows,$type,$this->_pk,'module_pid','module_title','module_isok');
	}
	
	
	
}

?>