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
import org.hibernate.annotations.Type;
import org.hibernate.validator.constraints.Length;

@Entity
@org.hibernate.annotations.Entity(optimisticLock = OptimisticLockType.ALL, dynamicUpdate = true)
@Table(name = "words_general", uniqueConstraints = {
		@UniqueConstraint(columnNames = "id"),
		@UniqueConstraint(columnNames = "word")
})

public class Word implements Serializable {
	
	private static final long serialVersionUID = -1798070786993154676L;
	
	@Id
	@GeneratedValue(strategy = GenerationType.AUTO)
	@Column(columnDefinition = "int(4)")
	private int id;
	
	@Column(name = "word", length = 64, unique = true, nullable = false)
	private String word;
	
	@Column(name = "meaning", length = 64, unique = false, nullable = false)
	private String meaning;
	
	@Column(name = "stars", unique = false, nullable = false, columnDefinition = "int(16) default '1'")
	private int stars;
	
	@Column(name = "points", unique = false, nullable = false, columnDefinition = "int(16) default '0'")
	private int points;
	
	@Column(name = "pub_date", unique = false, nullable = false, 
			columnDefinition = "timestamp default CURRENT_TIMESTAMP")
	private Date pub_date;
	
	@Column(name = "base", unique = false, nullable = false)
	private int base;
	
	@Column(name = "type", length = 64, unique = false, nullable = false)
	private String type;
	
	@Column(name = "times_used", unique = false, nullable = false, columnDefinition = "int(16) default '0'")
	private int times_used;
	
	public Word() {}
	
	public Word(String word) {
		this.word = word;
	}
	
	public void setId(Integer id) {
    	this.id = id;
    }
	
	public Integer getId() {
    	return this.id;
    }
	
	public void setWord(String word) {
    	this.word = word;
    }
	
	public String getWord() {
    	return this.word;
    }
	
	public void setMeaning(String meaning) {
    	this.meaning = meaning;
    }
	
	public String getMeaning() {
    	return this.meaning;
    }
	
	public void setStars(Integer stars) {
    	this.stars = stars;
    }
	
	public Integer getStars() {
    	return this.stars;
    }
	
	public void setPoints(Integer points) {
    	this.points = points;
    }
	
	public Integer getPoints() {
    	return this.points;
    }
	
	public void setPubDate(String date) {
	    pub_date = new Date();
	}
	
	public Date getPubDate() {
    	return this.pub_date;
    }
	
	public void setBase(Integer base) {
    	this.base = base;
    }
	
	public Integer getBase() {
    	return this.base;
    }
	
	public void setType(String type) {
    	this.type = type;
    }
	
	public String getType() {
    	return this.type;
    }
	
	public void setTimesUsed(int times_used) {
		this.times_used = times_used;
	}
	
	public int getTimesUsed() {
		return this.times_used;
	}
	
	public String toString() {
		return String.format(
				"Word General: [id=%d|word=%s|meaning=%s|base=%d|type=%s|times_used=%d]",
				id, word, meaning, base, type, times_used);
	}
}