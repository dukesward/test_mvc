<?php

class Template_NodeElement {

	protected $_config;
	//protected $_data;
	protected $_tag;
	protected $_level;
	protected $_attributes;
	protected $_parent;
	protected $_children = array();

	public function __construct($node, Template_Config $config) {
		$this->_config = $config;
		//$this->_data = $config->header->getDataObject();
		$this->_tag = Kernel_Utils::_getArrayElement($node, 'tag');
		$this->_level = Kernel_Utils::_getArrayElement($node, 'level');

		$value = Kernel_Utils::_getArrayElement($node, 'value');
		$this->_value = $value;

		$this->_attributes = array();
		$attributes = Kernel_Utils::_getArrayElement($node, 'attributes');
		//if($this->_tag === 'COMMON') var_dump($attributes);
		if($attributes) {
			foreach ($attributes as $attr => $val) {
				//var_dump($attr[0]);
				if($attr[0] === '_') {
					$this->_attributes[$attr] = $val;
				}else {
					$this->_attributes[$attr] = $this->_parseAttributes($val);
				}
			}
		}
		//if($this->_tag === 'COMMON') var_dump($this->_attributes);
		$this->_processSpecialTagFunctions();
	}

	public function isA($tag, $nodeType = null) {
		$result = true;
		if($tag !== $this->_tag) {
			$result = false;
		}else if($nodeType && $this->getNodeAttr('type') !== $nodeType) {
			$result = false;
		}
		return $result;
	}

	public function setChild(Template_NodeElement $node) {
		array_push($this->_children, $node);
		$node->setParent($this);
	}

	public function setParent(Template_NodeElement $node) {
		$this->_parent = $node;
	}

	public function getNodeTag() {
		return $this->_tag;
	}

	public function getNodeLevel() {
		return $this->_level;
	}

	public function setNodeAttr($key, $val) {
		if(is_array($this->_attributes)) {
			$this->_attributes[$key] = $val;
		}else {
			$this->_attributes = array(
				$key => $val
			);
		}
	}

	public function getNodeAttr($key) {
		$attr = Kernel_Utils::_getArrayElement($this->_attributes, $key);
		return $attr;
	}

	public function trackNodeAttr($key) {
		$val = $this->getNodeAttr($key);
		if(!$val && $this->_parent) {
			$val = $this->_parent->trackNodeAttr($key);
		}
		return $val;
	}

	public function renderRootNode() {
		if($this->isA('ROOT')) {
			$root = '';
			$docType = $this->getNodeAttr('doc');

			if(null !== $docType && constant('Kernel_Constants::' . $docType)) {
				$root .= constant('Kernel_Constants::' . $docType);
			}else {
				$root .= Kernel_Constants::HTML_5_DOCTYPE;
			}

			$content = $this->renderNode();
			$root = $this->_wrapWithHtmlTag($content, 'html');
		}else {
			if($this->_parent) {
				$root = $this->_parent->renderRootNode();
			}else {
				$root = 'error: must provide a valid root node';
			}
		}
		
		return $root;
	}

	public function renderNode() {
		if($this->getNodeAttr('TYPE') === 'test') {
			var_dump($this->_attributes);
		}
		foreach ($this->_attributes as $attr => $val) {
			//var_dump($attr[0]);
			if($attr[0] === '_') {
				$this->_attributes[$attr] = $this->_parseAttributes($val, 1);
				//if($this->getNodeAttr('TYPE') === 'test') {
					//var_dump($this->_attributes[$attr]);
				//}
			}
		}
		$children = '';
		$content = '';

		switch (strtolower($this->_tag)) {
			case 'for':
				$iterator = (int)$this->getNodeAttr('ITERATOR');
				$index = $this->getNodeAttr('INDEX');

				for($i=0; $i<$iterator; $i++) {
					$this->setStoredVariable($index, $i);
					foreach ($this->_children as $child) {
						if($this->_parent) {
							$child->setParent($this->_parent);
						}
						$children .= $child->renderNode();
					}
					$content .= $children;
				}
				break;
			case 'node':
				$tag = $this->getNodeAttr('TYPE');
				$opened = Kernel_Utils::_isHtmlSingleTag($tag);
				foreach ($this->_children as $i => $child) {
					$children .= $child->renderNode();
				}
				$content = $this->_wrapWithHtmlTag($children, $tag, $opened);
				break;
			case 'static':
				$tag = $this->getNodeAttr('TYPE');
				$name = $this->getNodeAttr('NAME');
				$query = $this->_config->queryParams;
				$cache = Util_FileCache::getInstance();

				if($tag === 'script') {
					$ext = Kernel_Constants::CACHE_SCRIPT_EXT;
				}

				$file = $cache->setupFileCache(array(
					'root' => 'static',
					'file' => $name,
					'ext' => isset($ext) ? $ext : null,
				));

				if(null === $file || null !== Kernel_Utils::_getArrayElement($query, 'fc')) {
					$this->_getStaticFileConfig($type);
					$files = Kernel_Utils::_getArrayElement($this->_static, $type . '->' . $name);
					$this->_cache->createFileCache(Kernel_Constants::KERNEL_ROUTES_SCRIPT_ROOT, $files, 'static:'.$name, $ext);
				}

				$path = 'staticcontent/cscripts/static/' . $name;
				if($tag === 'script') {
					$this->setNodeAttr('_src', $path);
				}
				foreach ($this->_children as $i => $child) {
					$children .= $child->renderNode();
				}
				$opened = Kernel_Utils::_isHtmlSingleTag($tag);
				$content = $this->_wrapWithHtmlTag($children, $tag, $opened);
				break;
			case 't':
				$content = $this->_parseAttributes($this->_value);
				break;
			default:
				foreach ($this->_children as $i => $child) {
					$children .= $child->renderNode();
				}
				$content = $children;
				break;
		}
		
		return $content;
	}

