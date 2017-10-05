package com.cms.kingdom.controller;

import com.cms.kingdom.config.AppConfig;
import com.cms.kingdom.lib.core.Engine;
import com.cms.kingdom.model.*;
import com.cms.kingdom.service.CardService;

import org.hibernate.Session;

import org.springframework.web.servlet.ModelAndView;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.AnnotationConfigApplicationContext;
import org.springframework.context.support.AbstractApplicationContext;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;

//import javax.ejb.EJB;

@Controller
public class FlashCardController {
	
	private static final String VIEW_FLASH = "flash_main";
	private static final String VIEW_TEST = "flash_test";
	
	@RequestMapping(value = "/flash", method= RequestMethod.GET)
    public ModelAndView getCard() {
		AbstractApplicationContext context = new AnnotationConfigApplicationContext(AppConfig.class);
		CardService service = (CardService)context.getBean("cardService");
		Card card = new Card((Word) service.fetchAllCards().get(0));
		//use engine to handle all the backend stuff
		
        ModelAndView mav = new ModelAndView(VIEW_FLASH);
        mav.addObject("data", Engine.StartEngine());
        mav.addObject("card", card.printWord());
        
        return mav;
    }
	
	@RequestMapping(value = "/flash/test", method = RequestMethod.GET)
	public ModelAndView testUnit() {
		ModelAndView mav = new ModelAndView(VIEW_TEST);
		return mav;
	}
}