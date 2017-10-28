package com.cms.kingdom.lib.util;

import javax.xml.bind.annotation.XmlElement;
import javax.xml.bind.annotation.XmlRootElement;
import javax.xml.bind.annotation.XmlType;

@XmlRootElement
@XmlType(propOrder={"test"})
public class TestMarshal {
	
	String test;
	
	public String getTest() {
		return this.test;
	}
	
	@XmlElement(name="test")
	public void setTest(String test) {
		this.test = test;
	}
	
	public void print() {
		System.out.println(this.test);
	}
	
}