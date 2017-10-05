package com.cms.kingdom.lib.util;

import com.utils.general.SystemUtils;

public class SystemConstants {

	public static final String CMS_BASE_PATH = "/webapps/cms/";
	public static final String CMS_CONFIG_PATH = "../../resources/config/";
	public static final String CMS_CONFIG_SOURCE = "/resources/config/";
	public static final String CMS_ACTION_LOG = "resources/action/";
	
	public static String fetchConfigSourceFull() {
		return SystemUtils.getSystemPath() + CMS_BASE_PATH + CMS_CONFIG_SOURCE;
	}
	
	public static String fetchActionSourceFull() {
		return SystemUtils.getSystemPath() + CMS_BASE_PATH + CMS_ACTION_LOG;
	}
}