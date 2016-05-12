<?php

class Kernel_Exception_Standard extends Exception {

	public function __construct($details = null, $code = 0, Exception $previous = null) {
		$msg = $this->assembleMsg($details);
		parent::__construct($msg, (int) $code, $previous);
	}

	public function assembleMsg($details = null) {

	}

	public function prepareMsgContainer() {

	}
}