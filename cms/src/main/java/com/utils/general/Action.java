package com.utils.general;

import java.util.ArrayList;
import java.util.List;
import java.util.stream.Collectors;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;

import com.cms.kingdom.lib.util.SystemConstants;

@XmlRootElement
public class Action {
	
	private static final String OUTPUT = SystemConstants.fetchActionSourceFull();
	
	private String action;
	private List<Step> steps;
	
	public Action() {
		this.setAction("Unknown Action");
		this.steps = new ArrayList<Step>();
	}
	
	public Action(String action) {
		this.setAction(action);
		this.steps = new ArrayList<Step>();
	}
	
	@XmlElement
	public void setAction(String action) {
		this.action = action;
	}
	
	public String getAction() {
		return this.action;
	}
	
	@XmlElement
	public void setSteps(List<Step> steps) {
		this.steps = steps;
	}
	
	public boolean hasStep(String stepName) {
		return this.steps.stream()
					.filter(step -> step.getStepName() == stepName)
					.collect(Collectors.toList()).size() >= 0;
	}
	
	public List<Step> getSteps() {
		return this.steps;
	}
	
	public void registerStep(String stepName) {
		if(!this.hasStep(stepName)) this.steps.add(this.createStep(stepName));
	}
	
	public void configureStep(String items) {
		//this.steps.get(this.steps.size() - 1).addItem(items);
	}
	
	public void configureStep(String stepName, String items) {
		Step step = this.searchStep(stepName);
		if(step != null) step.addItem(items);
	}
	
	public void outputAction(FileParser fp) {
		//System.out.println(OUTPUT);
		if(SystemUtils.generatePath(OUTPUT)) {
			fp.antiParse(OUTPUT + this.action + fp.printOutputExt(), this);
		}
	}
	
	private Step createStep(String stepName) {
		Step step = new Step(stepName);
		return step;
	}
	
	private Step searchStep(String stepName) {
		for(Step s:this.steps) {
			if(s.getStepName() == stepName) return s;
		}
		return null;
	}
}
