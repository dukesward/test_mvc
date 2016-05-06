<?php

class Controller_Administrator {

	public static function InitModels($models) {
		$coreProcessor = Model_CoreProcessor::getInstance();
		$models['routeProcessor'] = Model_Utils_RouteProcessor::getInstance($coreProcessor);
	}

}