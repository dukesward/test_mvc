package com.cms.kingdom.lib.util;

import java.util.HashMap;
import java.util.Map;

import com.utils.general.SystemUtils;

public class SystemConstants {
	
	protected static final String CMS_ACTION_LOG = "resources/action/";
	protected static final String CMS_BASE_PATH = "/webapps/cms/";
	protected static final String CMS_CONFIG_PATH = "../../resources/config/";
	protected static final String CMS_CONFIG_SOURCE = "resources/config/";
	protected static final String CMS_SRC_PATH = "/webapps/cms/WEB-INF/classes/";
	protected static Map<String, String> packages;
	protected static Map<String, String> fileUtils;
	
	static {
		packages = new HashMap<String, String>();
		packages.put("core", "com.cms.kingdom.lib.core");
		packages.put("utils", "com.utils.general");
		
		fileUtils = new HashMap<String, String>();
		fileUtils.put("cfg", "Cfg");
		fileUtils.put("xml", "XmlMarshaller");
	}
	
	//public static final String CMS_CORE_PATH = "com.cms.kingdom.lib.core";
	public static String fetchBasePath() {
		return CMS_BASE_PATH;
	}
	
	public static String fetchConfigPath() {
		return CMS_CONFIG_PATH;
	}
	
	public static String fetchConfigSource() {
		return CMS_BASE_PATH + CMS_CONFIG_SOURCE;
	}
	
	public static String fetchSrcPath() {
		return CMS_SRC_PATH;
	}
	
	public static String fetchConfigSourceFull() {
		return SystemUtils.getSystemPath() + CMS_BASE_PATH + CMS_CONFIG_SOURCE;
	}
	
	public static String fetchActionSourceFull() {
		return SystemUtils.getSystemPath() + CMS_BASE_PATH + CMS_ACTION_LOG;
	}
	
	public static String parsePackage(String type) {
		return packages.get(type) + ".";
	}
	
	public static String lookupFileUtils(String file) {
		return fileUtils.get(file);
	}
}