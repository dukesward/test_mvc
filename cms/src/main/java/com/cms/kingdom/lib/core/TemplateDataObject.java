package com.cms.kingdom.lib.core;

public class TemplateDataObject {
	
	private String attribute;
	
	public void addAttribute(String attr) {
		this.attribute = attr;
	}
	
	public String getAttribute() {
		return this.attribute;
	}
}
