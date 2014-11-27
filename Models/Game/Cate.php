<?php


class Models_Game_Cate extends Cola_Model {
    
	protected $_table = 'game_cate';
	
	protected $_pk = 'cate_id';
	
	/**
	 * 取分类列表
	 * @param string $xk
	 * @param string $page
	 * @param string $limit
	 */
	public function getList($xk,$page,$limit){
	      $sql = "select * from {$this->_table} where is_ok = 1 and xk = '$xk' order by cate_order";
	      return $this->getListBySql($sql, $page, $limit,'cat','cate_id','','name');
	}
	
}

?>