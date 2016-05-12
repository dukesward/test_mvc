<?php

class Controller_BaseController {

	protected $_request;
	protected $_response;

	public function __construct(Kernel_Request $request, Kernel_Response $response) {
		$this->_setRequest($request);
		$this->_setResponse($response);
		$this->_init();
	}

	protected function _init() {

	}

	protected function _setRequest(Kernel_Request $request) {
		$this->_request = $request;
	}

	protected function _setResponse(Kernel_Response $response) {
		$this->_response = $response;
	}

	public function dispatch() {

	}
}