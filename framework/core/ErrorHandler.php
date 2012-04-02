<?php

error_reporting(E_ALL);
set_exception_handler(array('ErrorHandlerClass', 'ExceptionHandler'));
set_error_handler(array("ErrorHandlerClass", "ErrorHandler"));

class ErrorHandlerClass {

	public static function show_error($message, $code = null, $file = null, $line = null, $errno = null, $type = null){
		if(!is_null($file) && !is_null($line)) {
			$firstLine = 'In file '.str_replace(APPPATH, '', $file).' at line '.$line;
		} else if(!is_null($file) && is_null($line)) {
			$firstLine = 'In file '.str_replace(APPPATH, '', $file);
		} else if(is_null($file) && !is_null($line)) {
			$firstLine = 'At line '.$line;
		}

		echo '<div style="border: 1px solid #990000; padding: 5px 0 5px 20px; margin: 0 0 10px 0; text-align: left;">';
		echo '<strong>A PHP Error was encountered</strong> <br />';
		if(!is_null($message)) echo 'Message: '.$message.'<br />';
		if(isset($firstLine)) echo $firstLine.'<br />';
		if(!is_null($type)) echo '<span style="padding-right:20px;">Type: '.$type.'</span>';
		if(!is_null($errno)) echo '<span style="padding-right:20px;">Severity: '.$errno.'</span>';
		if(!is_null($code)) echo 'Error code: '.$code;
		echo '</div>';
	}

	function ExceptionHandler($e){
		self::show_error($e->getMessage(), $e->getCode(), $e->getLine(), $e->getFile());
	}

	function ErrorHandler($errno, $message, $file, $line) {
		if (!(error_reporting() & $errno)) {
			return;
		}

		switch ($errno) {
			case E_ERROR:
				self::show_error($message, null, $file, $line, $errno, "Error");
				exit(1);
				break;

			case E_WARNING:
				self::show_error($message, null, $file, $line, $errno, "Warning");
				break;

			case E_NOTICE:
				self::show_error($message, null, $file, $line, $errno, "Notice");
				break;

			default:
				self::show_error($message, null, null, null, $errno, "Unidentified");
			break;
		}
		return true;
	}

}

function show_error($message, $code = NULL, $file = NULL, $line = NULL, $errno = NULL, $type = NULL) {
	ErrorHandlerClass::show_error($message, $code, $file, $line, $errno, $type);
}