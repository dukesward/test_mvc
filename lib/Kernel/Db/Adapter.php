<?php

class Kernel_Db_Adapter {

	protected static $_instance;
	protected $_nameSpace = 'Kernel_Db_Adapter_';
	protected $_db;

	protected function __construct($adapter = null) {
		if(!$adapter) {
			$adapter = $this->getDefaultAdapter();
		}

		$this->_db = $this->getDbAdapterFromAdapter($adapter);
	}

	protected function getDefaultAdapter() {
		$defaultAdapter = Kernel_Registry_Config::loadKernelConfig('model_config', 'default_db_adapter');
		return 
	}

	protected function getDbConfigFromAdapter($adapter) {
		if(is_string($adapter)) {
			$adapterName = $this->$_nameSpace + ucwords($adapter);
		}

		$dbAdapter = new $adapterName();
		return $dbAdapter;
	}

	protected function handleConnectionError($error) {

	}

	public static function getDbConfigs($adapter = null) {
		if(!isset(self::$_instance)) {
			self::$_instance = new Kernel_Db_Adapter($adapter);
		}

		return self::$_instance;
	}
}