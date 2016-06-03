<?php

define("BASE_PATH", "\..\\");
define("ROOT_PATH", "\..\..\\");

class Util_AutoLoader {
	private $_namespaces = array(
		'Util' => true,
	);
	private $_base_file_path;
	protected static $_instance;

	protected function __construct() {
		spl_autoload_register(array($this, 'loadFile'));
	}

	public static function autoLoad($class) {
		$self = self::getInstance();
	}

	public static function getInstance() {
		if(null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function loadFile($file, $ext = null) {
		if(!$ext) {
			$parts = explode('_', $file);
			$path = __DIR__ . BASE_PATH;

			if(isset($this->_namespaces[$parts[0]])) {
				foreach($parts as $i=>$v) {
					$path = $path . '\\' . $v;
				}
				require_once $path . '.php';
			}
		}else {
			$path = __DIR__ . ROOT_PATH;
			$ext = '.' . $ext;
			$contents = file_get_contents($path . $file . $ext);
			return $contents;
		}
	}

	private function parseFileContent($content) {
		$key;
		$base = array();
		$output = array();
		$contents = preg_split("/\r\n|\n|\r/", $content);
		
		foreach ($contents as $c) {
			if($c) {
				if($c[0] === "@") {
					$key = substr($c, 1);
					$output['_'.$key] = array();
				}else if($c[0] === "#") {
					$base = array();
				}else if($c[0] === "<") {
					array_pop($base);
				}else {
					$pairs = explode("=", $c);
					
					if(sizeof($pairs) == 2) {
						$_key = implode('', $base) . trim($pairs[0]);
						$_value = trim($pairs[1]);

						$output['_'.$key][$_key] = $_value;
					}else if(sizeof($pairs) == 1) {
						array_push($base, $pairs[0]);
					}
				}
			}
		}
		return $output;
	}

	public function getFileContent($file, $ext, $output = null) {
		if(!$file || !$ext) {
			throw new Exception('File name and extension must be specified');
		}else {
			$content = Util_AutoLoader::loadFile($file, $ext);
		}

		if(is_object($output) && method_exists($output, 'setAttributes')) {
			$content = $output->setAttributes($this->parseFileContent($content));
		}

		return $content;
	}

	public function registerNameSpace($name) {
		if(is_string($name) && !isset($this->_namespaces[$name])) {
			$this->_namespaces[$name] = true;
		}
		return $this;
	}

	protected function unregister() {
		spl_autoload_unregister(array($this, 'autoLoad'));
	}
}

