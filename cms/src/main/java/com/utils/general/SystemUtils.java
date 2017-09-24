package com.utils.general;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;

import com.cms.kingdom.lib.util.SystemConstants;

public class SystemUtils {
	
	public static String path;
	
	public static String getSystemPath() {
		return System.getProperty("user.dir");
	}
	
	public static String getCurrentPath() {
		return Paths.get(".").toAbsolutePath().normalize().toString();
	}
}