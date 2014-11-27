<?php
/**
 * 基础节点接口类
 * @author michaeltsui98@qq.com
 */
class Models_Interface_Node extends Models_Interface_Base {

    /**
     * 取所有学段
     * @return Ambigous <Cola_Config, mixed, multitype:, unknown>
     */
    public  function getXd() {
    	return Cola::getConfig('_xd');
    } 
    /**
     * 取所有学科
     * @return Ambigous <Cola_Config, mixed, multitype:, unknown>
     */
    public  function getXk() {
    	return Cola::getConfig('_xk');
    }
    /**
     * 取所有版本
     * @return Ambigous <Cola_Config, mixed, multitype:, unknown>
     */ 
    public  function getBb() {
    	return Cola::getConfig('_bb');
    } 
    /**
     * 所有年级
     * @return Ambigous <Cola_Config, mixed, multitype:, unknown>
     */
    public  function getNj() {
    	return Cola::getConfig('_nj');
    } 
     
}