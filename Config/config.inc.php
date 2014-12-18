<?php
include 'const.inc.php';
include 'url.inc.php';
include 'db.inc.php';
include 'cache.inc.php';
include 'queue.inc.php';
include 'fs.inc.php';
include 'webservices.inc.php';
include 'xhprof.inc.php';
include 'node.inc.php';
include 'database.php';
$config = array(

    '_search' => array(
            'file' => 'wenku',
            'charset' => 'UTF-8'
    ),
    '_resource_search' => array(
            'file' => 'resource',
            'charset' => 'UTF-8'
    ),
    '_activity_search' => array(
            'file' => 'activity',
            'charset' => 'UTF-8'
    ),
    '_modules'=>array('Admin','Sm','Xl','Wap'),
    '_modelsHome'      => 'Models',
    '_controllersHome' => 'Controllers',
    '_viewsHome'       => 'views',
    '_widgetsHome'     => 'widgets'
);
$config = array_merge($config, $constConfig, $urlConfig, $cacheConfig, $dbConfig, $fsConfig, $queueConfig, $webServicesConfig, $xhprofConfig,$nodeConfig,$ormdbConfig);
