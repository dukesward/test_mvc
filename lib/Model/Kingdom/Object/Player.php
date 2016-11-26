<?php

class Model_Kingdom_Object_Player implements JsonSerializable {

	protected $_id;
	protected $_name;
	protected $_gender;
	protected $_class;
	protected $_class_conf;
	protected $_race;

	protected $_attr_wt = array();
	protected $_attrs = array();
	protected $_sp_type;
	protected $_props = array();

	protected $_hp;
	protected $_sp;
	protected $_exp;
	protected $_exp_next;

	protected $_equip_config = array();
	protected $_equips = array();

	protected $_items = array();
	protected $_max_items;

	protected $_ability_config = array();
	protected $_abilities = array();
	protected $_level;
	protected $_db;
	protected $_location;

	public function __construct($config = null) {
		$this->_db = Model_Kingdom_Config_Common::getInstance();
		//a new player
		if($config) {
			$this->_preSetFromConfig($config);
		}
	}

	public function initRandomly() {
		$this->_id = $this->_db->getPlayerCount() + 1;

		do {
			$name = Kernel_Utils::_createRandomEnChar();
		} while ($this->_db->getPlayerCount($name) > 0);

		$this->_name = $name;
		$this->_name = 'test';
		$this->_gender = Kernel_Utils::_decide() ? 'M' : 'F';
		$this->_generatePlayerBaseAttrWt();
		$this->_level = 1;

		$this->_class_conf = $this->_db->getClassPropConfig($this->_class);
		$this->_class_alt = $this->_class_conf['class_name_alt'];
		if($this->_class) $this->_addPlayerAttrWt($this->_class_conf);
		if($this->_race) $this->_addPlayerAttrWt($this->_db->getRacePropConfig($this->_race, $this->_gender));

		$this->_generatePlayerBaseAttr();

		$this->_hp = $this->_props['hp_max'];
		$this->_sp = $this->_props['sp_max'];
		$this->_exp = 0;
		$this->_exp_next = pow($this->_level, 2)*$this->_db->getConfig('base_exp_incr');
	}

	public function initFromDb($player) {
		$this->_id = $player['id'];
		$this->_name = $player['name'];
		$this->_gender = $player['gender'];
		$this->_level = $player['level'];

		$this->_class = $player['class'];
		$this->_race = $player['race'];
		$this->_location = $player['location'];

		$attrs = $this->_db->getPlayerAttrs($this->_name)[0];

		$attrBriefs = Kernel_Constants::getPlayerAttrBriefs();
		$propCollection = Kernel_Constants::getPlayerProps();
		foreach ($attrBriefs as $a) {
			$this->_attrs[$a] = $attrs[$a];
		}

		foreach ($propCollection as $p) {
			$this->_props[$p] = $attrs[$p];
		}

		$equips = $this->_db->getPlayerEquips($this->_name)[0];
		$parts = Kernel_Constants::getEquipParts();
		foreach ($parts as $p) {
			if($equips[$p]) {
				$this->_equips[$p] = $this->_db->getEquipObject($equips[$p]);
			}else {
				$this->_equips[$p] = null;
			}
			
		}

		$items = $this->_db->getPlayerItems($this->_name)[0];
		$this->_max_items = $items['max_items'];
		for ($i=1; $i<$this->_max_items+1; $i++) {
			if(isset($items['item_'.$i])) {
				$item = explode('*', $items['item_'.$i]);
				//array_push($this->_items, $items['item_'.$i]);
				$this->_items[$item[0]] = $item[1];
			}
		}
	}

	public function jsonSerialize() {
		$info = Kernel_Constants::getPlayerInfo();
		$serialized = array();

		foreach ($info as $i) {
			$prop = '_' . $i;
			if(isset($this->$prop)) {
				$serialized[$i] = $this->$prop;
			}
		}
		return $serialized;
	}

	public function serialize() {
		$serialized = array(
			"player"     => array(),
			"attributes" => array(),
			"equipments" => array(),
			"items"      => array(),
			"weights"    => array()
		);

		$serialized['player']['id'] = $this->_id;
		$serialized['player']['name'] = $this->_name;
		$serialized['player']['level'] = $this->_level;
		$serialized['player']['gender'] = $this->_gender;
		$serialized['player']['class'] = $this->_class;
		$serialized['player']['race'] = $this->_race;
		$serialized['player']['sp_type'] = $this->_sp_type;
		$serialized['player']['location'] = $this->_location;

		$serialized['attributes']['player_name'] = $this->_name;
		foreach ($this->_attrs as $attr => $val) {
			$serialized['attributes'][$attr] = $val;
		}

		foreach ($this->_props as $attr => $val) {
			$serialized['attributes'][$attr] = $val;
		}
		//var_dump($this->_equips);
		$serialized['equipments']['player_name'] = $this->_name;
		foreach ($this->_equips as $equip => $obj) {
			$serialized['equipments'][$equip] = $obj['name'];
		}

		$serialized['weights']['player_name'] = $this->_name;
		foreach ($this->_attr_wt as $attr => $wt) {
			$serialized['weights'][$attr.'_wt'] = $wt;
		}

		$serialized['items']['player_name'] = $this->_name;
		$serialized['items']['num_items'] = sizeof($this->_items);
		$counter = 1;
		foreach ($this->_items as $id => $num) {
			$serialized['items']['item_'.$counter] = $id.'*'.$num;
			$counter++;
		}
		return $serialized;
	}

