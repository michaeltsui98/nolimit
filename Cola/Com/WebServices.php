<?php

/**
 * Web services
 *
 */
class Cola_Com_WebServices
{

    public static function factory($config)
    {
        $class = 'Cola_Com_WebServices_' . ucfirst($config['adapter']);
        return new $class($config);
    }

}