package com.utils.general;

import java.util.Arrays;
import java.util.List;

public class StringUtils {
	
	public static String firstCapitalize(String seed) {
		String merged = "";
		if(seed.length() > 1) {
			//last char index
			int slp = seed.length();
			merged += seed.substring(0, 1).toUpperCase() + seed.substring(1, slp);
		}else {
			merged += seed.substring(0, 1).toUpperCase();
		}
		return merged;
	}
	
	public static String firstCapitalize(String[] seeds) {
		String merged = "";
		for(int i=0; i<seeds.length; i++) {
			merged += firstCapitalize(seeds[i]);
		}
		return merged;
	}
	
	public static String allCapitalize(String seed, String delimiter) {
		String []tokens = seed.split(delimiter);
		return Arrays.asList(tokens).stream().reduce("", (s1, s2) -> s1 + firstCapitalize(s2));
	}
	
	public static String allCapitalize(String seed) {
		return allCapitalize(seed, "_");
	}
	
	public static String buildClassName(String pkg, String name) {
		String[] tokens = name.split("_");
		String className = pkg;
		if(name != null) {
			className += "." + String.join("", firstCapitalize(tokens));
		}
		//System.out.println("com.widgets.classes." + className);
		return "com.widgets.classes." + className;
	}
	
	public static String parseClassPath(String path) {
		return path.replace(".", "/");
	}
}