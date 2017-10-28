package com.utils.general;

import java.util.List;
import java.util.Map;

import javax.xml.bind.annotation.XmlElement;

public class StepItem {
	
	private String item;
	private List<Map<String, String>> attrs;
	
	public StepItem() {
		
	}
	
	public StepItem(String item) {
		this.item = item;
	}
	
	@XmlElement
	public void setItem(String item) {
		this.item = item;
	}
	
	public String getItem() {
		return this.item;
	}
}