<?php

class Controller_FlashCardController extends Controller_BaseController {
	
	public function getAction() {
		$params = $this->_request->getParams();

		try {
			$processor = Controller_Administrator::getModel('flashCardProcessor');
		}catch (Exception $e) {
			echo 'Processor not found: '.$e->getMessage();
		}

		if(isset($processor)) {
			$details = $processor->loadCardDetails($params);
		}
	}
}