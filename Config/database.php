<?php

$ormdbConfig =
array('database'=>
  array(
/*
|--------------------------------------------------------------------------
| PDO 类型
|--------------------------------------------------------------------------
| 默认情况下 Laravel 的数据库是用 PDO 来操作的，这样能极大化的提高数据库兼容性。
| 那么默认查询返回的类型是一个对象，也就是如下的默认设置。
| 如果你需要返回的是一个数组，你可以设置成 'PDO::FETCH_ASSOC'
*/
        'fetch' => PDO::FETCH_CLASS,

        /*
         |--------------------------------------------------------------------------
| 默认的数据库连接名
|--------------------------------------------------------------------------
| 这里所说的名字是和下面的 'connections' 中的名称对应的，而不是指你用的什么数据库
| 为了你更好的理解，我在这里换了一个名字
*/
        'default' => 'mysql',

        /*
         |--------------------------------------------------------------------------
| 数据库连接名
|--------------------------------------------------------------------------
| 这里就是设置各种数据库的配置的，每个数组里的 'driver' 表明了你要用的数据库类型
| 同一种数据库类型可以设置多种配置，名字区分开就行，就像下面的 'mysql' 和 'meinv'
| 其他的么，我觉得不需要解释了吧，就是字面意思，我相信你英文的能力（其实是我英文不好）
*/
        'connections' => array(

                'sqlite' => array(
                        'driver'   => 'sqlite',
                        'database' => __DIR__.'/../database/production.sqlite',
                        'prefix'   => '',
                ),

                'mysql' => array(
                        'driver'    => 'mysql',
                        'read' => array(
                                'host' => 'localhost',
                        ),
                        'write' => array(
                                'host' => 'localhost'
                        ),
                        //'host'      => 'localhost',
                        'database'  => 'df',
                        'username'  => 'root',
                        'password'  => '123456',
                        'charset'   => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'prefix'    => '',
                ),

                'dev' => array( //这里就是上面例子里的默认连接数据库名，实际上是 mysql 数据库
                        'driver'    => 'mysql',
                        'read' => array(
                                'host' => '172.16.0.3',
                        ),
                        'write' => array(
                                'host' => '172.16.0.3'
                        ),
                        'database'  => 'dodowenku',
                        'username'  => 'wenku',
                        'password'  => 'wenku',
                        'charset'   => 'utf8',
                        'collation' => 'utf8_unicode_ci',
                        'prefix'    => '',
                ),

                'pgsql' => array(
                        'driver'   => 'pgsql',
                        'host'     => 'localhost',
                        'database' => 'database',
                        'username' => 'root',
                        'password' => '',
                        'charset'  => 'utf8',
                        'prefix'   => '',
                        'schema'   => 'public',
                ),

                'sqlsrv' => array(
                        'driver'   => 'sqlsrv',
                        'host'     => 'localhost',
                        'database' => 'database',
                        'username' => 'root',
                        'password' => '',
                        'prefix'   => '',
                ),

        ),
)
  );
