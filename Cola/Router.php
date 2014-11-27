<?php

/**
 *
 */
class Cola_Router
{

    /**
     * Singleton instance
     *
     * Marked only as protected to allow extension of the class. To extend,
     * simply override {@link getInstance()}.
     *
     * @var Cola_Router
     */
    protected static $_instance = null;
    protected $_enableDynamicMatch = true;
    protected $_dynamicRule = array(
        'defaultController' => 'Controllers_Index',
        'defaultAction' => 'indexAction'
    );

    /**
     * Router rules
     *
     * @var array
     */
    public $_rules = array();

    /**
     * Constructor
     *
     */
    public function __construct()
    {

    }

    /**
     * Singleton instance
     *
     * @return Cola_Router
     */
    public static function getInstance()
    {
        if (null === self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * Get rules
     *
     * @param string $regex
     * @return array
     */
    public function rules($regex = null)
    {
        if (null === $regex) return $this->_rules;
        return isset($this->_rules[$regex]) ? $this->_rules[$regex] : null;
    }

    /**
     * Add rule
     *
     * @param array $rule
     * @param boolean $overwrite
     */
    public function add($rules, $overwrite = true)
    {
        $rules = (array) $rules;
        if ($overwrite) {
            $this->_rules = $rules + $this->_rules;
        } else {
            $this->_rules += $rules;
        }
       
        return $this;
    }

    /**
     * Remove rule
     *
     * @param string $regex
     */
    public function remove($regex)
    {
        unset($this->_rules[$regex]);
        return $this;
    }

    /**
     * Enable or disable dynamic match
     *
     * @param boolean $flag
     * @param array $opts
     * @return Cola_Router
     */
    public function enableDynamicMatch($flag = true, $opts = array())
    {
        $this->_enableDynamicMatch = true;

        $this->_dynamicRule = $opts + $this->_dynamicRule;

        return $this;
    }

    /**
     * Dynamic Match
     *
     * @param string $pathInfo
     * @return array $dispatchInfo
     */
    protected function _dynamicMatch($pathInfo)
    {
        $dispatchInfo = array();
        $tmp = explode('/', $pathInfo);
        $modelus = Cola::getInstance()->config->get('_modules');
        //var_dump($tmp,$pathInfo);
        $modelus = array_flip($modelus);
        $module = ucfirst($tmp[0]);

        if(isset($modelus[$module])){
        	$dispatchInfo['controller'] = 'Modules_'.ucfirst(current($tmp)).'_Controllers_'.ucfirst(next($tmp));
        	$action = next($tmp);
        }else{
	        $controller = current($tmp);
	        if (!empty($controller)) {
	            $dispatchInfo['controller'] = 'Controllers_'.ucfirst($controller) ;
	        } else {
	            $dispatchInfo['controller'] = $this->_dynamicRule['defaultController'];
	        }
	        $action = next($tmp);
        }

      //var_dump($dispatchInfo['controller'],$action);
        //die;
        if (!empty($action)) {
            $dispatchInfo['action'] = $action . 'Action';
        } else {
            $dispatchInfo['action'] = $this->_dynamicRule['defaultAction'];
        }

        $params = array();

        while (false !== ($next = next($tmp))) {
            $params[$next] = urldecode(next($tmp));
        }

        Cola::setReg('_params', $params);

        return $dispatchInfo;
    }

    /**
     * Match path
     *
     * @param string $path
     * @return boolean
     */
    public function match($pathInfo = null)
    {
        $pathInfo = trim($pathInfo, '/');
       
        foreach ($this->_rules as $regex => $rule) {
           
            //var_dump($regex,$pathInfo);
            $res = preg_match($regex, $pathInfo, $values);
            if (0 === $res) continue;

            if (isset($rule['maps']) && count($rule['maps'])) {
                $params = array();

                foreach ($rule['maps'] as $pos => $key) {
                    if (isset($values[$pos]) && '' !== $values[$pos]) {
                        $params[$key] = urldecode($values[$pos]);
                    }
                }

                if (isset($rule['defaults'])) $params += $rule['defaults'];

                Cola::setReg('_params', $params);
            }
            
            return $rule;
        }

        if ($this->_enableDynamicMatch) {
            return $this->_dynamicMatch($pathInfo);
        }

        return false;
    }

}