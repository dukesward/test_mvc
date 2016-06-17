<?php

class Controller_FlashCardController extends Controller_BaseController {
	
	public function getAction() {
		$details = null;
		$params = $this->_request->getParams();

		try {
			$processor = Controller_Administrator::getModel('flashCardProcessor');
		}catch (Exception $e) {
			echo 'Processor not found: '.$e->getMessage();
		}

		if(isset($processor)) {
			$details = $processor->loadCardDetails($params);
		}
		//var_dump(json_encode($details));
		return json_encode($details);
	}
}