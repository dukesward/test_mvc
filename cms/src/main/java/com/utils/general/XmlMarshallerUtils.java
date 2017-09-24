package com.utils.general;

import java.io.File;
import java.util.ArrayList;
import java.util.Collection;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;
import javax.xml.bind.Unmarshaller;

import com.cms.kingdom.lib.util.StaticModules;
//import com.cms.kingdom.lib.util.TestMarshal;

public class XmlMarshallerUtils extends FileUtils implements FileParser {
	
	//private JAXBContext jaxbc;
	private String filename;
	private String content;
	
	public XmlMarshallerUtils() {
		//default constructor
	}
	
	public XmlMarshallerUtils(String file) {
		super(file);
		//System.out.println(file);
		this.readContent();
	}
	
	@Override
	public void InitFactory() {
		
	}

	@Override
	public Object parse() {
		// TODO Auto-generated method stub
		try {
			JAXBContext jaxbc = JAXBContext.newInstance(StaticModules.class);
			Unmarshaller unmarshaller = jaxbc.createUnmarshaller();
			return unmarshaller.unmarshal(this.file);
		}catch (JAXBException jaxbe) {
			jaxbe.printStackTrace();
		}
		return null;
	}
	
	@Override
	public Object parse(String file) {
		// TODO Auto-generated method stub
		try {
			JAXBContext jaxbc = JAXBContext.newInstance(StaticModules.class);
			Unmarshaller unmarshaller = jaxbc.createUnmarshaller();
			return unmarshaller.unmarshal(new File(file));
		}catch (JAXBException jaxbe) {
			jaxbe.printStackTrace();
		}
		return null;
	}

	@Override
	public int getElementNumber(String element) {
		// TODO Auto-generated method stub
		return 0;
	}

	@Override
	public String print() {
		try {
			JAXBContext jaxbc = JAXBContext.newInstance(StaticModules.class);
			Marshaller marshaller = jaxbc.createMarshaller();
		}catch (Exception e) {
			e.printStackTrace();
		}
		// TODO Auto-generated method stub
		return null;
	}
	
}