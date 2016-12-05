<?php

class Kernel_Utils {

	public static function _assembleController($name) {
		$controller = 'Controller_' . $name . Kernel_Constants::KERNEL_ROUTE_CONTROLLER;
		return $controller;
	}

	public static function _assembleFilePath($path, $ext) {
		$file;

		if(is_string($path)) {
			$file = $path . Kernel_Constants::MODEL_ROUTES_EXT . $ext;
		}else if(is_array($path)) {

		}
		return $file;
	}

	public static function _camelStyleString($string, $delimiter = null, $connector = '', $firstCapital = 0) {
		$result = '';

		if(null === $delimiter) {
			$delimiter = '_';
		}

		if(is_string($string)) {
			$tokens = explode($delimiter, $string);

			if($firstCapital) {
				$result = ucwords(implode($connector, $tokens));
			}else {
				foreach ($tokens as $token) {
					$result = $result . ucfirst($token);
				}
			}
			
		}

		return $result;
	}

	public static function _createRandomEnChar() {
		$consonants = Kernel_Constants::getConsonants();
		$vowels = Kernel_Constants::getVowals();
		$wildcards = Kernel_Constants::getWildcards();

		$numOfSyllables = self::_createRandomNumber(0, 2, 3);
		$tokens = array();
		$name = '';

		for($i=0; $i<$numOfSyllables; $i++) {
			if($i == 0) {
				$head = self::_selectRandomFromArray(array_merge($consonants, $vowels));
			}else {
				$lastHead = substr($tokens[$i - 1], 0, 1);
				if(in_array($lastHead, $vowels)) {
					$head = self::_selectRandomFromArray($consonants);
				}else {
					$head = self::_selectRandomFromArray($vowels);
				}
			}

			$head .= self::_selectRandomFromArray($vowels);
			if($i == 0 || strlen($tokens[$i - 1]) < 3) {
				if(self::_decide()) {
					$head .= self::_selectRandomFromArray($wildcards);
				}
			}
			array_push($tokens, $head);
		}

		foreach($tokens as $t) {
			$name .= $t;
		}
		return $name;
	}

	public static function _createRandomChChar() {
		$first = self::_createRandomHex('b','d');
		if($first === 'd') {
			$second = self::_createRandomHex(0, 7);
		}else {
			$second = self::_createRandomHex(0, 'f');
		}
		$third = self::_createRandomHex('a', 'f');
		if($third === 'a') {
			$forth = self::_createRandomHex(1, 'f');
		}else if($third === 'f') {
			$forth = self::_createRandomHex(0, 'e');
		}else {
			$forth = self::_createRandomHex(0, 'f');
		}

		$random = chr('0x' . $first . $second) . chr('0x' . $third . $forth);
		$ch = iconv('GB2312', 'UTF-8', $random);

		return $ch;
	}

	public static function _createRandomHex($from, $to) {
		$diff = (int)hexdec($to) - (int)hexdec($from);
		$hex = 0;

		if($diff > 0) {
			$dec = self::_createRandomNumber(0, hexdec($from), hexdec($to));
		}
		return dechex($dec);
	}

	public static function _createRandomNumber($decimal, $min, $max) {
		$rand = mt_rand($min, $max);
		$factor = pow(10, $decimal);

		return round($rand*$factor)/$factor;
	}

	public static function _concat($source, $tokens, $delimiter, $type) {
		$output = '';

		if(is_array($source)) {
			foreach ($tokens as $token) {
				foreach ($source as $key => $value) {
					if($key === $token) {
						if($type === 'each' && $token !== $tokens[0]) {
							$output = $output . $delimiter;
						}
						$output = $output . $value;
					}
				}
			}
		}

		return $output;
	}

	public static function _decide() {
		$decision = self::_createRandomNumber(0, 0, 1);
		return $decision == 0 ? false : true;
	}

	public static function _elementInArray($arr, $el) {
		$result = false;

		foreach ($arr as $e) {
			if($el === $e) $result = true;
		}
		return $result;
	}

	public static function _expandArray($num, $proto = null) {
		$expanded = array();
		if(is_array($proto)) {
			for($i=0; $i<$num; $i++) {
				$expanded = array_merge($expanded, $proto);
			}
		}
		return $expanded;
	}

	public static function _endKey($source) {
		$key = '';

		if(is_array($source)) {
			end($source);
			$key = key($source);
		}

		return $key;
	}

