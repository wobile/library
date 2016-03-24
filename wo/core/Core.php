<?php 

namespace Wo;

class Core
{
	public static $_version = '1.0.0';
	public static $_classes;
    public static $content;
    public static $component; 
    public static $io;

    public static function __callStatic($methodName, $params)
    {
        if(isset($methodName) && !empty($methodName) && !is_null($methodName) && is_string($methodName) && mb_strlen($methodName) > 0) { 
            $methodName = ucfirst($methodName);
        }
    }

    public static function component($className, $config = NULL) { 
    	if(isset($className) && !empty($className) && !is_null($className) && is_string($className) && mb_strlen($className) > 0) { 
            $className = ucfirst($className);
            if(isset(static::$_classes[$className]) && isset(static::$_classes[$className]['file']) && file_exists(static::$_classes[$className]['file'])) { 
            	$classFile = static::$_classes[$className]['file'];
            	include($classFile);
            	return new $className;
            }
        }
    }

    public static function getInfo() { }

    public static function getCredits() { }

    public static function getHelp() { }

    public static function getUsage() { }

    public static function getVersion() { return static::$_version; }

    public static function getSupported() { 
    	$supported = [];
    	if(count(static::$_classes)) { 
    		foreach (static::$_classes as $classKey => $classValue) {
    			$supported[$classKey] = $classValue;
    		}
    		ksort($supported);
    	}
    	return $supported;
    }

}