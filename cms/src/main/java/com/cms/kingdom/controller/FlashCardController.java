package com.cms.kingdom.controller;

//import com.utils.general.SystemUtils;
import com.cms.kingdom.lib.core.Engine;
import com.cms.kingdom.lib.db.KingdomDAO;
import com.cms.kingdom.model.*;

import org.hibernate.Session;

import org.springframework.web.servlet.ModelAndView;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;

//import javax.ejb.EJB;

@Controller
public class FlashCardController {
	
	private static final String VIEW_FLASH = "flash_main";
	//private KingdomDAO kdao = KingdomDAO.getInstance();
	
	@RequestMapping(value = "/flash", method= RequestMethod.GET)
    public ModelAndView getCard() {
		//Session session = kdao.prepareSession();
		//Card card = new Card((Word) session.get(Word.class, 1));
		//use engine to handle all the backend stuff
		
        ModelAndView mav = new ModelAndView(VIEW_FLASH);
        mav.addObject("data", Engine.StartEngine());
        //mav.addObject("card", card.printWord());
        
        return mav;
    }
}