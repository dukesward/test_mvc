<?php

class Kernel_Request {

	public function __construct($url = null) {
		if(null !== $url) {
			$this->setRequestUrl($url);
		}else {
			$this->setRequestUrl();
		}
	}

	public function setRequestUrl($url = null) {

	}
}