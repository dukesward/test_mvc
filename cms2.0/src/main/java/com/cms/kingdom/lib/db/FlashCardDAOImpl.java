package com.cms.kingdom.lib.db;

import java.util.List;

import org.hibernate.Criteria;
import org.hibernate.criterion.Restrictions;
import org.springframework.stereotype.Repository;

import com.cms.kingdom.model.Word;

@Repository("flashCardDAO")
public class FlashCardDAOImpl extends AbstractDAO<Word, Integer> implements FlashCardDAO {
	
	@Override
	public Word fetchWord(Integer id) {
		Criteria criteria = getCurrentSession().createCriteria(Word.class);
		criteria.add(Restrictions.eq("id", id));
		return (Word)criteria.uniqueResult();
	};
}