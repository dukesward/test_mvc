<?php

class Kernel_Db_Adapter_Mysqli {

	public static $_isConnected = false;
	protected static $_instance;

	protected $_connection;

	protected $_host;
	protected $_username;
	protected $_password;
	protected $_db;
	protected $_dsn;

	protected function _connect($pdo = false) {
		if(Kernel_Db_Adapter_Mysqli::$_isConnected) {
			return;
		}
		
		if($this->_dsn) {
			$dsn = $this->_dsn;
		}else {

		}

		if($pdo) {
			try {
				$this->_connection = new PDO(
					$dsn,
					$this->_host,
					$this->_username,
					$this->_password,
					$this->_options
				);
			}catch (PDOException $e) {

			}

			Kernel_Db_Adapter_Mysqli::$_isConnected = true;
		}else {
			$this->_connection = mysqli_init();

			if(!$this->_connection->real_connect($this->_host, $this->_username, $this->_password, $this->_db)) {
				$this->closeConnection();
			}

			Kernel_Db_Adapter_Mysqli::$_isConnected = true;
		}
	}

	public function __construct($config) {
		if(Kernel_Db_Adapter_Mysqli::$_instance) {
			return Kernel_Db_Adapter_Mysqli::$_instance;
		}else {
			Kernel_Db_Adapter_Mysqli::$_instance = $this;
		}

		$this->_host = $config['host'];
		$this->_username = $config['username'];
		$this->_password = $config['password'];
		$this->_db = $config['db'];

		if(!Kernel_Db_Adapter_Mysqli::$_isConnected) {
			$this->_connect();
		}
	}

	public function prepare($sql = null, $type = 'SELECT') {
		if(!Kernel_Db_Adapter_Mysqli::$_isConnected) {
			$this->_connect();
		}
		$stmt = new Kernel_Db_Statement_Mysqli($sql, $type);
		return $stmt;
	}

	public function getConnection() {
		if($this->_connection) {
			return $this->_connection;
		}
	}

	public function closeConnection() {
        if (Kernel_Db_Adapter_Mysqli::$_isConnected) {
            $this->_connection->close();
        }
        $this->_connection = null;
    }
	
	public function showTables() {

	}

}