<?php

class Model_Kingdom_Manager_Player {

	public static function createNewPlayer() {

	}

	protected static function _createRandomPlayerName() {
		$numOfChars = Kernel_Utils::_createRandomNumber(0, 1, 3);
		$name = '';
		while(strlen($name) < $numOfChars) {
			$name .= Kernel_Utils::_createRandomChChar();
		}
		return $name;
	}
}