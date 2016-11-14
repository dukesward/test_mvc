<?php

class Controller_MallController extends Controller_BaseController {

	public function indexAction() {
		return $this->_config;
	}

	public function dataAction() {
		Model_Mall_MallManagement::startMallManaging();
	}
}