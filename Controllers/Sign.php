<?php

/**
 * @var 平台登录
 * @author michael
 *
 */
class Controllers_Sign extends Controllers_Base
{
    
    /**
     * 登录页面
     */
    public  function indexAction ()
    {
        
        
        $xk = $this->getVar('xk');
        $ref = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:DODOJC.'/'.ucfirst($xk).'/Index';
        if($this->user_info){
        	//var_dump($_SESSION);die;
        	$this->redirect($ref);
        }
        
        $dd = new Models_DDClient();
        if(!$xk){
             echo '学科参数错误';	
        }
        $tokens =  $dd->getToken();
        $this->view->access_tokens = $tokens['access_token'];
        $this->view->appKey = DD_AKEY;
        $this->view->callBackUrl = DD_CALLBACK_URL;
        $this->view->xk = $ref;
        
         
        
        $this->tpl();
 
    }
    /**
     * 退出
     */
    public function exitAction(){
    	session_destroy();
    	Models_Oauth::init()->clear();
    	$refer = $this->getVar('refer');
    	if(Cola_Request::isAjax()){
    		$this->echoJson('success', '退出成功');
    	}
    	if($refer){
    	   $this->redirect($refer);
    	}else{
    	   $this->redirect('/');
    	}
    }
 
    public function callBackACtion(){
         
    	$code = $this->getVar('code');
    	$errcode = (int)$this->getVar('errcode');
    	$ref = $this->getVar('state');
    	if($errcode){
    	    $err = array(
    	                   0=>'表示成功',
    	                   1=>'用户名密码错误',
    	                   2=>'尚未注册', 
    	                   3=>'程序内部错误'
    	    );
    	    if(Cola_Request::isAjax()){
    	    	$this->echoJson('error', $err[$errcode]);
    	    }else{
    		  $this->response()->alert($err[$errcode],$ref);
    		}
    		exit;
    	}
    	 
    	
    	//$_SESSION['user'] = $user_info; 
    	//var_dump($userInfo);die;
    	if(Cola_Request::isAjax() and isset($_SESSION['user']['user_id'])){
    	    $this->echoJson('success', '登录成功');
    	}else{
    	   $this->redirect($ref);
    	}
    	
    } 
 
    
    
}
