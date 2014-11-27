<?php

class Cola_Exception_Debug
{

    public static $charset = 'utf-8';

    public static function vars ()
    {
        if (func_num_args() === 0)
            return;
        $variables = func_get_args();
        $output = array();
        foreach ($variables as $var) {
            $output[] = self::_dump($var, 1024);
        }
        echo '<pre class="debug">' . implode("\n", $output) . '</pre>';
    }

    public static function dump ($value, $length = 128)
    {
        return self::_dump($value, $length);
    }

    protected static function _dump (& $var, $length = 128, $level = 0)
    {
        ob_get_length() > 0 and ob_clean();
        if ($var === NULL) {
            return '<small>NULL</small>';
        } elseif (is_bool($var)) {
            return '<small>bool</small> ' . ($var ? 'TRUE' : 'FALSE');
        } elseif (is_float($var)) {
            return '<small>float</small> ' . $var;
        } elseif (is_resource($var)) {
            if (($type = get_resource_type($var)) === 'stream' and
                     $meta = stream_get_meta_data($var)) {
                $meta = stream_get_meta_data($var);
                if (isset($meta['uri'])) {
                    $file = $meta['uri'];
                    if (function_exists('stream_is_local')) {
                        if (stream_is_local($file)) {
                            $file = self::path($file);
                        }
                    }
                    return '<small>resource</small><span>(' . $type . ')</span> ' .
                             htmlspecialchars($file, ENT_NOQUOTES, 
                                    self::$charset);
                }
            } else {
                return '<small>resource</small><span>(' . $type . ')</span>';
            }
        } elseif (is_string($var)) {
            if (strlen($var) > $length) {
                $str = htmlspecialchars(substr($var, 0, $length), ENT_NOQUOTES, 
                        self::$charset) . '&nbsp;&hellip;';
            } else {
                $str = htmlspecialchars($var, ENT_NOQUOTES, self::$charset);
            }
            return '<small>string</small><span>(' . strlen($var) . ')</span> "' .
                     $str . '"';
        } elseif (is_array($var)) {
            $output = array();
            $space = str_repeat($s = ' ', $level);
            static $marker = null;
            if ($marker === null) {
                $marker = uniqid("\x00");
            }
            if (empty($var)) {} elseif (isset($var[$marker])) {
                $output[] = "(\n$space$s*RECURSION*\n$space)";
            } elseif ($level < 5) {
                $output[] = "<span>(";
                $var[$marker] = TRUE;
                foreach ($var as $key => & $val) {
                    if ($key === $marker)
                        continue;
                    if (! is_int($key)) {
                        $key = '"' .
                                 htmlspecialchars($key, ENT_NOQUOTES, 
                                        self::$charset) . '"';
                    }
                    $output[] = "$space$s$key => " .
                             self::_dump($val, $length, $level + 1);
                }
                unset($var[$marker]);
                $output[] = "$space)</span>";
            } else {
                $output[] = "(\n$space$s...\n$space)";
            }
            return '<small>array</small><span>(' . count($var) . ')</span> ' .
                     implode("\n", $output);
        } elseif (is_object($var)) {
            $array = (array) $var;
            $output = array();
            $space = str_repeat($s = ' ', $level);
            $hash = spl_object_hash($var);
            static $objects = array();
            if (empty($var)) {} elseif (isset($objects[$hash])) {
                $output[] = "{\n$space$s*RECURSION*\n$space}";
            } elseif ($level < 10) {
                $output[] = "<code>{";
                $objects[$hash] = TRUE;
                foreach ($array as $key => & $val) {
                    if ($key[0] === "\x00") {
                        $access = '<small>' .
                                 (($key[1] === '*') ? 'protected' : 'private') .
                                 '</small>';
                        $key = substr($key, strrpos($key, "\x00") + 1);
                    } else {
                        $access = '<small>public</small>';
                    }
                    $output[] = "$space$s$access $key => " .
                             self::_dump($val, $length, $level + 1);
                }
                unset($objects[$hash]);
                $output[] = "$space}</code>";
            } else {
                $output[] = "{\n$space$s...\n$space}";
            }
            return '<small>object</small> <span>' . get_class($var) . '(' .
                     count($array) . ')</span> ' . implode("\n", $output);
        } else {
            
            return '<small>' . gettype($var) . '</small> ' .
                     htmlspecialchars(print_r($var, TRUE), ENT_NOQUOTES, 
                            self::$charset);
        }
    }

    public static function path ($file)
    {
        return $file;
    }

    public static function source ($file, $line_number, $padding = 5)
    {
        if (! $file or ! is_readable($file)) {
            return FALSE;
        }
        $file = fopen($file, 'r');
        $line = 0;
        $range = array(
                'start' => $line_number - $padding,
                'end' => $line_number + $padding
        );
        $format = '% ' . strlen($range['end']) . 'd';
        $source = '';
        while (($row = fgets($file)) !== FALSE) {
            $row = htmlspecialchars(print_r($row, TRUE), ENT_NOQUOTES);
            if (++ $line > $range['end'])
                break;
            if ($line >= $range['start']) {
                $row = '<span class="number">' . sprintf($format, $line) .
                         '</span> ' . $row;
                if ($line === $line_number) {
                    $row = '<span class="line highlight">' . $row . '</span>';
                } else {
                    $row = '<span class="line">' . $row . '</span>';
                }
                $source .= $row;
            }
        }
        fclose($file);
        return '<pre class="source"><code>' . $source . '</code></pre>';
    }

    public static function trace (array $trace = NULL)
    {
        
        if ($trace === NULL) {
            $trace = debug_backtrace();
        }
        $statements = array(
                'include',
                'include_once',
                'require',
                'require_once'
        );
        $output = array();
        
        foreach ($trace as $step) {
            if (! isset($step['function'])) {
                continue;
            }
            if (isset($step['file']) and isset($step['line'])) {
                $source = self::source($step['file'], $step['line']);
            }
            if (isset($step['file'])) {
                $file = $step['file'];
                if (isset($step['line'])) {
                    $line = $step['line'];
                }
            }
            $function = $step['function'];
            if (in_array($step['function'], $statements)) {
                if (empty($step['args'])) {
                    $args = array();
                } else {
                    $args = array(
                            $step['args'][0]
                    );
                }
            } elseif (isset($step['args'])) {
                if (! function_exists($step['function']) or
                         strpos($step['function'], '{closure}') !== FALSE) {
                    $params = NULL;
                } else {
                    if (isset($step['class'])) {
                        if (method_exists($step['class'], $step['function'])) {
                            $reflection = new ReflectionMethod($step['class'], 
                                    $step['function']);
                        } else {
                            $reflection = new ReflectionMethod($step['class'], 
                                    '__call');
                        }
                    } else {
                        $reflection = new ReflectionFunction($step['function']);
                    }
                    $params = $reflection->getParameters();
                }
                $args = array();
                foreach ($step['args'] as $i => $arg) {
                    if (isset($params[$i])) {
                        $args[$params[$i]->name] = $arg;
                    } else {
                        $args[$i] = $arg;
                    }
                }
            }
            if (isset($step['class'])) {
                $function = $step['class'] . $step['type'] . $step['function'];
            }
            $output[] = array(
                    'function' => $function,
                    'args' => isset($args) ? $args : NULL,
                    'file' => isset($file) ? $file : NULL,
                    'line' => isset($line) ? $line : NULL,
                    'source' => isset($source) ? $source : NULL
            );
            unset($function, $args, $file, $line, $source);
        }
        
        return $output;
    }
}
