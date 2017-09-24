package com.utils.general;

import javax.xml.bind.JAXBException;

public interface FileParser {
	
	public void InitFactory();
	
	public Object parse();
	
	public Object parse(String file);
	
	public int getElementNumber(String element);
	
	public String getRawContent();
	
	public String print();
}