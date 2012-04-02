<?php
define(FRAMEWORK_LOADED, true);
define(APPPATH, getcwd() . "/");
define(EXT, ".php");

require_once("framework/core/SummerSnow.php");

$instance = SummerSnow::getInstance();
