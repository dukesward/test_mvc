<?php

class Model_CoreProcessor {

	protected static $_instance;

	public static function getInstance() {
		if(null === self::$_instance) {
			self::$_instance = new self($table);
		}
		return self::$_instance;
	}
}