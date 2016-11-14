<?php

class Template_TemplateCompiler {

	const TEMP_PATH        = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_ROOT;
	const TEMP_DEFAULT_EXT = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_DEFAULT_EXT;
	const CONFIG_PATH      = Kernel_Constants::KERNEL_ROUTES_CONFIG_ROOT;
	const SCRIPT_PATH      = Kernel_Constants::KERNEL_ROUTES_SCRIPT_ROOT;
	const DEFAULT_CONFIG   = 'xml';
	const ROOT_BASE        = 'root:';

	const CONFIG_CONF      = 'FILES';
	const CONFIG_MOD       = 'FILE';

	protected $_loader;
	protected $_template;
	protected $_parsed;
	protected $_content;
	protected $_processor;

	public function __construct($data) {
		//$this->_config = $data;
		$file = $data->path;
		$this->_loader = Util_AutoLoader::getInstance();
		$this->_template = $this->_loader->getFileContent($file, $data->type);

		$this->_parsed = $this->_parseXmlContent();
		$this->_content = '';

		$this->_processor = new Template_TemplateProcessorRefined($data, $this);
	}

	protected function _loadTemplateRawData($templateName, $ext) {
		$this->generateContent($this->loadTemplateConfig($templateName, $ext));
	}

	protected function _parseXmlContent($template = null) {
		if(null === $template) {
			$template = $this->_template;
		}
		//var_dump($this->_template);
		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $template, $values);

		//var_dump($this->_template);
		//var_dump($values);
		return $values;
	}

	protected function _getValueFromParsedXML($xml, $root = '') {
		$values = array();
		$name = null;

		foreach ($xml as $el) {
			$tag = $el['tag'];
			if($tag === self::CONFIG_CONF) {
				if($el['type'] === 'open') {
					$name = Kernel_Utils::_getArrayElement($el, 'attributes->NAME');
					$values[$name] = array();
				}
			}else if ($tag === self::CONFIG_MOD && null !== $name) {
				if($el['type'] === 'complete') {
					array_push($values[$name], $root . Kernel_Utils::_getArrayElement($el, 'value'));
				}
			}
		}
		return $values;
	}

	public function extractInnerContent($parsed, $i, $level, $tag) {
		//var_dump($parsed);
		$inner = array();
		$ii = $i + 1;
		
		while ($parsed[$ii]['tag'] !== $tag || $parsed[$ii]['level'] !== $level || $parsed[$ii]['type'] !== 'close') {
			array_push($inner, $parsed[$ii]);
			$ii++;
		}
		array_unshift($inner, $parsed[$i]);
		//$inner = Kernel_Utils::_expandArray($iterator, $inner);
		return $inner;
	}

	public function loadTemplateConfig($template, $ext = null) {
		//$template = $el['TEMPLATE'];
		if(!$ext) {
			$ext = self::TEMP_DEFAULT_EXT;
		}

		$path = self::TEMP_PATH . $template;
		$template = $this->_loader->getFileContent($path, $ext);
		//$this->_processor->addTask($template);
		$config = $this->_parseXmlContent($template);
		return $config;
	}

	public function generateContent($parsed = null) {
		if(null === $parsed) {
			$parsed = $this->_parsed;
		}
		
		if(sizeof($parsed) > 0) {
			$tag = Kernel_Utils::_getArrayElement($parsed[0], 'tag');
			if($tag === 'INHERIT' || $tag === 'ROOT') {
				$this->_processor->setRoot($parsed);
				//var_dump($this->_processor->hasRoot());
			}
		}

		if($root = $this->_processor->hasRoot()) {
			return $this->_processor->render();
		}
	}
}