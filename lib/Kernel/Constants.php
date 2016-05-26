<?php

class Kernel_Constants {

	const UTIL_STRING_WRAPPER     = "'";

	const KERNEL_ROUTE_CONTROLLER = "Controller";
	const KERNEL_ROUTE_CONTROLLER_NAMESPACE = "Controller_";
	const KERNEL_ROUTE_ACTION     = "Action";
	
	const DB_SQL_FROM             = "FROM";
	const DB_SQL_SELECT           = "SELECT";
	const DB_SQL_SELECT_ALL       = "*";
	const DB_SQL_VALUE            = "VALUE";
	const DB_SQL_WHERE            = "WHERE";
	const DB_SQL_WRAPPER          = "`";
	const DB_SQL_DELIMITER        = " ";

	const MODEL_ROUTES            = "kernel_model_routes";
	const MODEL_ROUTES_SPLITTER   = "/";
	const MODEL_ROUTES_DEFAULT    = "default";
	const MODEL_ROUTES_EXCEPTION  = "exception";
	const MODEL_ROUTES_PRIME      = "name";

	const MODEL_NODE_DETAILS      = "cms_node_details";
	const MODEL_NODE_ROUTE_PRIME  = "pattern";
}