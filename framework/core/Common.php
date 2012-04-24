<?php

function &get_instance() {
	$instance =& SummerSnow::getInstance();
	return $instance;
}


function show_error($message) {
	
	Exceptions::show_error($message);
}

function show_404() {
	
	Exceptions::show_404();
}

function show_system_error($title, $heading, $message, $code) {
	
	Exceptions::show_system_error($title, $heading, $message, $code);
}

function preg_error_to_text() {
	if (preg_last_error() == PREG_NO_ERROR) {
		return 'There is no error.';
	}
	else if (preg_last_error() == PREG_INTERNAL_ERROR) {
		return 'There is an internal error!';
	}
	else if (preg_last_error() == PREG_BACKTRACK_LIMIT_ERROR) {
		return 'Backtrack limit was exhausted!';
	}
	else if (preg_last_error() == PREG_RECURSION_LIMIT_ERROR) {
		return 'Recursion limit was exhausted!';
	}
	else if (preg_last_error() == PREG_BAD_UTF8_ERROR) {
		return 'Bad UTF8 error!';
	}
	else if (preg_last_error() == PREG_BAD_UTF8_ERROR) {
		return 'Bad UTF8 offset error!';
	}
}

function set_status_header($code = 200) {
	$status = array(
			200	=> 'OK',
			201	=> 'Created',
			202	=> 'Accepted',
			203	=> 'Non-Authoritative Information',
			204	=> 'No Content',
			205	=> 'Reset Content',
			206	=> 'Partial Content',

			300	=> 'Multiple Choices',
			301	=> 'Moved Permanently',
			302	=> 'Found',
			304	=> 'Not Modified',
			305	=> 'Use Proxy',
			307	=> 'Temporary Redirect',

			400	=> 'Bad Request',
			401	=> 'Unauthorized',
			403	=> 'Forbidden',
			404	=> 'Not Found',
			405	=> 'Method Not Allowed',
			406	=> 'Not Acceptable',
			407	=> 'Proxy Authentication Required',
			408	=> 'Request Timeout',
			409	=> 'Conflict',
			410	=> 'Gone',
			411	=> 'Length Required',
			412	=> 'Precondition Failed',
			413	=> 'Request Entity Too Large',
			414	=> 'Request-URI Too Long',
			415	=> 'Unsupported Media Type',
			416	=> 'Requested Range Not Satisfiable',
			417	=> 'Expectation Failed',

			500	=> 'Internal Server Error',
			501	=> 'Not Implemented',
			502	=> 'Bad Gateway',
			503	=> 'Service Unavailable',
			504	=> 'Gateway Timeout',
			505	=> 'HTTP Version Not Supported'
	);

	if(!isset($status[$code])) show_error("Invalid status code!");
	$text = $status[$code];

	$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

	if (substr(php_sapi_name(), 0, 3) == 'cgi') {
		header("Status: {$code} {$text}", TRUE);
	} elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0') {
		header($server_protocol." {$code} {$text}", TRUE, $code);
	} else {
		header("HTTP/1.1 {$code} {$text}", TRUE, $code);
	}
}