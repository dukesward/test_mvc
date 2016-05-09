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
		$defaultAdapter = Kernel_Registry_Configs::loadKernelConfig('model');
		return $defaultAdapter;
	}

	protected function getDbConfigFromAdapter($adapter) {
		if(is_string($name = $adapter->getContentAttribute('default_db_adapter'))) {
			$adapterName = $this->_nameSpace . ucwords($name);
		}else {
			throw new Kernel_Exception_Standard();
		}

		$config = array(
			'host' => $adapter->getContentAttribute('use_db_host'),
			'username' => $adapter->getContentAttribute('use_db_username'),
			'password' => $adapter->getContentAttribute('use_db_password'),
			'db' => $adapter->getContentAttribute('use_db_name'),
		);

		$dbAdapter = new $adapterName($config);
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