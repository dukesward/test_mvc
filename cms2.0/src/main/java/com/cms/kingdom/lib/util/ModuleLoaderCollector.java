package com.cms.kingdom.lib.util;

import java.util.HashMap;
import java.util.Map;

public class ModuleLoaderCollector {
	
	protected static Map<String, ModuleLoader<Object>> collection;
	
	public static void initCollection() {
		collection = new HashMap<String, ModuleLoader<Object>>();
	}
	
	public static Object fetchModule(String module, String type) {
		return fetchModule(module, type, null);
	}
	
	public static Object fetchModule(String module, String type, Object[] args) {
		//System.out.println("fetch module: " + module);
		if(collection == null) initCollection();
		
		if(collection.get(module + type) == null) {
			ModuleLoader<Object> loader = moduleLoaderFactory(module, type, args);
			if(loader.getLoaded() != null) {
				collection.put(module + type, loader);
				return loader.getBindedModule();
			}
		} else {
			return collection.get(module + type).getBindedModule(args);
		}
		return null;
	}
	
	protected static ModuleLoader<Object> moduleLoaderFactory(String module, String type, Object[] args) {
		return new ModuleLoader<Object>(module, type, args);
	}

}
