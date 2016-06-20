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
					//var_dump($record);
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
		}else {
			if(null !== Kernel_Utils::_getArrayElement($this->_data, 'variable->' . $data)) {
				$data = $this->_data['variable'][$data];
			}
		}
		return $data;
	}

	public function setupVariable($key, $value) {
		if(!isset($this->_data['variable'])) {
			$this->_data['variable'] = array();
		}

		$this->_data['variable'][$key] = $this->_parseAttributes($value);
	}

	public function setTemplateAttribute($tokens, $val = null) {
		//var_dump($tokens);
		if(is_string($tokens)) {
			$tokens = explode(':', $tokens);
		}

		$_temp = &$this->_content;

		foreach ($tokens as $t) {
			if(!isset($_temp[$t])) {
				//if(is_string($_temp)) {var_dump($this->_content['root']['body']);}
				$_temp[$t] = array();
			}
			$_temp = &$_temp[$t];
		}

		if(null !== $val) {
			$val = $this->_parseAttributes($val);
			$_temp = $val;
		}
		return $_temp;
	}

	public function setTemplateAttributeByArray($arr, $base = null) {
		//var_dump($base);
		if(null !== $arr && is_array($arr)) {
			foreach ($arr as $key => $val) {
				//$base = $base . ':' . $key;
				if(is_array($val)) {
					$this->setTemplateAttributeByArray($val, $base . ':' . $key);
				}else {
					$this->setTemplateAttribute($base . ':' . $key, $val);
				}
			}
		}
	}

	public function setAttributes($content) {
		//var_dump($content);
		//parse the content according to annotations
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

	public function getDataVariable($variable) {
		return Kernel_Utils::_getArrayElement($this->_data, 'variable->' . $variable);
	}

	public function getContentAttribute($attr = null) {
		if(null === $attr) {
			$value = $this->_content;
		}else {
			$attr = explode(':', $attr);
			$value = Kernel_Utils::_getArrayElement($this->_content, implode('->', $attr));
		}

		return $value;
	}

	public function getNumOfElements($path) {
		$num = 0;
		$target = $this->setTemplateAttribute($path);

		if(null !== $target && is_array($target)) {
			$num = count($target);
		}

		return $num;
	}

	public function getDataObject() {
		return $this->_data;
	}
}