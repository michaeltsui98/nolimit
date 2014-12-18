<?php

/**
 * Define
 */
defined('COLA_DIR') || define('COLA_DIR', dirname(__FILE__));
require COLA_DIR . '/Config.php';

class Cola
{
    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Cola
     */
    protected static $_instance = null;

    /**
     * Object register
     *
     * @var array
     */
    public $reg = array();

    /**
     * Run time config
     *
     * @var Cola_Config
     */
    public $config;

    /**
     * Router
     *
     * @var Cola_Router
     */
    public $router;

    /**
     * Path info
     *
     * @var string
     */
    public $pathInfo;

    /**
     * Dispathc info
     *
     * @var array
     */
    public $dispatchInfo;

    /**
     * Constructor
     *
     */
    protected function __construct()
    {
        $this->config = new Cola_Config(array(
            '_class' => array(
                'Cola_Model'               => COLA_DIR . '/Model.php',
                //'Orm_Database'               => COLA_DIR . '/Orm/Database.php',
                'Cola_View'                => COLA_DIR . '/View.php',
                'Cola_Controller'          => COLA_DIR . '/Controller.php',
                'Cola_Router'              => COLA_DIR . '/Router.php',
                'Cola_Request'             => COLA_DIR . '/Request.php',
                'Cola_Response'            => COLA_DIR . '/Response.php',
                'Cola_Ext_Validate'        => COLA_DIR . '/Ext/Validate.php',
                'Cola_Exception'           => COLA_DIR . '/Exception.php',
                'Cola_Exception_Dispatch'  => COLA_DIR . '/Exception/Dispatch.php',
                'Cola_Com'                 => COLA_DIR . '/Com.php',
                'Cola_Com_Widget'          => COLA_DIR . '/Com/Widget.php',
                'Cola_Exception'           => COLA_DIR . '/Exception.php'
            ),
        ));

        Cola::registerAutoload();
        Cola::registerAutoload('Cola::loadNameSapce');
    }

    /**
     * Bootstrap
     *
     * @param mixed $arg string as a file and array as config
     * @return Cola
     */
    public static function boot($config = 'config.inc.php')
    {
        
        set_error_handler(array('Cola','error_handler'));
        
        $config_file =  dirname(COLA_DIR).'/Config/'.$config;
        if (is_string($config) && file_exists($config_file)) {
            require $config_file;
        }

        if (!is_array($config)) {
            throw new Exception('Boot config must be an array or a php config file with variable $config');
        }
         
        self::getInstance()->config->merge($config);
        return self::$_instance;
    }

    /**
     * Singleton instance
     *
     * @return Cola
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }
    
    public static function error_handler($code, $error, $file = NULL, $line = NULL) {
    
        if ($code != 8 ) {
            ob_get_level () and ob_clean ();
            Cola_Exception::handler ( new ErrorException ( $error, $code, 0, $file, $line ) );
        }
        return TRUE;
    }
    /**
     * Set Config
     *
     * @param string $name
     * @param mixed $value
     * @param string $delimiter
     * @return Cola
     */
    public static function setConfig($name, $value, $delimiter = '.')
    {
        self::getInstance()->config->set($name, $value, $delimiter);
        return self::$_instance;
    }

    /**
     * Get Config
     *
     * @return Cola_Config
     */
    public static function getConfig($name, $default = null, $delimiter = '.')
    {
        return self::getInstance()->config->get($name, $default, $delimiter);
    }
    
    public static function config(){
        return self::getInstance()->config;
    }

    /**
     * Set Registry
     *
     * @param string $name
     * @param mixed $obj
     * @return Cola
     */
    public static function setReg($name, $obj)
    {
        self::getInstance()->reg[$name] = $obj;
        return self::$_instance;
    }

