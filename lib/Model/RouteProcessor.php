<?php

class Model_RouteProcessor {

	protected $_instance;
	protected $_table;

	protected function __construct($table = null) {
		if($table) {
			$this->_table = $table;
		}else {
			$this->_table = Kernel_Db_Adapter::getDbConfigs();
		}
	}

	public static function getInstance($table = null) {
		if(null === self::$_instance) {
			self::$_instance = new self($table);
		}
		return self::$_instance;
	}

	public function loadDefaultRoute() {

	}

}