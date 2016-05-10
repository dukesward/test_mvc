<?php

class Model_Utils_RouteProcessor extends Model_CoreProcessor {

	protected static $_instance;
	protected static $_className = 'Model_Utils_RouteProcessor';
	protected $_table;

	protected function __construct($table = null) {
		if($table) {
			$this->_table = $table;
		}else {
			$this->_table = Kernel_Db_Adapter::getDbAdapter()->getDbConfigTable();
		}
	}

}