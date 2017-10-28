package com.cms.kingdom.lib.core;

import java.util.ArrayList;

import org.springframework.util.StringUtils;

import com.widgets.special.Invoke;

public class Engine extends ModuleManager {
	
	private static ModuleManager instance;
	public static final String toInvoke = "startManager";
	
	protected Engine() {
		super();
		this.type = "core";
		this.prefix = StringUtils.capitalize("manager");
	}
	
	public static ModuleManager getInstance() {
		if(instance == null) {
			instance = new Engine();
		}
		return instance;
	}
	
	public void initModules() {
		this.modules = new ArrayList<String>();
		this.modules.add("config");
	}
	
	@Invoke(trigger = "default", type = "manager")
	public TemplateDataObject startManager() {
		super.startManager();
		return this.tdo;
	}
	
	private void processApplicationConfigurations() {
		//StaticManager sthelper = StaticManager.getInstance(new XmlMarshallerUtils());
		//sthelper.takeAction();
		//this.tdo.addAttribute(sthelper.content);
	}

	@Override
	public void configureModules() {
		
	}
}