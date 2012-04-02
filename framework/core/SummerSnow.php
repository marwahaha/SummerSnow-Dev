<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

require_once("ErrorHandler.php");
require_once("Loader.php");
require_once("Router.php");

class SummerSnow {

	public static $instance;
	public $router;

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new SummerSnow();
			self::$instance->bootstrap();
		}

		return self::$instance;
	}

	private function bootstrap() {
		$url = isset($_GET['url']) ? $_GET['url'] : $_SERVER['REQUEST_URI'];
		$this->router = new Router($url);

		$loader = new Loader();
		$class = $this->router->get_class_name();
		$method = $this->router->get_method_name();
		$loader->load_controller($class);
		$this->$class->$method();
	}

}

function get_instance() {
	return SummerSnow::getInstance();
}
