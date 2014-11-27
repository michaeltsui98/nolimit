<?php

$dbConfig = array(
    '_db' => array(
        'adapter' => 'Pdo_Mysql',
            'host' => '172.16.0.3',
            'port' => 3306,
            'user' => 'jc',
            'password' => 'jc123456',
            'database' => 'dodojc',
            'charset' => 'utf8',
            'table_prefix'=>'',
            'persitent' => false

    ),

    '_xhprofdb' => array(
        'adapter' => 'Pdo_Mysql',
        'host' => '172.16.0.3',
        'port' => 3306,
        'user' => 'cjsq',
        'password' => '1010',
        'database' => 'cjsq_xhprof',
        'charset' => 'utf8',
        'persistent' => true,
    ),
    '_commentDiscussdb' => array(
        'server' => 'mongodb://172.16.0.4:30000',
        'database' => 'cjsq'
    ),

    '_handlerSocker' => array(
        'host' => '172.16.0.3',
        'port' => 9998,
        'port_wr' => 9999,
        'options' => array()
    )
);
