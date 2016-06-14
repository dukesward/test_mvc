<?php

class Template_TemplateProcessor {

	protected $_queue;
	protected $_paths;

	public function __construct() {
		$this->_queue = array();
		$this->_paths = array();
	}

	public function addTask($task) {
		$task = array(
			'template' => $task,
		);
		//always add new tasks to beginning of queue
		array_unshift($this->_queue, $task);
	}

	public function addTaskAttr($attr, $val, $accumulative = 0, $template = null) {

		$current = &$this->_queue[0];

		if(!isset($current[$attr])) {
			if(null === $template) {
				if($accumulative) {
					$current[$attr] = array();
					array_push($current[$attr], $val);
				}else {
					$current[$attr] = $val;
				}
			}else {

			}
		}else {
			if($accumulative) {
				//var_dump($val);
				array_push($current[$attr], $val);
			}else {
				$current[$attr] = $val;
			}
		}
		if($accumulative) var_dump($this->_queue[0]);
	}

	public function currentTask() {
		$task = null;

		if(count($this->_queue) > 0) {
			$task = $this->_queue[0];
		}
		return $task;
	}

	public function hasTaskAttr($attr, $target = null) {
		$result = null;

		if(null === $target) {
			$target = &$this->_queue[0];
		}else {
			//var_dump($this->_queue);
		}

		if(isset($target[$attr])) {
			if(is_array($target[$attr])) {
				$result = implode('', $target[$attr]);
			}else {
				$result = $target[$attr];
			}
		}
		return $result;
	}

	public function cutOffAttr($attr, $num) {
		$str = $attr;
		$attr = $this->hasTaskAttr($attr);
		
		$tokens = explode(':', $attr);
		for ($i=0; $i<$num; $i++) {
			array_pop($tokens);
		}

		$attr = implode(':', $tokens);
		$this->addTaskAttr($str, $attr);
		return $attr;
	}

	public function shiftTask() {
		if(count($this->_queue) > 0) {
			array_shift($this->_queue);
		}
	}

	public function registerPath($name, $val) {
		if(!isset($this->_paths[$name])) {
			$this->_paths[$name] = array();
		}

		$path = &$this->_paths[$name];
		$path[$val] = $this->hasTaskAttr('target');
	}

	public function searchPath($name, $val) {
		$path = null;

		if(isset($this->_paths[$name])) {
			foreach ($this->_paths[$name] as $v => $p) {
				if($val === $v) {
					$path = $p;
				}
			}
		}
		return $path;
	}
}