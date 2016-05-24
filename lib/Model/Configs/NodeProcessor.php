<?php

class Model_Configs_NodeProcessor extends Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_Configs_NodeProcessor';
	protected $_default;
	protected $_table;

	public function loadNodeConfig($path) {
		var_dump($path);
		$this->_configs = array(
			'table' => Kernel_Constants::MODEL_NODE_DETAILS,
			'query' => array(
				'where' => 'pattern =' . Kernel_Utils::_processUrl($path, 'raw'),
			);
		);

		$adapter = Kernel_Db_Adapter::getDbAdapter();
		$this->_table = $adapter->getDbConfigTable($this->_configs);
		var_dump($this->_table);
	}

}