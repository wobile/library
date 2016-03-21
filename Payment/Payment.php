
<?php
require_once 'Gateway/Factory.php';

use Payment\Gateway\Factory as Factory;

class Payment
{
    private static $factory;

    public static function getFactory()
    {
        if (is_null(static::$factory)) {
            static::$factory = new Factory;
        }
        return static::$factory;
    }

    public static function setFactory(Factory $factory = null)
    {
        static::$factory = $factory;
    }

    public static function __callStatic($method, $parameters)
    {
        $factory = static::getFactory();
        return call_user_func_array(array($factory, $method), $parameters);
    }
}
