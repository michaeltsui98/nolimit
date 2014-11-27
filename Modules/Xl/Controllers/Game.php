<?php

class Modules_Xl_Controllers_Game extends  Modules_Xl_Controllers_Base {
	
    public $layout = '';
	
	public function __construct(){
	    parent::__construct();
		$this->layout = $this->getCurrentLayout('index.htm');
	}
	
	public function indexAction(){
	 
		$this->view->page_title = '游戏中心';
	    
		$cate_id = $this->getVar('cate_id');
		$page = $this->getVar('page',1);
		$limit = 20 ;
		//游戏分类
		$cate_arr = Models_Game_Cate::init()->getList($this->view->xk_code, 1, 20);
		$this->view->cate_arr = $cate_arr['rows'];
		$game_arr = Models_Game_Info::init()->getGameList($cate_id, $this->view->xk_code, $page, $limit);
		$game = $game_arr['rows'];
		$count = $game_arr['total'];
		
		$this->view->game = $game;
		$this->view->img_url = Models_Attachment::init()->getBaseUrl();
		
		$pager = new Cola_Com_Pager($page,$limit,$count,Cola_Model::init()->getPageUrl());
		$this->view->page_html = $pager->html();
		$this->view->cate_id = $cate_id;
		
		$this->setLayout($this->layout);
        $this->tpl();
	}
	/**
	 * 详细页
	 */
	public function infoAction(){
		$id = (int) $this->getVar('id');
		$info = Models_Game_Info::init()->load($id);
		$this->view->info = $info;
		$this->view->js = array('game/game_details.js');
		$this->view->root_js = array('script/comment.js');
		$this->setLayout($this->layout);
		$this->tpl();
	}
	
	/**
	 * 评分页
	 */
	public function remarkAction(){
	    $this->tpl();
	}
	/**
	 * 提交评分,一天只能提交一次用cookie 
	 */
	public function remarkDoAction(){
        $user_id = $this->user_info['user_id'];
        if(!$user_id){
        	$this->echoJson('error', '请登录');
        }
	    $id = (int)$this->getVar('id');
        $remark = (int)$this->getVar('hdn_ty');
        
        $cookie_name = "game_".$user_id.$id;
        
        $cookie_val = Cola_Request::cookie($cookie_name);
        if(!$cookie_val){
            Cola_Response::cookie($cookie_name,1,time()+3600*24,"/");
            $res = Models_Game_Info::init()->remark($id, $remark);
        	$this->echoJson('success', '谢谢您 的评价');
        }else{
        	$this->echoJson('error', '已经评过了');
        }
	    
	    
	    
	}
	
	
	 
}

?>