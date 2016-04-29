<?php

class Controller_Dispatcher {

	const DEFAULT_CONTROLLER = "IndexController";
	const DEFAULT_ACTION = "index";

	protected static $_instance;
	private $_request;
	private $_response;
	private $_router;

	private function __construct() {
		echo ' start dispatching ';
		$this->getRouter();
	}

	private function getRouter() {
		if(!isset($this->_router)) {
			$this->_router = Kernel_Router::getInstance();
		}
	}

	public function setRequest($request) {
		$this->_request = $request;
	}

	public static function getInstance() {
		if(!(Self::$_instance)) {
			Self::$_instance = new Controller_Dispatcher();
		}
		return Self::$_instance;
	}

	public function dispatch(Kernel_Request $request = null, Kernel_Response $response = null) {
		if(null == $request) {
			$request = new Kernel_Request();
		}
		$this->setRequest($request);

		try {
			try {
				$this->_router->route($this->_request);
			}catch (Exception $e) {
				$this->_response->setException($e);
			}
		}
	}

}