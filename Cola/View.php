<?php

/**
 * Cola_View
 */
class Cola_View
{

    /**
     * Base path of views
     *
     * @var string
     */
    protected $_basePath = '';

    /**
     * Widgets Home
     *
     * @var string
     */
    protected $_widgetsHome = '';

    /**
     * Cache config
     *
     * @var string|array
     */
    protected $_cacheConfig = '_cache';

    /**
     * Cache object
     *
     * @var Cola_Com_Cache
     */
    protected $_cache = NULL;

    /**
     * tpl layout
     * @var html
     */
    protected $_layout = null;
    /**
     * Constructor
     *
     */
    public function __construct($params = array())
    {
        if (isset($params['basePath'])) {
            $this->_basePath = $params['basePath'];
        }
    }

    /**
     * Set base path of views
     *
     * @param string $path
     */
    public function setBasePath($path)
    {
        $this->_basePath = $path;
    }

    /**
     * Get base path of views
     *
     * @return string
     */
    public function getBasePath()
    {
        return $this->_basePath;
    }
    
    public function setLayout($layout){
    	$this->_layout = $layout;
    }

    /**
     * Render view
     *
     */
    protected function _render($tpl, $dir = NULL)
    {

    }

    /**
     * Fetch
     *
     * @param string $tpl
     * @param string $dir
     * @return string
     */
    public function fetch($tpl, $dir = NULL)
    {
        ob_start();
        ob_implicit_flush(0);
        $this->display($tpl, $dir);
        return ob_get_clean();
    }

    /**
     * Display
     *
     * @param string $tpl
     * @param string $dir
     */
    public function display($tpl, $dir = NULL)
    {
        NULL === $dir && $dir = $this->_basePath;
        $dir = rtrim($dir, '/\\') . DIRECTORY_SEPARATOR;
        include ($dir . $tpl);
    }

    /**
     * Slot
     *
     * @param string $tpl
     * @param mixed $data
     * @return string
     */
    public function slot($file, $data = NULL)
    {
        ob_start();
        ob_implicit_flush(0);
        include $file;
        return ob_get_clean();
    }

    /**
     * 第三方模板解析
     * @example 使用方法1:$this->view->tpl('test'); //加载/views/test.htm
     * @example  使用方法2:$this->view->tpl('test','layout1'); //加载/views/test.htm,并加载父模板 /views/layout1.htm
     * @param string $file  模板文件
     * @param string $layout  模板父文件
     */
    public function tpl($file=NULL, $layout = NULL, $isReturn = FALSE)
    {
        ob_start();
        ob_implicit_flush(0);
        NULL === $layout and $layout = $this->_layout;
        NULL === $file and $file = $this->defaultTpl();
        $view = (array) $this;
        $view['_tpl'] = $file;
        $view['_dir'] = $layout;
        //var_dump($file,$layout);
        extract($view);
        if (Cola_Request::isAjax()) {
            include Cola_Tpl::template($file);
        } else {
            if ($layout !== null and $layout) {
                include Cola_Tpl::template($layout);
            } else {
                include Cola_Tpl::template($file);
            }
        }

        if ($isReturn) {
            return ob_get_clean();
        }
        echo ob_get_clean();
    }
	
    public  function defaultTpl(){
    	$cola = Cola::getInstance();
    	$dispatchInfo = $cola->getDispatchInfo();
    	$controller = strtr($dispatchInfo['controller'],array('Controllers_'=>'','controllers_'=>''));
    	$controller = strtr($controller,array('_'=>'/'));
    	$action  = strtr($dispatchInfo['action'], array('Action'=>''));
    	$controller_arr = explode('_', $dispatchInfo['controller']);
    	$tmp = current($controller_arr);

    	//var_dump($controller_arr);die;
    	if($tmp=='Modules'){
    	    $key = array_search('Controllers', $controller_arr);
    	    $controller_arr[$key] = 'Views';
    	    $uri = implode('/', $controller_arr);
    	    //$url =  current($controller_arr).'/'.next($controller_arr).'/Views/'.end($controller_arr).'/'.$action.'.htm';
    		//var_dump($url,$uri);die;
    		return  $uri.'/'.$action.'.htm';
    		//return $url;
    	}
    	 
    	return $controller.'/'.$action;
    }
    
