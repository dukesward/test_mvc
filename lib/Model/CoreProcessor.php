<?php

class Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_CoreProcessor';
	protected $_table;

	public static function getInstance($table = null) {
		if(null === static::$_instance) {
			static::$_instance = new static::$_className($table);
		}
		return static::$_instance;
	}

	public function loadDefaultConfigs() {

	}
}