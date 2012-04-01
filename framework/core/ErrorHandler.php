<?php

error_reporting(E_ALL);
set_exception_handler(array('ErrorHandlerClass', 'ExceptionHandler'));
set_error_handler(array("ErrorHandlerClass", "ErrorHandler"));

class ErrorHandlerClass {

	public static function show_error($message, $code = NULL, $line = NULL, $file = NULL, $errno = NULL, $type = NULL){
		$url = (!empty($_SERVER['HTTPS'])) ? "https://".$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']
		: "http://".$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

		$ifcode = (is_null($code)) ? '' : '<b>Код на грешката: </b>'.$code;
		$iferrno = (is_null($errno)) ? '' : '<b>Ниво на грешката: </b>'.$errno;
		$ifline = (is_null($line)) ? '' : '<b>Ред на грешката: </b>'.$line.'<br>';
		$iffile = (is_null($file)) ? '' : '<b>Повреден файл: </b>'.$file.'<br>';
		$iftype = (is_null($type)) ? '' : '<br>'.$type;
		echo '<div id="Exception" style="background-color: #FFFFFF; border:15px #00aeff double;
			padding-bottom: 20px; margin: 0 50px 20px 50px;
			border-radius: 25px; z-index: 1000; color: #000;">
			<h1 align="center"><u>Бъгнах се!</u>'.$iftype.'</h1>
			<img src="images/bug_buddy.png" align="left" width="128" height="128" alt="Бъгнах се!" 
			style=" margin:-50px 0 0 90px;;position: absolute; z-index: 10000;" />
			<p style="text-align: left; margin-left: 350px;">
			<b>Съобщение:</b> '.$message.'<br />'.$ifline.$iffile.$ifcode.
			'<br><div style="text-align:center;">
			<a href="'.$url.'">Рефреш</a></div></p></div>';
	}

	function ExceptionHandler($e){
		self::show_error($e->getMessage(), $e->getCode(), $e->getLine(), $e->getFile());
	}

	function ErrorHandler($errno, $errstr, $errfile, $errline) {
		if (!(error_reporting() & $errno)) {
			return;
		}
		switch ($errno) {
			case E_ERROR:
				self::show_error($errstr, null, $errline, $errfile, $errno, "Error");
				exit(1);
				break;

			case E_WARNING:
				self::show_error($errstr, null, $errline, $errfile, $errno, "Warning");
				break;

			case E_NOTICE:
				self::show_error($errstr, null, $errline, $errfile, $errno, "Notice");
				break;

			default:
				self::show_error($errstr, null, null, null, $errno, "Unidentified");
			break;
		}
		return true;
	}
}

function show_error($message, $code = NULL, $line = NULL, $file = NULL, $errno = NULL, $type = NULL) {
	ErrorHandlerClass::show_error($message, $code, $line, $file, $errno, $type);
}