<?php

class Util_ConfigFile {

	public $_content;

	public function __construct() {
		
	}

	public function setAttributes($content) {
		$refl = new ReflectionClass($this);

		foreach ($content as $key => $value) {
			$property = $refl->getProperty($key);

			if($property instanceof ReflectionClass) {
				$property->setValue($this, $value);
			}
		}

		return $this;
	}
}
