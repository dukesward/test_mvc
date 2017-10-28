package com.cms.kingdom.lib.db;

import java.util.List;

import com.cms.kingdom.model.Word;

public interface FlashCardDAO {
	
	Word fetchWord(Integer id);
}