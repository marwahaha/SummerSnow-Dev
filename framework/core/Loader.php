<?php if(!defined("FRAMEWORK_LOADED")) exit('File access denied!');

class Loader {

	public function controller($name, $populate_namespace) {
		return $this->load_class("components" . "/" . "controllers" . "/" . $name, $name, null, $populate_namespace);
	}

	public function model($model) {
		return $this->models(array($model));
	}

	public function models($models) {
		return $this->load_array($models, "models", "load_class");
	}

	public function view($name, $params) {
		export($params);
		return include(APPPATH . "components" . DS . "models" . DS . $name . EXT);
	}

	public function helper($helper) {
		return $this->helpers(array($helper));
	}

	public function helpers($helpers) {
		return $this->load_array($helpers, "helpers");
	}

	public function module($module) {
		return $this->modules(array($module));
	}

	public function modules($modules) {
		return $this->load_array($modules, "modules", "load_class", true);
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

	private function load_array($array, $component, $method = "include", $is_dir = false) {
		if(count($array) == 0) return true;

		$status = false;

		foreach($array as $element) {
			foreach(array("components" . DS . $component . DS, "framework" . DS . $component . DS) as $dir) {
				if($method == "load_class") {
					if(file_exists(APPPATH . $dir . ($is_dir ? $element . DS : "") . $element . EXT)) {
						$this->load_class($dir . ($is_dir ? $element . DS : "") . $element . EXT, $element);
						$status = true;
						break;
					}

				} else { // default: include

					if(file_exists(APPPATH . $dir . $element . EXT)) {
						include(APPPATH . $dir . $element . EXT);
						$status = true;
						break;
					}
				}
			}
		}

		if(!$status) show_error("Could not load " . $dir . ($is_dir ? $element . "/" : "") . $element . EXT);
			
		return $status;
	}

	private function load_class($class, $object_name, $params = null, $populate_namespace = true) {
		$class = str_replace(EXT, '', trim($class, '/'));

		$subdir = '';
		if (($last_slash = strrpos($class, '/')) !== FALSE) {
			$subdir = substr($class, 0, $last_slash + 1);
			$class = substr($class, $last_slash + 1);
		}

		foreach (array(ucfirst($class), strtolower($class)) as $class) {
			$subclass = APPPATH . $subdir . $class . EXT;

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
		if (!class_exists($class) || !class_exists($object_name)) show_error("Non-existent class: ".$class);

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