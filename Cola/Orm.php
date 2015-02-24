<?php
 //orm init
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Events\Dispatcher;


require COLA_DIR.DIRECTORY_SEPARATOR.'Illuminate/support/helpers.php';

$capsule = new Capsule;
$connect = Cola::getConfig('database');

$capsule->addConnection($connect['connections']['mysql']);


// Set the event dispatcher used by Eloquent models... (optional)

$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();
 
// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent(); 



/**
 * 
 * @author michael
 *
 */
abstract class Cola_Orm extends Eloquent 
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