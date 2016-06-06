<?php

class Kernel_Request {

	private $_state;
	private $_pathInfo = array();
	private $_routeInfo;

	public function __construct($url = null) {
		if(null !== $url) {
			$this->setRequestUrl($url);
		}else {
			$this->setRequestUrl();
		}
	}

	public function setPathInfo() {
		$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
		$path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);
		$this->_pathInfo = $path;
	}

	public function getPathInfo() {
		if(!$this->_pathInfo) {
			$this->setPathInfo();
		}
		return $this->_pathInfo;
	}

	public function setRouteInfo(Kernel_Core_Route $route) {
		$this->_routeInfo = $route;
	}

	public function setRequestUrl($url = null) {

	}

	public function setState($state) {
		$this->_state = $state;
	}

	public function isDispatched() {
		$isDispatched = $this->_state === 'dispatched';
		return $isDispatched;
	}

	public function getControllerName() {
		$controller = $this->_routeInfo->getControllerName();
		return $controller;
	}

	public function setControllerName() {

	}

	public function getParams() {
		return $this->_routeInfo->getParams();
	}

	public function setParams() {
		
	}

	public function getAction() {
		return $this->_routeInfo->getActionName();
	}
}