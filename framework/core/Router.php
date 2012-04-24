<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

class Router {
	public $default_class;
	public $url;
	public $custom_routes;
	public $segments;
	public $params;

	public function __construct($url, $default_class, $custom_routes) {
		$this->default_class = $default_class;
		$this->url = $url;
		$this->custom_routes = $custom_routes;
		$this->_handle_custom_routes();
		$this->_parse_segments();
		$this->_populate_data();
	}

	public function segment($index) {
		$index--;
		return isset($this->segments[$index]) ? $this->segments[$index] : false;
	}

	public function get_class_name() {
		return isset($this->segments[0]) ? $this->segments[0] : $this->default_class;
	}

	public function get_method_name() {
		return isset($this->segments[1]) ? $this->segments[1] : "index";
	}

	private function _handle_custom_routes() {
		if(count($this->custom_routes) == 0) return;
		foreach($this->custom_routes as $find_pattern=>$replace_pattern) {
			$result = preg_replace("/" . $find_pattern . "/", $replace_pattern, $this->url);
			if($result == null) {
				show_error("Error at custom route " . $find_pattern . "<br />" . preg_error_to_text());
			} else {
				$this->url = $result;
			}
		}
	}

	private function _parse_segments() {
		foreach (explode("/", preg_replace("|/*(.+?)/*$|", "\\1", $this->url)) as $val) {
			$val = trim($this->_filter_url($val));

			if ($val != '') {
				$this->segments[] = $val;
			}
		}
	}

	private function _populate_data() {
		if(count($this->segments) == 0) return;
		$this->params = array();
		foreach($this->segments as $segment) {
			if(preg_match("/^(.+)\:(.+)$/", $segment, $out)) {
				$this->params[$out[1]] = $out[2];
			}
		}
	}

	private function _filter_url($str) {
		$permitted_chars = "a-z 0-9~%.:_\-";
		if ($str != '') {
			if (!preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($permitted_chars, '-'))."]+$|i", $str)) {
				show_error('The URI you submitted has disallowed characters.');
			}
		}

		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $str);
	}
}
