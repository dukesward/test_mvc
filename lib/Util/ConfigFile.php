<?php

class Util_ConfigFile {

	protected $_content;

	public function __construct() {
		
	}

	public function setAttributes($content) {
		$refl = new ReflectionClass($this);

		foreach ($content as $key => $value) {
			if(property_exists($this, $key)) {
				$this->$key = $value;
			}
		}

		return $this;
	}

	public function getContentAttribute($attr) {
		$value = null;

		if($this->_content[$attr]) {
			$value = $this->_content[$attr];
		}

		return $value;
	}
}