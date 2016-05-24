<?php

class Controller_BaseController {

	protected $_action;
	protected $_request;
	protected $_response;
	protected $_config;
	protected $_unableHtmlCache;

	public function __construct(Kernel_Request $request, Kernel_Response $response) {
		$this->_setRequest($request);
		$this->_setResponse($response);
		$this->_init();
	}

	protected function _init() {
		$this->_action = $this->_request->getAction();
		
		try {
			$processor = Controller_Administrator::getModel('nodeProcessor');
		}catch (Exception $e) {
			echo 'Processor not found: '.$e->getMessage();
		}

		if(isset($processor)) {
			$this->_config = $processor->loadNodeConfig($this->_request->getPathInfo());
		}
	}

	protected function indexAction() {
		//all actions are abstract
	}

	protected function _setRequest(Kernel_Request $request) {
		$this->_request = $request;
	}

	protected function _setResponse(Kernel_Response $response) {
		$this->_response = $response;
	}

	public function dispatch() {
		$action = $this->_action;

		if($this->_unableHtmlCache) {

		}else {
			$dataConfig = call_user_func(array($this, $action));
			$content = Template_Engine::prepareContent($this->generateData($dataConfig));
		}
		
		$this->render($content);
	}

	public function generateData($dataConfig) {
		$data = new Template_Config($dataConfig);
		return $data;
	}

	public function render($content) {
		$this->_response->attachContent($content);
	}
}