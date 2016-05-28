<?php

class Template_Transformer {
	
	protected static $_instance;

	public static function getInstance() {
		if(!self::$_instance) {
			self::$_instance = new Template_Transformer();
		}

		return self::$_instance;
	}
}