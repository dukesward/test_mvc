package com.cms.kingdom.lib.util;

import java.util.ArrayList;

//import org.w3c.dom.Node;
//import org.w3c.dom.Element;

import java.util.List;
import java.util.stream.Collectors;

import javax.xml.bind.annotation.*;

import com.utils.general.Action;

@XmlRootElement
public class StaticModules {
	
	private ArrayList<StaticModule> modules;
	private ArrayList<StaticFile> files;
	private Action action;
	
	@XmlElement(name="staticModule")
	public void setModules(ArrayList<StaticModule> modules) {
		this.modules = modules;
	}
	
	public ArrayList<StaticModule> getModules() {
		if(this.action != null) {
			this.action.registerStep("get_modules");
			this.action.configureStep("stepDetail", this.printModuleNames());
		}
		return this.modules;
	}
	
	@XmlElement(name="staticFile")
	public void setFiles(ArrayList<StaticFile> files) {
		this.files = files;
	}
	
	public ArrayList<StaticFile> getFiles() {
		return this.files;
	}
	
	public int numberOfModules() {
		//get total number of modules for logging purpose
		return this.modules.size();
	}
	
	public void printModules() {
		this.modules.stream().forEach(m -> m.printModule());
		//this.files.stream().forEach(f -> f.printModule());
	}
	
	public String printModuleNames() {
		return this.modules.stream()
				.map(m -> m.getModuleName())
				.reduce("", (n1, n2) -> n1 + "|" + n2);
	}
	
	public void registerAction(Action action) {
		this.action = action;
	}
	
	public Action getAction() {
		return this.action;
	}
}