package com.cms.kingdom.lib.util;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;

@XmlRootElement
public class ActionLog {
	
	private String action;
	
	public ActionLog(String action) {
		this.setTitle(action);
	}
	
	@XmlElement
	public void setTitle(String action) {
		this.action = action;
	}
	
	public String getTitle() {
		return this.action;
	}
}