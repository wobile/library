<?php 

require_once 'common/Helper.php';
require_once 'common/Parameter.php';
require_once 'Gateway/Gateway.php';
require_once 'Gateway/GatewayInterface.php';
require_once 'Gateway/GatewayAbstract.php';

use cPay\Common\Helper;

class cPay
{
	public static $_version = '1.0.0';
	public static $_classes;
	public static $_gateway;

    public static function __callStatic($name, $params)
    {
    	if(false !== $gateway = static::getGateway($name,$params)) { 
    		return $gateway;
    	}
    }

    public static function gateway($name = NULL, $params = array()) { 
    	if(!is_null($name) && !empty($name) && is_string($name) && mb_strlen($name) > 0) { 
    		return static::getGateway($name,$params);
    	} else { 
    		throw new Exception("Gateway is required.",500);
    	}
    }

    public static function getGateway($name = NULL, $params = array()) {
    	$return = false;
        if(!is_null($name) && !empty($name) && is_string($name) && mb_strlen($name) > 0) { 
        	$name = ucfirst(str_replace('.', '', $name));
    		static::__loadClasses();
    		if(array_key_exists($name, static::$_classes)) { 
    			$gateway = static::$_classes[$name];
    			if(isset($gateway['file']) && file_exists($gateway['file'])) { 
    				require_once $gateway['file'];
    				$return = static::$_gateway = new $name($params);
    			}
    		}
    	}
    	if(false === $return) { 
    		throw new Exception("$name is not supported.",500);
    	} else { 
    		return $return;
    	}
    }

    public static function getVersion()
    {
        return static::$_version;
    }

    public static function getInfo() { 
    	// TO-DO : Info result
    	return 'Info !';
    }

    public static function getHelp() { 
    	// TO-DO : Help result
    	return 'Help Me!';
    }

    public static function getSupportedPayments() { 
    	$supported = [];
    	if(count(static::$_classMap)) { 
    		foreach (static::$_classMap as $classKey => $classValue) {
    			$supported[$classKey] = $classValue;
    		}
    		ksort($supported);
    	}
    	return $supported;
    }

    private static function __loadClasses() { 
    	if(is_array(static::$_classes)) { 
    		$gatewayClasses = scandir('Gateway');
    		if(is_array($gatewayClasses) && count($gatewayClasses) > 2) { 
    			$classesMap = [];
    			foreach ($gatewayClasses as $gate) {
    				$gate = ucfirst(str_replace('.', '', $gate));
    				if(!empty($gate) && !is_null($gate)) { 
						$gateFile = 'Gateway/'. $gate .'/'. $gate .'.php';
	    				if(file_exists($gateFile)) { 
	    					$classesMap[$gate] = [
	    						'name' => $gate,
	    						'file' => $gateFile
	    					];
	    				}
    				}
    			}
    			return static::$_classes = $classesMap;
    		}
    	} else { 
    		static::$_classes = [];
    		return static::__loadClasses();
    	}
    }

}