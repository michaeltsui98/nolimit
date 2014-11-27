<?php
/**
 * 学段相关数据模型
 */
class Models_Circle  extends Cola_Model{
    
    public $circle;
    
    public function __construct(){
         $this->circle or $this->circle = Cola_Com_WebServices::factory(Cola::getconfig('_webServicesCircle'));
    }
    /**
     * 根据用户编号获取用户当前所在的学校、学段
     * @param unknown $user_id
     */
    public  function getSchGraByuserId($user_id){
        $arr =  $this->circle->getSchGraByuserId((string)$user_id);
    	$info = current($arr);
    	$xd=array(1=>'xd001',2=>'xd002',3=>'xd003',4=>'xd004');
    	return  array('xd'=>$xd[$info['Type']],'school_name'=>$info['School_name'],'school_id'=>$info['ID']);
    }
    /**
     * 取当前登录用户的所在班级信息
     */
    public function getClassList($user_id = null){
        $user_id or $user_id = $_SESSION['user']['user_id'];
        if(!$user_id){
            return false;
        }
            
        $key = $this->getCacheKey(__FUNCTION__,array($user_id));
        $data = $this->cache->get($key);
        if(!$data){
            $client = new Models_DDClient();
            $data =   $client->getDataByApi('class/myclasslistinfo', array('user_id'=>$user_id), 0);
            //var_dump($data);die;
             if($data['data']){
              	$this->cache->set($key,$data['data'],3600*3600);
            }
            $data = $data['data'];  
        }
        return $data;
    }
    /**
     * 取用户的学校与班级信息
     * @param string $user_id
     * @return multitype:NULL
     */
    public function getCurClassSchoolByUserId($user_id){
        $res = array();
        $school = $this->circle->getSchoolNameByUserId((string)$user_id);
        if(isset($school[0]['ID'])){
           	$res['school_id']  = $school[0]['ID'];
           	$res['school_name']  = $school[0]['School_name'];
        }
        $data =  $this->circle->getCurClassByUserId((string)$user_id);
        if(isset($data[0]['ID'])){
            $res['class_id']  = $data[0]['ID'];
            $res['xd']  = $data[0]['Type'];
            $res['grade_id']  = $data[0]['Grade_name'];
        }
        return $res;
    }
    
}