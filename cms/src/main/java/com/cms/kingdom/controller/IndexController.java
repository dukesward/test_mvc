package com.cms.kingdom.controller;

import com.cms.kingdom.model.Node;
import com.cms.kingdom.lib.util.SystemConstants;
import com.cms.kingdom.lib.db.KingdomDAO;
import com.cms.kingdom.test.UnitTest;

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

import java.io.File;

@Controller
public class IndexController {

	private static final String VIEW_INDEX = "index";
	private static final String VIEW_TEST = "test";
	//private static final String HIBERNATE_CONFIG = "test.txt";
	private KingdomDAO kdao = KingdomDAO.getInstance();
	private Node node = new Node();

	@Autowired
	private HttpServletRequest request;

	protected final Log logger = LogFactory.getLog(getClass());
	
	@RequestMapping(value = "/home", method = RequestMethod.GET)
	public String init(ModelMap model) {
		System.out.println("test std log out");
		model.addAttribute("message", "Welcome!");

		/*try {
			//File file = new File(HIBERNATE_CONFIG);
			String content = new String(Files.readAllBytes(Paths.get(filePath)));
			model.addAttribute("content", content);
		}catch (IOException ioe) {
			System.err.println("Failed reading file: " + ioe);
		}*/

		Session session = kdao.prepareSession();
		session.beginTransaction();
		node = (Node)session.get(Node.class, 1);

		model.addAttribute("node", node.toString());
		kdao.shutDown();

		return VIEW_INDEX;
	}

	@RequestMapping("/test/{test_name}")
	public String test(@PathVariable String test_name, ModelMap model) {
		model.addAttribute("name", test_name);
		return VIEW_TEST;
	}
}