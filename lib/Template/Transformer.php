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

	protected function _createCloseTag($tag) {
		$close = '</' . $tag . '>';
		return $close;
	}

	protected function _createClosedElement($tag, $configs = null, $closed = true) {
		//var_dump($tag);
		$open = '<' . $tag;
		$close = '';

		if(null !== $configs && is_array($configs) && isset($configs['attrs'])) {
			$this->_injectElementAttrs($open, $configs['attrs']);
		}

		if($closed) {
			$close .= $this->_createCloseTag($tag);
		}

		$element = $open . '>' . $close;
		return $element;
	}

	protected function _injectElementAttrs($el, $attrs) {
		foreach ($attrs as $key => $val) {
			$el .= ' ' . $key . '="' . $val . '"';
		}
	}

	protected function _createHTMLFromConfig($config, $close = array()) {
		//var_dump($config);
		$content = '';
		$i = 0;

		if(is_array($config)) {
			//var_dump($config);
			foreach ($config as $key => $node) {
				$type = Kernel_Utils::_getArrayElement($node, 'attributes->TYPE');
				//var_dump($type);
				$children = Kernel_Utils::_getArrayElement($node, 'children');

				if(null !== $type) {
					if(Kernel_Utils::_elementInArray($this->_singleClose, $type) || null === $children) {
						$content .= $this->_createClosedElement($type, $node);
					}else {
						if(null !== $children && is_array($children)) {
							array_unshift($close, $this->_createCloseTag($type));

							$content .= $this->_createClosedElement($type, $node, false);
							$content .= $this->_createHTMLFromConfig($children, $close);
						}
					}

					if($i === count($config) - 1 && count($close) > 0) {
						var_dump($config);
						var_dump($i);
						$closeTag = array_shift($close);
						$content .= $closeTag;
					}
				}

				$i++;
			}
		}

		return $content;
	}

	public function render($config) {
		$root = Kernel_Utils::_getArrayElement($config, 'root');
		$content = '';

		if(null !== $config && isset($config['docType'])) {
			$content .= constant(self::SELF_STR . $configs['docType']);
		}else {
			$content .= self::HTML_5_DOCTYPE;
		}

		if(null !== $root && is_array($root)) {
			if(isset($root['html'])) {
				$content .= $this->_createHTMLFromConfig($root);
			}
		}

		return $content;
	}
}