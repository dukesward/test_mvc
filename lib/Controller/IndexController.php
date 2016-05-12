<?php

class Controller_IndexController extends Controller_BaseController {

	protected $_action;

	public function dispatch() {
		$content = $this->_action->generateData();
	}

	public function generateData() {
		$template = new Template
	}

	public function render($content) {
		$this->_response->_attachContent($content);
	}
}