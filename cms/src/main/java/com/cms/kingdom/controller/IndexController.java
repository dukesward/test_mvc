package com.cms.kingdom.controller;

import com.cms.kingdom.lib.db.KingdomDAO;

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

@Controller
public class IndexController {

	private static final String VIEW_INDEX = "index";
	private KingdomDAO kdao;
	protected final Log logger = LogFactory.getLog(getClass());
	
	@RequestMapping("/home")
	public String init(ModelMap model) {
		System.out.println("test std log out");
		model.addAttribute("message", "Welcome!");
		return VIEW_INDEX;
	}

	
}