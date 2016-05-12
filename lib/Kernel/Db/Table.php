<?php

class Kernel_Db_Table {

	protected $_fields;
	protected $_prime;
	protected $_data;
	protected $_db;

	protected function _createTable($data) {
		$table = array();
		$i = 0;

		foreach ($this->_fields as $key => $value) {
			if(isset($data[$i])) {
				$table[$key] = $data[$i];
			}
			$i ++;
		}

		return $table;
	}

	public function __construct($db, $fields) {
		$this->_db = $db;
		$this->_fields = $fields;
	}

	public function setPrime($prime) {
		$this->_prime = $prime;
	}

	public function fetchData($prime = null, $key = null) {
		$output = $this->_data;

		if($prime && $this->_prime) {
			for ($i=0; $i<count($this->_data); $i++) {
				if($this->_data[$i][$this->_fields[$this->_prime]->id] === $prime) {
					$output = $output[$i];
				}
			}
		}

		return $this->_createTable($output);
	}

	public function fillData($col, $row, $index = null, $type = null) {
		if($index !== NULL) {
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

	public function resolveConfigs($configs) {
		if(isset($configs['prime']) && is_string($configs['prime'])) {
			$this->_prime = $configs['prime'];
		}
	}
}