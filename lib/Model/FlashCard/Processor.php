<?php

class Model_FlashCard_Processor extends Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_FlashCard_Processor';
	protected $_default;
	protected $_table = array();
	
	public function loadCardDetails($params) {
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

	public function updateCardDetails($card) {
		$adapter = Kernel_Db_Adapter::getDbAdapter(null, 'flashcard');
		$prime = Kernel_Constants::MODEL_CARD_DETAILS_PRIME;
		$data = null;

		if(isset($card[$prime])) {
			$card_details = array(
				'table' => Kernel_Constants::MODEL_CARD_DETAILS,
				'prime' => $prime,
				'query' => array(
					'set' => array(),
					'where' => array(
						'key' => $prime,
						'value' => $card[$prime],
					),
				),
			);

			foreach ($card as $key => $value) {
				if($key !== $prime) {
					$card_details['query']['set'][$key] = $value;
				}
			}

			$adapter->getDbConfigTable($card_details, null, 'UPDATE');
		}

		return $data;
	}
}