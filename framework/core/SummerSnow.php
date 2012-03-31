<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

require_once("Loader.php");
require_once("Router.php");

class SummerSnow {

	public static $router;

	public function __construct() {
		getRouter();
		loadController(self::$router->getClassName());
	}

	private function getRouter() {
		$url = $_GET['url'];
		self::$router = new Router($url);
	}

	private function loadController($class) {
		$loader = new Loader();
		$loader->loadController($class);
	}

}

// http://www.talkphp.com/advanced-php-programming/1304-how-use-singleton-design-pattern.html