<?php

//域名
define('DOMAIN_NAME', 'http://dev.dodoedu.com');

define('DODOJC', 'http://dev-jc.dodoedu.com');
//静态文件,美工效果图片，CSS文件显示路径
define('HTTP_UI', 'http://dev-images.dodoedu.com/shequPage/');
//分布图片文件
define('HTTP_MFS_IMG', 'http://dev-images.dodoedu.com/image/');
//分布附件文件
define('HTTP_MFS_ATS', 'http://dev-images.dodoedu.com/attachments/');
//分布式网盘文件
define('HTTP_MFS_DISK', 'http://dev-images.dodoedu.com/download.php?');
//分布式文库封面地址
define('HTTP_MFS_WENKU', 'http://dev-images.dodoedu.com/wenku/');
//分布式文库资源地址
define('HTTP_MFS_RESOURCE', 'http://dev-images.dodoedu.com/resource/');
//分布应用图标文件
define('HTTP_APP_IMG', 'http://dev-images.dodoedu.com/');
//文库的地址
define('HTTP_WENKU', 'http://dev-wenku.dodoedu.com/');
//学堂的地址
define('HTTP_XUE', 'http://dev-xue.dodoedu.com/');
//用户系统域名
define('DOMAIN_ACCOUNT', 'http://dev-account.dodoedu.com');
//模板缓存
define('TPL_CACHE', 0);

//后台管理的静态文件地址
define('STATIC_ADMIN_PATH', 'http://dev-images.dodoedu.com/jiaocaiPage/static/');

define('STATIC_WAP_PATH', 'http://dev-images.dodoedu.com/dodoWap/jiaocaiWap/');
//前台静态文件地址

define('STATIC_PATH', 'http://dev-images.dodoedu.com/jiaocaiPage');



/* 站内应用生成的APPID */
define("DD_APPID", '82');
/* APP_KEY */
define("DD_AKEY", 'b8c8320d9c6d35c0b7dc412a44549bbb');
/* APP_SERCET */
define("DD_SKEY", '0049cec872accb28');
/* 回调地址，这个不是已经写过去了么,估计是未授权就跳，但是可能是多此一举 */
define("DD_CALLBACK_URL", DODOJC.'/Sign/callBack');
/* 多多社区API地址 */
define("DD_API_URL", DOMAIN_NAME.'/DDApi/');

//Debug true显示报错信息，false 跳转到404页面
define('DEBUG', TRUE);

$constConfig = array(
    '_resourceType'=>array(
            1=>'教案',2=>"课件",3=>'题库',4=>'素材',5=>'微视频',6=>'观摩课',
            //8=>'备课夹'
    ),
    '_userSex' => array(
        '1' => '男',
        '2' => '女'
    ),
    '_userRole' => array(
        '1' => '学生',
        '2' => '教师',
        '3' => '家长',
        '4' => '教育从业者'
    ),
    '_resourceUrl'=>'http://dev-images.dodoedu.com/resource',

    // 咨询相关配置
    '_infomation' => array(
        'xl' => array(
            // 线上 98056819 | dev 64181726
            'site' => '64181726',
        ),
        'sm' => array(
            // 线上 98056604 | dev 64181726
            'site' => '64181726',
        ),
    ),
);