package com.cms.kingdom.lib.util;

import java.util.ArrayList;
import java.util.List;

import javax.xml.bind.annotation.*;

@XmlAccessorType(XmlAccessType.FIELD)
public class StaticModule {

	String moduleName;
	
	@XmlElementWrapper(name="components")
	@XmlElement(name="component")
	ArrayList<String> components;
	
	public String getModuleName() {
		return this.moduleName;
	}
	
	public void setModuleName(String moduleName) {
		this.moduleName = moduleName;
	}
	
	public List<String> getComponents() {
		return this.components;
	}
	
	public void setComponents(ArrayList<String> components) {
		this.components = components;
	}
	
	public void printModule() {
		System.out.println("Module Name: " + this.moduleName);
		System.out.println("Components: ");
		//System.out.println(this.components.size());
		this.components.stream().forEach(c -> System.out.println(c));
	}
	
}