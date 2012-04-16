<?php
abstract class Controller {
	
	public $config;
	public $route;
	public $load;
	
	public function __construct() {
		$ss =& get_instance();
		$this->config =& $GLOBALS['config'];
		$this->route =& $ss->route;
		$this->load =& $ss->load;
	}
	
}