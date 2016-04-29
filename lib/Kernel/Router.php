<?php

class Kernel_Router {

	protected static $_instance;
	private $_path;

	private function __construct() {
		echo ' start routing ';
		$this->parseUrl();
	}

	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new Kernel_Router();
		}

		return self::$_instance;
	}

	public function route($request) {
		@list($controller, $action, $params) = explode("/", $this->_path, 3);
		
		if(isset($controller)) {
			$this->useController($controller);
		}

		if(isset($action)) {
			$this->useAction($action);
		}

		if(isset($params)) {
			$this->useParams(explode("/", $params));
		}

		return $request;
	}

	protected function parseUrl() {
		$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
		$path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);

		$this->_path = $path;
	}

	public function useController($controller) {

	}

	public function useAction($action) {

	}

	public function useParams($params) {

	}

}