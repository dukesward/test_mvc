package com.cms.kingdom.lib.core;

import java.util.List;

import com.cms.kingdom.lib.util.ModuleLoaderCollector;
import com.cms.kingdom.lib.util.SystemConstants;
import com.widgets.special.Joker;
import com.widgets.special.Invoke;

public class ModuleManager {
	
	protected List<String> modules;
	protected TemplateDataObject tdo;
	protected Joker<Object> joker;
	protected String config;
	protected String type;
	protected String prefix;
	
	protected ModuleManager() {
		this.joker = new Joker<Object>(this);
		this.type = "";
		this.initModules();
	}
	
	public void initModules() {
		if(this.config != null) this.configureModules();
	}
	
	public TemplateDataObject startManager() {
		this.tdo = new TemplateDataObject();
		this.prepareModules();
		this.joker.trigger();
		return this.tdo;
	}
	
	public Object getModule(String module) {
		return this.joker.getElement(module);
	}
	
	public void configureModules() {};
	
	public void prepareModules() {
		for(String module:this.modules) {
			//System.out.println("type: " + this.type);
			this.joker.registerElement(module, ModuleLoaderCollector.fetchModule(module + this.prefix, this.type));
		}
	}
}