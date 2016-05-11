<?php

class Kernel_Db_Table {

	protected $_fields;
	protected $_prime;
	protected $_data;
	protected $_db;

	public function __construct($db, $fields) {
		$this->_db = $db;
		$this->_fields = $fields;
	}

	public function fetchData($prime = null, $key = null) {
		$output = $this->_data;

		if($prime && $this->_keys[$prime]) {
			foreach ($this->_data as $i => $d) {
				if($d[$this->_prime] === $prime) {
					$output = $output[$i];
				}
			}
		}

		return $output;
	}

	public function fillData($col, $row, $index = null, $type = null) {
		if($index) {
			$this->_data[$index][$col] = $row;
		}else {

		}
	}

	public function generateFields($keys) {
		//fill data matrix will nulls according to num of keys
		if(is_array($keys)) {
			$this->_fields = $keys;
			//$this->_data = array_fill(0, count($keys), null);
		}
	}
}