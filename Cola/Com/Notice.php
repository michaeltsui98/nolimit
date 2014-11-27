<?php

class Cola_Com_Notice
{

    /**
     *
     * @param array $config
     * @return object
     */
    public static function porxy($config = array())
    {
        if (empty($config)) $config = Cola::$_config->get('_webServicesNotice');
        return Cola_Com_WebServices::factory($config);
    }

    /**
     * @param array $head 消息头
     * @param array $body 消息体
     * @param array $config 配置
     * @return Boolean
     */
    public static function send(array $head, array $body, $config = array())
    {
        if (empty($config)) $config = Cola::$_config->get('_noticeQueue');
        return Cola_Com_Queue::factory($config)->put(json_encode(array('head' => $head, 'body' => $body)));
    }

}