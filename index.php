<?php
define("FRAMEWORK_LOADED", true);
define("APPPATH", str_replace("\\", "/", getcwd()) . "/");
define("EXT", ".php");

require_once("framework/core/SummerSnow.php");

$config['default_controller'] = "test";
$config['autoload_modules'] = array();

SummerSnow::getInstance();