	public static function _getArrayElement($arr, $key) {
		$tokens = explode('->', $key);
		$el = null;

		if(is_array($arr)) {
			foreach ($tokens as $token) {
				if(isset($arr[$token])) {
					$el = $arr[$token];
					$arr = $arr[$token];
				}else {
					$el = null;
				}
			}
		}

		return $el;
	}

	public static function _getValueFromParsedXML($xml, $root = '') {
		$values = array();
		$name = null;

		foreach ($xml as $el) {
			$tag = $el['tag'];
			if($tag === 'FILES') {
				if($el['type'] === 'open') {
					$name = Kernel_Utils::_getArrayElement($el, 'attributes->NAME');
					$values[$name] = array();
				}
			}else if ($tag === 'FILE' && null !== $name) {
				if($el['type'] === 'complete') {
					array_push($values[$name], $root . Kernel_Utils::_getArrayElement($el, 'value'));
				}
			}
		}
		return $values;
	}

	public static function _isStringInArray($arr, $str) {
		$inArr = false;
		if(is_array($arr) && is_string($str)) {
			for($i=0; $i<sizeof($arr); $i++) {
				if($str === $arr[$i]) {
					$inArr = true;
					break;
				}
			}
		}
		return $inArr;
	}

	public static function _isHtmlSingleTag($tag) {
		$single_types = array(
			'link', 'img',
		);
		return self::_isStringInArray($single_types, $tag);
	}

	public static function _match($str, $match) {
		$result = false;

		if(null !== $str && is_string($str) && is_string($match)) {
			if($match === '*') {
				$result = true;
			}else {
				$result = ($str === $match);
			}
		}
		return $result;
	}

	public static function _parseXmlContent($template = null) {
		if(null === $template) {
			$template = $this->_template;
		}

		$parser = xml_parser_create();
		xml_parse_into_struct($parser, $template, $values);
		return $values;
	}

	public static function _processGlobalTime($day, $time, $duration) {
		$processed = array();
		$times = explode('|', $time);
		$days = explode('|', $day);
		//for test only
		//$duration = 9999;

		$hour = $times[0];
		$minute = $times[1];
		$d = $days[2];
		$m = $days[1];
		$y = $days[0];

		$minute += $duration;
		while($minute > 59) {
			$minute -= 59;
			$hour += 1;
			if($hour > 23) {
				$hour -= 23;
				$d += 1;
				if($d > 30) {
					$d -= 30;
					$m += 1;
					if($m > 12) {
						$m -= 12;
						$y ++;
					}
				}
			}
		}

		$processed['day'] = $y . '|' . $m . '|' . $d;
		$processed['time'] = $hour . '|' . $minute;
		return $processed;
	}

	public static function _processUrl($url, $type) {
		$_url = '';

		if(is_string($url)) {
			$_url = $url;
		}

		switch($type) {
			case 'raw':
				$_url = Kernel_Constants::MODEL_ROUTES_SPLITTER . $_url;
				break;
		}

		return $_url;
	}

	public static function _query($arr, $key, $value, $name = null) {
		$val = null;
		foreach($arr as $a) {
			if(self::_getArrayElement($a, $key) === $value) {
				if($name) {
					$val = self::_getArrayElement($a, $name);
				}else {
					$val = $a;
				}
				
			}
		}
		return $val;
	}

	public static function _filter($arr, $key, $obj, $process) {
		$val = array();
		foreach($arr as $a) {
			$value = self::_getArrayElement($a, $key);
			if(call_user_func($process, $value, $obj)) {
				//$val = self::_getArrayElement($a, $name);
				array_push($val, $a);
			}
		}
		return $val;
	}

	public static function _selectRandomFromArray($arr) {
		$size = sizeof($arr);
		$random = self::_createRandomNumber(0, 0, $size - 1);
		return $arr[$random];
	}

	public static function _templatePathToId($path) {
		$id = null;
		$templateRoot = Kernel_Constants::KERNEL_ROUTES_TEMPLATE_ROOT;

		if(is_string($path) && strpos($path, $templateRoot) === 0) {
			$template = explode($templateRoot, $path);
			$tokens = explode('\\', $template[1]);
			$id = 'template_' . implode('_', $tokens);
		}

		return $id;
	}

	public static function _wrapStr($str, $wrapper) {
		$output = $str;
		//var_dump($output);
		if(is_string($wrapper)) {
			$output = $wrapper . $output . $wrapper;
		}

		return $output;
	}

}