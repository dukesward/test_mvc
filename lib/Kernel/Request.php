<?php

class Kernel_Request {

	private $state;

	public function __construct($url = null) {
		if(null !== $url) {
			$this->setRequestUrl($url);
		}else {
			$this->setRequestUrl();
		}
	}

	public function setRequestUrl($url = null) {

	}

	public function setState($state) {
		$this->state = $state;
	}

	public function getController() {

	}

	public function getParams() {

	}

	public function getAction() {
		
	}
}