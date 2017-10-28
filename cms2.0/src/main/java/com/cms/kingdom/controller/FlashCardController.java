package com.cms.kingdom.controller;

//import com.utils.general.SystemUtils;
import com.cms.kingdom.lib.core.Engine;
import com.cms.kingdom.model.*;
import com.cms.kingdom.service.FlashcardService;

import org.springframework.web.servlet.ModelAndView;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.RequestMethod;

@Controller
public class FlashCardController {
	
	private static final String VIEW_FLASH = "flash_main";
	@Autowired
	private FlashcardService service;
	
	@RequestMapping(value = "/flash", method = RequestMethod.GET)
    public ModelAndView getCard() {
		Card card = new Card((Word)service.findById(1));
        ModelAndView mav = new ModelAndView(VIEW_FLASH);
        //use engine to handle all the backend stuff
        mav.addObject("data", Engine.getInstance().startManager());
        mav.addObject("card", card.printWord());
        
        return mav;
    }
}