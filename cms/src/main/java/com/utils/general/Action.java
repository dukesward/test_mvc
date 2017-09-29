package com.utils.general;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;

import com.cms.kingdom.lib.util.SystemConstants;

public class Action {
	
	private static final String OUTPUT = SystemConstants.fetchActionSourceFull();
	
	private String action;
	private List<Map<String, String>> steps;
	
	public Action(String action) {
		this.action = action;
		this.steps = new ArrayList<Map<String, String>>();
	}
	
	public void registerStep(String stepName) {
		this.steps.add(this.createMap());
		this.configureStep("stepName", stepName);
	}
	
	public void configureStep(String key, String val) {
		this.steps.get(this.steps.size() - 1).put(key, val);
	}
	
	public void outputAction(FileParser fp) {
		
	}
	
	private Map<String, String> createMap() {
		Map<String, String> map = new HashMap<String, String>();
		return map;
	}
}