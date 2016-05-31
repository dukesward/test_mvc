<?php

class Template_TemplateCompiler {

	const TEMP_PATH        = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_ROOT;
	const TEMP_DEFAULT_EXT = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_DEFAULT_EXT;

	protected $_markers = array();

	protected $_loader;
	protected $_template;
	protected $_transformer;
	protected $_parsed;
	protected $_content;
	protected $_config;

	public function __construct($data) {
		$this->_config = $data;

		$file = $data->path;
		$this->_loader = Util_AutoLoader::getInstance();
		$this->_template = $this->_loader->getFileContent($file, $data->type);
		$this->_transformer = Template_Transformer::getInstance();

		$this->_parsed = $this->_parseXmlContent();
		$this->_content = '';
	}

	protected function _loadTemplateRawData($templateName, $ext) {
		$path = self::TEMP_PATH . $templateName;
		$template = $this->_loader->getFileContent($path, $ext);
		$this->generateContent($this->_parseXmlContent($template));
	}

	protected function _parseXmlContent($template = null) {
		if(null === $template) {
			$template = $this->_template;
		}

		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $template, $values);
		//var_dump($this->_template);
		//var_dump($values);
		return $values;
	}

	protected function _getTagAttribute($tag, $attr = null) {
		//var_dump($attr);
		if(isset($tag['attributes'])) {
			if(null !== $attr) {
				$attr = Kernel_Utils::_getArrayElement($tag['attributes'], $attr);
				//var_dump($attr);
			}else {
				$attr = $tag['attributes'];
			}
		}
		return $attr;
	}

	protected function _createPageRoot($parsed) {
		//var_dump($this->_config->header->getContentAttribute('root'));
		$header = $this->_config->header;
		$rootConfig = $header->getContentAttribute('root');
		//var_dump($parsed);
		$root = $this->_transformer->createHtmlRoot($rootConfig);
		
		$this->_content = $this->_content . $root;
	}

	protected function _processInherit($el) {
		$template = $el['TEMPLATE'];

		if(isset($el['EXT'])) {
			$ext = $el['EXT'];
		}else {
			$ext = self::TEMP_DEFAULT_EXT;
		}

		$raw = $this->_loadTemplateRawData($template, $ext);
	}

	protected function _processRootConfig($el, $parsed = null) {
		if(null === $parsed) {
			$parsed = $this->_parsed;
		}

		$header = $this->_config->header;
		$rootConfig = $header->getContentAttribute('root');
		$type = $this->_getTagAttribute($el, 'TYPE');
		if($type === 'head' || $type === 'body') {
			//$rootConfig[$type]['attrs'] = $this->_getTagAttribute($el);
			foreach ($this->_getTagAttribute($el) as $key => $val) {
				$element = 'root:' . $type . ':attrs:' . $key;
				$header->setTemplateAttribute($element, $val);
			}
		}
		var_dump($this->_config->header);
	}

	public function generateContent($parsed = null) {
		$trigger = null;
		if(null === $parsed) {
			$parsed = $this->_parsed;
		}
		//var_dump($this->_parsed);
		foreach($parsed as $el) {
			if($el['type'] === 'open' || $el['type'] === 'complete') {
				$trigger = 'on';
				switch(strtolower($el['tag'])) {
					case 'inherit':
						$this->_markers['inherit'] = true;
						$this->_processInherit($el['attributes']);
						break;
					case 'root':
						$this->_markers['root'] = true;
						break;
					case 'node':
						if(isset($this->_markers['root'])) {
							$this->_processRootConfig($el, $parsed);
						}
						break;
					case 'block':
						break;
				}
			}else if($el['type'] === 'close') {

			}
		}

		return $this->_content;
	}
}