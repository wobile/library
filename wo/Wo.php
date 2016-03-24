<?php

/* 
WO Core
*/

defined('WO_PATH') or define('WO_PATH', __DIR__);
require(WO_PATH . '/core/Core.php');
class Wo extends \Wo\Core {}
Wo::$_classes = require(WO_PATH . '/io/classes.php');

//Wo::$io = new Wo\Io\Content();