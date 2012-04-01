<?php
define(FRAMEWORK_LOADED, true);
define(APPPATH, __DIR__);
define(EXT, ".php");

require_once("framework/core/SummerSnow.php");

$instance = SummerSnow::getInstance();
