<?php

class Controller_FlashCardController extends Controller_BaseController {
	
	protected $_config;
	//implement indexAction function
	protected function indexAction() {
		$config = new Template_DataConfig($this->_config);
	}
}