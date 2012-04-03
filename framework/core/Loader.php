<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

class Loader {
	public function controller($name, $populate_namespace) {
		return $this->load_class("controllers/" . $name, $name, null, $populate_namespace);
	}

	public function model($name) {
		$this->load_class("models/" . $name, $name);
	}

	public function init_modules() {
		if ($handle = opendir(APPPATH . "framework/modules/")) {
			while (false !== ($entry = readdir($handle))) {
				if (end(explode(".", $entry)) == "php") {
					$this->load_class(substr($entry, 0, strrpos($entry, '.')));
				}
			}
			closedir($handle);
		}
	}

	public function validate_method($class, $method) {
		if(!class_exists($class)) return false;

		$array1 = get_class_methods($class);
		if($parent_class = get_parent_class($class)){
			$array2 = get_class_methods($parent_class);
			$array3 = array_diff($array1, $array2);
		} else {
			$array3 = $array1;
		}

		return in_array(strtolower($method), array_map('strtolower', $array3));
	}

	private function load_class($class, $object_name, $params = null, $populate_namespace = true) {
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

				return $this->really_load_class($class, "", $params, $object_name, $populate_namespace);
			}
		}

		if ($subdir == '') {
			$path = strtolower($class).'/'.$class;
			return $this->really_load_class($path, "", $params, null, $populate_namespace);
		}
	}


	private function really_load_class($class, $prefix = '', $config = FALSE, $object_name = NULL, $populate_namespace = true) {
		if (!class_exists($class) || !class_exists($object_name)) {
			die(show_error("Non-existent class: ".$class));
		}

		$class = strtolower($class);

		if (is_null($object_name)) {
			$classvar = $class;
		} else {
			$classvar = $object_name;
		}

		if($populate_namespace) {
			$ss =& get_instance();
			if ($config !== NULL) {
				$ss->$classvar = new $class($config);
			} else {
				$ss->$classvar = new $class;
			}
		} else {
			if ($config !== NULL) {
				return new $class($config);
			} else {
				return new $class;
			}
		}

	}
}