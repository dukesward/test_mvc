<?php

class Kernel_Response {

	protected $_body = array();

	public function __construct() {

	}

	protected function _sendBody() {
		$body = implode('', $this->_body);
		echo $body;
	}

	public function attachContent($content, $name = null) {
		if(null === $name) {
			if(isset($this->_body['default'])) {
				$this->_body['default'] .= (string) $content;
			}else {
				$this->_body['default'] = (string) $content;
			}
		}else {
			$this->_body[$name] = (string) $content;
		}
	}

	public function send() {
		$this->_sendBody();
	}
}