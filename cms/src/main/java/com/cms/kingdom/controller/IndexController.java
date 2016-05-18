package com.cms.kingdom.controller;

import org.springframework.ui.ModelMap;
import org.springframework.stereotype.Controller;

import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.web.servlet.ModelAndView;

import javax.servlet.ServletException;
import javax.servlet.http.HttpServletRequest;
import javax.servlet.http.HttpServletResponse;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;

import java.io.IOException;

//apply request mapping to each method for a multi-action controller
@Controller
public class IndexController {

	private static final ModelAndView model = new ModelAndView("index");
	protected final Log logger = LogFactory.getLog(getClass());
	
	@RequestMapping("/home")
	public ModelAndView init() {
		model.addObject("message", "Welcome!");
		return model;
	}
}