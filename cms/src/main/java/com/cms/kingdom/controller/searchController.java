package com.cms.kingdom.controller;

import javax.servlet.http.HttpServletRequest;

import org.hibernate.Session;

import com.cms.kingdom.model.Node;
import com.cms.kingdom.lib.util.SystemConstants;
//import com.cms.kingdom.lib.db.KingdomDAO;

public class searchController {
	
	//private static KingdomDAO kdao;
	
	public static String handleRequests(String content, HttpServletRequest request) {
		//kdao = KingdomDAO.getInstance();
		String result = "";
		switch(content) {
			case "int":
				searchNodeById(Integer.parseInt(content));
				break;
		}
		return result;
	}
	
	protected static void searchNodeById(int id) {
		//Session session = kdao.getSession();
		//session.beginTransaction();
		
		//Node node = (Node)session.get(Node.class, 1);
		//kdao.shutDown();
	}
}