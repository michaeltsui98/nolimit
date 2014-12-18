<?php
error_reporting ( E_ALL | E_STRICT );
ini_set ( 'display_errors', 'on' );
date_default_timezone_set ( 'Asia/Shanghai' );
mb_internal_encoding ( 'utf-8' );
header ( "Content-Type:text/html;charset=utf-8" );
define('S_ROOT', __DIR__.DIRECTORY_SEPARATOR);
define('BASE_PATH', '/');

define('XHPROF', false);

if (XHPROF) {
    //xhprof_enable();
}


require 'Cola/Cola.php';
$cola = Cola::getInstance ();
//$xh = new Cola_Com_Xhprof ();
//$benchmark = new Cola_Com_Benchmark ();

 
 
    //die('wwewe');
$cola->boot ();

 






$cfg = $cola->getConfig('_sessionCache');
//ini_set('session.cookie_domain', '.dodoedu.com');
//ini_set("session.save_handler", 'memcache');
//ini_set("session.save_path", $cfg['host']); 
session_start();

Cola_Response::charset();

try {
    $cola->dispatch ();
    //性能收集
    if (XHPROF) {
    
        $info = $cola->getDispatchInfo();
        $controllerName = $info['controller'];
        $controllerConfig = Cola::getConfig('_' . $controllerName);
    
        if (isset($controllerConfig['xhprofStat']) && 'on' === $controllerConfig['xhprofStat'] && isset($controllerConfig['rate']) && 1 == mt_rand(1, $controllerConfig['rate'])) {
            echo   $xh->save ();
        } else {
           // xhprof_disable();
        }
    }
    
} catch (Cola_Exception $e) {
    if (FALSE === DEBUG and defined('DEBUG')) {
        Cola_Exception::log($e);
        Cola_Exception::sendMail($e);
        header("Location: /404.html", true, 302);
        exit;
    }
    Cola_Exception::handler($e);
    
} catch (Cola_Exception_Dispatch $e) {
    if (defined('DEBUG') and FALSE === DEBUG) {
        Cola_Exception_Dispatch::log($e);
        Cola_Exception_Dispatch::sendMail($e);
        header("Location: /404.html", true, 302);
        exit;
    }
    Cola_Exception_Dispatch::handler($e);
}
 


//echo "<br />cost:", $benchmark->cost (), 's';
//echo $a = $xh->save ();