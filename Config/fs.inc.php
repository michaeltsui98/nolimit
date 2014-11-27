<?php

$fsConfig = array(
    '_atsUpload' => array(
        'savePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upload',
        'requestUrl' => 'http://dev-images.dodoedu.com/jiaocai/',
        'maxSize' => 209715200,
        'maxWidth' => 0,
        'maxHeight' => 0,
        'override' => 1,
        'mogilefs' => array(
            'domain' => 'jiaocai',
            'class' => 'attachments',
            'trackers' => 'http://172.16.0.5:7777'
        )
    ),
    '_atsDodoUpload' => array(
        'savePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . 'upload',
        'maxSize' => 2097152,
        'maxWidth' => 0,
        'maxHeight' => 0,
        'override' => 1,
        'mogilefs' => array(
            'domain' => 'attachments',
            'class' => 'files',
            'trackers' => 'http://172.16.0.5:7777'
        )
    ),
    '_resourceFs' => array(
        'domain' => 'resource',
        'class' => 'file',
        'trackers' => 'tcp://172.16.0.5:7777'
    ),
);
