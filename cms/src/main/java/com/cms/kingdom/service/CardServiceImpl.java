package com.cms.kingdom.service;

import java.util.List;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import com.cms.kingdom.lib.db.FlashCardDAO;
import com.cms.kingdom.model.Word;

@Service("cardService")
@Transactional
public class CardServiceImpl implements CardService {
	
	@Autowired
	FlashCardDAO dao;
	
	@Override
	public List<Word> fetchAllCards() {
		return dao.findAllCards();
	};
}