<?php
/**
 * 手机用户登录
 * @author michaeltsui98@qq.com
 */
class Models_Interface_Sign extends Models_Interface_Base {
     
    /**
     * 手机用户登录接口
     * @param string $user_name
     * @param string $pwd
     * @return array errcode = 1 用户名密码错误
     * errocde = 0  登录成功
     */
     public function login($user_name,$pwd){
     	 $dd= Models_DDClient::init();
         $data = array();
         $data['userName'] = $user_name;
         $data['userPass'] = $pwd;
         $tokens = $dd->getToken();
         $data['access_token'] = $tokens['access_token'];
         $data['appKey'] = DD_AKEY;
         $data['callBackUrl'] = DD_CALLBACK_URL;
         $data['responseType'] = 'code';
         $data['state'] =   isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:DODOJC;
         $uri = DD_API_URL . 'auth/clientauthorizeinfo';
         $json = Cola_Com_Http::post($uri, $data);
         $arr = json_decode($json,1);
         if($arr['errcode']==0 ){
         	$_SESSION['user'] = $arr['user_info'];         	
         }
         $xd_info  = array();
         if(!isset($_SESSION['user']['xd']) and $_SESSION['user']['user_id']){
             $xd_info = Models_Circle::init()->getSchGraByuserId($_SESSION['user']['user_id']);
             $_SESSION['user'] += $xd_info;
             $arr['user_info'] += $xd_info;
         }
         return $arr + $xd_info;
     }
     /**
      * 手机用户登出接口
      * @return boolean
      */
     public function out(){
         session_destroy();
         return true;
     }
     /**
      * 取当面登录用户信息 
      * @return array
      */
     public function getUserInfo(){
         if(!isset($_SESSION['user']['xd']) and $_SESSION['user']['user_id']){
             $xd_info = Models_Circle::init()->getSchGraByuserId($_SESSION['user']['user_id']);
             $_SESSION['user'] += $xd_info;
         }
     	return $_SESSION['user'];
     }
     
     public function test(){
         return $_SESSION;
     }
 
}