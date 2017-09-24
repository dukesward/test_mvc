package com.utils.general;

import java.io.IOException;

import javax.xml.parsers.*;
import org.w3c.dom.*;

public class XmlUtils extends FileUtils implements FileParser {
	
	private DocumentBuilder builder;
	private Document doc;
	
	public XmlUtils(String file) {
		super(file);
		this.readContent();
	}
	
	public void InitFactory() {
		try {
			this.builder = DocumentBuilderFactory.newInstance().newDocumentBuilder();
		} catch(ParserConfigurationException pce) {
			pce.printStackTrace();
		}
	}
	
	@Override
	public Object parse() {
		try {
			this.doc = this.builder.parse(this.file);
			//we need to normalize the parsed doc so that empty tags don't bother
			doc.getDocumentElement().normalize();
		}catch (Exception e) {
			e.printStackTrace();
		}
		return this.doc;
	}
	
	@Override
	public Object parse(String file) {
		// TODO Auto-generated method stub
		return null;
	}
	
	public int getElementNumber(String element) {
		NodeList nl = this.doc.getElementsByTagName(element);
		return nl.getLength();
	}
	
	public NodeList getNodeElement(String tag) {
		return this.doc.getElementsByTagName(tag);
	}
	
	public String print() {
		return "";
	}
}