    /**
     * Set widgets home dir
     *
     * @param string $dir
     * @return Cola_View
     */
    public function setWidgetsHome($dir)
    {
        $this->_widgetsHome = $dir;
        return $this;
    }

    /**
     * Get widgets Home
     *
     * @return string
     */
    public function getWidgetsHome()
    {
        return $this->_widgetsHome;
    }

    /**
     * Widget
     *
     * @param string $name
     * @param array $data
     * @return Cola_Exception
     */
    public function widget($name, $data = NULL)
    {
        if (empty($this->_widgetsHome) && $widgetsHome = Cola::$_config->get('_widgetsHome')) {
            $this->_widgetsHome = $widgetsHome;
        }

        $class = ucfirst($name) . 'Widget';

        if (!Cola::loadClass($class, $this->_widgetsHome)) {
            throw new Cola_Exception("Can not find widget:{$class}");
        }

        $widget = new $class($data);

        return $widget;
    }

    /**
     * Escape
     *
     * @param string $str
     * @param string $type
     * @param string $encoding
     * @return string
     */
    public static function escape($str, $type = 'html', $encoding = 'UTF-8')
    {
        switch ($type) {
            case 'html':
                return htmlspecialchars($str, ENT_QUOTES, $encoding);

            case 'htmlall':
                return htmlentities($str, ENT_QUOTES, $encoding);

            case 'javascript':
                return strtr($str, array('\\' => '\\\\', "'" => "\\'", '"' => '\\"', "\r" => '\\r', "\n" => '\\n', '</' => '<\/'));

            case 'mail':
                return str_replace(array('@', '.'), array(' [AT] ', ' [DOT] '), $str);

            default:
                return $str;
        }
    }

    /**
     * Truncate
     *
     * @param string $str
     * @param int $limit
     * @param string $encoding
     * @param string $suffix
     * @param string $regex
     * @return string
     */
    public static function truncate($str, $limit, $encoding = 'UTF-8', $suffix = '...', $regex = NULL)
    {
        if (function_exists('mb_strwidth')) {
            return self::mbTruncate($str, $limit, $encoding, $suffix);
        }
        return self::regexTruncate($str, $limit, $encoding, $suffix, $regex = NULL);
    }

    /**
     * Truncate with mbstring
     *
     * @param string $str
     * @param int $limit
     * @param string $encoding
     * @param string $suffix
     * @return string
     */
    public static function mbTruncate($str, $limit, $encoding = 'UTF-8', $suffix = '...')
    {
        if (mb_strwidth($str, $encoding) <= $limit) {
            return $str;
        }

        $limit -= mb_strwidth($suffix, $encoding);
        $tmp = mb_strimwidth($str, 0, $limit, '', $encoding);
        return $tmp . $suffix;
    }

    /**
     * Truncate with regex
     *
     * @param string $str
     * @param int $limit
     * @param string $encoding
     * @param string $suffix
     * @param string $regex
     * @return string
     */
    public static function regexTruncate($str, $limit, $encoding = 'UTF-8', $suffix = '...', $regex = NULL)
    {
        $defaultRegex = array(
            'UTF-8' => "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/",
            'GB2312' => "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/",
            'GBK' => "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/",
            'BIG5' => "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/"
        );

        $encoding = strtoupper($encoding);

        if (NULL === $regex && !isset($defaultRegex[$encoding])) {
            throw new Cola_Exception("Truncate failed: not supported encoding, you should supply a regex for $encoding encoding");
        }

        $regex || $regex = $defaultRegex[$encoding];

        preg_match_all($regex, $str, $match);

        $trueLimit = $limit - strlen($suffix);
        $len = $pos = 0;

        foreach ($match[0] as $word) {
            $len += strlen($word) > 1 ? 2 : 1;
            if ($len > $trueLimit) {
                continue;
            }
            $pos++;
        }
        if ($len <= $limit) {
            return $str;
        }
        return join("", array_slice($match[0], 0, $pos)) . $suffix;
    }

