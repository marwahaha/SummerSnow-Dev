<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

require_once("Common.php");
require_once("Exceptions.php");

require_once("Loader.php");
require_once("Router.php");

require_once("Controller.php");

class SummerSnow {

	public static $instance;
	public $config;
	public $route;
	public $load;

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new SummerSnow();
			self::$instance->bootstrap();
		}

		return self::$instance;
	}

	private function bootstrap() {
		$this->config =& $GLOBALS['config'];

		$url = isset($_GET['url']) ? $_GET['url'] : $_SERVER['REQUEST_URI'];

		$this->route = new Router($url, $this->config['default_controller']);
		$this->load = new Loader();

		$class = $this->route->get_class_name();
		$method = $this->route->get_method_name();

		$this->load->modules($this->config['autoload_modules']);

		$controller = $this->load->controller($class, false);

		if($this->load->validate_method($class, $method)) {
			$controller->$method();
		} else {
			show_404();
		}

	}

}
