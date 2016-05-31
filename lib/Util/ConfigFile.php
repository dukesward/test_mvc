<?php

class Util_ConfigFile {

	protected $_content = array();
	protected $_data = array();
	protected $_config;

	public function __construct($config = null) {
		if(null !== $config) {
			$this->_config = $config;
		}
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
					if($i > 0) {
						$unreplaced = $unreplaced . substr($attr, 0, $i);
					}else {
						$unreplaced = null;
					}
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
		if(!$unreplaced && null !== $unreplaced) {
			$result = $attr;
		}else {
			$result = $unreplaced;
		}

		if($left) {
			$result = $result . $this->_replaceData($record) . $this->_parseAttributes($left);
		}else {
			$result = $result . $this->_replaceData($record);
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

	public function setTemplateAttribute($tokens, $val) {
		if(is_string($tokens)) {
			$tokens = explode(':', $tokens);
		}

		$_temp = &$this->_content;

		foreach ($tokens as $t) {
			if(!isset($_temp[$t])) {
				$_temp[$t] = array();
			}
			$_temp = &$_temp[$t];
		}
		$val = $this->_parseAttributes($val);
		$_temp = $val;
	}

	public function setAttributes($content) {
		//var_dump($content);
		//$refl = new ReflectionClass($this);
		foreach ($content as $key => $value) {
			if(property_exists($this, $key)) {
				$property = $this->$key;
				foreach ($value as $attr => $val) {
					$tokens = explode(':', $attr);

					if(count($tokens) > 1) {
						$this->setTemplateAttribute($tokens, $val);
					}else {
						$this->$key = $value;
					}
				}
			}
		}
		//var_dump($this->_content);
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