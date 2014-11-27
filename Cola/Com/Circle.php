<?php

class Cola_Com_Circle
{

    /**
     *
     * @param array $config
     * @return object
     */
    public static function porxy($config = array())
    {
        if (empty($config)) $config = Cola::$_config->get('_webServicesCircle');
        return Cola_Com_WebServices::factory($config);
    }

}