<?php

class Controller_Administrator {

	protected static $_models;

	public static function InitModels($models) {
		$models['routeProcessor'] = Model_Utils_RouteProcessor::getInstance();
		$models['nodeProcessor'] = Model_Configs_NodeProcessor::getInstance();
		$models['flashCardProcessor'] = Model_FlashCard_Processor::getInstance();
		$models['commonProcessor'] = Model_Utils_CommonProcessor::getInstance();
		self::$_models = $models;
	}

	public static function getModel($modelName) {
		if(self::$_models[$modelName]) {
			$model = self::$_models[$modelName];
			return $model;
		}else {
			throw new Exception('Specified model name: '.$modelName.' does not match any model');
		}
	}

}