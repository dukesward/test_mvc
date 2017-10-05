package com.cms.kingdom.lib.util;

import java.util.ArrayList;
import java.util.List;

import javax.xml.bind.annotation.*;

import com.utils.general.Action;

@XmlAccessorType(XmlAccessType.FIELD)
public class StaticModule {

	private String moduleName;
	private Action action;
	
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
		if(this.action != null) {
			if(!this.action.hasStep("get_components")) {
				this.action.registerStep("get_components");
			}
			this.action.configureStep(this.getModuleName());
		}
		return this.components;
	}
	
	public void setComponents(ArrayList<String> components) {
		this.components = components;
	}
	
	public StaticModule registerAction(Action action) {
		this.action = action;
		return this;
	}
	
	public void printModule() {
		System.out.println("Module Name: " + this.moduleName);
		System.out.println("Components: ");
		//System.out.println(this.components.size());
		this.components.stream().forEach(c -> System.out.println(c));
	}
	
	public String printComponents() {
		return this.components.stream()
					.reduce("", (c1, c2) -> c1 + "," + c2);
	}
	
}