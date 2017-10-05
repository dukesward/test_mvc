package com.cms.kingdom.service;

import java.util.List;

import com.cms.kingdom.model.Word;

public interface CardService {
	
	List<Word> fetchAllCards();
}