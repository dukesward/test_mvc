<?php

class Kernel_Response {

	protected $_httpResponseCode = 200;
	protected $_headers;
	protected $_body = array();

	public function __construct() {

	}

	protected function _sendHeaders() {

		if(null !== $this->_headers && $this->_httpResponseCode === 200) {
			foreach ($this->_headers as $header) {
				header($header['name'] . ':' . $header['value'], $header['replace']);
			}
		}
	}

	protected function _sendBody() {
		$body = implode('', $this->_body);
		echo $body;
	}

	public function setHeader($name, $value, $replace = true) {
		if(null === $this->_headers) {
			$this->_headers = array();
		}

		$header = array(
			'name'  => $name,
			'value' => $value,
			'replace' => $replace,
		);

		array_push($this->_headers, $header);
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
		$this->_sendHeaders();
		$this->_sendBody();
	}
}