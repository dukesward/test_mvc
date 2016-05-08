<?php

class Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_CoreProcessor';

	public static function getInstance($table = null) {
		if(null === static::$_instance) {
			static::$_instance = new static::$_className($table);
		}
		return static::$_instance;
	}

	public function loadDefaultConfigs() {

	}
}