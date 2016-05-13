package com.cms.kingdom.controller;

import org.springframework.ui.ModelMap;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;

@Controller
public class IndexController {

	private static final String VIEW_INDEX = "index";
	
	@RequestMapping("/home")
	public String init(ModelMap model) {
		model.addAttribute("message", "Welcome");
		return VIEW_INDEX;
	}
}