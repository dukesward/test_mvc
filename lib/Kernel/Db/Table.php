<?php

class Kernel_Db_Table {

	protected $_fields;
	protected $_prime;
	protected $_data;
	protected $_db;

	protected function _createTable($data) {
		$table = array();
		$i = 0;
		$ii = 0;

		foreach($data as $id => $config) {
			$table[$i] = array();
			foreach ($this->_fields as $key => $value) {
				if(isset($config[$ii])) {
					$table[$i][$key] = $config[$ii];
				}
				$ii ++;
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

	public function fetchData($query = null) {
		$data = $this->_data;

		if(null === $query) {
			$output = $data;
		}else {
			$output = array();
		}

		if(isset($query['prime']) && $this->_prime) {
			for ($i=0; $i<count($data); $i++) {
				if($data[$i][$this->_fields[$this->_prime]->id] === $query['prime']) {
					$output[$query['prime']] = $data[$i];
				}
			}
		}

		if(isset($query['keys'])) {
			for ($i=0; $i<count($data); $i++) {
				$matched = true;
				foreach ($query['keys'] as $key => $value) {
					$to_match = $data[$i][$this->_fields[$key]->id];
					if(!Kernel_Utils::_match($to_match, $value)) {
						$matched = false;
						break;
					}
				}
				if($matched) {
					$_id = $this->_fields[$this->_prime]->id;
					$id = $data[$i][$_id];
					$output[$id] = $data[$i];
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