<?php

class Kernel_Db_Adapter {

	const DEFAULT_DB_NAME = 'use';

	protected static $_instance;
	protected $_nameSpace;
	protected $_db_name;
	protected $_db;

	protected function __construct($adapter, $db) {
		$this->_nameSpace = 'Kernel_Db_Adapter_';

		if(!$adapter) {
			$adapter = $this->getDefaultAdapter();
		}

		if(!$db) {
			$this->_db_name = self::DEFAULT_DB_NAME;
		}else {
			$this->_db_name = $db;
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
			'db' => $adapter->getContentAttribute($this->_db_name . '_db_name'),
		);

		$dbAdapter = new $adapterName($config);
		//var_dump($dbAdapter);
		return $dbAdapter;
	}

	protected function handleConnectionError($error) {

	}

	public static function getDbAdapter($adapter = null, $db = null) {
		if(!isset(self::$_instance) || ($db && $db !== self::$_instance->_db_name)) {
			self::$_instance = new Kernel_Db_Adapter($adapter, $db);
		}

		return self::$_instance;
	}

	public function getDbConfigTable($configs = null, $db = null, $type = 'SELECT') {
		$stmt = $this->_db->prepare($db, null, $type);
		$debug = false;

		if($configs) {
			$stmt->resolveTableConfigs($configs);
			if(isset($configs['debug'])) $debug = true;
		}

		$stmt = $stmt->execute($this->_db->getConnection(), $debug);

		return $stmt;
	}
}