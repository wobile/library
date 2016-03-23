<?php

/* 
WO Core
*/

defined('WO_PATH') or define('WO_PATH', __DIR__);

require(WO_PATH . '/WoBase.php');

class Wo extends \Wo\WoBase {}

Wo::$_classMap = require(WO_PATH . '/classes.php');