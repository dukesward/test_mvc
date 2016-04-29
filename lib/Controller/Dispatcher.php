<?php

class Controller_Dispatcher {

	protected static $_instance;

	private function __construct() {
		echo ' start dispatching ';
		$this->parseURL();
	}

	protected function parseURL() {
		$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
		print_r($path);
	}

	public static function getInstance() {
		if(!(Self::$_instance)) {
			Self::$_instance = new Controller_Dispatcher();
		}
		return Self::$_instance;
	}

	public function dispatch() {

	}

	public function useController($controller) {

	}

	public function useAction($action) {

	}

	public function useParams($params) {

	}

}