	public function getId() {
		return $this->_id;
	}

	public function getLocation() {
		return $this->_location;
	}

	public function getClass() {
		return $this->_class;
	}

	public function getLevel() {
		return $this->_level;
	}

	public function addItem($item) {
		$items = $this->_items;
		if(sizeof($items) < $this->_max_items) {
			//array_push($this->_items, $item);
			if(isset($this->_items[$item])) {
				$this->_items[$item]++;
			}else {
				$this->_items[$item] = 1;
			}
		}
		//var_dump($this->_items);
	}

	public function translate($str) {
		eval('$value='.$this->_translate($str).';');
		return $value;
	}

	protected function _preSetFromConfig($config) {
		$this->_class = Kernel_Utils::_getArrayElement($config, 'class');
		$this->_race = Kernel_Utils::_getArrayElement($config, 'race');
		$this->_location = Kernel_Utils::_getArrayElement($config, 'location');
	}

	protected function _generatePlayerBaseAttrWt() {
		$attrBriefs = Kernel_Constants::getPlayerAttrBriefs();
		foreach ($attrBriefs as $attr) {
			$this->_attr_wt[$attr] = Kernel_Utils::_createRandomNumber(0, 1, 5);
		}
	}

	protected function _addPlayerAttrWt($config) {
		$attrBriefs = Kernel_Constants::getPlayerAttrBriefs();
		foreach ($attrBriefs as $attr) {
			if(isset($config[$attr.'_wt'])) {
				$this->_attr_wt[$attr] += $config[$attr.'_wt'];
			}
		}
	}

	protected function _generatePlayerBaseAttr() {
		$attrWt = new Model_Kingdom_Object_WeightMap();
		$attrWt->feedArr($this->_attr_wt);

		$this->_initAttrs();
		$baseAttrNumPoints = $this->_db->getConfig('base_attr_num_points');
		for($i=0; $i<$baseAttrNumPoints; $i++) {
			$decision = $attrWt->makeDecision();
			$this->_attrs[$decision] += 1;
		}
		
		$this->_generatePropsFromAttr();
	}

	protected function _initAttrs() {
		$attrBriefs = Kernel_Constants::getPlayerAttrBriefs();
		foreach ($attrBriefs as $attr) {
			$this->_attrs[$attr] = 1;
		}
	}

	protected function _generatePropsFromAttr() {
		$this->_initSPFromConfig();
		$this->_initPropFromConfig();
		$this->_initStartEquips();
		$this->_initStartAbilities();
	}

	protected function _initSPFromConfig() {
		$config = $this->_class_conf;
		$this->_sp_type = Kernel_Utils::_getArrayElement($config, 'sp_type');
	}

	protected function _initPropFromConfig() {
		$config = $this->_class_conf;
		$props = Kernel_Constants::getPlayerProps();

		foreach ($props as $prop) {
			if(isset($config[$prop . '_formula'])) {
				$formula = $config[$prop . '_formula'];

				$translated = $this->_translate($formula);
				//evaluate the result calculated from formula string 
				eval('$value='.$translated.';');
				$this->_props[$prop] = $value;
			}else {
				$this->_props[$prop] = 0;
			}
		}
	}

	protected function _translate($str) {
		$pattern = "/\{(\w+?)\}/i";
		$translated = preg_replace_callback($pattern, array($this, '_replace'), $str);
		return $translated;
	}

	protected function _replace($matches) {
		$match = $matches[1];
		$replaced = '';

		if(isset($this->_attrs[$match])) {
			$replaced = $this->_attrs[$match];
		}else if(isset($this->_props[$match])) {
			$replaced = $this->_props[$match];
		}else if(Kernel_Utils::_getArrayElement($this, '_'.$match)) {
			$replaced = Kernel_Utils::_getArrayElement($this, '_'.$match);
		}
		//var_dump($replaced);
		return $replaced;
	}

	protected function _initStartEquips() {
		$equip_config = $this->_db->getEquipConfig($this->_class);
		$equip_parts = Kernel_Constants::getEquipParts();
		foreach ($equip_config as $config) {
			if(isset($config['equip_sub_type'])) {
				$sub = $config['equip_sub_type'];
				if(!isset($this->_equip_config[$sub])) {
					$this->_equip_config[$sub] = array();
				}
				array_push($this->_equip_config[$sub], $config['equip_type']);
			}
		}

		foreach ($equip_parts as $part) {
			$this->_equips[$part] = null;
			if(isset($this->_class_conf['equip_'.$part])) {
				$name = $this->_class_conf['equip_'.$part];
				if($name) {
					$this->_equips[$part] = $this->_db->getEquipObject($name);
				}
			}
		}
	}

	protected function _initStartAbilities() {
		$ability_config = $this->_db->getClassAbilityConfig($this->_class);

		foreach ($ability_config as $config) {
			$ability = $this->_db->getAbilityObject($config['ability_name']);
			array_push($this->_abilities, $ability);
		}
	}
}