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
		'SQL_AS'         => Kernel_Constants::DB_SQL_AS,
		'SQL_REPLACE'    => Kernel_Constants::DB_SQL_REPLACE,
		'SQL_UPDATE'     => Kernel_Constants::DB_SQL_UPDATE,
		'SQL_INSERT'     => Kernel_Constants::DB_SQL_INSERT,
		'SQL_FROM'       => Kernel_Constants::DB_SQL_FROM,
		'SQL_SET'        => Kernel_Constants::DB_SQL_SET,
		'SQL_WHERE'      => Kernel_Constants::DB_SQL_WHERE,
		'SQL_DELIMITER'  => Kernel_Constants::DB_SQL_DELIMITER,
		'SQL_WRAPPER'    => Kernel_Constants::DB_SQL_WRAPPER,
		'SQL_TOKENS'     => array('type', 'select', 'sql_as', 'as', 'sql_from', 'from', 'sql_set', 'set', 'sql_where', 'where'),
	);

	protected function fetchAll() {
		$this->_stmt['select'] = $this->_sqlConsts['SQL_SELECT_ALL'];
		return $this->_stmt;
	}

	protected function prepareSelect($select) {
		if(!is_array($select)) {
			$this->_stmt['select'] = $select;
		}

		return $this;
	}

	protected function select($sql = null) {
		//$preparedSql = $sql;
		if(!$sql) {
			$preparedSql = $this->fetchAll();
		}else {
			$preparedSql = $sql[0];
		}

		$select = $this->prepareSelect($preparedSql)
					->from()
					->where()
					->accordingTo();

		return $this;
	}

	protected function update() {
		$this->set()
			->where();

		return $this;
	}

	protected function insert() {
		$this->_stmt['type'] = $this->_sqlConsts['SQL_INSERT'];
		$this->set();
		return $this;
	}

	public function __construct($sql = null, $type) {
		$this->_class = 'mysqli';
		$this->_stmt = array();

		if(method_exists($this, $type)) {
			$this->_stmt['type'] = $type;
			if($sql) {
				call_user_func(array($this, $type), array($sql));
			}else {
				call_user_func(array($this, $type));
			}
			
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

	public function set($attrs = null) {
		if(is_array($attrs)) {
			$this->_stmt['set'] = '';
			foreach ($attrs as $attr => $value) {
				$st = Kernel_Utils::_wrapStr($attr, $this->_sqlConsts['SQL_WRAPPER'])
					. '='
					. Kernel_Utils::_wrapStr($value, $this->_sqlConsts['STRING_WRAPPER'])
					. ',';

				$this->_stmt['set'] .= $st;
			}
			$this->_stmt['set'] = substr($this->_stmt['set'], 0, -1);
		}

		return $this;
	}

	public function where($condition = null) {
		if($condition) {
			//var_dump($condition);
			$key = Kernel_Utils::_wrapStr($condition['key'], $this->_sqlConsts['SQL_WRAPPER']);
			$value = Kernel_Utils::_wrapStr($condition['value'], $this->_sqlConsts['STRING_WRAPPER']);
			
			$this->_stmt['where'] = $key . ' = ' . $value;
		}

		return $this;
	}

	public function whereN($condition = null) {
		if($condition) {
			//var_dump($condition);
			foreach ($condition as $k => $v) {
				$key = Kernel_Utils::_wrapStr($k, $this->_sqlConsts['SQL_WRAPPER']);
				$value = Kernel_Utils::_wrapStr($v, $this->_sqlConsts['STRING_WRAPPER']);
				if(isset($this->_stmt['where'])) {
					$this->_stmt['where'] .= ' && ' . $key . ' = ' . $value;
				}else {
					$this->_stmt['where'] = $key . ' = ' . $value;
				}
			}

		}
		return $this;
	}

	public function asVar($var = null) {
		if($var) {
			$this->_stmt['as'] = $var;
		}
	}

	public function accordingTo($condition = null) {
		return $this;
	}

	public function assemble() {
		if(isset($this->_stmt['from'])) {
			if($this->_stmt['type'] === 'SELECT') {
				$this->_stmt['sql_from'] = $this->_sqlConsts['SQL_FROM'];
			}
		};

		if(isset($this->_stmt['as'])) {
			$this->_stmt['sql_as'] = $this->_sqlConsts['SQL_AS'];
		}

		if(isset($this->_stmt['set'])) {
			$this->_stmt['sql_set'] = $this->_sqlConsts['SQL_SET'];
		}
		
		if(isset($this->_stmt['where'])) {
			$this->_stmt['sql_where'] = $this->_sqlConsts['SQL_WHERE'];
		};
		//var_dump($this->_stmt);
		$query = Kernel_Utils::_concat($this->_stmt, $this->_sqlConsts['SQL_TOKENS'], $this->_sqlConsts['SQL_DELIMITER'], 'each');
		//if($this->_stmt['type'] === 'INSERT INTO') var_dump($query);
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
						case 'where_n':
							$this->whereN($config);
							break;
						case 'set':
							$this->set($config);
							break;
						case 'as':
							$this->asVar($config);
							break;
					}
				}
			}

			$this->_configs = $configs;
		}
	}

	public function execute($connection, $debug = false) {
		$fields = array();
		$_fields = &$fields;
		$table = new Kernel_Db_Table($this->_class, $_fields);

		if($this->_configs) {
			$table->resolveConfigs($this->_configs);
		}

		if(!$this->_query) {
			$this->assemble();
		}
		//var_dump($this->_query);
		if($debug) var_dump($this->_query);
		$stmt = $connection->prepare($this->_query);

		try {
			if($stmt != NULL && $result = $stmt->execute()) {
				$meta = $stmt->result_metadata();
				$keys = array();

				$keyId = 0;
				
				if(null !== $meta && $meta) {
					foreach ($meta->fetch_fields() as $col) {
						//fill table fields with metadata
						$keys[$col->name] = $col;
						$keys[$col->name]->id = $keyId;
						$keyId ++;
					}
				}

				$table->generateFields($keys);

				if(count($keys) > 0) {
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
				}
				
				$stmt->close();
			}
		}catch (Exception $e) {

		}
		
		return $table;

	}
}