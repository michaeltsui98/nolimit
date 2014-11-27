<?php

/**
 * 后台游戏分类管理
 * 
 * @author michaeltsui98@qq.com 2014-05-21
 *
 */
class Modules_Admin_Controllers_GameCate extends  Modules_Admin_Controllers_Base {
	
	public  function indexAction(){
	    $this->view->title = '游戏分类管理-列表';
 	    if(!$this->request()->isAjax()){
	        $layout = $this->getCurrentLayout('common.htm');
	        $this->setLayout($layout);
	    }
	    $this->tpl();
	}
	/**
	 * 添加
	 */
	public  function addAction(){
		 
		$this->tpl();
	}
 	
	public  function addDoAction(){
	    $data = array();
		$data = $this->getVar('data');
		$data['xk'] = $this->getXkCode();
		$res = Models_Game_Cate::init()->insert($data);
		$this->flash_page('gamecate', $res);
		
	}
	/**
	 * 编辑用户组信息
	 */
	public  function editAction(){
		$cate_id = $this->getVar('cate_id');
		$data = Models_Game_Cate::init()->load($cate_id);
		$this->view->data = $data;
		$this->view->cate_id = $cate_id;
		$this->tpl();
	}
	/**
	 * 保存编辑用户组信息
	 */
	public  function editDoAction(){
		$cate_id = $this->getVar('cate_id');
		$data = $this->getVar('data');
		$res = Models_Game_Cate::init()->update($cate_id, $data);
		$this->flash_page('gamecate', $res);
	}
	/**
	 * 设置用户组状态
	 */
	public function isOkAction(){
		$cate_id = $this->get('cate_id');
		$ok = $this->get('ok');
		$res  =  Models_Game_Cate::init()->update($cate_id, array('is_ok'=>$ok));
		$this->flash_page('gamecate', $res);
	}
	/**
	 * 排序
	 */
	public function orderAction(){
		$cate_id = $this->get('cate_id');
		$type = $this->get('type');
		$obj_id = $this->get('obj_id');
		$res  =  Models_Game_Cate::init()->update($cate_id, array('app_order'=>$obj_id));
		$this->flash_page('gamecate',$res);
	}
	/**
	 * 删除 
	 */
	public function delAction(){
		$cate_id = $this->get('cate_id');
		$res  =  Models_Game_Cate::init()->delete($cate_id);
		 
		$this->flash_page('gamecate', $res);
	}
	/**
	 * json数据输出
	 */
	public function jsonAction() {
	     
	    $page =  $this->getVar('page',1);
	    $rows =  $this->getVar('rows',20);
	    $data =  Models_Game_Cate::init()->getList($this->getXkCode(), $page, $rows);
	    $this->view->data = $data;
	    $this->view->ats_url = Models_Attachment::init()->getBaseUrl();
	    $this->view->isOkUrl = url($this->c,'isOkAction');
	    $this->view->orderUrl = url($this->c,'orderAction');
	    $this->tpl();
	    
	}
	/**
	 * 列表数据输出
	 */
	public function selectAction(){
	    $data  = Models_Game_Cate::init()->getList($this->getXkCode(), 1, 200);
	    $tmp = array();
	    foreach ($data['rows'] as $k=>$v){
	    	$tmp[$k]['id'] = $v['cate_id'];
	    	$tmp[$k]['text'] = $v['name'];
	    	$tmp[$k]['option'] = $v['option'];
	    }
	   echo  json_encode($tmp);
	}
 
 
	
}