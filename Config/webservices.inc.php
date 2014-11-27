<?php

$webServicesConfig = array(
    '_webServicesCircle' => array(
        'adapter' => 'Rest',
        'servers' => array(
            'default' => array(
                'host' => 'http://172.16.0.4',
                'port' => 9080,
                'name' => 'circle',
                'services' => '/Circle/Circle',
                'options' => array(
                    //设置执行超时时间，单位毫秒
                    'timeout' => 15,
                ),
            ),
            'slave' => array(
                'host' => 'http://172.16.0.4',
                'port' => 9081,
                'name' => 'circle',
                'services' => '/Circle/Circle',
                'options' => array(
                    //设置执行超时时间，单位毫秒
                    'timeout' => 15,
                ),
            )
        )
    ),
    '_webServicesNotice' => array(
        'adapter' => 'Hessian',
        //'host' => '172.16.3.7',
        'host' => '172.16.0.4',
        'port' => '8080',
        'name' => 'Notice',
        'services' => '/Notice/noti',
        'options' => array(
            //设置执行超时时间，单位毫秒
            'transportOptions' => array(CURLOPT_TIMEOUT_MS => 20000),
        ),
    ),
    '_webServicesNoticeTest' => array(
        'adapter' => 'Hessian',
        'host' => '172.16.3.7',
        //'host' => '172.16.0.4',
        'port' => '8080',
        'name' => 'Notice',
        'services' => '/Notice/noti',
        'options' => array(
            //设置执行超时时间，单位毫秒
            'transportOptions' => array(CURLOPT_TIMEOUT_MS => 20000),
        ),
    ),
    '_webServicesMail' => array(
        'adapter' => 'Hessian',
        'host' => '172.16.0.4',
        'port' => '8080',
        'name' => 'Notice',
        'services' => '/Notice/mail',
        'options' => array(
            //设置执行超时时间，单位毫秒
            'transportOptions' => array(CURLOPT_TIMEOUT_MS => 20000),
        ),
    ),
    '_webServicesNotify' => array(
        'adapter' => 'Hessian',
        'host' => '172.16.0.4',
        'port' => '8080',
        'name' => 'Notice',
        'services' => '/Notice/notify',
        'options' => array(
            //设置执行超时时间，单位毫秒
            'transportOptions' => array(CURLOPT_TIMEOUT_MS => 20000),
        ),
    ),
);
