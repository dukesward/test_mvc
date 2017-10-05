package com.utils.general;

import java.util.ArrayList;
import java.util.List;

import javax.xml.bind.annotation.XmlElement;

public class Step {
	
	private String stepName;
	private List<StepItem> items;
	
	public Step(String stepName) {
		this.stepName = stepName;
		this.items = new ArrayList<StepItem>();
	}
	
	@XmlElement
	public void setStepName(String stepName) {
		this.stepName = stepName;
	}
	
	public String getStepName() {
		return this.stepName;
	}
	
	@XmlElement
	public void setItems(List<StepItem> items) {
		this.items = items;
	}
	
	public List<StepItem> getItems() {
		return this.items;
	}
	
	public void addItem(StepItem item) {
		this.items.add(item);
	}
	
	public void addItem(String items) {
		//if using pipe as delimiter, need escape
		//String[] splitItems = items.split("\\|");
		for(String i:items.split(",")) {
			//System.out.println(i);
			//this is mainly to get rid of the first delimiter
			if(i != "") this.addItem(new StepItem(i));
		}
	}
}