package com.cms.kingdom.lib.db;

import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.springframework.beans.factory.annotation.Autowired;

public class AbstractDAO {

	@Autowired
	private SessionFactory sessionFactory;
	
	protected Session getSession() {
		return this.sessionFactory.getCurrentSession();
	};
	
	public void persist(Object entity) {
		this.getSession().persist(entity);
	}
	
	public void delete(Object entity) {
		this.getSession().delete(entity);
	}
}