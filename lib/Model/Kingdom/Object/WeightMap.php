<?php

class Model_Kingdom_Object_WeightMap {

	protected $_map;

	public function __construct() {
		$this->_map = array();
	}

	public function feed($key, $weight = null) {
		if($weight && is_int((int)$weight)) {
			$this->_map[$key] = (int)$weight;
		}else {
			$this->_map[$key] = 0;
		}
	}

	public function feedArr($arr) {
		foreach ($arr as $k => $v) {
			$this->feed($k, $v);
		}
	}

	public function makeDecision($debug = 0) {
		$total = $this->_calculateTotal();
		$decision = null;
		if($total > 0) {
			$random = Kernel_Utils::_createRandomNumber(0, 1, $total);
			$decision = $this->_findDecision($random, 1);
		}
		return $decision;
	}

	public function multipleDecision($num = 1) {
		$decisions = array();
		for($i=0; $i<$num; $i++) {
			array_push($decisions, $this->makeDecision(1));
		}
		return $decisions;
	}

	protected function _calculateTotal() {
		$total = 0;
		foreach ($this->_map as $key => $val) {
			$total += $val;
		}
		return $total;
	}

	protected function _findDecision($ticket, $debug = 0) {
		$start = 0;
		$decision = null;

		foreach ($this->_map as $key => $val) {
			$start += $val;
			if($start >= $ticket) {
				$decision = $key;
				return $decision;
			}
		}
		return $decision;
	}
}
