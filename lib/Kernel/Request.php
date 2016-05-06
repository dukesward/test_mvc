<?php

class Kernel_Request {

	private $_state;
	private $_pathInfo = array();

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

	public function setRouteInfo() {

	}

	public function setRequestUrl($url = null) {

	}

	public function setState($state) {
		$this->_state = $state;
	}

	public function getControllerName() {

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