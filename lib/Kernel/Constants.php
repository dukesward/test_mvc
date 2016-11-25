<?php

class Kernel_Constants {

	const UTIL_STRING_WRAPPER                = "'";

	const HTML_5_DOCTYPE                     = '<!DOCTYPE html>';

	const KERNEL_ROUTE_CONTROLLER            = "Controller";
	const KERNEL_ROUTE_CONTROLLER_NAMESPACE  = "Controller_";
	const KERNEL_ROUTE_ACTION                = "Action";
	const KERNEL_ROUTES_VIEW_ROOT            = "view\\";
	const KERNEL_ROUTES_TEMPLATE_ROOT        = "view\\templates\\";
	const KERNEL_ROUTES_TEMPLATE_CONFIG_ROOT = "view\\configs\\";
	const KERNEL_ROUTES_SCRIPT_ROOT          = "view\\scripts\\";
	const KERNEL_ROUTES_IMAGE_ROOT           = "view\\images";
	const KERNEL_ROUTES_CONFIG_ROOT          = "config\\";
	const KERNEL_ROUTES_CONFIG_EXT           = "conf";
	const KERNEL_ROUTES_TEMPLATE_DEFAULT_EXT = "xml";

	const CACHE_CACHE_BASE                   = "cache\\";
	const CACHE_SCRIPT_EXT                   = "js";
	
	const DB_SQL_FROM                        = "FROM";
	const DB_SQL_SELECT                      = "SELECT";
	const DB_SQL_SELECT_ALL                  = "*";
	const DB_SQL_COUNT                       = "COUNT(*)";
	const DB_SQL_AS                          = "AS";
	const DB_SQL_REPLACE                     = "REPLACE INTO";
	const DB_SQL_UPDATE                      = "UPDATE";
	const DB_SQL_INSERT                      = "INSERT INTO";
	const DB_SQL_VALUE                       = "VALUE";
	const DB_SQL_WHERE                       = "WHERE";
	const DB_SQL_SET                         = "SET";
	const DB_SQL_WRAPPER                     = "`";
	const DB_SQL_DELIMITER                   = " ";

	const MODEL_ROUTES                       = "kernel_model_routes";
	const MODEL_ROUTES_SPLITTER              = "/";
	const MODEL_ROUTES_FILE_SPLITTER         = "\\";
	const MODEL_ROUTES_EXT                   = ".";
	const MODEL_ROUTES_DEFAULT               = "default";
	const MODEL_ROUTES_EXCEPTION             = "exception";
	const MODEL_ROUTES_PRIME                 = "name";

	const MODEL_NODE_DETAILS                 = "cms_node_details";
	const MODEL_NODE_TEMPLATE                = "cms_node_template";
	const MODEL_NODE_ROUTE_PRIME             = "pattern";
	const MODEL_NODE_DETAILS_PRIME           = "nid";

	const MODEL_COMMON                       = "common_node_config";
	const MODEL_COMMON_PRIME                 = "config";

	const MODEL_CARD_DETAILS                 = "words_general";
	const MODEL_CARD_DETAILS_PRIME           = "id";

	const MODEL_MALL_DB                      = "mall";
	const MODEL_MALL_GENERAL                 = "general";
	const MODEL_MALL_GENERAL_PRIME           = "name";
	const MODEL_MALL_DATA                    = "mall_data";
	const MODEL_MALL_DATA_PRIME              = "name";

	protected static $consonants = array(
		'b','c','d','f','g','h','j','k','l','m','n','p','r','s','t','v','w','z'
	);

	protected static $vowals = array(
		'a','e','i','o','u'
	);

	protected static $wildcards = array(
		'b','d','e','f','g','h','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z'
	);

	public static function getConsonants() {
		return self::$consonants;
	}

	public static function getVowals() {
		return self::$vowals;
	}

	public static function getWildcards() {
		return self::$wildcards;
	}

	protected static $attrMap = array(
		"str" => "strength",
		"agi" => "agility",
		"sta" => "stamina",
		"int" => "intelligence",
		"spr" => "spirit",
		"luc" => "luck"
	);

	protected static $propCollection = array(
		"hp_max","sp_max","ap","hit","eva","blk","crt"
	);

	protected static $equipParts = array(
		"head","shoulder","chest","hand","waist","leg","foot","main","off","finger"
	);

	protected static $playerInfo = array(
		"id","name","level","gender","class","class_alt","race","attrs","props","sp_type","equips","abilities","hp","sp","exp","exp_next"
	);

	public static function getPlayerAttrMap() {
		return self::$attrMap;
	}

	public static function getPlayerAttrBriefs() {
		$briefs = array();
		foreach (self::$attrMap as $b => $a) {
			array_push($briefs, $b);
		}
		return $briefs;
	}

	public static function getPlayerProps() {
		return self::$propCollection;
	}

	public static function getEquipParts() {
		return self::$equipParts;
	}

	public static function getPlayerInfo() {
		return self::$playerInfo;
	}
}