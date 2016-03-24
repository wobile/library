<?php 

namespace wo\Payment;

class Payment { 

	public static function __callStatic($methodName, $params) { 
		echo $methodName;
	}

}