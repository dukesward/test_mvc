<?php

class Model_Kingdom_Config_Common {

	private $_common;
	private $_player;
	private $_location;
	private static $_instance;

	public static function getInstance() {
		if(null === self::$_instance) {
			self::$_instance = new Model_Kingdom_Config_Common();
		}
		return self::$_instance;
	}

	protected function __construct() {
		$this->_common = Controller_Administrator::getModel('commonProcessor');
		$this->_player = Model_Kingdom_Processor_PlayerProcessor::getInstance();
	}

	public function getPlayerCount($name = null) {
		$playerCount = $this->_player->getPlayerCount($name);
		return $playerCount;
	}

	public function getPlayer($id) {
		return $this->_player->getConfig($id);
	}

	public function getDebugPlayer() {
		$query = array(
			'table' => 'kingdom_players',
			'query' => array(
				'where' => array(
					'key' => 'debug',
					'value' => 'T'
				)
			)
		);
		return $this->_player->getCustomConfig($query)[0];
	}

	public function getPlayerAttrs($name) {
		$query = array(
			'table' => 'player_attributes',
			'query' => array(
				'where' => array(
					'key' => 'player_name',
					'value' => $name
				)
			)
		);
		return $this->_player->getCustomConfig($query);
	}

	public function getPlayerEquips($name) {
		$query = array(
			'table' => 'player_equips',
			'query' => array(
				'where' => array(
					'key' => 'player_name',
					'value' => $name
				)
			)
		);
		return $this->_player->getCustomConfig($query);
	}

	public function getPlayerItems($name) {
		$query = array(
			'table' => 'player_items',
			'query' => array(
				'where' => array(
					'key' => 'player_name',
					'value' => $name
				)
			)
		);
		return $this->_player->getCustomConfig($query);
	}

	public function getEnemyByLocation($loc) {
		$query = array(
			'table' => 'location_enemy',
			'query' => array(
				"where_n" => array(
					"location" => $loc,
					"active"   => "T"
				)
			),
			//"debug" => "1"
		);
		return $this->_player->getCustomConfig($query);
	}

	public function getEnemy($name) {
		$query = array(
			'table' => 'enemies',
			'query' => array(
				'where' => array(
					'key' => 'name',
					'value' => $name
				)
			)
		);
		return $this->_player->getCustomConfig($query)[0];
	}

	public function getEnemyLoot($e, $troop) {
		$query = array(
			'table' => 'enemy_loot',
			'query' => array(
				"where_n" => array(
					"enemy_name" => $e,
					"troop"      => $troop
				)
			),
			//"debug" => "1"
		);
		return $this->_player->getCustomConfig($query)[0];
	}

	public function getWorldConfig() {
		return $this->_player->fetchAll('world_settings');
	}

	public function getPlayerEvents() {
		$query = array(
			'table' => 'player_events',
			'query' => array(
				"where_n" => array(
					"is_active" => 'T',
				)
			),
			//"debug" => "1"
		);
		return $this->_player->getCustomConfig($query);
	}

	public function getActiveAnnc() {
		$query = array(
			'table' => 'events',
			'query' => array(
				'where' => array(
					'key' => 'active',
					'value' => 'T'
				)
			)
		);
		return $this->_player->getCustomConfig($query);
	}

	public function getLocation($name) {
		$query = array(
			'table' => 'kingdom_locations',
			'query' => array(
				'where' => array(
					'key' => 'brief',
					'value' => $name
				)
			),
			//"debug" => true
		);
		$locations = $this->_player->getCustomConfig($query);
		return $locations[0];
	}

	public function getItemById($id) {
		$query = array(
			'table' => 'items',
			'query' => array(
				'where' => array(
					'key' => 'id',
					'value' => $id
				)
			)
		);
		$items = $this->_player->getCustomConfig($query);
		return $items[0];
	}

	public function getAvailableLocation() {
		return $this->_player->getAvailableLocation();
	}

	public function getLocationAvailClasses($loc) {
		return $this->_player->getLocationAvailClasses($loc);
	}

	public function getLocationAvailRaces($loc) {
		return $this->_player->getLocationAvailRaces($loc);
	}

	public function getConfig($key) {
		return $this->_common->getConfig($key);
	}

	public function getClassPropConfig($class) {
		$query = array(
			'table' => 'class_properties',
			'query' => array(
				"where" => array(
					"key" => "class_name",
					"value" => $class
				)
			),
			//"debug" => "1"
		);
		$data = $this->_player->getCustomConfig($query);
		return $data[0];
	}

	public function getRacePropConfig($class, $gender) {
		$query = array(
			'table' => 'race_properties',
			'query' => array(
				"where_n" => array(
					"race_name" => $class,
					"gender" => $gender
				)
			),
			//"debug" => "1"
		);
		$data = $this->_player->getCustomConfig($query);
		return $data[0];
	}

	public function getEquipConfig($class) {
		$query = array(
			'table' => 'class_equip_config',
			'query' => array(
				"where" => array(
					"key" => "class_name",
					"value" => $class
				)
			),
			//"debug" => "1"
		);
		$data = $this->_player->getCustomConfig($query);
		return $data;
	}

	public function getEquipById($id) {
		$query = array(
			'table' => 'equipments',
			'query' => array(
				"where" => array(
					"key" => "id",
					"value" => $id
				)
			),
			//"debug" => "1"
		);
		$data = $this->_player->getCustomConfig($query);
		return $data[0];
	}

	public function getEquipObject($name) {
		$query = array(
			'table' => 'equipments',
			'query' => array(
				"where" => array(
					"key" => "name",
					"value" => $name
				)
			),
			//"debug" => "1"
		);
		$data = $this->_player->getCustomConfig($query);
		return $data[0];
	}

	public function getClassAbilityConfig($class_name, $level = 1) {
		$query = array(
			'table' => 'class_ability',
			'query' => array(
				"where_n" => array(
					"class_name" => $class_name,
					"level_min" => $level
				)
			),
			//"debug" => "1"
		);
		$data = $this->_player->getCustomConfig($query);
		return $data;
	}

	public function getAbilityObject($name) {
		$query = array(
			'table' => 'abilities',
			'query' => array(
				"where" => array(
					"key" => "ability_name",
					"value" => $name
				)
			),
			//"debug" => "1"
		);
		$data = $this->_player->getCustomConfig($query);
		return $data[0];
	}

	public function postNewPlayer($player) {
		$this->_player->insertData('kingdom_players', $player['player']);
		$this->_player->insertData('player_attributes', $player['attributes']);
		$this->_player->insertData('player_equips', $player['equipments']);
		$this->postPlayerItems($player);
		$this->_player->insertData('player_weights', $player['weights']);
	}

	public function postPlayerItems($player) {
		$this->_player->insertData('player_items', $player['items']);
	}

	public function simpleUpdate($table, $data, $prime) {
		$this->_player->updateData($table, $data, $prime);
	}

	public function updatePlayerInfo($data) {
		$table = 'kingdom_players';
		$prime = 'name';

		$this->simpleUpdate($table, $data, $prime);
	}
}