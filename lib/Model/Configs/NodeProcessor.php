<?php

class Model_Configs_NodeProcessor extends Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_Configs_NodeProcessor';
	protected $_default;
	protected $_table = array();

	public function loadNodeConfig($path) {
		$config = array();
		$adapter = Kernel_Db_Adapter::getDbAdapter();
		$node_template_config = null;

		$node_details = array(
			'table' => Kernel_Constants::MODEL_NODE_DETAILS,
			'prime' => Kernel_Constants::MODEL_NODE_DETAILS_PRIME,
			'query' => array(
				'where' => array(
					'key' => 'pattern',
					'value' => Kernel_Utils::_processUrl($path, 'raw'), 
				),
			),
		);

		$this->_table['details'] = $adapter->getDbConfigTable($node_details);
		$node_details_config = $this->_table['details']->fetchData();

		if(count($node_details_config) > 0) {
			$node_template = array(
				'table' => Kernel_Constants::MODEL_NODE_TEMPLATE,
				'query' => array(
					'where' => array(
						'key' => 'id',
						'value' => $node_details_config[0]['nid'],
					),
				),
			);

			$this->_table['template'] = $adapter->getDbConfigTable($node_template);
			$node_template_config = $this->_table['template']->fetchData();
		}
		
		return $node_template_config;
	}

}