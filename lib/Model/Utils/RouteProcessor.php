<?php

class Model_RouteProcessor {

	protected $_instance;

	protected function __construct() {

	}

	public static function getInstance() {
		if(null === self::$_instance) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function loadDefaultRoute() {

	}

}