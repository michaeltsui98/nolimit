<?php

/**
 * 后台的基础控制器，其它控制器必须继承
 * 
 * @author michaeltsui98@qq.com  2014-04-17
 *
 */


class Modules_Admin_Controllers_Base extends  Controllers_Base {
	
    
    
    public $html;
    
    /**
     * 前置执行Action
     */
    public function adminBefore()
    {
        $this->xk  = $this->getVar('xk');
        define('XK', $this->xk);
        // 如果用户没有登录，或者没有选择学科，退出到登录界面
        if(!isset($_SESSION['admin_user']) or !XK){
            $this->redirect(BASE_PATH.'Admin/Sign');
            die;
        }
        $this->view->xk = $this->xk;
        $user = $_SESSION['admin_user'];
        /* 后台菜单列表 */
        $menu = Modules_Admin_Models_SysModule::init()->getMenu($user['user_group_id'], XK);
        $this->view->c = $this->c;
        $this->view->a = $this->a;
        $this->view->menu = $menu;
        $this->view->user = $user;
        //后台数据生成请求地址
        $this->view->getJsonDataUrl  = url($this->c, 'jsonAction');
        //后台添加数据的url
        $this->view->addUrl  = url($this->c, 'addAction');
        $this->view->addDoUrl  = url($this->c, 'addDoAction');
        //后台修改数据的url
        $this->view->editUrl  = url($this->c, 'editAction');
        $this->view->editDoUrl  = url($this->c, 'editDoAction');
        //后台删除数据的url
        $this->view->delUrl  = url($this->c, 'delAction');
        //文件上传的url
        $this->view->upload_url = DODOJC.'/Attachment/flashUpload';
    }
    
 
    
	public function __construct(){
		parent::__construct();
		$this->adminBefore();
	}
	
	public function page($page,$limit,$count,$ajax){
	    $url = Cola_Model::init()->getPageUrl();
	    $pager = new Cola_Com_Pager($page, $limit, $count, $url, $ajax);
	    return $html = $pager->html();
	}
	
	/**
	 * 取后台学科code
	 * @return string
	 */
	public function getXkCode(){
	    $subject = '';
		if($this->xk == 'xl'){
			$subject = Models_Subject::XL;
		}elseif($this->xk=='sm'){
		    $subject = Models_Subject::SM;
		}
		return $subject;
	}
	
	/**
	 * easyUI ajax 刷新页面
	 * @param string $table_id  table id="user-dg"  取 user
	 * @param string $status
	 * @param string $message
	 */
	public function flash_page($table_id,$status,$message=null,$type=null){
		null===$message and $message = '操作成功';
		if($type!=null){
		    //刷新treegrid
			$arr = array('status'=>$status,'message'=>$message,'success_callback'=>"ajax_flash('$table_id','$type');");
		}else{
		    //刷新datagrid
			$arr = array('status'=>$status,'message'=>$message,'success_callback'=>"ajax_flash('$table_id');");
		}
		$this->abort($arr);
	}
	public  function alert_page($table_id,$status,$message){
	    $arr = array('status'=>$status,'message'=>$message,'success_callback'=>"ajax_flash('$table_id');");
	    $this->abort($arr);
	}
		
} 