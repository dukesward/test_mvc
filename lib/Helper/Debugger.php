<?php

class Helper_Debugger {

	protected static $_instance;

	protected function __construct($configs) {
		self::$_instance = $this;
		self::configInstance($configs);
	}

	protected static function configInstance($configs) {

	}

	public function debugPrint($msg, $mode) {

	}

	public static function getInstance($configs = array('log'=>0)) {
		if(!isset(self::$_instance)) {
			self::$_instance = new Helper_Debugger($configs);
		}else {
			self::configInstance($configs);
		}

		return self::$_instance;
	}
}