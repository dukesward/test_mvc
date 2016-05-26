<?php

class Kernel_Db_Statement_Mysqli {

	protected $_query;
	protected $_stmt;
	protected $_class;
	protected $_configs;
	protected $_sqlConsts = array(
		'STRING_WRAPPER' => Kernel_Constants::UTIL_STRING_WRAPPER,
		'SQL_SELECT'     => Kernel_Constants::DB_SQL_SELECT,
		'SQL_SELECT_ALL' => Kernel_Constants::DB_SQL_SELECT_ALL,
		'SQL_FROM'       => Kernel_Constants::DB_SQL_FROM,
		'SQL_WHERE'      => Kernel_Constants::DB_SQL_WHERE,
		'SQL_DELIMITER'  => Kernel_Constants::DB_SQL_DELIMITER,
		'SQL_WRAPPER'    => Kernel_Constants::DB_SQL_WRAPPER,
		'SQL_TOKENS'     => array('type', 'select', 'sql_from', 'from', 'sql_where', 'where'),
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

	public function from($table = null, $schema = null) {
		if($table) {
			if(is_string($table)) {
				$this->_stmt['from'] = Kernel_Utils::_wrapStr($table, $this->_sqlConsts['SQL_WRAPPER']);
			}
		}else {

		}

		return $this;
	}

	public function where($condition = null) {
		if($condition) {
			var_dump($condition);
			$key = Kernel_Utils::_wrapStr($condition['key'], $this->_sqlConsts['SQL_WRAPPER']);
			$value = Kernel_Utils::_wrapStr($condition['value'], $this->_sqlConsts['STRING_WRAPPER']);
			$this->_stmt['where'] = $key . ' = ' . $value;
		}

		return $this;
	}

	public function accordingTo($condition = null) {
		return $this;
	}

	public function assemble() {
		if(isset($this->_stmt['from'])) {
			$this->_stmt['sql_from'] = $this->_sqlConsts['SQL_FROM'];
		};
		
		if(isset($this->_stmt['where'])) {
			$this->_stmt['sql_where'] = $this->_sqlConsts['SQL_WHERE'];
		};

		$query = Kernel_Utils::_concat($this->_stmt, $this->_sqlConsts['SQL_TOKENS'], $this->_sqlConsts['SQL_DELIMITER'], 'each');
		var_dump($query);
		$this->_query = $query;
	}

	public function resolveTableConfigs($configs) {
		if(is_array($configs)) {
			if(isset($configs['table'])) {
				$this->from($configs['table']);
			}

			if(isset($configs['query'])) {
				foreach ($configs['query'] as $key => $config) {
					switch ($key) {
						case 'where':
							$this->where($config);
							break;
					}
				}
			}

			$this->_configs = $configs;
		}
	}

	public function execute($connection, $cols = null) {
		$fields = array();
		$_fields = &$fields;
		$table = new Kernel_Db_Table($this->_class, $_fields);

		if($this->_configs) {
			$table->resolveConfigs($this->_configs);
		}

		if(!$this->_query) {
			$this->assemble();
		}

		$stmt = $connection->prepare($this->_query);

		try {
			if($stmt != NULL && $result = $stmt->execute()) {
				$meta = $stmt->result_metadata();
				$keys = array();

				$keyId = 0;

				foreach ($meta->fetch_fields() as $col) {
					//fill table fields with metadata
					$keys[$col->name] = $col;
					$keys[$col->name]->id = $keyId;
					$keyId ++;
				}

				$table->generateFields($keys);
				$data = array_fill(0, count($keys), null);

				$refs = array();
	            foreach ($data as $i => &$f) {
	                $refs[$i] = &$f;
	            }

				$stmt->store_result();

				call_user_func_array(
					array($stmt, 'bind_result'),
					$data
				);

				$index = 0;

				while ($row = $stmt->fetch()) {
					foreach ($data as $key => $value) {
						$table->fillData($key, $value, $index);
					}
					$index ++;
				}

				$stmt->close();
			}
		}catch (Exception $e) {

		}
		
		return $table;

	}
}