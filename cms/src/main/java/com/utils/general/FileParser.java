package com.utils.general;

import javax.xml.bind.JAXBException;

public interface FileParser {
	
	public void InitFactory();
	
	public Object parse();
	
	public Object parse(String file, Class object);
	
	public void antiParse(String file, Object object);
	
	public int getElementNumber(String element);
	
	public String getRawContent();
	
	public String print();
	
	public String printOutputExt();
}