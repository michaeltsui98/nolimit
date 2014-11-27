<?php
/**
 * 用户应用模型
 *
 * @author michaeltusi98@qq.com  2014-05-16
 *
 * @copyright  Copyright (c) 2014 Wuhan Bo Sheng Education Information Co., Ltd.
 */

class Models_UserApp extends Cola_Model {

    protected $_table = 'user_app';
    protected $_pk = 'id';
    
    /**
     * 取系统应用列表
     * @param int $role 角色ID
     * @param char $xk_code  学科编号
     */
    public function getSysAppList($role,$xk_code,$page=1,$limit=20){
    	return Modules_Admin_Models_SysApp::init()->getAppList($xk_code, $page,$limit,$role);
    }
    
    public function getAppByNoMobile($role,$xk_code,$page=1,$limit=20){
        return Modules_Admin_Models_SysApp::init()->getAppByNoMobile($xk_code, $page,$limit,$role);
    }
    /**
     * 取我的应用 for web
     * @param string $user_id
     * @param string $xk_code
     * @return Ambigous <multitype:, boolean>
     */
    public function getMyAppList($user_id,$xk_code){
        $where = " and b.is_ok =1 and b.is_mobile!=1";
        $sql = "SELECT a.id,b.* FROM `{$this->_table}` a
                inner join sys_app b 
                on a.app_id = b.app_id
                where a.user_id = '$user_id' 
                and b.xk = '$xk_code' $where
                    ";
       	return $this->sql($sql);
    }
    /**
     * 取我的应用 for app
     * @param string $user_id
     * @param string $xk_code
     * @return Ambigous <multitype:, boolean>
     */
    public function getMyMobileAppList($user_id,$xk_code){
        $where .= " and b.is_mobile=1 and b.is_ok =1";
        $sql = "SELECT a.id,b.* FROM `{$this->_table}` a
                inner join sys_app b 
                on a.app_id = b.app_id
                where a.user_id = '$user_id' 
                and b.xk = '$xk_code' $where
                    ";
       	return $this->sql($sql);
    }
    /**
     * 删除我的应用(WEB用)
     * @param int $id
     * @return Ambigous <boolean, unknown>
     */
    public function delMyAppById($id){
       return $this->delete($id);
    }
    /**
     * 删除用户的ID (手机用)
     * @param string $user_id
     * @param string $app_id
     * @return Ambigous <multitype:, boolean>
     */
    public function delUserApp($user_id,$app_id){
       $sql = "delete from {$this->_table} where user_id = '$user_id' and app_id = '$app_id'";
    	return $this->sql($sql);
    }
    /**
     * 添加我的应用
     * @param string $user_id
     * @param int $app_id
     * @return boolean
     */
    public function addMyApp($user_id,$app_id){
    	return $this->insert(array('user_id'=>$user_id,'app_id'=>$app_id));
    }
    
    
} 