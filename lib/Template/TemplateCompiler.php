<?php

class Template_TemplateCompiler {

	protected $_loader;
	protected $_template;
	protected $_transformer;

	public function __construct($data) {
		$file = $data->path;
		$this->_loader = Util_AutoLoader::getInstance();
		$this->_template = $this->_loader->getFileContent($file, $data->type);
		$this->_transformer = Template_Transformer::getInstance();
	}

	public function _parseXmlContent() {
		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $this->_template, $values);
		//var_dump($this->_template);
		//var_dump($values);
	}

	public function generateContent() {
		$parsed = $this->_parseXmlContent();
	}
}