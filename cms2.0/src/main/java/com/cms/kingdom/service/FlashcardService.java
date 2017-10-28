package com.cms.kingdom.service;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import com.cms.kingdom.lib.db.FlashCardDAO;
import com.cms.kingdom.model.Word;

@Service("flashcardService")
@Transactional
public class FlashcardService {
	
	@Autowired
	private FlashCardDAO dao;
	
	public Word findById(int id) {
		return dao.fetchWord(id);
	}
}