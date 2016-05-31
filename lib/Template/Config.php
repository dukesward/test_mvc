<?php

class Template_Config {

	const HEADER_ROOT   = Kernel_Constants::KERNEL_ROUTES_CONFIG_ROOT;
	const HEADER_PATH   = 'template\\global_header';

	const CONFIG_EXT    = Kernel_Constants::KERNEL_ROUTES_CONFIG_EXT;
	const CONFIG_PATH   = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_CONFIG_ROOT;

	const FILE_SPLITTER = Kernel_Constants::MODEL_ROUTES_FILE_SPLITTER;

	protected $_data = array();

	public function __construct($config) {
		$root = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_ROOT;
		$delimiter = Kernel_Constants::MODEL_ROUTES_FILE_SPLITTER;
		$tokens = array('root', 'template');
		$this->_data['path'] = $root . Kernel_Utils::_concat($config, $tokens, $delimiter, 'each');

		$this->_setUpTemplateConfig($config);
		//$this->_type = $config['template_type'];
	}

	protected function _setUpTemplateConfig($config) {

		$loader = Util_AutoLoader::getInstance();
		$file = new Util_ConfigFile();
		$path = self::CONFIG_PATH . $config['root'] . self::FILE_SPLITTER . $config['template'];
		$content = $loader->getFileContent($path, self::CONFIG_EXT, $file);

		foreach ($content->getDataObject() as $key => $val) {
			if(isset($config[$val])) {
				$this->_data[$key] = $config[$val];
			}
		}
		//var_dump($this->_data);
	}

	public function __get($key) {
		$val = null;

		if(isset($this->_data[$key])) {
			$val = $this->_data[$key];
		}
		return $val;
	}

	public function injectGlobalHeader() {
		if(!isset($this->_data['header'])) {
			$loader = Util_AutoLoader::getInstance();
			$path = self::HEADER_ROOT . self::HEADER_PATH;
			$file = new Util_ConfigFile($this->_data);
			$this->_data['header'] = $loader->getFileContent($path, self::CONFIG_EXT, $file);
			//var_dump($this->_data);
		}
	}
}