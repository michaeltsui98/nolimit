<?php

/**
 * @var  控制器基类
 * @author michael
 *
 */

include S_ROOT.'Models/AdminFunc.php';

class Controllers_Base extends Cola_Controller
{
    public $c;
    
    public $a;
    
    public $xk;
    
    public $user_info = array();
 
    public function before(){
        //$this->xk  = $this->getVar('xk');
        $this->c = Cola::getInstance()->dispatchInfo['controller'];
        $this->a = Cola::getInstance()->dispatchInfo['action'];
        //define('XK', $this->xk);
        $user_info = array();
        if(isset($_SESSION['user'])){
            if(!isset($_SESSION['user']['xd']) and $_SESSION['user']['user_id']){
                $xd_info = Models_Circle::init()->getSchGraByuserId($_SESSION['user']['user_id']);
                $_SESSION['user'] += $xd_info;
            }
            $user_info = $_SESSION['user'];
        }
        $this->user_info = $user_info;
        
        $this->view->user_info = $user_info;
        
    }
   
    
    public function __construct(){
    	$this->before();
    }
    
    /**
     * messagePage 方法重载，加入ajax请求的消息调用方式
     * @author liujie <ljyf5593@gmail.com>
     */
    protected function messagePage($url = '/', $message = 'goto home', $ms = 3000){
        if (Cola_Request::isAjax()) {
            $json = array(
                'type' => 'error',
                'data' => array(),
                'message' => $message,
            );
            // 如果消息中包含有成功字样则将消息返回的类型标记为成功
            if (strpos($message, '成功') !== FALSE) {
                $json['type'] = 'success';

            // 如果消息中包含有登录的字样，则将消息返回标记为需要登录
            } elseif (strpos($message, '登录') !== FALSE) {
                $json['type'] = 'login';
            }
            echo json_encode($json);
            exit();
        } else {
            parent::messagePage($url, $message, $ms);
        }
    }
}
