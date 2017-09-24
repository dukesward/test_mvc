package com.cms.kingdom.lib.util;

import javax.xml.bind.annotation.*;

@XmlRootElement(name="StaticFile")
@XmlAccessorType(XmlAccessType.FIELD)
public class StaticFile {

	private String fileName;
	private String fileLocation;
	
	public String getFileName() {
		return this.fileName;
	}
	
	public void setFileName(String fileName) {
		this.fileName = fileName;
	}
	
	public String getFileLocation() {
		return this.fileLocation;
	}
	
	public void setFileLocation(String fileLocation) {
		this.fileLocation = fileLocation;
	}
	
	public void printModule() {
		System.out.println("File Name: " + this.fileName);
		System.out.println("File Location: " + this.fileLocation);
	}
	
}