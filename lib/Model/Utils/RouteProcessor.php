<?php

class Model_Utils_RouteProcessor extends Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_Utils_RouteProcessor';
	protected static $_db = Kernel_Constants::MODEL_ROUTES;
	protected $_default;
	protected $_configs;
	protected $_table;

	protected function __construct($table = null) {
		if($table) {
			$this->_table = $table;
		}else {
			$this->_configs = array(
				'table' => Model_Utils_RouteProcessor::$_db,
				'prime' => Kernel_Constants::MODEL_ROUTES_PRIME,
			);

			$this->_default = Kernel_Constants::MODEL_ROUTES_DEFAULT;
			$this->_table = Kernel_Db_Adapter::getDbAdapter()->getDbConfigTable($this->_configs);
		}
	}

	public function loadRouteConfigs($route) {
		$config = null;

		if(is_string($route)) {
			$config = $this->_table->fetchData($route);
		}
		return $config;
	}

}