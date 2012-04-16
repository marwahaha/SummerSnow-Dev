<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

class Router {
	public $url;
	public $default_class;
	public $segments;
	public $params;

	public function __construct($url, $default_class) {
		$this->default_class = $default_class;
		$this->url = $url;
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
