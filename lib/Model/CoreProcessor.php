<?php

class Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_CoreProcessor';
	protected static $_db_name;
	protected static $_db;
	protected static $_prime;
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
		$query = array(
			'prime' => $this->_default,
		);
		$config = $this->_table->fetchData($query);

		return $config;
	}

	public function getTableCount($db_name, $db) {
		$query = array(
			'table' => $db,
			'query' => array(
				"as" => "counts"
			),
			"debug" => "1"
		);

		$this->_table = Kernel_Db_Adapter::getDbAdapter($db_name)->getDbConfigCount($query);
		$config = $this->_table->fetchData();
		return $config;
	}
}