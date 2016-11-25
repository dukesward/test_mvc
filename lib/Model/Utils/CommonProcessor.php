<?php

class Model_Utils_CommonProcessor extends Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_Utils_CommonProcessor';
	protected static $_db_name = 'use';
	protected static $_db = Kernel_Constants::MODEL_COMMON;
	protected static $_prime = Kernel_Constants::MODEL_COMMON_PRIME;
	protected $_default;
	protected $_table;

	protected function __construct($table = null) {
		//this processor does not support fetch all method
	}

	public function getConfig($config) {
		$this->_configs = array(
			'table' => self::$_db,
			'prime' => self::$_prime,
			'query' => array(
				'where' => array(
					'key' => self::$_prime,
					'value' => $config
				)
			),
			//'debug' => true
		);
		//$this->_default = Kernel_Constants::MODEL_ROUTES_DEFAULT;
		$this->_table = Kernel_Db_Adapter::getDbAdapter(null, self::$_db_name)->getDbConfigTable($this->_configs);
		$value = $this->_table->fetchData();
		return Kernel_Utils::_getArrayElement($value, '0->value');
	}
}