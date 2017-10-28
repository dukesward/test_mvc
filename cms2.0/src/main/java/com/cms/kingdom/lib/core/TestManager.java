package com.cms.kingdom.lib.core;

import java.util.Map;

import com.widgets.special.Invoke;

public class TestManager extends ModuleManager {
	
	private static ModuleManager instance;
	
	private String[] modules;
	
	public static ModuleManager getInstance() {
		if(instance == null) {
			instance = new TestManager();
		}
		return instance;
	}
	
	public TemplateDataObject startManager() {
		super.startManager();
		return this.tdo;
	}
	
	@Invoke(trigger = "callback", type = "config")
	public void configureModules(Map<String, String> config) {
		System.out.println("test manager configure");
	}
}