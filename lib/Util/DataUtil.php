<?php

class Util_DataUtil {

	protected static function generateRandomNumber($decimal, $min, $max) {
		$rand = mt_rand($min, $max);
		$factor = pow(10, $decimal);

		return round($rand*$factor)/$factor;
	}

	public static function roundWithDecimal($num, $decimal) {
		return round($num*pow(10, $decimal))/pow(10, $decimal);
	}

	public static function createRandomData($size, $decimal, $min, $max) {
		$data = array();
			
		for($i=0; $i<$size; $i++) {
			$d = self::generateRandomNumber($decimal, $min, $max);
			array_push($data, $d);
		}
		return $data;
	}

	public static function generateSequencedData($min, $max, $interval) {
		$data = array();

		$total = round(($max - $min)/$interval);
		for($i=0; $i<$total; $i++) {
			$next = $min + $i*$interval;

			if($next <= $max) {
				array_push($data, $next);
			}
		}
		return $data;
	}

	public static function generateSequencedDataFromSize($min, $max, $size, $ac = 2) {
		$data = array();

		$interval = self::roundWithDecimal(($max - $min)/$size, $ac);
		for($i=0; $i<$size; $i++) {
			$next = $min + $i*$interval;

			if($next <= $max) {
				array_push($data, $next);
			}
		}
		return $data;
	}

	public static function generateMap($mapX, $mapY) {
		$map = new Util_Data_Map($mapX, $mapY);
		return $map;
	}

	public static function expandMap($map, $proto) {
		$diff = $map->differentiate();
		$y_diff = $diff->getY();

		$x = $proto->getX();
		//$y = $proto->getY();

		$x_train = $map->getX();
		$y_train = $map->getY();

		$size = sizeof($x_train);
		$min = $x_train[0];
		$max = $x_train[$size - 1];

		$x_exp = self::generateSequencedDataFromSize($min, $max, sizeof($x), 4);
		$interval = self::roundWithDecimal(($max - $min)/sizeof($x), 4);
		//var_dump($x_exp);die();
		$y_exp = array();

		array_push($y_exp, $y_train[0]);
		for($i=1; $i<sizeof($x); $i++) {
			$index = floor($x_exp[$i]);
			$next = $y_diff[$index] * $interval + $y_exp[$i-1];
			//$next_f = floor($next);
			array_push($y_exp, $next);
		}
		//var_dump($differentiated);
		return $y_exp;
	}
}