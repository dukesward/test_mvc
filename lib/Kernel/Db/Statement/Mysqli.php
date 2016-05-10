<?php

class Kernel_Db_Statement_Mysqli {

	protected $_query;
	protected $_stmt;
	protected $_class;
	protected $_sqlConsts = array(
		'SQL_SELECT'     => Kernel_Constants::SELECT,
		'SQL_SELECT_ALL' => Kernel_Constants::SELECT_ALL,
		'SQL_FROM'       => Kernel_Constants::FROM,
		'SQL_WHERE'      => Kernel_Constants::WHERE,
	);

	protected function fetchAll() {
		$this->_stmt['select'] = $this->_sqlConsts['SQL_SELECT_ALL'];
		return $this->_stmt;
	}

	protected function prepareSelect($select) {
		if(!is_array($select)) {
			$this->_stmt['select'] = $sql;
		}

		return $this;
	}

	protected function select($sql = null) {

		$preparedSql = $sql;
		if(!$preparedSql) {
			$preparedSql = $this->fetchAll();
		}

		$select = $this->prepareSelect($preparedSql)
					->from()
					->where()
					->accordingTo();

		return $this;
	}

	public function __construct($sql = null, $type) {
		$this->_class = 'mysqli';
		$this->_stmt = array();

		if(method_exists($this, $type)) {
			$this->_stmt['type'] = $type;
			call_user_func(array($this, $type));
		}else {

		}
	}

	public function from($name, $schema = null) {
		if($name) {
			$this->_stmt['from'] = $name;
		}else {

		}

		return $this;
	}

	public function where($condition = null) {
		return $this;
	}

	public function accordingTo($condition = null) {
		return $this;
	}

	public function assemble() {
		$query = $this->_stmt['type']; 

		$query = $query . $this->_stmt['select'] . $this->_sqlConsts['FROM'] . $this->_stmt['from'];

		if($this->_stmt['where']) {
			$query = $query . $this->_sqlConsts['WHERE'] . $this->_stmt['where'];
		};

		$this->_query = $query;
	}

	public function execute($db) {
		if(!$this->_query) {
			$this->assemble();
		}

		var_dump($this->_query);
	}
}