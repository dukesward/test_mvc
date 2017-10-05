package com.cms.kingdom.lib.db;

import java.util.List;

import com.cms.kingdom.model.Word;

public class FlashCardDAOImpl extends AbstractDAO<Word, Integer> implements FlashCardDAO {
	
	protected FlashCardDAOImpl(Class entityClass) {
		super(entityClass);
		// TODO Auto-generated constructor stub
	}

	public Word fetchWord(Integer id) {
		return (Word)findById(id);
	};
}