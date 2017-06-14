<?php

class Controller_FlashCardController extends Controller_BaseController {
	
	protected $_processor;

	public function getAction() {
		$details = null;
		$params = $this->_request->getParams();
		$data = $_GET;

		if(!isset($this->_processor)) {
			try {
				$processor = Controller_Administrator::getModel('flashCardProcessor');
			}catch (Exception $e) {
				echo 'Processor not found: '.$e->getMessage();
			}
		}

		$details = $processor->loadCardDetails($params, $data);
		//var_dump(json_encode($details));
		return json_encode($details);
	}

	public function updateAction() {
		$data = $_POST;

		if(!isset($this->_processor)) {
			try {
				$processor = Controller_Administrator::getModel('flashCardProcessor');
			}catch (Exception $e) {
				echo 'Processor not found: '.$e->getMessage();
			}
		}

		$details = $processor->updateCardDetails($data);
	}
}