	public function setStoredVariable($name, $value) {
		if($name) {
			if($this->_parent) {
				$this->_parent->setNodeAttr($name, $this->_parseAttributes($value));
			}else {
				$this->_config->header->setupVariable($name, $value);
			}
		}
	}

	public function replaceData($data) {
		$replaced = $this->trackNodeAttr($data);
		if(!$replaced) {
			$dataObj = $this->_config->header->getDataObject();
			if(isset($dataObj[$data])) {
				$d = $dataObj[$data];
				$replaced = $this->_config->$d;
			}else {
				$replaced = Kernel_Utils::_getArrayElement($dataObj, 'variable->' . $data);
			}
		}
		if(!$replaced) $replaced = $data;
		return $replaced;
	}

	protected function _processSpecialTagFunctions() {
		switch (strtolower($this->_tag)) {
			case 'var':
				$name = $this->getNodeAttr('NAME');
				$value = $this->getNodeAttr('VALUE');
				$this->setStoredVariable($name, $value);
				break;
			case 'common':
				$name = $this->getNodeAttr('NAME');
				$value = $this->getNodeAttr('VALUE');
				if($name) {
					$config = $this->_config->header->fetchConfigProp($name);
					$this->setStoredVariable($value, $config);
				}
				break;
		}
	}

	protected function _wrapWithHtmlTag($content, $tag, $opened = null, $attrs = null) {
		//var_dump($tag);
		$open = '<' . $tag;
		$text = $content;
		if(!$attrs) {
			$attrs = $this->_attributes;
		}
		$close = '';

		if($attrs) {
			$open = $this->_injectElementAttrs($open, $attrs);
		}

		if(!$opened) {
			$close .= '</' . $tag . '>';
		}

		$wrapper = $open . '>' . $text . $close;
		return $wrapper;
	}

	protected function _injectElementAttrs($el, $attrs) {
		foreach ($attrs as $key => $val) {
			if($key[0] === '_') {
				$key = substr($key, 1);
				$el .= ' ' . strtolower($key) . '="' . $val . '"';
			}
		}
		return $el;
	}

	protected function _getStaticFileConfig($type = null) {
		$static = array();
		if(null !== $type) {
			$static[$type] = array();
		}else {
			$script = Kernel_Constants::KERNEL_ROUTES_CONFIG_ROOT . 'static\\scripts';
			$scriptConfig = $this->_parseXmlContent($this->_loader->getFileContent($script, 'xml'));
			$static['script'] = $this->_getValueFromParsedXML($scriptConfig, Kernel_Constants::KERNEL_ROUTES_SCRIPT_ROOT);
		}
	}

	protected function _parseAttributes($attr) {
		$flag = 'off';
		$location = 0;
		$record = '';
		$unreplaced = '';
		$left = '';
		$strlen = strlen($attr);

		for($i=0; $i<$strlen; $i++) {
			$char = substr($attr, $i, 1);

			if($char === '[' && $flag === 'off') {
				if(($strlen > $i + 1) && substr($attr, $i+1, 1) === '*') {
					$flag = 'on';
					$location = $i;
					if($i > 0) {
						$unreplaced = $unreplaced . substr($attr, 0, $i);
					}else {
						$unreplaced = null;
					}
				}
			}else if($flag === 'on' && $char !== '*') {
				if($char === ']') {
					$flag = 'off';
					$left = $left . substr($attr, $i+1);
					break;
				}else {
					//var_dump($record);
					$record = $record . $char;
				}
			}
		}
		//if nothing is to be replaced, take original as unreplaced
		if(!$unreplaced && null !== $unreplaced) {
			$result = $attr;
		}else {
			$result = $unreplaced;
		}

		if($left) {
			$result = $result . $this->replaceData($record) . $this->_parseAttributes($left);
		}else {
			$result = $result . $this->replaceData($record);
		}
		//var_dump($result);
		return $result;
	}
}
