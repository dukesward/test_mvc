<?php

class Template_TemplateCompiler {

	protected $_template;

	public function __construct($data) {
		$file = $data->getPath();
		$loader = Util_AutoLoader::getInstance();
		$this->_template = $loader->getFileContent($file, $data->getType());
		var_dump($this->_template);
	}

	public function generateContent() {

	}
}