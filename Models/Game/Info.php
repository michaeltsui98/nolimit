<?php


class Models_Game_Info extends Cola_Model {
    
	protected $_table = 'game';
	
	protected $_pk = 'game_id';
	
    /**
     * 取游戏列表
     * @param int $cate_id
     * @param int $page
     * @param int $limit
     */	
	public  function getGameList($cate_id,$xk,$page,$limit) {
	    $where = " 1 ";
	    $cate_id and $where .= " and a.cate_id = '$cate_id' ";
	    $sql = "SELECT a.* FROM `game` a
                left join game_cate b
                on a.cate_id = b.cate_id
                where $where and b.xk = '$xk'  
                order by a.game_order 
                ";
	    return $this->getListBySql($sql, $page, $limit);
	}
	/**
	 * 游戏评分
	 * @param int $id
	 * @param int $remark
	 */
	public function remark($id,$remark){
		$sql  = "update {$this->_table} set remark= remark+$remark, remark_num = remark_num +1 where game_id = '$id'";
		return $this->sql($sql);
	}
}

?>