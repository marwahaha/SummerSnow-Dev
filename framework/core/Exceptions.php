<?php
error_reporting(E_ALL);

set_error_handler(array("Exceptions", "ErrorHandler"));
set_exception_handler(array("Exceptions", "ExceptionHandler"));
register_shutdown_function(array("Exceptions", "ShutdownHandler"));

class Exceptions {
	
	public static $levels = array(
			E_ERROR				=>	'Error',
			E_WARNING			=>	'Warning',
			E_PARSE				=>	'Parsing Error',
			E_NOTICE			=>	'Notice',
			E_CORE_ERROR		=>	'Core Error',
			E_CORE_WARNING		=>	'Core Warning',
			E_COMPILE_ERROR		=>	'Compile Error',
			E_COMPILE_WARNING	=>	'Compile Warning',
			E_USER_ERROR		=>	'User Error',
			E_USER_WARNING		=>	'User Warning',
			E_USER_NOTICE		=>	'User Notice',
			E_STRICT			=>	'Runtime Notice'
	);

	public static function show_php_error($message, $file = null, $line = null, $severity = null){
	
		if(!is_null($file) && !is_null($line)) {
			$firstLine = 'In file '.str_replace(APPPATH, '', str_replace("\\", "/", $file)).' at line '.$line;
		} else if(!is_null($file) && is_null($line)) {
			$firstLine = 'In file '.str_replace(APPPATH, '', str_replace("\\", "/", $file));
		} else if(is_null($file) && !is_null($line)) {
			$firstLine = 'At line '.$line;
		}

		echo '<div style="border: 1px solid #990000; padding: 5px 0 5px 20px; margin: 0 0 10px 0; text-align: left;">';
		echo '<strong>A PHP Error was encountered</strong> <br />';
		if(!is_null($message)) echo 'Message: '.$message.'<br />';
		if(isset($firstLine)) echo $firstLine.'<br />';
		if(!is_null($severity)) echo '<span style="padding-right:20px;">Severity: '.$severity.'</span>';
		echo '</div>';
	}

	public static function show_error($message, $heading = "An Error Was Encountered") {
		
		$title = "Error";
		@ob_end_clean();
		include(APPPATH.'framework/templates/user_error.php');
		exit;
	}
		
	public static function show_404() {
	
		$title = "404 Page Not Found";
		
		$heading = "404";
		$message = "The page was not found.";
	
		@ob_end_clean();
		set_status_header(404);
		include(APPPATH.'framework/templates/system_error.php');
		exit;
	}
	
	public static function show_system_error($title, $heading, $message, $code) {
	
		@ob_end_clean();
		set_status_header($code);
		include(APPPATH.'framework/templates/system_error.php');
		exit;
	}
	
	public static function ErrorHandler($severity, $message, $file, $line) {
		if (!(error_reporting() & $severity)) return;

		$severity = (!isset(self::$levels[$severity])) ? $severity : self::$levels[$severity];

		self::show_php_error($message, $file, $line, $severity);

		return true;
	}

	public static function ExceptionHandler($e) {
		self::show_php_error($e->getMessage(), $e->getLine(), $e->getFile(), $e->getCode());
	}

	public static function ShutdownHandler() {
		$error = error_get_last();

		if($error !== NULL)
			self::show_php_error($error['message'], ($error['file'] == "Unknown") ? null : $error['file'], ($error['line'] == 0) ? null : $error['line'], (!isset(self::$levels[$error['type']])) ? $error['type'] : self::$levels[$error['type']]);
	}
	
}