<?php

class Model_Utils_RouteProcessor {

	protected static $_instance;

	protected function __construct() {

	}

	public static function getInstance() {
		if(null === self::$_instance) {
			self::$_instance = new Model_Utils_RouteProcessor();
		}
		return self::$_instance;
	}

	public function loadDefaultRoute() {

	}

}