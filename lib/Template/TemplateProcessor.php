<?php

class Template_TemplateProcessor {

	protected $_queue;

	public function __construct() {
		$this->_queue = array();
	}

	public function addTask($task) {
		$task = array(
			'template' => $task,
		);
		//always add new tasks to beginning of queue
		array_unshift($this->_queue, $task);
	}

	public function addTaskAttr($attr, $val, $uniqueness = 0, $template = null) {
		if(!isset($this->_queue[$attr]) || $uniqueness !== 1) {
			if(null === $template) {
				$current = &$this->_queue[0];
				$current[$attr] = $val;
			}else {

			}
		}
	}

	public function currentTask() {
		$task = null;

		if(count($this->_queue) > 0) {
			$task = $this->_queue[0];
		}
		return $task;
	}

	public function hasTaskAttr($attr, $val = null) {
		$result = null;
		$current = &$this->_queue[0];

		if(isset($current[$attr])) {
			$result = $current[$attr];
		}
		return $result;
	}

	public function shiftTask() {
		if(count($this->_queue) > 0) {
			array_shift($this->_queue);
		}
	}
}