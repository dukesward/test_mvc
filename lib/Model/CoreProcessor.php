<?php

class Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_CoreProcessor';
	protected $_default;
	protected $_table;

	public static function getInstance($table = null) {
		if(null === static::$_instance) {
			static::$_instance = new static::$_className($table);
		}
		return static::$_instance;
	}

	public function loadDefaultConfigs() {
		//fetch default config from binded table
		$config = $this->_table->fetchData($this->_default);

		return $config;
	}
}