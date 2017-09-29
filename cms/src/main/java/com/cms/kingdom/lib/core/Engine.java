package com.cms.kingdom.lib.core;

import com.cms.kingdom.helper.StaticIntegrationHelper;
import com.utils.general.XmlMarshallerUtils;

public class Engine {
	
	public static TemplateDataObject tdo;
	
	public static TemplateDataObject StartEngine() {
		tdo = new TemplateDataObject();
		ProcessApplicationConfigurations();
		return tdo;
	}
	
	private static void ProcessApplicationConfigurations() {
		StaticIntegrationHelper sthelper = StaticIntegrationHelper.getInstance(new XmlMarshallerUtils());
		sthelper.takeAction();
		tdo.addAttribute(sthelper.content);
	}
}