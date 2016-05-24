<?php

class Kernel_Router {

	protected static $_instance;
	protected $_routes = array();
	protected $_processor;
	private $_path;

	private function __construct() {
		echo ' start routing ';
		if(!isset($this->_processor)) {
			try {
				$this->_processor = Controller_Administrator::getModel('routeProcessor');
			}catch (Exception $e) {
				echo 'RouteProcessor not found: '.$e->getMessage();
			}
		}
		$this->parseUrl();
	}

	protected function parseUrl() {
		$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
		$path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);

		$this->_path = $path;
	}

	protected function prepareRoutes() {
		$routes = explode('/', $this->_path);
		
		if(is_string($routes[0])) {
			$this->_routes['controller'] = $routes[0];
		}

		if(is_string($routes[1])) {
			$this->_routes['action'] = $routes[1];
		}
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
		if($this->_path) {
			$this->_routes = $this->prepareRoutes();
		}

		if(count($this->_routes) == 0) {
			$useRoute = $this->setDefaultRoute();
		}else {
			foreach (array_reverse($this->_routes, true) as $name => $route) {
				$path = $request->getPathInfo();

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