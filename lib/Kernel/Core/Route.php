<?php

class Kernel_Core_Route {

	protected $_route;
	protected $_pattern;
	protected $_associative = false;

	public function __construct($route) {
		$this->_route = $route;

		if(isset($route['pattern'])) {
			if(substr($route['pattern'], -1) === '/') {
				$this->_associative = true;
				$this->_pattern = substr($route['pattern'], 0, -1);
			}else {
				$this->_pattern = $route['pattern'];
			}
		}
	}

	public function matchRoute($pattern) {
		$match = false;

		if(!$this->_associative) {
			if($pattern === $this->_pattern) {
				$match = true;
			}
		}else {
			$tokens = explode('/', $pattern);

			if(count($tokens) === 1) {
				if($pattern === $this->_pattern) {
					$match = true;
				}
			}else {
				//var_dump($this->_pattern);
				if(array_shift($tokens) === $this->_pattern) {
					$this->setActionName(array_shift($tokens));
					$this->setParameters($tokens);
					$match = true;
				}
			}
		}

		return $match;
	}

	public function getControllerName() {
		$name = null;

		if(isset($this->_route['controller'])) {
			$_name = $this->_route['controller'];
			$name = Kernel_Constants::KERNEL_ROUTE_CONTROLLER_NAMESPACE . Kernel_Utils::_camelStyleString($_name) . Kernel_Constants::KERNEL_ROUTE_CONTROLLER;
		}
		return $name;
	}

	public function getActionName() {
		$name = null;

		if(isset($this->_route['action'])) {
			$_name = $this->_route['action'];
			$name = $_name . Kernel_Constants::KERNEL_ROUTE_ACTION;
		}
		return $name;
	}

	public function setActionName($action) {
		$this->_route['action'] = $action;
	}

	public function getParams() {
		$params = null;

		if(isset($this->_route['params'])) {
			$params = $this->_route['params'];
		}
		return $params;
	}

	public function setParameters($params) {
		if(!isset($this->_route['params'])) {
			$this->_route['params'] = array();
		}

		if(is_string($params)) {
			array_push($this->_route['params'], $params);
		}else if(is_array($params)) {
			$this->_route['params'] = array_merge($this->_route['params'], $params);
		}
	}
}