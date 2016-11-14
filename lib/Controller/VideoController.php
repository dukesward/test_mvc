<?php

class Controller_VideoController extends Controller_BaseController {

	private $_url;

	public function indexAction() {
		$common = Controller_Administrator::getModel('commonProcessor');
		$this->_url = $common->getConfig('video_base_url');
		$url = $this->_url . '?s=';

		$content = Util_CurlUtil::initSimpleCurl($url);
		//var_dump($content);
		return $content;
	}
}