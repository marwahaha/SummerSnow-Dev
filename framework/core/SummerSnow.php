<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

require_once("ErrorHandler.php");
require_once("Loader.php");
require_once("Router.php");

class SummerSnow {

	public static $instance;
	public $router;

	public function __construct() {
		$this->getRouter();
		$this->loadController($this->router->get_class_name());
	}

	public static function getInstance() {
		if (!self::$instance) {
			self::$instance = new SummerSnow();
		}

		return self::$instance;
	}

	private function getRouter() {
		$url = isset($_GET['url']) ? $_GET['url'] : $_SERVER['REQUEST_URI'];
		$this->router = new Router($url);
	}

	private function loadController($class) {
		$loader = new Loader();
		echo $class ."<br/>";
		echo $this->router->get_method_name();
		$loader->load_controller($class);
	}

}

function get_instance() {
	return SummerSnow::getInstance();
}

// http://www.talkphp.com/advanced-php-programming/1304-how-use-singleton-design-pattern.html