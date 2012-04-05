<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

require_once("ErrorHandler.php");
require_once("Loader.php");
require_once("Router.php");

require_once("Controller.php");

class SummerSnow {

	public static $instance;
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
		$url = isset($_GET['url']) ? $_GET['url'] : $_SERVER['REQUEST_URI'];

		$this->route = new Router($url, $config['default_controller']);
		$this->load = new Loader();

		$class = $this->route->get_class_name();
		$method = $this->route->get_method_name();

		$this->load->modules(array()); // TODO: autoload

		$controller = $this->load->controller($class, false);

		if($this->load->validate_method($class, $method)) {
			$controller->$method();
		} else {
			show_error("404");
		}

	}

}

function &get_instance() {
	return SummerSnow::getInstance();
}
