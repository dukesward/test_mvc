<?php

class Kernel_Router {

	protected static $_instance;
	protected $_routes = null;
	protected $_processor;
	private $_path;

	private function __construct() {
		//echo ' start routing ';
		if(!isset($this->_processor)) {
			try {
				$this->_processor = Controller_Administrator::getModel('routeProcessor');
			}catch (Exception $e) {
				echo 'RouteProcessor not found: '.$e->getMessage();
			}
		}
		//$this->parseUrl();
	}

	protected function prepareRoutes() {
		//load all route configs with non-null pattern
		$_routes = $this->_processor->loadStandardRouteConfigs();
		$routes = array();

		foreach ($_routes as $pattern => $_route) {
			$routes[$pattern] = new Kernel_Core_Route($_route);
		}

		return $routes;
	}

	protected function setDefaultRoute() {
		$defaultRoutes = $this->_processor->loadDefaultConfigs();
		return $defaultRoutes;
	}

	protected function setExceptionRoute() {
		$ExceptionRoutes = $this->_processor->loadRouteConfigs(Kernel_Constants::MODEL_ROUTES_EXCEPTION);
		return $ExceptionRoutes;
	}

	public function addRoute($name, Kernel_Core_Route $route) {
		$this->_routes[$name] = $route;
	}

	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new Kernel_Router();
		}

		return self::$_instance;
	}

	public function route(Kernel_Request $request) {

		$path = $request->getPathInfo();

		if(null === $this->_routes) {
			$this->_routes = $this->prepareRoutes($request);
		}

		if(!$path) {
			$route = $this->setDefaultRoute();
			$useRoute = new Kernel_Core_Route($route[0]);
		}else {
			foreach (array_reverse($this->_routes, true) as $index => $route) {
				if($route->matchRoute($path)) {
					$useRoute = $route;
					break;
				}
			}
		}

		if(!isset($useRoute)) {
			$useRoute = $this->setExceptionRoute();
		}

		$request->setRouteInfo($useRoute);

		return $request;
	}

}