package com.cms.kingdom.lib.db;

import java.util.List;

import com.cms.kingdom.model.Word;;

public interface FlashCardDAO {
	
	List<Word> findAllCards();

	void findCardById(int id);
}