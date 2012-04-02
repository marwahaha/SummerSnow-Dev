<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

class Router {
	public $url;
	public $default_class = "test";
	public $segments;

	public function __construct($url) {
		$this->url = $url;
		$this->_parse_segments();
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

	private function _filter_url($str) {
		$permitted_chars = "a-z 0-9~%.:_\-";
		if ($str != '') {
			if (!preg_match("|^[".str_replace(array('\\-', '\-'), '-', preg_quote($permitted_chars, '-'))."]+$|i", $str)) {
				die(show_error('The URI you submitted has disallowed characters.', 400));
			}
		}

		$bad	= array('$',		'(',		')',		'%28',		'%29');
		$good	= array('&#36;',	'&#40;',	'&#41;',	'&#40;',	'&#41;');

		return str_replace($bad, $good, $str);
	}
}