    /**
     * Get Registry
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public static function getReg($name, $default = null)
    {
        $instance = self::getInstance();
        return isset($instance->reg[$name]) ? $instance->reg[$name] : $default;
    }

    /**
     * Common factory pattern constructor
     *
     * @param string $type
     * @param array $config
     * @return Object
     */
    public static function factory($type, $config)
    {
        $adapter = $config['adapter'];
        $class = $type . '_' . ucfirst($adapter);
        return new $class($config);
    }
    public  static function loadNameSapce($classNmae){
       // var_dump(COLA_DIR.DIRECTORY_SEPARATOR.$classNmae);die;
        require COLA_DIR.DIRECTORY_SEPARATOR.$classNmae . '.php';
    }
    /**
     * Load class
     *
     * @param string $className
     * @param string $classFile
     * @return boolean
     */
    public static function loadClass($className, $classFile = '')
    {
        if (class_exists($className, false) || interface_exists($className, false)) {
            return true;
        }

        if ((!$classFile)) {
            $key = "_class.{$className}";
            $classFile = self::getConfig($key);
        }

        /**
         * auto load Cola class
         */
       /*  if ((!$classFile) && ('Cola' === substr($className, 0, 4))) {
            $classFile = dirname(COLA_DIR) . DIRECTORY_SEPARATOR
                       . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
        }
 */
        /**
         * auto load controller class
         */
        /* if ((!$classFile) && ('Controller' === substr($className, -10))) {
            $classFile = self::getConfig('_controllersHome') . "/{$className}.php";
        } */

        /**
         * auto load model class
         */
        /* if ((!$classFile) && ('Model' === substr($className, -5))) {
            $classFile = self::getConfig('_modelsHome') . "/{$className}.php";
        } */
        
        //var_dump($className);
        $classFile = strtr($className,array('_'=>DIRECTORY_SEPARATOR)) . '.php';
        //var_dump($classFile,get_included_files());
        if (file_exists($classFile)) {
            include $classFile;
        }
        return true;
        //return (class_exists($className, false) || interface_exists($className, false));
    }

    /**
     * User define class path
     *
     * @param array $classPath
     * @return Cola
     */
    public static function setClassPath($class, $path = '')
    {
        if (!is_array($class)) {
            $class = array($class => $path);
        }

        self::getInstance()->config->merge(array('_class' => $class));

        return self::$_instance;
    }

    /**
     * Register autoload function
     *
     * @param string $func
     * @param boolean $enable
     * @return Cola
     */
    public static function registerAutoload($func = 'Cola::loadClass', $enable = true)
    {
        $enable ? spl_autoload_register($func) : spl_autoload_unregister($func);
        return self::$_instance;
    }

    /**
     * Get dispatch info
     *
     * @param boolean $init
     * @return array
     */
    public function getDispatchInfo($init = false)
    {
        if ((null === $this->dispatchInfo) && $init) {
            $this->router || ($this->router = new Cola_Router());
            $urls = self::getConfig('_urls');
            if ($urls) {
                $this->router->_rules += $urls;
            }

           // var_dump($this->router->_rules);
            $this->pathInfo || $this->pathInfo = (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '');

            $this->dispatchInfo = $this->router->match($this->pathInfo);
        }

        return $this->dispatchInfo;
    }

    /**
     * Dispatch
     *
     */
    public function dispatch()
    {
        if (!$dispatchInfo = $this->getDispatchInfo(true)) {
            throw new Cola_Exception_Dispatch('No dispatch info found');
        }
        
        if (isset($dispatchInfo['file'])) {
            if (!file_exists($dispatchInfo['file'])) {
                throw new Cola_Exception_Dispatch("Can't find dispatch file:{$dispatchInfo['file']}");
            }
            require  $dispatchInfo['file'];
        }

        if (isset($dispatchInfo['controller']) ) {
           // $classFile = self::getConfig('_controllersHome') . "/{$dispatchInfo['controller']}.php";
          
            /* if (!self::loadClass($dispatchInfo['controller'], $classFile)) {
                throw new Cola_Exception_Dispatch("Can't load controller:{$dispatchInfo['controller']}");
            } */
            //var_dump( $dispatchInfo['controller']);
            
                $controller = new $dispatchInfo['controller']();
             
            //var_dump($controller);die;
        }

        if (isset($dispatchInfo['action'])) {
            $func = isset($controller) ? array($controller, $dispatchInfo['action']) : $dispatchInfo['action'];
            if (!is_callable($func, true)) {
                throw new Cola_Exception_Dispatch("Can't dispatch action:{$dispatchInfo['action']}");
            }
            //var_dump($func);
            call_user_func($func);
        }
    }
}

 