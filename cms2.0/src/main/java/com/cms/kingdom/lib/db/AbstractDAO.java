package com.cms.kingdom.lib.db;

import java.io.Serializable;

import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.springframework.beans.factory.annotation.Autowired;

public abstract class AbstractDAO<E, I extends Serializable> {
	
	//private Class<E> entityClass;
	
	@Autowired
	SessionFactory sessionFactory;
	
	protected Session getCurrentSession() {
		return sessionFactory.getCurrentSession();
	}
	
	public void persist(Object entity) {
		getCurrentSession().persist(entity);
	}
	
	public void delete(Object entity) {
		getCurrentSession().delete(entity);
	}
}