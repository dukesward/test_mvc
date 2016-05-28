<?php

class Util_ConfigFile {

	protected $_content = array();
	protected $_data = array();
	protected $_config;

	public function __construct() {
		
	}

	protected function _parseAttributes($attr) {
		$flag = 'off';
		$location = 0;
		$record = '';
		$unreplaced = '';
		$left = '';
		$strlen = strlen($attr);

		for($i=0; $i<$strlen; $i++) {
			$char = substr($attr, $i, 1);

			if($char === '[' && $flag === 'off') {
				if(($strlen > $i + 1) && substr($attr, $i+1, 1) === '*') {
					$flag = 'on';
					$location = $i;
					if($i > 0) $unreplaced = $unreplaced . substr($attr, 0, $i);
				}
			}else if($flag === 'on' && $char !== '*') {
				if($char === ']') {
					$flag = 'off';
					$left = $left . substr($attr, $i+1);
					break;
				}else {
					$record = $record . $char;
				}
			}
		}
		//if nothing is to be replaced, take original as unreplaced
		if(!$unreplaced) $unreplaced = $attr;

		if($left) {
			$result = $unreplaced . $this->_replaceData($record) . $this->_parseAttributes($left);
		}else {
			$result = $unreplaced . $this->_replaceData($record);
		}
		//var_dump($result);
		return $result;
	}

	protected function _replaceData($data) {
		if($data && isset($this->_data[$data])) {
			$mapped = $this->_data[$data];
			if(null !== $this->_config && isset($this->_config[$mapped])) {
				$data = $this->_config[$mapped];
			}
		}
		return $data;
	}

	public function setAttributes($content) {
		//$refl = new ReflectionClass($this);
		foreach ($content as $key => $value) {
			if(property_exists($this, $key)) {
				$property = $this->$key;
				foreach ($value as $attr => $val) {
					$tokens = explode(':', $attr);

					if(count($tokens) > 1) {
						$_temp = &$this->_content;

						foreach ($tokens as $t) {
							if(!isset($_temp[$t])) {
								$_temp[$t] = array();
							}
							$_temp = &$_temp[$t];
						}
						$val = $this->_parseAttributes($val);
						$_temp = $val;
					}else {
						$this->$key = $value;
					}
				}
			}
		}
		var_dump($this->_content);
		return $this;
	}

	public function getContentAttribute($attr) {
		$value = null;

		if($this->_content[$attr]) {
			$value = $this->_content[$attr];
		}

		return $value;
	}

	public function getDataObject() {
		return $this->_data;
	}
}