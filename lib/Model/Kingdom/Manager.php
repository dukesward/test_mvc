<?php

class Model_Kingdom_Manager {

	const NPEW = 'new_player_event_weight';
	const WORLD = 'world_settings';
	const ITEMS = 'player_items';

	private static $_config;
	private static $_decision;
	private static $_eventInfo = array();

	public static function lauchUpcomingEvent() {
		//Util_Test::test();
		//Kernel_Utils::_createRandomEnChar();
		if(null === self::$_config) {
			self::$_config = Model_Kingdom_Config_Common::getInstance();
		}

		self::_processPublicAnnouncement();
		self::_makeBasicEventDecision();
		self::$_eventInfo['world'] = self::$_config->getWorldConfig();
		//self::_updateWorldSettings();
		return self::$_eventInfo;
	}

	public static function processDbFetch($config) {
		self::$_config = Model_Kingdom_Config_Common::getInstance();
		$data = null;

		switch($config['type']) {
			case 'item':
				$id = Kernel_Utils::_getArrayElement($config, 'id');
				if($id) $data = self::$_config->getItemById($id);
				break;
			case 'equips':
				$id = Kernel_Utils::_getArrayElement($config, 'id');
				if($id) $data = self::$_config->getEquipById($id);
				break;
		}
		return json_encode($data);
	}

	protected static function _processPublicAnnouncement() {
		$announcements = self::$_config->getActiveAnnc();
		self::$_eventInfo['public'] = $announcements;
	}

	protected static function _makeBasicEventDecision() {
		$config = self::$_config;
		$playerCount = $config->getPlayerCount();
		$newPlayerEvtWt = (int)$config->getConfig(self::NPEW);

		$basicDecision = new Model_Kingdom_Object_WeightMap();

		//$basicDecision->feed('create', round($playerCount*0.1 + $newPlayerEvtWt));
		$basicDecision->feed('event', $playerCount);

		$decision = $basicDecision->makeDecision();
		//var_dump($decision);//die();
		self::$_decision = $decision;
		//var_dump($decision);
		if($decision === 'create') {
			self::$_eventInfo['type'] = "new_player";
			self::$_eventInfo['player'] = self::_createNewPlayer();
		}else if($decision === 'event') {
			self::$_eventInfo['type'] = "new_event";
			$event = self::_makeEventDecision();
			$player = self::$_eventInfo['player'];
			
			self::$_eventInfo['event'] = $event;
			self::$_eventInfo['location'] = $config->getLocation($player->getLocation());
		}
	}

	protected static function _createNewPlayer() {
		$config = self::_createNewPlayerConfig();

		$player = new Model_Kingdom_Object_Player($config);
		$player->initRandomly();
		self::$_config->postNewPlayer($player->serialize());
		//var_dump(json_encode($player));
		return $player;
	}

	protected static function _createNewPlayerConfig() {
		$config = array();
		$loc = self::_getPlayerAvailLocation();
		$config['class'] = self::_decidePlayerClass($loc);
		$config['race'] = self::_decidePlayerRace($loc);
		$config['location'] = $loc;

		return $config;
	}

	protected static function _decidePlayerClass($loc_avail = null) {
		if(!$loc_avail) $loc_avail = self::_getPlayerAvailLocation();
		$classes = self::$_config->getLocationAvailClasses($loc_avail);

		$map = new Model_Kingdom_Object_WeightMap();
		foreach ($classes as $i => $cls) {
			$map->feed($cls['class_name'], 1);
		}
		return $map->makeDecision();
	}

	protected static function _decidePlayerRace($loc_avail = null) {
		if(!$loc_avail) $loc_avail = self::_getPlayerAvailLocation();
		$races = self::$_config->getLocationAvailRaces($loc_avail);

		$map = new Model_Kingdom_Object_WeightMap();
		foreach ($races as $i => $rc) {
			$map->feed($rc['race_name'], 1);
		}
		return $map->makeDecision();
	}

	protected static function _getPlayerAvailLocation() {
		$loc_avail = self::$_config->getAvailableLocation();
		$map = new Model_Kingdom_Object_WeightMap();
		foreach ($loc_avail as $i => $loc) {
			$map->feed($i, 1);
		}
		
		$loc_decided = $loc_avail[$map->makeDecision()];
		self::$_eventInfo['location'] = $loc_decided;
		$brief = $loc_decided['brief'];
		return $brief;
	}

	protected static function _updateWorldSettings() {
		self::_updateGlobalTime();
	}

	protected static function _updateGlobalTime() {
		$world = self::$_eventInfo['world'];
		$day = Kernel_Utils::_query($world, 'setting', 'global_time_day', 'value');
		$time = Kernel_Utils::_query($world, 'setting', 'global_time_time', 'value');

		if(self::$_decision === 'create') {
			$duration = 10;
		}

		$time = Kernel_Utils::_processGlobalTime($day, $time, $duration);
		self::$_config->simpleUpdate(self::WORLD, array(
			'setting' => 'global_time_day',
			'value' => $time['day']
		), 'setting');

		self::$_config->simpleUpdate(self::WORLD, array(
			'setting' => 'global_time_time',
			'value' => $time['time']
		), 'setting');
	}

