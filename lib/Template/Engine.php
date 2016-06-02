<?php

class Template_Engine {
	
	public static function prepareContent(Template_Config $data) {
		$template = new Template_TemplateCompiler($data);
		$content = $template->generateContent(null, 1);
		return $content;
	}

}