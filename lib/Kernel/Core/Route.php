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

			switch(count($tokens)) {
				case 1:
					if($pattern === $this->_pattern) {
						$match = true;
					}
					break;
				default:
					break;
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
}