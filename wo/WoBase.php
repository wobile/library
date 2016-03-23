<?php 

/* 
WO Base 
*/

namespace Wo;

use Wo\Exception\UnknownClassException;

class WoBase
{
	public static $_version = '1.0.0';
	public static $_classMap = [];

    public static function __callStatic($method, $parameters)
    {
    	echo $method;
    }

    public static function getVersion()
    {
        return static::$_version;
    }

    public static function getSupported() { 
    	$supported = [];
    	if(count(static::$_classMap)) { 
    		foreach (static::$_classMap as $classKey => $classValue) {
    			$supported[$classKey] = $classValue;
    		}
    		ksort($supported);
    	}
    	return $supported;
    }

}