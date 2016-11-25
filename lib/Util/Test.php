<?php

class Util_Test {

	public static function test() {
		$str = "{a}*5+{b}";
		$arr = array(
			"a" => 3
		);

		$pattern = "/\{(\w+?)\}/i";
		preg_match_all($pattern, $str, $matches);
		//var_dump($matches);
		$replaced = preg_replace_callback($pattern, function($matches) {
			$arr = array(
				"a" => 3,
				"b" => 4
			);
			return $arr[$matches[1]];
		}, $str);
		var_dump($replaced);
		die();
	}
}