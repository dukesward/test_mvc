<?php

class Kernel_Utils {

	public static function _assembleController($name) {
		$controller = 'Controller_' . $name . Kernel_Constants::KERNEL_ROUTE_CONTROLLER;
		return $controller;
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

	public static function _wrapStr($str, $wrapper) {
		$output = $str;

		if(is_string($wrapper)) {
			 $output = $wrapper . $output . $wrapper;
		}

		return $output;
	}

}