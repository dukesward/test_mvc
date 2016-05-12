<?php

class Kernel_Request {

	private $_state;
	private $_pathInfo = array();
	private $_routeInfo = array();

	public function __construct($url = null) {
		if(null !== $url) {
			$this->setRequestUrl($url);
		}else {
			$this->setRequestUrl();
		}
	}

	public function getPathInfo() {
		return $this->_pathInfo;
	}

	public function setRouteInfo($route) {
		if($route && is_array($route)) {
			if(isset($route['controller'])) {
				$this->_routeInfo['controller'] = Kernel_Utils::_assembleController($route['controller']);
			}

			if(isset($route['action'])) {
				$this->_routeInfo['action'] = $route['action'];
			}

			if(isset($route['params'])) {
				$this->_routeInfo['params'] = $route['params'];
			}
		}
	}

	public function setRequestUrl($url = null) {

	}

	public function setState($state) {
		$this->_state = $state;
	}

	public function getControllerName() {
		$controller = null;
		
		if(isset($this->_routeInfo['controller'])) {
			$controller = $this->_routeInfo['controller'];
		}

		return $controller;
	}

	public function setControllerName() {

	}

	public function getParams() {

	}

	public function setParams() {
		
	}

	public function getAction() {

	}
}