<?php

class Controller_KingdomController extends Controller_BaseController {

	public function indexAction() {
		//$event = Model_Kingdom_Manager::lauchUpcomingEvent();
		//var_dump(json_encode($event));
		return $this->_config;
	}

	public function pullingAction() {
		$event = Model_Kingdom_Manager::lauchUpcomingEvent();
		return json_encode($event);
	}

	public function pushingAction() {

	}

	public function requestAction() {
		$post = $_POST;
		$data = null;

		if(isset($post['type'])) {
			$type = $post['type'];
			$data = Model_Kingdom_Manager::processDbFetch($post);
		}
		return $data;
	}

}