package com.cms.kingdom.model;

import java.util.Date;
import java.io.Serializable;

import javax.persistence.Entity;
import javax.persistence.Column;
import javax.persistence.Table;
import javax.persistence.Id;
import javax.persistence.GeneratedValue;
import javax.persistence.GenerationType;
import javax.persistence.UniqueConstraint;

import org.hibernate.annotations.OptimisticLockType;

@Entity
@org.hibernate.annotations.Entity(optimisticLock = OptimisticLockType.ALL, dynamicUpdate = true)
@Table(name = "cms_node_details", uniqueConstraints = {
		@UniqueConstraint(columnNames = "nid"),
		@UniqueConstraint(columnNames = "pattern")
})
public class Node implements Serializable {
	
	private static final long serialVersionUID = -1798070786993154676L;
	
	@Id
	@GeneratedValue(strategy = GenerationType.IDENTITY)
	@Column(name = "nid", unique = true, nullable = false)
	private int nid;
	
	@Column(name = "type_id", unique = false, nullable = false)
	private int type_id;
	
	@Column(name = "type", unique = false, nullable = false)
	private String type;
	
	@Column(name = "title", unique = false, nullable = false)
	private String title;
	
	@Column(name = "pub_date", unique = false, nullable = false)
	private Date pub_date;
	
	@Column(name = "pattern", unique = false, nullable = false)
	private String pattern;

	public Node() {}
	
	public Node(int nid, int type_id, String type, String title, Date pub_date, String pattern) {
		this.nid = nid;
		this.type_id = type_id;
		this.type = type;
		this.title = title;
		this.pub_date = pub_date;
		this.pattern = pattern;
	}
	
	public void setNid(Integer nid) {
    	this.nid = nid;
    }
    
    public void setTypeId(Integer id) {
    	type_id = id;
    }
    
    public void setType(String type) {
    	this.type = type;
    }
    
    public void setTitle(String title) {
    	this.title = title;
    }
    
    public void setPubDate(String date) {
    	pub_date = new Date();
    }
    
    public void setPattern(String pattern) {
    	this.pattern = pattern;
    }
    
    public Integer getNid() {
    	return this.nid;
    }
    
    public Integer getTypeId() {
    	return this.type_id;
    }
    
    public String getType() {
    	return this.type;
    }
    
    public String getTitle() {
    	return this.title;
    }
    
    public Date getPubDate() {
    	return this.pub_date;
    }
    
    public String getPattern() {
    	return this.pattern;
    }
    
    public String toString() {
    	return "[nid:" + this.nid + "|title:" + this.title + "|pattern:" + this.pattern + "]";
    }
}