	protected static function _makeEventDecision() {
		$player = new Model_Kingdom_Object_Player();
		$player->initFromDb(self::_lookForOnlinePlayer());
		self::$_eventInfo['player'] = $player;

		$events = self::$_config->getPlayerEvents();
		$decidedEvt = self::_filterEvents($events, $player);
		self::_processEventEffect($decidedEvt, $player);

		return $decidedEvt;
	}

	protected static function _lookForOnlinePlayer() {
		$config = self::$_config;
		$playerCount = $config->getPlayerCount();
		//this is not the best solution
		$player = $config->getDebugPlayer();
		//var_dump($player);die();
		if(!$player) {
			do {
				$random = Kernel_Utils::_createRandomNumber(0, 1, $playerCount);
				$player = $config->getPlayer($random);
				//var_dump($player);
			} while ($player['is_dead'] == 'T');
		}
		return $player;
	}

	protected static function _filterEvents($events, $player) {
		$filtered = Kernel_Utils::_filter($events, 'location', $player, function($loc, $player) {
			return $loc === $player->getLocation() || $loc === 'all';
		});

		$filtered = Kernel_Utils::_filter($filtered, 'class_require', $player, function($cls, $player) {
			return $cls === $player->getClass() || $cls === 'all';
		});

		$filtered = Kernel_Utils::_filter($filtered, 'level_require', $player, function($lv, $player) {
			return $lv <= $player->getLevel();
		});

		$filtered = Kernel_Utils::_filter($filtered, 'class_level', $player, function($lv, $player) {
			$cls_config = self::$_config->getClassPropConfig($player->getClass());
			return $lv <= $cls_config['class_level'];
		});

		$map = new Model_Kingdom_Object_WeightMap();
		foreach ($filtered as $evt) {
			$wt = $player->translate($evt['decision_factor']);
			if($wt < 0) $wt = 0;
			$map->feed($evt['name'], $wt);
		}
		//var_dump($player);
		$decision = $map->makeDecision();
		return Kernel_Utils::_query($filtered, 'name', $decision);
	}

	protected static function _processEventEffect($evt, $player) {
		$effect = $evt['effect'];
		$effect = explode('|', $effect);

		foreach ($effect as $e) {
			self::_applyEffect($e, $player);
		}
	}

	protected static function _applyEffect($effect, $player) {
		$config = self::$_config;
		$effect = explode(':', $effect);

		switch ($effect[0]) {
			case 'add_item':
				$itemIds = explode(',', $effect[1]);
				foreach ($itemIds as $id) {
					//$item = $config->getItemById($itemId);
					$player->addItem($id);
				}
				$config->simpleUpdate(self::ITEMS, $player->serializeItems(), 'player_name');
				break;
			case 'battle':
				self::_prepareBattleEvent($player);
				break;
			case 'special':
				self::_applySpecialEffect($effect[1], $player);
				break;
		}
	}

	protected static function _applySpecialEffect($id, $player) {
		$config = self::$_config;

		switch($id) {
			case '70':
				$player->convert('mercenary');
				$config->updatePlayerInfo(array(
					'name' =>$player->getName(),
					'class'=>$player->getClass()
				));
				break;
			case '71':
				$player->convert('apprentice');
				$config->updatePlayerInfo(array(
					'name' =>$player->getName(),
					'class'=>$player->getClass()
				));
				break;
			case '72':
				$player->convert('deacon');
				$config->updatePlayerInfo(array(
					'name' =>$player->getName(),
					'class'=>$player->getClass()
				));
				break;
			case '73':
				$player->convert('thief');
				$config->updatePlayerInfo(array(
					'name' =>$player->getName(),
					'class'=>$player->getClass()
				));
				break;
		}
	}

	protected static function _prepareBattleEvent($player) {
		self::$_eventInfo['type'] = 'battle';
		self::$_eventInfo['battle'] = array();

		self::$_eventInfo['battle']['players'] = array();
		array_push(self::$_eventInfo['battle']['players'], self::$_eventInfo['player']);

		$config = self::$_config;
		$location = $player->getLocation();
		$enemies = $config->getEnemyByLocation($location);

		$map = new Model_Kingdom_Object_WeightMap();
		foreach($enemies as $e) {
			$map->feed($e['troop_name'], $e['chance']);
		}
		$decision = $map->makeDecision();
		$troopEnemies = self::_buildEnemyTroop(Kernel_Utils::_query($enemies, 'troop_name', $decision, 'composition'), $decision);
		self::$_eventInfo['battle']['troop'] = $decision;
		self::$_eventInfo['battle']['enemies'] = $troopEnemies;
	}

	protected static function _buildEnemyTroop($troop, $name) {
		$troopEnemies = array();
		$enemies = explode('|', $troop);
		foreach ($enemies as $e) {
			$tokens = explode('*', $e);
			$enemy = self::$_config->getEnemy($tokens[0]);
			$enemy['loot'] = self::_processEnemyLoot($tokens[0], $name);
			$enemy['number'] = $tokens[1];
			array_push($troopEnemies, $enemy);
		}
		return $troopEnemies;
	}

	protected static function _processEnemyLoot($e, $troop) {
		$lootItems = array();
		$loot = self::$_config->getEnemyLoot($e, $troop);

		for($i=1; $i<sizeof($loot)-1; $i++) {
			$l = explode('*', $loot['loot_'.$i]);
			//array_push($lootItems, $loot['loot_'.$i]);
			$lootItems[$l[0]] = $l[1];
		}
		return $lootItems;
	}
}
