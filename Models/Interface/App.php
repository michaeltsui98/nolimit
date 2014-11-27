<?php
/**
 * 教材应用接口类
 * @author michaeltsui98@qq.com
 */
class Models_Interface_App extends Models_Interface_Base {

    /**
     * 取学科的所有应用
     * @param number $role 1学生，2老师    (*)
     * @param string $xk_code 学科代码 GS0024   (*)
     * @param number $page 当前页码，默认为1
     * @param number $limit 每页取多少条,默认10
     * @return array 返回应用数据与应用总数
     */
    public  function getListByXk($role=1,$xk_code,$page=1,$limit=10) {
    	return Modules_Admin_Models_SysApp::init()->getMobileAppList($xk_code, $page, $limit,$role);
    }
    /**
     * 取某学科下我的应用
     * @param string $user_id 用户ID
     * @param string $xk_code 学科代码 GS0024   (*)
     * @return Ambigous <Ambigous, multitype:, boolean>
     */
    public  function getMyAppByXk($user_id,$xk_code) {
    	return Models_UserApp::init()->getMyMobileAppList($user_id, $xk_code);
    }
    /**
     * 添加一个我的应用
     * @param string $user_id
     * @param int $app_id
     */
    public function addMyApp($user_id,$app_id){
       $res =   Models_UserApp::init()->addMyApp($user_id, $app_id);
       if($res){
           return "success";
       }else{
           return "faild";
       }
    }
    /**
     * 删除我的一个应用
     * @param string $user_id
     * @param int $app_id
     */
    public function delMyApp($user_id,$app_id){
       $res =  Models_UserApp::init()->delUserApp($user_id,$app_id);  
       if($res){
           return "success";
       }else{
           return "faild";
       } 	
    }
 
        
}