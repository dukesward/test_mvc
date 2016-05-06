<?php

class Kernel_Router {

	protected static $_instance;
	protected $_routes = array();
	private $_path;

	private function __construct() {
		echo ' start routing ';
		$this->parseUrl();
	}

	protected function parseUrl() {
		$path = trim(parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH), "/");
		$path = preg_replace('/[^a-zA-Z0-9]\//', "", $path);

		$this->_path = $path;
	}

	protected function setDefaultRoute() {
		$defaultRoutes = $routeProcessor->loadDefaultRoutes();
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
		$useRoute = $this->setDefaultRoute();

		foreach (array_reverse($this->_routes, true) as $name => $route) {
			$path = $request.getPathInfo();
			if($route->matchRoute($path)) {
				$useRoute = $route;
				break;
			}
		}

		$request->setRouteInfo($useRoute);

		return $request;
	}

}