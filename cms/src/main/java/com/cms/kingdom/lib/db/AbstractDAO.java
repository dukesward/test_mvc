package com.cms.kingdom.lib.db;

import java.io.Serializable;

import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.springframework.beans.factory.annotation.Autowired;

public abstract class AbstractDAO<E, I extends Serializable> {

	@Autowired
	SessionFactory sessionFactory;

	protected AbstractDAO() {
		System.out.println("test dao init");
	}
	
	protected Session getCurrentSession() {
		return sessionFactory.getCurrentSession();
	}
	
	public void saveOrUpdate(E e) {
		getCurrentSession().saveOrUpdate(e);
	}
}