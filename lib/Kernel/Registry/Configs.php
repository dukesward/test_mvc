<?php

class Kernel_Registry_Configs {

	protected static $_configPath = 'config\kernel\\';
	protected static $_configName = 'config';
	protected static $_loader;

	public static function initConfigRegistry() {
		self::$_loader = Util_AutoLoader::getInstance();
	}

	public static function loadKernelConfig($module, $key) {
		$path = self::$_configPath . $module . '_' . self::$_configName;
		$file = new Util_ConfigFile();
		$contents = self::$_loader->getFileContent($path, 'conf', $file);
		$config = $contents->getContentAttribute($key);

		return $config;
	}
}