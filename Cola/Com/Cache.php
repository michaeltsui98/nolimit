<?php

/**
 *
 */
class Cola_Com_Cache
{

    public static function factory($config)
    {
        $class = 'Cola_Com_Cache_' . ucfirst($config['adapter']);
        return new $class($config);
    }

}