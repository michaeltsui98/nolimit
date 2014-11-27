<?php
/**
 * 请求地址：http://dev-

jc.dodoedu.com/interface/index/app_key/b8c8320d9c6d35c0b7dc412a44549bbb/c/Models_Interface_Sign/m/login?

user_name=michaells&pwd=12345

基本参数
app_key => 应用的唯一编号
业务参数
c =>  models类名称
m =>  方法名称
p =>  表示所需参数

输出格式：JSON
type=>error,success
message=>'操作提示'  
data=>'返回的业务数据'
errcode=>'' 错误代码
time=> '' 请求时间

错误代码说明：
100=> access_token 为空
200=> 验证 app_key 无效
300=> 验证 app_key 过期
400=> 请求的类名与方法名不能为空
500=> 请求的类名不存在
600=> 请求的参数错误
800=> 请求的方法不存在
10000=> 方法内容异常出错
 * @author michael
 *
 */
class Controllers_Interface extends Controllers_Base {

    /**
     * @var 类名
     */
    public $c;
    /**
     * 
     * @var 方法名
     */
    public $m;
    
    public function __construct(){
        parent::__construct();
        $this->init();
     }
     /**
      * 初始化接口参数，判断数据是不是有效的请求
      * 通过Access_token 到oauth 平台来判断
      * 0代表令牌无效，1代表有效，2代表过期
      */
     public function init(){
         $status= false;
         $app_key = $this->getVar('app_key');
         if(!$app_key){
             $this->echoJson('error', 'access_token 为空',array(),100);
         }
         $dd = new Models_DDClient();
         
         $url = DD_API_URL."auth/checkappkey";
         //echo $app_key;die;
         $res = (int)Cola_Com_Http::post($url, array('app_key'=>$app_key));
         
         if($res==0){
             $this->echoJson('error', '验证 app_key 无效',array(),200);
         }
         if($res==2){
             $this->echoJson('error', '验证 app_key 过期',array(),300);
         }
         $this->c = $this->getVar('c');
         $this->m = $this->getVar('m');
         if(!$this->c or !$this->m){
             $this->echoJson('error', '请求的类名与方法名不能为空',array(),400);
         }
         if($res==1){
             return true;
         }
     } 
     
     /**
      * ajax json 统一输出
      * @param string $type error,success
      * @param string $message  提示信息
      * @param array $data  数据
      * @param $error_code 错误代码
      */
     public function echoJson($type,$message,array $data = array(),$error_code=null){
         $array = array('type' => $type, 'message' => $message ,'data' => $data,'errcode'=>$error_code,'time'=>time());
         $this->abort($array);
     }
     /**
      * 手机请求的入口
      */
     public function indexAction(){
        if(!class_exists($this->c)){
            $this->echoJson('error', $this->c.'not exists',array(),500);
        }
        $cls = new $this->c;
	    $func = $this->m;
	    $params = $this->post()?$this->post():$this->get();
	    if($params and !is_array($params)){
	        $this->echoJson('error', '请求参数错误',$params,600);
	    }
	    if(method_exists($cls, $func)){
	           $data =  call_user_func_array(array($cls, $func), $params);
    	       if($params['debug']){
    	       	 echo var_export($data,1);die;
    	       }
    	       
    	       if(is_array($data)){
    	           $data = Models_Interface_Base::init()->filterNull($data);
    	       }elseif(empty($data) or is_null($data)){
    	       	   $data = array();
    	       }else{
    	       	   $data = array($data);
    	          	       }
    	       $this->echoJson('success','', $data,0);
	        
	       
	    }else{
	        $this->echoJson('error', $this->c.'->'.$func.'not exists',array(),800);
	    }
     }
}
