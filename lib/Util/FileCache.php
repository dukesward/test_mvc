<?php

class Util_FileCache {

	const CACHE_BASE = Kernel_Constants::CACHE_CACHE_BASE;
	const DEFAULT_EXT = '.txt';

	private static $_instance;
	protected $_loader;

	protected function __construct() {
		$this->_loader = Util_AutoLoader::getInstance();
	}

	public static function getInstance() {
		if(!isset(self::$_instance)) {
			self::$_instance = new Util_FileCache();
		}

		return self::$_instance;
	}

	public function setupFileCache($config) {
		$file = null;

		$root = Kernel_Utils::_getArrayElement($config, 'root');
		$path = Kernel_Utils::_getArrayElement($config, 'file');
		$ext = Kernel_Utils::_getArrayElement($config, 'ext');

		if(null !== $root && null !== $path) {
			$fullPath = self::CACHE_BASE . $root . '\\' . $path;
			$file = $this->_loader->getFileContent($fullPath, $ext);
		}

		return $file;
	}

	public function createFileCache($root, $config, $file = null, $ext = null) {
		//$version = 0;

		if(null === $file || !is_string($file)) {
			$file = 'temp\\temp';
		}else {
			$tokens = explode(':', $file);
			$file = $tokens[0] . Kernel_Constants::MODEL_ROUTES_FILE_SPLITTER;
			if(count($tokens) > 0) {
				$file .= $tokens[1];
			}
		}

		if(null === $ext) {
			$file .= self::DEFAULT_EXT;
		}else {
			$file .= ('.' . $ext);
		}

		$file = self::CACHE_BASE . $file;
		$contents = '';
		if(is_string($config)) {
			$config = array('0' => $config,);
		}

		if(is_array($config)) {
			foreach ($config as $feed) {
				//var_dump($feed);
				$content = $this->_loader->getFileContent($feed, $ext);
				if(is_string($content)) {
					//make sure each file input starts with new line
					$content = "\n" . $content;
					$contents .= ($content);
				}
			}
		}

		$this->_loader->writeFile($file, $contents);
		//var_dump($contents);
	}
}