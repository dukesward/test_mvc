<?php

class Model_Kingdom_Processor_PlayerProcessor extends Model_CoreProcessor {

	const LOC_AVAIL = "player_available";
	const CLS_NAME  = "class_name";

	protected static $_instance;
	protected static $_className = 'Model_Kingdom_Processor_PlayerProcessor';
	protected static $_db_name = 'kingdom';
	protected static $_db_player = 'kingdom_players';
	protected static $_db_location = 'kingdom_locations';
	protected static $_db_lc_class = 'location_classes';
	protected static $_db_lc_race = 'location_races';
	protected static $_prime = 'id';
	protected $_default;
	protected $_table;

	protected function __construct($table = null) {
		//this processor does not support fetch all method
	}

	public function getConfig($config) {
		$this->_configs = array(
			'table' => self::$_db_player,
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
		$this->_table = Kernel_Db_Adapter::getDbAdapter(self::$_db_name)->getDbConfigTable($this->_configs);
		$value = $this->_table->fetchData();
		return Kernel_Utils::_getArrayElement($value, '0');
	}

	public function fetchAll($table) {
		$this->_configs = array(
			'table' => $table,
			//'debug' => true
		);
		//$this->_default = Kernel_Constants::MODEL_ROUTES_DEFAULT;
		$this->_table = Kernel_Db_Adapter::getDbAdapter(null, self::$_db_name)->getDbConfigTable($this->_configs);
		$value = $this->_table->fetchData();
		return $value;
	}

	public function getPlayerCount($name) {
		$varName = 'counts';
		$counts = null;

		$query = array(
			'table' => self::$_db_player,
			'query' => array(
				"as" => $varName
			),
			//"debug" => "1"
		);
		if($name) {
			$query['query']['where'] = array(
				"key" => "name",
				"value" => $name
			);
		}

		$this->_table = Kernel_Db_Adapter::getDbAdapter(null, self::$_db_name)->getDbConfigCount($query);
		$config = $this->_table->fetchData();
		
		if(is_array($config)) {
			$counts = $config[0][$varName];
		}
		return $counts;
	}

	public function getAvailableLocation() {
		$query = array(
			'table' => self::$_db_location,
			'query' => array(
				"where" => array(
					"key" => self::LOC_AVAIL,
					"value" => "T"
				)
			),
			//"debug" => "1"
		);

		$this->_table = Kernel_Db_Adapter::getDbAdapter(null, self::$_db_name)->getDbConfigTable($query);
		$config = $this->_table->fetchData();
		
		return $config;
	}

	public function getLocationAvailClasses($loc) {
		$query = array(
			'table' => self::$_db_lc_class,
			'query' => array(
				"where" => array(
					"key" => "location",
					"value" => $loc
				)
			),
			//"debug" => "1"
		);

		$this->_table = Kernel_Db_Adapter::getDbAdapter(null, self::$_db_name)->getDbConfigTable($query);
		$config = $this->_table->fetchData();
		
		return $config;
	}

	public function getLocationAvailRaces($loc) {
		$query = array(
			'table' => self::$_db_lc_race,
			'query' => array(
				"where" => array(
					"key" => "location",
					"value" => $loc
				)
			),
			//"debug" => "1"
		);

		$this->_table = Kernel_Db_Adapter::getDbAdapter(null, self::$_db_name)->getDbConfigTable($query);
		$config = $this->_table->fetchData();
		
		return $config;
	}

	public function getCustomConfig($query) {
		$this->_table = Kernel_Db_Adapter::getDbAdapter(null, self::$_db_name)->getDbConfigTable($query);
		$config = $this->_table->fetchData();
		
		return $config;
	}

	public function insertData($table, $data) {
		$query = array(
			"table" => $table,
			"query" => array(
				"set" => array()
			),
			//"debug" => "1"
		);

		foreach($data as $key => $val) {
			$query['query']['set'][$key] = $val;
		}
		Kernel_Db_Adapter::getDbAdapter(null, self::$_db_name)->getDbConfigTable($query, null, 'INSERT');
	}

	public function updateData($table, $data, $prime) {
		$query = array(
			"table" => $table,
			"query" => array(
				"set" => array(),
				"where" => array(
					"key" => $prime,
					"value" => $data[$prime]
				)
			),
			//"debug" => "1"
		);

		foreach($data as $key => $val) {
			$query['query']['set'][$key] = $val;
		}
		Kernel_Db_Adapter::getDbAdapter(null, self::$_db_name)->getDbConfigTable($query, null, 'UPDATE');
	}
}