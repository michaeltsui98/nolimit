<?php

$cacheConfig = array(
    '_cache' => array(
            'adapter' => 'Memcache',
            'servers' => array(
                    'default' => array(
                            'host' => '172.16.0.3',
                            'port' => 8888,
                            'persistent' => true
                    )
            )
    ),
    '_redis' => array(
            'adapter' => 'redis',
            'host' => '172.16.0.5',
            'port' => 6379,
            'persistent' => false
    ),
    '_sessionCache' => array(
            'host' => 'tcp://172.16.0.3:8888',
    ),

);
