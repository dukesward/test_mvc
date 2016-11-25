<?php

class Template_TemplateProcessorRefined {

	private $_config;
	private $_compiler;
	private $_root;

	public function __construct(Template_Config $config, Template_TemplateCompiler $compiler) {
		//var_dump($config->header);
		$this->_config = $config;
		$this->_compiler = $compiler;
	}

	public function setRoot($root, $child = null) {
		if($root) {
			$temp = $root;
			$tempNode = $this->_processNodeElement($temp);
			if($tempNode->getNodeTag() === 'INHERIT') {
				$template = $tempNode->getNodeAttr('TEMPLATE');
				$ext = $tempNode->getNodeAttr('EXT');

				$root = $this->_compiler->loadTemplateConfig($template, $ext);
				//var_dump($tempNode);
				if($child) {
					//var_dump($temp);
					$temp->setChild($child);
				}
				$this->setRoot($root, $tempNode);
			}else {
				$rootNode = $tempNode;
				$this->_root = $rootNode;
				if($child) {
					$this->_root->setChild($child);
				}
			}
		}
	}

	public function hasRoot() {
		return $this->_root;
	}

	public function render() {
		return $this->_root->renderRootNode();
	}

	protected function _processNodeElement($config) {
		$node = new Template_NodeElement($config[0], $this->_config);
		for($i=1; $i<sizeof($config); $i++) {
			$c = $config[$i];
			$tag = Kernel_Utils::_getArrayElement($c, 'tag');
			$type = Kernel_Utils::_getArrayElement($c, 'type');
			$level = Kernel_Utils::_getArrayElement($c, 'level');
			//var_dump($c);
			if($level === $node->getNodeLevel() + 1) {
				if($type === 'open') {
					//var_dump($i);
					$nodeConfig = $this->_compiler->extractInnerContent($config, $i, $level, $tag);
					$node->setChild($this->_processNodeElement($nodeConfig));
				}else if($type === 'complete') {
					$node->setChild(new Template_NodeElement($c, $this->_config));
				}
			}
		}
		return $node;
	}

}