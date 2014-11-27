<?php

/**
 * 后台游戏信息管理
 * 
 * @author michaeltsui98@qq.com 2014-05-21
 *
 */
class Modules_Admin_Controllers_Game extends  Modules_Admin_Controllers_Base {
	
	public  function indexAction(){
	    $this->view->title = '游戏信息管理-列表';
 	    if(!$this->request()->isAjax()){
	        $layout = $this->getCurrentLayout('common.htm');
	        $this->setLayout($layout);
	    }
	    $this->view->get_cate_url = url('Modules_Admin_Controllers_GameCate','selectAction');
	    $this->tpl();
	}
	/**
	 * 添加
	 */
	public  function addAction(){
        $cate_id = $this->getVar('cate_id');
        $cate_arr = Models_Game_Cate::init()->getList($this->getXkCode(), 1, 200);
        
        $this->view->cate_arr = $cate_arr['rows'];
       
        $this->view->cate_id = $cate_id;
        
	    
		$this->tpl();
	}
 	
	public  function addDoAction(){
	    $data = array();
		$data = $this->getVar('data');
		 
		$img_arr = json_decode($this->post('img'),1);
		if(isset($img_arr['file_path'])){
			$data['img'] = $img_arr['file_path'];
		}
		
		$res = Models_Game_Info::init()->insert($data);
		$this->flash_page('game', $res);
		
	}
	/**
	 * 编辑用户组信息
	 */
	public  function editAction(){
		$game_id = $this->getVar('game_id');
		$data = Models_Game_Info::init()->load($game_id);
		$cate_arr = Models_Game_Cate::init()->getList($this->getXkCode(), 1, 200);
        
        $this->view->cate_arr = $cate_arr['rows'];
		$this->view->data = $data;
		$this->view->game_id = $game_id;
		$this->view->ats_url = Models_Attachment::init()->getBaseUrl();
		$this->tpl();
	}
	/**
	 * 保存编辑用户组信息
	 */
	public  function editDoAction(){
		$game_id = $this->getVar('game_id');
		$data = array();
		$data = $this->getVar('data');
		$img_arr = json_decode($this->post('img'),1);
		if(isset($img_arr['file_path'])){
		    $data['img'] = $img_arr['file_path'];
		}
		
		$res = Models_Game_Info::init()->update($game_id, $data);
		$this->flash_page('game', $res);
	}
	/**
	 * 设置用户组状态
	 */
	public function isOkAction(){
		$game_id = $this->get('game_id');
		$ok = $this->get('ok');
		$res  =  Models_Game_Info::init()->update($game_id, array('is_ok'=>$ok));
		$this->flash_page('game', $res);
	}
	/**
	 * 排序
	 */
	public function orderAction(){
		$game_id = $this->get('game_id');
		$type = $this->get('type');
		$obj_id = $this->get('obj_id');
		$res  =  Models_Game_Info::init()->update($game_id, array('app_order'=>$obj_id));
		$this->flash_page('game',$res);
	}
	/**
	 * 删除 
	 */
	public function delAction(){
		$game_id = $this->get('game_id');
		$game_info = Models_Game_Info::init()->load($game_id);
		Models_Attachment::init()->deleteByPath($game_info['img']);
		$res  =  Models_Game_Info::init()->delete($game_id);
		$this->flash_page('game', $res);
	}
	/**
	 * json数据输出
	 */
	public function jsonAction() {
	     
	    $page =  $this->getVar('page',1);
	    $rows =  $this->getVar('rows',20);
	    $cate_id = (int) $this->getVar('cate_id');
	    $data =  Models_Game_Info::init()->getGameList($cate_id, $this->getXkCode(), $page, $rows);
	    $this->view->data = $data;
	    $this->view->ats_url = Models_Attachment::init()->getBaseUrl();
	    $this->view->isOkUrl = url($this->c,'isOkAction');
	    $this->view->orderUrl = url($this->c,'orderAction');
	    $this->tpl();
	    
	}
 
 
	
}