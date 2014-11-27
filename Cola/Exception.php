<?php

class Cola_Exception extends Exception
{
    
 
    public function log ()
    {
        $loger = new Cola_Com_Log_File(Cola::$_config->get('_log'));
        $loger->error($this->getString());
    }

    public function sendMail ()
    {
        $email = new Cola_Com_Email(Cola::$_config->get('_emailQueue'));
        $email::put(Cola::$_config->get('_errorReportMail'), 
                'Exception message', $this->getString(), 'HTML');
    }

    public function debug ()
    {
        if (defined('DEBUG') && FALSE == DEBUG) {
            header("Location: /404.html", true, 302);
            exit();
        } else {
            echo $this->getString();
        }
    }

    public function getString ()
    {
        $string = $this->getFile() . '(' . $this->getLine() . '): ';
        $string .= $this->getMessage() .
                 ($this->getCode() > 0 ? '(S#' . $this->getCode() . ')' : '');
        return $string;
    }
    
 
    public static $php_errors = array(
            E_ERROR => 'Fatal Error',
            E_USER_ERROR => 'User Error',
            E_PARSE => 'Parse Error',
            E_WARNING => 'Warning',
            E_USER_WARNING => 'User Warning',
            E_STRICT => 'Strict',
            E_NOTICE => 'Notice',
            E_RECOVERABLE_ERROR => 'Recoverable Error'
    );

    public static $error_view = '';

    public function __construct ($message, array $variables = NULL, $code = 0)
    {
        if (defined('E_DEPRECATED')) {
            self::$php_errors[E_DEPRECATED] = 'Deprecated';
        }
        $this->code = $code;
        $message = empty($variables) ? $message : strtr($message, $variables);
        parent::__construct($message, (int) $code);
    }

    public function __toString ()
    {
        return self::text($this);
    }

    public static function handler (Exception $e)
    {
        try {
            $type = get_class($e);
            $code = $e->getCode();
            $message = $e->getMessage();
            $file = $e->getFile();
            $line = $e->getLine();
            $trace = $e->getTrace();
            
            $c = Cola::getInstance()->dispatchInfo['controller'];
            
            if($c ==='Controllers_Interface'){
                $array = array('type' => 'error', 'message' => $message ,'data' => array(),'errcode'=>10000,'time'=>time());
                echo json_encode($array);die;
            }
            
            
            
            if ($e instanceof ErrorException) {
                /**
                 * If XDebug is installed, and this is a fatal error,
                 * use XDebug to generate the stack trace
                 */
                
                if (function_exists('xdebug_get_function_stack') and
                         $code == E_ERROR) {
                    $trace = array_slice(
                            array_reverse(xdebug_get_function_stack()), 4);
                    
                    foreach ($trace as & $frame) {
                        /**
                         * XDebug pre 2.1.1 doesn't currently set the call type
                         * key
                         * http://bugs.xdebug.org/view.php?id=695
                         */
                        if (! isset($frame['type'])) {
                            $frame['type'] = '??';
                        }
                        
                        // XDebug also has a different name for the parameters
                        // array
                        if (isset($frame['params']) and ! isset($frame['args'])) {
                            $frame['args'] = $frame['params'];
                        }
                    }
                }
                
                if (isset(self::$php_errors[$code])) {
                    $code = self::$php_errors[$code];
                }
                if (version_compare(PHP_VERSION, '5.3', '<')) {
                    for ($i = count($trace) - 1; $i > 0; -- $i) {
                        if (isset($trace[$i - 1]['args'])) {
                            $trace[$i]['args'] = $trace[$i - 1]['args'];
                            unset($trace[$i - 1]['args']);
                        }
                    }
                }
            }
            
            $error = self::text($e);
            
            /*
             * if(is_object(Keke::$_log)){ Keke::$_log->add(log::ERROR, $error);
             * $strace = self::text($e)."\n--\n" . $e->getTraceAsString();
             * Keke::$_log->add(log::STRACE, $strace); Keke::$_log->write(); }
             */
            $data['type'] = $type;
            $data['code'] = $code;
            $data['message'] = $message;
            $data['file'] = $file;
            $data['line'] = $line;
            
            $vars = array(
                    '_SESSION',
                    '_GET',
                    '_POST',
                    '_FILES',
                    '_COOKIE',
                    '_SERVER'
            );
            
            $data['trace'] = array_reverse(Cola_Exception_Debug::trace($trace));
            
            require COLA_DIR . DIRECTORY_SEPARATOR . 'Exception/ShowError.php';
            die();
        } catch (Exception $e) {
            ob_get_level() and ob_clean();
            echo self::text($e), "\n";
            exit(1);
        }
    }

    public static function text (Exception $e)
    {
        return sprintf('%s [ %s ]: %s ~ %s [ %d ]', get_class($e), 
                $e->getCode(), strip_tags($e->getMessage()), $e->getFile(), 
                $e->getLine());
    }
}