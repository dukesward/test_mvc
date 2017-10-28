package com.cms.kingdom.model;

import java.io.Serializable;

public class Card implements Serializable {
	
	private static final long serialVersionUID = -1798070786993154676L;
	
	private Word source;
	
	public Card() {};
	
	public Card(Word word) {
		System.out.println(word);
		this.source = word;
	}
	
	public String printWord() {
		return this.source.toString();
	}
	
	
}

