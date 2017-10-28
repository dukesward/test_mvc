package com.cms.kingdom.lib.core;

import java.util.ArrayList;
import java.util.List;
import java.util.Map;

import com.utils.general.StringUtils;

import com.cms.kingdom.lib.util.ModuleLoaderCollector;
import com.cms.kingdom.lib.util.SystemConstants;
import com.utils.general.CfgUtils;
import com.utils.general.ConfigFileUtils;
import com.widgets.special.Joker;
import com.widgets.special.Invoke;

public class ConfigManager extends ModuleManager {
	
private static ModuleManager instance;

	public static final String resources = SystemConstants.fetchConfigSourceFull();
	
	protected List<ConfigFileUtils> modules;
	private ConfigFileUtils cfg;
	private Map<String, String> toConfigure;
	
	protected ConfigManager() {
		super();
	}
	
	public static ModuleManager getInstance() {
		if(instance == null) {
			instance = new ConfigManager();
		}
		return instance;
	}
	
	@Override
	public void initModules() {
		this.config = "config_config";
		this.prefix = StringUtils.firstCapitalize("utils");
		this.cfg = this.configFileUtilsFactory("Cfg", this.config, "cfg");
		super.initModules();
	}
	
	@Invoke(trigger = "default")
	public TemplateDataObject startManager() {
		super.startManager();
		return this.tdo;
	}
	
	@SuppressWarnings("unchecked")
	public void configureModules() {
		//System.out.println("configure module from: " + this.config);
		this.modules = new ArrayList<ConfigFileUtils>();
		this.toConfigure = (Map<String, String>)this.cfg.parse();
		//System.out.println("count: " + this.cfg.getConfigCount());
		if(this.toConfigure.size() > 0) {
			for(int i=0; i<this.cfg.getConfigCount(); i++) {
				String name = this.toConfigure.get("name_" + (i + 1));
				String type = this.toConfigure.get(name + "_type");
				this.modules.add(this.configFileUtilsFactory(SystemConstants.lookupFileUtils(type), name, type));
			}
		}
	}
	
	protected ConfigFileUtils configFileUtilsFactory(String type, String path, String ext) {
		//System.out.println("path: " + path + "." + ext);
		Object[] args = {SystemConstants.fetchConfigSource() + path, ext};
		return (ConfigFileUtils)ModuleLoaderCollector.fetchModule(type + this.prefix, "utils", args);
	}
	
	@Invoke(trigger = "manager", type = "config")
	protected void distributeConfig(List<ConfigFileUtils> modules) {
		
	}
	
	@Override
	public void prepareModules() {
		for(ConfigFileUtils module:this.modules) {
			//System.out.println("type: " + this.type);
			System.out.println("filename: " + module.fileName());
			//this.joker.registerElement(module, module);
		}
	}
}