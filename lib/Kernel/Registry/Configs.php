<?php

class Kernel_Registry_Configs {

	protected static $_configPath = 'config\kernel\\';
	protected static $_configName = 'config';
	protected static $_loader;

	public static function initConfigRegistry($loader = null) {
		if(is_object($loader)) {
			self::$_loader = $loader;
		}else {
			self::$_loader = Util_AutoLoader::getInstance();
		}	
	}

	public static function loadKernelConfig($module, $key = null) {
		$path = self::$_configPath . $module . '_' . self::$_configName;
		$file = new Util_ConfigFile();
		$contents = self::$_loader->getFileContent($path, 'conf', $file);

		if($key) {
			$config = $contents->getContentAttribute($key);
		}else {
			$config = $contents;
		}

		return $config;
	}
}