    /**
     * Dynamic set vars
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value = NULL)
    {
        $this->$key = $value;
    }

    /**
     * get images from mogilefs
     *
     * @param string $filename
     * @param string $type
     * @param string $size
     * @param string $icon
     * @return string
     */
    public static function getImage($filename, $type, $size, $icon = '')
    {
        //获取默认图标
        if (empty($filename) || !preg_match('/([^\>\s]{1,255})\.(jpg|gif|png)/i', $filename)) {
            return ($icon == 'icon') ? HTTP_UI . "common/image/def-{$type}-{$size}.gif" : HTTP_UI . "common/image/{$type}-{$size}.gif";
        }

        // 默认缓存图片路径
        if (NULL === $this->_cache) {
            is_string($this->_cacheConfig) && $this->_cacheConfig = Cola::$_config->get($this->_cacheConfig);
            $this->_cache = Cola_Com_Cache::factory($this->_cacheConfig);
        }
        $key = md5($filename . $type . $size . $icon);
        $data = $this->_cache->get($key);
        if (!$data) {
            //获取远程图片
            $n = pathinfo($filename);
            $config = Cola::$_config->get('_imageUpload');
            $mfs = new Cola_Com_Mogilefs($config['mogilefs']['domain'], $config['mogilefs']['class'], $config['mogilefs']['trackers']);
            $filename = $n['filename'] . '-' . $size . '.' . $n['extension'];

            if ($mfs->exists($filename)) {
                $data = HTTP_MFS_IMG . $filename;
                //图片存在缓存24小时
                $this->_cache->set($key, $data, 60 * 60 * 24);
                return $data;
            } else {
                //是用户头像返回性别图标
                $data = ('icon' == $icon && in_array($n['filename'], array('info-b', 'info-g'))) ?
                        HTTP_UI . "common/image/{$n['filename']}-{$size}.gif" : HTTP_UI . "common/image/{$type}-{$size}.gif";
                //图片不存在缓存30分钟
                $this->_cache->set($key, $data, 60 * 60 * 30);
                return $data;
            }
        }

        return $data;
    }

    /**
     * Check and filter HTML
     * @param string $html
     * @return string
     */
    public static function checkhtml($html)
    {
        preg_match_all('/\<([^\<]+)\>/is', stripslashes($html), $ms);
        $searchs[] = '<';
        $replaces[] = '&lt;';
        $searchs[] = '>';
        $replaces[] = '&gt;';
        if ($ms[1]) {
//            $allowtags = 'img|a|font|div|table|tbody|caption|tr|td|th|br|p|b|i|u|ol|ul|li|blockquote|object|param|embed';
            $allowtags = '';
            $ms[1] = array_unique($ms[1]);
            foreach ($ms[1] as $value) {
                $searchs[] = '&lt;' . $value . '&gt;';
                $value = str_replace(array('\\', '/*'), array('.', '/.'), self::shtmlspecialchars($value));
                $skipkeys = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate',
                    'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange',
                    'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick',
                    'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate',
                    'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete',
                    'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel',
                    'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart',
                    'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop',
                    'onsubmit', 'onunload', 'javascript', 'script', 'eval', 'behaviour', 'expression', 'style', 'class');
                $skipstr = implode('|', $skipkeys);
                $value = preg_replace(array("/({$skipstr})/i"), '.', $value);
                if (!preg_match("/^[\/|\s]?($allowtags)(\s+|$)/is", $value)) {
                    $value = '';
                }
                $replaces[] = empty($value) ? '' : "<" . str_replace('&quot;', '"', $value) . ">";
            }
        }
        $html = str_replace($searchs, $replaces, $html);
        $html = addslashes($html);
        return $html;
    }

    /**
     *
     * @param mixed $string
     * @return string
     */
    public static function shtmlspecialchars($string)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = self::shtmlspecialchars($val);
            }
        } else {
            $string = preg_replace('/&amp;((#(\d{3,5}|x[a-fA-F0-9]{4})|[a-zA-Z][a-z0-9]{2,5});)/', '&\\1', str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $string));
        }
        return $string;
    }

    /**
     * Dynamic get vars
     *
     * @param string $key
     */
    public function __get($key)
    {
        switch ($key) {
            case 'config':
                $this->config = Cola::$_config;
                return $this->config;

            default:
                return NULL;
        }
    }

}
