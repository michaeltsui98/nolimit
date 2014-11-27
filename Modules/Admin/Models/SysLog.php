<?php


class Modules_Admin_Models_SysLog extends Cola_Model {
    
	protected $_table = 'sys_log';
	
	protected $_pk = 'log_id';
	
	public function getData(){
		return $this->find();
	}
	
}

?>