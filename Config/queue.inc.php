<?php

$queueConfig = array(
    '_noticeQueue' => array(
        'adapter' => 'Httpsqs',
        'host' => '172.16.0.5',
        'port' => '1212',
        'name' => 'notice',
    ),
 
    '_emailQueue' => array(
        'adapter' => 'Httpsqs',
        'host' => '172.16.0.5',
        'port' => '1212',
        'name' => 'email'
    ),
    '_videoQueue' => array(
        'adapter' => 'Httpsqs',
        'host' => '172.16.0.5',
        'port' => '1212',
        'name' => 'video'
    ),
 
    //系统通知
    '_notifyQueue' => array(
        'adapter' => 'Httpsqs',
        'host' => '172.16.0.5',
        'port' => '1212',
        'name' => 'notify'
    ),
    '_sms' => array(
        'host' => '172.16.0.5',
        'port' => '1212',
        'name' => 'testq',
        'appId' => 1
    ),
     
);
