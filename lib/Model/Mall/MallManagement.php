<?php

class Model_Mall_MallManagement {

	private static $_data;

	private static $_db = Kernel_Constants::MODEL_MALL_DB;
	private static $_adapter;
	private static $_config;
	private static $_table_general;
	private static $_table_data;

	public static function startMallManaging() {
		//self::initProductPlates();
		self::_initPreferences();
	}

	protected static function initProductPlates() {

	}

	protected static function _initPreferences() {
		$data = self::_loadData();

		if(sizeof($data) === 0) {
			$configs = self::_loadDataConfigs('basic_pref');
			$data = self::_createBasicData($configs);

			$trainer = self::_loadData('trainer');
			if(sizeof($trainer) === 0) {
				$trainers = self::_createTrainers();
				foreach ($trainers as $name => $trainer) {
					$data = self::_trainBasicPreference($data, self::_createTrainerData($trainer));
				}
			}else {

			}
		}else {

		}
	}

	protected static function _loadData($type = 'basic_pref') {
		if(null === self::$_adapter) {
			self::$_adapter = Kernel_Db_Adapter::getDbAdapter(null, self::$_db);
		}

		$fetch = array(
			'table' => Kernel_Constants::MODEL_MALL_DATA,
			'prime' => Kernel_Constants::MODEL_MALL_DATA_PRIME,
			'query' => array(
				'where' => array(
					'key' => 'type',
					'value' => $type
				)
			)
		);

		self::$_table_data = self::$_adapter->getDbConfigTable($fetch);
		$data = self::$_table_data->fetchData();
		return $data;
	}

	protected static function _loadDataConfigs($type) {
		if(null === self::$_adapter) {
			self::$_adapter = Kernel_Db_Adapter::getDbAdapter(null, self::$_db);
		}

		$adapter = self::$_adapter;
		$to_load = array(
			'table' => Kernel_Constants::MODEL_MALL_GENERAL,
			'prime' => Kernel_Constants::MODEL_MALL_GENERAL_PRIME,
			'query' => array(
				'where' => array(
					'key' => 'collection',
					'value' => $type
				)
			),
			//'debug' => true
		);

		self::$_table_general = $adapter->getDbConfigTable($to_load);
		$data = self::$_table_general->fetchData();
		return $data;
	}

	protected static function _createBasicData($configs) {
		$prime = Kernel_Constants::MODEL_MALL_GENERAL_PRIME;
		$default = array(
			'size' => 8,
			'weight' => 10
		);

		foreach ($configs as $config) {
			$name = Kernel_Utils::_getArrayElement($config, $prime);
			if($name) {
				$default[$name] = $config['value'];
			}
		}
		
		$mapX = Util_DataUtil::generateSequencedData(0, $default['size'], 1);
		$mapY = Util_DataUtil::createRandomData($default['size'], 0, 1, $default['weight']);
		$data = Util_DataUtil::generateMap($mapX, $mapY);
		return $data;
	}

	protected static function _createTrainers() {
		$trainers = self::_loadDataConfigs('trainer');
		$configs = array();

		if(sizeof($trainers) > 0) {
			foreach ($trainers as $conf) {
				if(isset($conf['sub_type'])) {
					$sub = $conf['sub_type'];
					$name = $conf['name'];
					$value = $conf['value'];
					if(!isset($configs[$sub])) {
						$configs[$sub] = array();
					}
					$configs[$sub][$name] = $value;
				}
			}
		}
		return $configs;
	}

	protected static function _createTrainerData($configs) {
		$prime = Kernel_Constants::MODEL_MALL_GENERAL_PRIME;
		$default = array(
			'size' => 5,
			'weight' => 10
		);

		foreach ($configs as $name => $config) {
			$default[$name] = $config;
		}
		
		$mapX = Util_DataUtil::generateSequencedData(0, $default['size'], 1);
		$mapY = Util_DataUtil::createRandomData($default['size'], 0, 1, $default['weight']);
		$data = Util_DataUtil::generateMap($mapX, $mapY);
		return $data;
	}

	protected static function _trainBasicPreference($data, $trainer) {
		$trainer_exp = Util_DataUtil::expandMap($trainer, $data);
		$trained = array();
		$x = $data->getX();
		$y = $data->getY();
		//var_dump($y);
		for($i=0; $i<sizeof($y); $i++) {
			$y_trained = $y[$i] + floor($trainer_exp[$i]);
			array_push($trained, $y_trained);
		}
		//var_dump($trained);
		//die();
		return $trained;
	}
}