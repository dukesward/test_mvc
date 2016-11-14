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

		if(is_string($wrapper)) {
			$output = $wrapper . $output . $wrapper;
		}

		return $output;
	}

}