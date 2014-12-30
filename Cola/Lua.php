<?php
/**
 * PHP 与 Lua 进行 接口通信
 * @author michael
 */
class Cola_Lua  
{
    protected static $_instance = array();
    
    /**
     * @return self | static
    */
    public static function init()
    {
        $cls = get_called_class();
    
        if (isset(self::$_instance[$cls]) && is_object(self::$_instance[$cls])) {
            return self::$_instance[$cls];
        }
        return self::$_instance[$cls] = new static();
    }
}