<?php

class Util_Data_Map {

	private $_map = array();
	private $_x;
	private $_y;

	public function __construct($x, $y) {
		$this->_x = $x;
		$this->_y = $y;

		foreach($x as $i => $d) {
			if($d < sizeof($y)) {
				$this->_map[$d] = $y[$d];
			}
		}
	}

	public function getX() {
		return $this->_x;
	}

	public function getY() {
		return $this->_y;
	}

	public function getInterval() {
		$interval = 0;
		if(sizeof($this->_x) > 0) {
			$interval = $this->_x[1] - $this->_x[0];
		}
		return $interval;
	}

	public function getSum() {
		$sum = 0;
		foreach ($this->_y as $y) {
			$sum += $y;
		}
		return $sum;
	}

	public function differentiate() {
		$x_diff = $this->_x;
		array_shift($x_diff);
		$y_diff = array();

		if(sizeof($x_diff) > 0) {
			$x_0 = $this->_x[0];
			$y_0 = $this->_y[0];
			for ($i=1; $i<sizeof($this->_x); $i++) {
				$x_1 = $this->_x[$i];
				$y_1 = $this->_y[$i];
				$diff = ($y_1 - $y_0)/($x_1 - $x_0);
				array_push($y_diff, $diff);
				$x_0 = $x_1;
				$y_0 = $y_1;
			}
		}else {
			array_push($y_diff, 0);
		}

		return new Util_Data_Map($x_diff, $y_diff);
	}
}