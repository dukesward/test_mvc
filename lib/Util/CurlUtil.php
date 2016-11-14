<?php

class Util_CurlUtil {

	public static function initSimpleCurl($url) {
		$ch = curl_init();
		$output = null;

		if($ch) {
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

			$output = curl_exec($ch);
			curl_close($ch);
		}
		return $output;
	}
}