<?php

class Controller_StaticController extends Controller_BaseController {

	const FILE_SPLITTER = Kernel_Constants::MODEL_ROUTES_FILE_SPLITTER;
	const CONTENT       = 'Content-type';
	const STYLE_EXT     = 'css';
	const SCRIPT_EXT    = 'js';

	protected $_loader;
	protected $base_static = Kernel_Constants::KERNEL_ROUTES_VIEW_ROOT;
	protected $base_cache = Kernel_Constants::CACHE_CACHE_BASE;

	public function __construct(Kernel_Request $request, Kernel_Response $response) {
		parent::__construct($request, $response);
		$this->_loader = Util_AutoLoader::getInstance();
	}

	public function stylesAction() {
		//var_dump($this->_request->getParams());
		$params = $this->_request->getParams();

		$base = $this->base_static . 'styles';
		foreach ($params as $param) {
			$base .= self::FILE_SPLITTER;
			$base .= $param;
		}

		$this->_response->setHeader(self::CONTENT, 'text/css');
		$content = $this->_loader->getFileContent($base, self::STYLE_EXT);

		return $content;
	}

	public function scriptsAction() {
		//var_dump($this->_request->getParams());
		$params = $this->_request->getParams();

		$base = $this->base_static . 'scripts';
		foreach ($params as $param) {
			$base .= self::FILE_SPLITTER;
			$base .= $param;
		}

		$this->_response->setHeader(self::CONTENT, 'text/javascript');
		$content = $this->_loader->getFileContent($base, self::SCRIPT_EXT);

		return $content;
	}

	public function cscriptsAction() {
		//var_dump($this->_request->getParams());
		$params = $this->_request->getParams();

		$base = 'cache';
		foreach ($params as $param) {
			$base .= self::FILE_SPLITTER;
			$base .= $param;
		}

		$this->_response->setHeader(self::CONTENT, 'text/javascript');
		$content = $this->_loader->getFileContent($base, self::SCRIPT_EXT);

		return $content;
	}
}