<?php

class Template_Config {

	protected $_nid;
	protected $_path;
	protected $_type;

	public function __construct($config) {
		$root = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_ROOT;
		$delimiter = Kernel_Constants::MODEL_ROUTES_FILE_SPLITTER;
		$tokens = array('root', 'template');
		$this->_path = $root . Kernel_Utils::_concat($config, $tokens, $delimiter, 'each');
		$this->_type = $config['template_type'];
	}

	public function getPath() {
		return $this->_path;
	}

	public function getType() {
		return $this->_type;
	}
}