package com.cms.kingdom.helper;

import java.util.stream.Collectors;

import com.cms.kingdom.lib.util.StaticModules;
import com.cms.kingdom.lib.util.SystemConstants;

import com.utils.general.Action;
import com.utils.general.FileParser;
import com.utils.general.FileUtils;
import com.utils.general.XmlMarshallerUtils;

public class StaticIntegrationHelper implements Helper {
	
	private static StaticIntegrationHelper helper;
	private static Action action;
	private final static String actionName = "static_modules_integration";
	private final static String configName = "config_static.xml";
	
	//private final Logger logger;
	//will be using jaxb for xml parsing purposes
	private final FileParser parser;
	
	public String content;
	
	public static StaticIntegrationHelper getInstance(FileParser parser) {
		if(helper == null) {
			helper = new StaticIntegrationHelper(parser);
		}
		return helper;
	}
	
	private StaticIntegrationHelper(FileParser parser) {
		this.parser = parser;
		this.init();
	}
	
	@Override
	public Action prepareAction() {
		if(action == null) {
			action = new Action(actionName);
		}
		return action;
	}
	
	@Override
	public void init() {}
	
	@Override
	public void takeAction() {
		//initialize source file path
		String file = SystemConstants.fetchConfigSourceFull() + configName;
		StaticModules modules = (StaticModules)this.parser.parse(file, StaticModules.class);
		modules.registerAction(this.prepareAction())
			.getModules()
			.stream()
			.map(m -> m.registerAction(action).getComponents())
			.collect(Collectors.toList());
		
		this.content = "test data";
		
		this.logAction(modules.getAction());
	}
	
	@Override
	public void logAction(Action action) {
		try {
			action.outputAction(this.parser);
		}catch (ClassCastException cce) {
			
		}
		
	}
}