<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

class Loader {

	public function load_controller($name) {
		$this->load_class("controllers/" . $name, $name);
	}

	public function load_model($name) {
		$this->load_class("models/" . $name, $name);
	}

	private function load_class($class, $object_name, $params = null) {
		$class = str_replace(EXT, '', trim($class, '/'));

		$subdir = '';
		if (($last_slash = strrpos($class, '/')) !== FALSE) {
			$subdir = substr($class, 0, $last_slash + 1);
			$class = substr($class, $last_slash + 1);
		}

		foreach (array(ucfirst($class), strtolower($class)) as $class) {
			$subclass = APPPATH.'components/'.$subdir.$class.EXT;

			if (file_exists($subclass)) {
				include_once($subclass);

				return $this->really_load_class($class, "", $params, $object_name);
			}
		}

		if ($subdir == '') {
			$path = strtolower($class).'/'.$class;
			return $this->really_load_class($path, $params);
		}
	}


	private function really_load_class($class, $prefix = '', $config = FALSE, $object_name = NULL) {
		if (!class_exists($class) || !class_exists($object_name)) {
			die(show_error("Non-existent class: ".$class));
		}

		$class = strtolower($class);

		if (is_null($object_name)) {
			$classvar = $class;
		} else {
			$classvar = $object_name;
		}

		$ss =& get_instance();
		if ($config !== NULL) {
			$ss->$classvar = new $class($config);
		} else {
			$ss->$classvar = new $class;
		}

	}
}