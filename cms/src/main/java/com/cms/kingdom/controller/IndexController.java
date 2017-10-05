package com.cms.kingdom.controller;

import com.cms.kingdom.model.Node;
//import com.cms.kingdom.lib.util.SystemConstants;
//import com.cms.kingdom.lib.db.KingdomDAO;
import com.cms.kingdom.test.UnitTest;
import com.utils.general.DateUtils;
import com.utils.general.StringUtils;

import org.hibernate.Session;
import org.springframework.ui.ModelMap;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;
import org.springframework.beans.factory.annotation.Autowired;

import javax.servlet.http.HttpServletRequest;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;

//import java.io.File;

@Controller
public class IndexController {

	private static final String VIEW_INDEX = "index";
	private static final String VIEW_TEST = "test";
	//private static final String HIBERNATE_CONFIG = "test.txt";
	//private KingdomDAO kdao;
	private Node node = new Node();

	//@Autowired
	//private HttpServletRequest request;

	protected final Log logger = LogFactory.getLog(getClass());
	
	@RequestMapping(value = "/home", method = RequestMethod.GET)
	public String init(ModelMap model) {
		System.out.println("test db pulling");
		model.addAttribute("message", "Welcome!");
		
		//Session session = kdao.getSession();
		node.setNid(1);
		//node = (Node) session.get(Node.class, node.getNid());
		model.addAttribute("node", node.toString());

		/*try {
			//File file = new File(HIBERNATE_CONFIG);
			String content = new String(Files.readAllBytes(Paths.get(filePath)));
			model.addAttribute("content", content);
		}catch (IOException ioe) {
			System.err.println("Failed reading file: " + ioe);
		}*/

		return VIEW_INDEX;
	}

	@RequestMapping(value = "/test/{pkg}/{name}")
	public String test(@PathVariable String pkg, @PathVariable String name, ModelMap model) {
		System.out.println(name);
		String testName = StringUtils.buildClassName(pkg, name);
		model.addAttribute("name", testName);
		model.addAttribute("date", DateUtils.getTimeStamp());

		try {
			UnitTest.prepareTesting(pkg, name);
			model.addAttribute("code", UnitTest.getTestCode());
		}catch (ClassNotFoundException cnfe) {
			System.out.println("exception");
			model.addAttribute("exception_test", true);
			model.addAttribute("exception", "cannot find specified class: " + testName);
		}

		return VIEW_TEST;
	}
	
	@RequestMapping(value = "/search/{content}")
	public String search(@PathVariable String content, HttpServletRequest request, ModelMap model) {
		String result = searchController.handleRequests(content, request);
		return result;
	}
	
	@RequestMapping(value = "/services/{name}")
	public String service(@PathVariable String name, HttpServletRequest request) {
		String output = serviceController.handleRequests(name, request);
		return output;
	}
}