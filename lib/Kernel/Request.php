<?php

class Kernel_Request {

	private $_state;
	private $_pathInfo = array();
	private $_routeInfo;

	protected function setRequestUrl() {

	}
	
	public function __construct($url = null) {
		if(null !== $url) {
			$this->setRequestUrl($url);
		}else {
			$this->setRequestUrl();
		}
	}

	public function setPathInfo() {
		$url = $_SERVER["REQUEST_URI"];

		$tokens = explode('?', $url);
		$path = trim(parse_url($tokens[0], PHP_URL_PATH), "/");
		$path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);

		if(count($tokens) > 1) {
			$query = $tokens[1];
			$path .= ('?' . $query);
		}

		$this->_pathInfo = $path;
	}

	public function getPathInfo() {
		if(!$this->_pathInfo) {
			$this->setPathInfo();
		}

		$pathInfo = explode('?', $this->_pathInfo)[0];
		//var_dump($this->_routeInfo);
		return $pathInfo;
	}

	public function getFullPathInfo() {
		if(!$this->_pathInfo) {
			$this->setPathInfo();
		}
		return $this->_pathInfo;
	}

	public function setRouteInfo(Kernel_Core_Route $route) {
		$this->_routeInfo = $route;
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

	public function getAction() {
		return $this->_routeInfo->getActionName();
	}

	public function getParams() {
		return $this->_routeInfo->getParams();
	}

	public function getQueryParams() {
		return $this->_routeInfo->getQueryParams();
	}
}