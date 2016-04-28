<?php

define("BASE_PATH", "\..\\");

class AutoLoader {
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

	private function loadFile($file) {
		$parts = explode('_', $file);
		$path = __DIR__ . BASE_PATH;

		if(isset($this->_namespaces[$parts[0]])) {
			foreach($parts as $i=>$v) {
				$path = $path . '\\' . $v;
			}
			require_once $path . '.php';
		}
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

