package com.utils.general;

import javax.xml.parsers.*;
import org.w3c.dom.*;

public class XmlUtils extends FileUtils implements FileParser {
	
	private DocumentBuilder builder;
	private Document doc;
	
	public XmlUtils(String file, String ext) {
		super(file, ext);
		this.readContent();
	}
	
	@SuppressWarnings("unchecked")
	@Override
	public DocumentBuilder InitFactory() {
		try {
			DocumentBuilder builder = DocumentBuilderFactory.newInstance().newDocumentBuilder();
			return builder;
		} catch(ParserConfigurationException pce) {
			pce.printStackTrace();
		}
		
		return null;
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
	public Object parse(String file, Class<?> object) {
		// TODO Auto-generated method stub
		return null;
	}
	
	@Override
	public void antiParse(String file, Object obejct) {
		// TODO Auto-generated method stub
		
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
	
	public String printOutputExt() {
		return ".xml";
	}
}