package com.cms.kingdom.lib.util;

import java.util.ArrayList;

//import org.w3c.dom.Node;
//import org.w3c.dom.Element;

import java.util.List;

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
		//System.out.println("test");
		this.modules.stream().forEach(m -> m.printModule());
		this.files.stream().forEach(f -> f.printModule());
	}
	
	public void registerAction(Action action) {
		this.action = action;
	}
	
	public Action getAction() {
		return this.action;
	}
}