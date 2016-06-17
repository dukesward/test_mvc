<?php

class Model_FlashCard_Processor extends Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_FlashCard_Processor';
	protected $_default;
	protected $_table = array();
	
	public function loadCardDetails($params) {
		$condig = array();
		$adapter = Kernel_Db_Adapter::getDbAdapter(null, 'flashcard');

		$card_details = array(
			'table' => Kernel_Constants::MODEL_CARD_DETAILS,
			'prime' => Kernel_Constants::MODEL_CARD_DETAILS_PRIME,
		);

		$this->_table = $adapter->getDbConfigTable($card_details);
		$data = $this->_table->fetchData();
		//var_dump($data);
		return $data;
	}
}