<?php

class Template_TemplateCompiler {

	const TEMP_PATH        = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_ROOT;
	const TEMP_DEFAULT_EXT = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_DEFAULT_EXT;
	const ROOT_BASE        = 'root:';

	protected $_loader;
	protected $_template;
	protected $_transformer;
	protected $_parsed;
	protected $_content;
	protected $_config;
	protected $_processor;

	public function __construct($data) {
		$this->_config = $data;

		$file = $data->path;
		$this->_loader = Util_AutoLoader::getInstance();
		$this->_template = $this->_loader->getFileContent($file, $data->type);
		$this->_transformer = Template_Transformer::getInstance();

		$this->_parsed = $this->_parseXmlContent();
		$this->_content = '';

		$this->_processor = new Template_TemplateProcessor();
		$this->_processor->addTask($file);
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

		$this->_processor->addTask($template);
		$raw = $this->_loadTemplateRawData($template, $ext);
	}

	protected function _checkForNodeInherit($level, $override) {
		$result = null;
		$header = $this->_config->header;

		if($this->_processor->hasTaskAttr('inherit') && $level === 1) {
			$result = true;
			$base = self::ROOT_BASE;

			if(null !== $override) {
				$path = $this->_processor->searchPath('block', $override);
				$result = $base . $path;
			}
			//var_dump($header);
		}
		return $result;
	}

	protected function _processNodeAttrs($el) {
		//var_dump($this->_config->header->getContentAttribute()['root']);
		$attrs = $this->_getTagAttribute($el);

		foreach ($attrs as $attr => $val) {
			switch ($attr) {
				case 'BLOCK':
					break;
			}
		}
	}

	protected function _processNodeConfig($el) {
		$header = $this->_config->header;
		$base = $this->_processor->hasTaskAttr('parent');
		if($el['attributes']['TYPE'] === 'link') var_dump($base);
		
		if(null === $base) {
			$base = '';
		}

		$numOfChildren = $header->getNumOfElements($base . ':children');
		$config = array(
			'parent'     => $this->_processor->hasTaskAttr('parent'),
			'attributes' => Kernel_Utils::_getArrayElement($el, 'attributes'),
			'level'      => $el['level'],
		);
		//var_dump($base . ':children:' . $numOfChildren);
		$header->setTemplateAttributeByArray($config, $base . ':children:' . $numOfChildren);
		//var_dump($header->getContentAttribute($base));
		$this->_processor->addTaskAttr('target', $base . ':children:' . $numOfChildren);
	}

	protected function _processRootConfig($el, $parsed = null) {
		if(null === $parsed) {
			$parsed = $this->_parsed;
		}

		$header = $this->_config->header;
		$rootConfig = $header->getContentAttribute('root');
		$type = $this->_getTagAttribute($el, 'TYPE');

		if($type === 'head' || $type === 'body') {
			foreach ($this->_getTagAttribute($el) as $key => $val) {
				$element = 'root:html:children:' . $type . ':attributes:' . $key;
				$header->setTemplateAttribute($element, $val);
			}
			//record basic type which is either head or body
			if($type === 'head') {
				$base = 'root:html:children:';
			}else {
				$base = 'html:children:';
			}
			$this->_processor->addTaskAttr('target', $base . $type);
			$header->setTemplateAttribute('level', 2);
		}else {
			$this->_processNodeConfig($el);
		}

		$this->_processNodeAttrs($el);
		//$this->_processor->addTaskAttr('root', 'init');
	}

	public function generateContent($parsed = null, $render = null) {
		$trigger = null;
		if(null === $parsed) {
			$parsed = $this->_parsed;
		}
		//var_dump($this->_parsed);
		foreach($parsed as $i => $el) {
			$attributes = Kernel_Utils::_getArrayElement($el, 'attributes');
			$targetStr = $this->_processor->hasTaskAttr('target');
			$header = $this->_config->header;
			$target = $header->getContentAttribute($targetStr);
			$level = Kernel_Utils::_getArrayElement($target, 'level');

			var_dump($target);
			if($el['type'] === 'open' || $el['type'] === 'complete') {
				$trigger = 'on';
				switch(strtolower($el['tag'])) {
					case 'inherit':
						//$this->_markers['inherit'] = true;
						//inherit tag must be the root tag of a template
						if($el['level'] === 1 && null !== $attributes && isset($attributes['TEMPLATE'])) {
							$this->_processor->addTaskAttr('inherit', $attributes['TEMPLATE']);
							$this->_processInherit($attributes);
						}
						break;
					case 'root':
						//$this->_markers['root'] = true;
						$this->_processor->addTaskAttr('root', 'begin');
						break;
					case 'node':
						//assures that root can only be inited once 
						if('begin' === $this->_processor->hasTaskAttr('root')) {
							$this->_processRootConfig($el, $parsed);

							if($level === $el['level'] - 1) {
								$this->_processor->addTaskAttr('parent', $this->_processor->hasTaskAttr('target'), 0);
							}
							//var_dump($this->_processor);
						}else {
							if($level === $el['level'] - 1) {
								//var_dump($level);
								$block = $this->_checkForNodeInherit($level, Kernel_Utils::_getArrayElement($attributes, 'OVERRIDE'));
								if(null !== $block) {
									//var_dump($block);
									$this->_processor->addTaskAttr('parent', $block, 0);
								}else {
									$this->_processor->addTaskAttr('parent', $this->_processor->hasTaskAttr('target'), 0);
								}
								//var_dump($this->_processor);
							}
							$this->_processNodeConfig($el);
							//var_dump($this->_config->header->getContentAttribute('root->html->children->body'));
						}

						if(isset($attributes['BLOCK'])) {
							//var_dump($el);
							$this->_processor->registerPath('block', $attributes['BLOCK']);
						}
						//var_dump($this->_processor->hasTaskAttr('parent'));
						break;
					case 't':
						$value = Kernel_Utils::_getArrayElement($el, 'value');
						$target = $this->_processor->hasTaskAttr('target');
						$path = $target . ':text';

						if(null !== $target) {
							$header->setTemplateAttribute($path, $value);
							$header->setTemplateAttributeByArray($attributes, $target . ':attributes');
						}
						
						//var_dump($this->_config->header->getContentAttribute()['root']['body']);
						break;
				}
			}else if($el['type'] === 'close') {
				switch(strtolower($el['tag'])) {
					case 'root':
						$this->_processor->addTaskAttr('root', 'end');
						break;
				}
				//switch target to be the current parent
				$this->_processor->addTaskAttr('target', $this->_processor->hasTaskAttr('parent'));
				//now the current parent needs to go a level up
				$this->_processor->cutOffAttr('parent', 1);
			}

			if($i === (count($parsed) - 1)) {
				$this->_processor->shiftTask();
			}

			$this->_processor->addTaskAttr('level', $el['level']);
		}

		if(null !== $render) {
			return $this->_transformer->render($this->_config->header->getContentAttribute());
		}
	}
}