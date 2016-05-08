<?php

class Kernel_Db_Adapter {

	protected static $_instance;
	protected $_nameSpace;
	protected $_db;

	protected function __construct($adapter = null) {
		$this->_nameSpace = 'Kernel_Db_Adapter_';

		if(!$adapter) {
			$adapter = $this->getDefaultAdapter();
		}

		$this->_db = $this->getDbConfigFromAdapter($adapter);
	}

	protected function getDefaultAdapter() {
		$defaultAdapter = Kernel_Registry_Configs::loadKernelConfig('model', 'default_db_adapter');
		return $defaultAdapter;
	}

	protected function getDbConfigFromAdapter($adapter) {
		if(is_string($adapter)) {
			$adapterName = $this->_nameSpace . ucwords($adapter);
		}else {
			throw new Kernel_Exception_Standard();
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