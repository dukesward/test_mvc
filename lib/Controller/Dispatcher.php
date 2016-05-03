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

	protected function dispatch(Kernel_Request $request) {
		//controllerName should be a className registered
		$controllerName = $request->getControllerName();
		$controller = new $controllerName($request, $this->_response);

		try {
			$controller->dispatch();
		}catch (Exception $e) {
			throw $e;
		}

		$this->_response->attachContent();
		$controller = null;
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

	public function run(Kernel_Request $request = null, Kernel_Response $response = null) {
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

			do {
				$this->_request->setState('dispatched');

				try{
					$this->dispatch();
				}catch (Exception $e) {
					$this->_response->setException($e);
				}

			}while (!$this->_request->isDispatched());
		}catch (Exception $e) {
			$this->_response->setException($e);
		}

		$this->_response->send();
	}

}