package com.cms.kingdom.lib.util;

import java.lang.reflect.Constructor;
import java.lang.reflect.Method;

import com.utils.general.StringUtils;

public class ModuleLoader<T> extends ClassLoader {
	
	private String module;
	private String type;
	private String moduleFullPackageName;
	private Object[] arguments;
	private Class<T> loaded;
	
	public ModuleLoader(String module, String type, Object[] args) {
		this.module = module;
		this.type = SystemConstants.parsePackage(type);
		this.arguments = args;
		
		String moduleFullName = StringUtils.firstCapitalize(this.module);
		this.moduleFullPackageName = this.type + moduleFullName;
		//System.out.println("package: " + moduleFullPackageName);
		
		this.loaded = this.loadModuleFast(this.moduleFullPackageName);
	}
	
	public Class<?> getLoaded() {
		return this.loaded;
	}
	
	public T getBindedModule() {
		return this.bindModule();
	}
	
	public T getBindedModule(Object[] args) {
		if(args != null) this.arguments = args;
		return this.bindModule();
	}
	
	@SuppressWarnings({ "unchecked", "rawtypes" })
	protected T bindModule() {
		if(this.loaded != null) {
			try {
				Method m = this.loaded.getMethod("getInstance", (Class<?>[]) null);
				if(m != null) {
					try {
						return (T)m.invoke(null, (Object[])null);
					}catch(Exception e) {
						System.out.println("the static method getInstance of:" + this.loaded + " cannot be invoked");
						//e.printStackTrace();
					}
				}
			}catch(NoSuchMethodException nsme) {
				Class[] paramTypes = new Class[this.arguments.length];
				for(int i=0; i<this.arguments.length; i++) {
					paramTypes[i] = this.arguments[i].getClass();
				}
				//this is used for testing only for constructors with unknown params
				//this.checkModuleConstructor();
				try {
					Constructor<T> constructor = (Constructor<T>)this.loaded.getConstructor(paramTypes);
					try {
						return (T)constructor.newInstance(this.arguments);
					}catch(Exception e) {
						System.out.println("the specified module instance cannot be binded");
						e.printStackTrace();
					}
				}catch(NoSuchMethodException _nsme) {
					this.checkModuleConstructor();
					System.out.println("the constructor with claimed type: " + this.loaded + " is not found");
				}
				
			}
		}
		return null;
	}
	
	@SuppressWarnings("unchecked")
	protected synchronized Class<T> loadModuleFast(String module) {
		try {
			return (Class<T>)ModuleLoader.class.getClassLoader().loadClass(module);
		}catch(ClassNotFoundException cnfe) {
			System.out.println("class not found");
			return null;
		}
	}
	
	protected synchronized Class<?> loadModule(String module) {
		if(this.findModule(module)) {
			try {
				return super.loadClass(module);
			}catch(ClassNotFoundException cnfe) {
				return null;
			}
		}
		return null;
	}
	
	protected boolean findModule(String module) {
		//String path = SystemUtils.getCurrentSrcPath() + StringUtils.parseClassPath(module) + ".class";
		try {
			super.findClass(module);
			return true;
		}catch(ClassNotFoundException cnfe) {
			//cnfe.printStackTrace();
			return false;
		}
	}
	
	@SuppressWarnings("rawtypes")
	protected void checkModuleConstructor() {
		try {
			Class c = Class.forName(this.moduleFullPackageName);
			Constructor[] constructors = c.getDeclaredConstructors();
			for(Constructor ctr:constructors) {
				Class<?>[] types = ctr.getParameterTypes();
				for(int i=0; i<types.length; i++) {
					System.out.println("ctr param: " + types[i]);
				}
			}
		}catch(ClassNotFoundException cnfe) {
			System.out.println("specified class: " + this.moduleFullPackageName + " does not exist");
		}
	}
	
}