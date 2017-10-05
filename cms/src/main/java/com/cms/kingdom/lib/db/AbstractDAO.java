package com.cms.kingdom.lib.db;

import java.io.Serializable;

import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.springframework.beans.factory.annotation.Autowired;

public abstract class AbstractDAO<E, I extends Serializable> {
	
	private Class<E> entityClass;
	
	protected AbstractDAO(Class<E> entityClass) {
		this.entityClass = entityClass;
	}
	
	@Autowired
	SessionFactory sessionFactory;
	
	protected Session getCurrentSession() {
		return sessionFactory.getCurrentSession();
	}
	
	public E findById(I id) {
		return (E)getCurrentSession().get(entityClass, id);
	}
	
	public void saveOrUpdate(E e) {
		getCurrentSession().saveOrUpdate(e);
	}
}