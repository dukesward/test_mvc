package com.utils.general;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.LinkOption;
import java.nio.file.Paths;

import com.cms.kingdom.lib.util.SystemConstants;

public class SystemUtils {
	
	public static String path;
	
	public static String getSystemPath() {
		return System.getProperty("user.dir");
	}
	
	public static String getClassPath() {
		return System.getProperty("java.class.path");
	}
	
	public static String getCurrentPath() {
		return Paths.get(".").toAbsolutePath().normalize().toString();
	}
	
	public static String getCurrentSrcPath() {
		return getCurrentPath() + SystemConstants.fetchSrcPath();
	}
	
	public static boolean verifyPath(String path) {
		File file = new File(path);
		return file.exists();
	}
	
	public static boolean generatePath(String path) {
		if(!verifyPath(path)) {
			File file = new File(path);
			return file.mkdir();
		}else {
			return true;
		}
	}
}