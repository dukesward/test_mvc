<?php

class Template_Transformer {
	
	const SELF_STR       = 'self::';
	const HTML_5_DOCTYPE = '<!DOCTYPE html>';

	protected static $_instance;
	protected $_singleClose = 
		array(
			'img',
		);

	public static function getInstance() {
		if(!self::$_instance) {
			self::$_instance = new Template_Transformer();
		}

		return self::$_instance;
	}

	protected function _createClosedElement($tag, $configs = null, $closed = true) {
		$open = '<' . $tag;

		if(null !== $configs && is_array($configs) && isset($configs['attrs'])) {
			$this->_injectElementAttrs($open, $configs['attrs']);
		}

		$close = '</' . $tag . '>';
		return $open . '>' . $close;
	}

	protected function _injectElementAttrs($el, $attrs) {
		foreach ($attrs as $key => $val) {
			$el .= ' ' . $key . '="' . $val . '"';
		}
	}

	public function createHtmlRoot($configs = null) {
		//var_dump($configs);
		$root = '';
		$type = 'HTML_5_DOCTYPE';

		if(null !== $configs && isset($configs['docType'])) {
			$doc = constant(self::SELF_STR . $configs['docType']);
		}else {
			$doc = self::HTML_5_DOCTYPE;
		}

		$html = $this->_createClosedElement('html', Kernel_Utils::_getArrayElement($configs, 'html'));
		$head = $this->_createClosedElement('head', Kernel_Utils::_getArrayElement($configs, 'head'), false);
		$root = $root . $doc . $html;

		return $root;
	}
}