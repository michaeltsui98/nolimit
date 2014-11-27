<?php

/**
 * Oauth数据库类
 *
 * @author sasou <zaipd@qq.com>
 * @version 2013-11-6
 * @copyright  Copyright (c) 2012 Wuhan Bo Sheng Education Information Co., Ltd.
 */
class Models_Oauth extends Cola_Model
{

    protected $_cache_key = 'jc_oauth';
    
    
    /**
     *  查看
     *
     * @param Int $id            
     * @return Array
     */
    public function view ()
    {
        return $this->cache->get($this->_cache_key);
    }

    /**
     * 增加数据
     *
     * @param Array $data            
     * @example accessToken,refreshToken,expires_in
     * @return Int
     */
    public function add (array $data)
    {
        $ttl = $_SERVER['REQUEST_TIME']+$data['expires_in'] ;
        return $this->cache->set($this->_cache_key,$data,$ttl);
    }

    /**
     *  修改数据
     *
     * @param
     *            Int id
     * @param Array $data            
     * @return Int
     */
    public function edit (array $data)
    {
        $ttl = $data['expires_in'] ;
        $this->_cache->delete($this->_cache_key);
        return $this->cache->set($this->_cache_key,$data,$ttl);
    }

    /**
     * 检测是否已经授权(如果存在,检测令牌的时间是否过期)
     * 1:未授权,2:授权了,时间过期了,3:授权了,时间没有过期
     */
    public function checkOauth ()
    {
        $data = $this->cache->get($this->_cache_key);
        if (empty($data) or $data['access_token']==null) {
            return 1;
        } else {
            return 3;
        }
    }
    public function clear(){
        $this->cache->delete($this->_cache_key);
    }
}
