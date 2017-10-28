package com.utils.general;

import java.io.File;

import javax.xml.bind.JAXBContext;
import javax.xml.bind.JAXBException;
import javax.xml.bind.Marshaller;
import javax.xml.bind.Unmarshaller;

import com.cms.kingdom.lib.util.StaticModules;
//import com.cms.kingdom.lib.util.TestMarshal;

public class XmlMarshallerUtils extends ConfigFileUtils {
	
	//private JAXBContext jaxbc;
	private String filename;
	private String content;
	
	public XmlMarshallerUtils() {
		//default constructor
	}
	
	public XmlMarshallerUtils(String file, String ext) {
		super(file, ext);
		//System.out.println(file);
		this.readContent();
	}
	
	@SuppressWarnings("unchecked")
	@Override
	public JAXBContext InitFactory() {
		try {
			JAXBContext jaxbc = JAXBContext.newInstance(StaticModules.class);
			return jaxbc;
		}catch (JAXBException jaxbe) {
			jaxbe.printStackTrace();
		}
		return null;
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
	public Object parse(String file, Class<?> object) {
		// TODO Auto-generated method stub
		try {
			JAXBContext jaxbc = JAXBContext.newInstance(object);
			Unmarshaller unmarshaller = jaxbc.createUnmarshaller();
			return unmarshaller.unmarshal(new File(file));
		}catch (JAXBException jaxbe) {
			jaxbe.printStackTrace();
		}
		return null;
	}
	
	@Override
	public void antiParse(String file, Object object) {
		try {
			JAXBContext jaxbc = JAXBContext.newInstance(object.getClass());
			Marshaller marshaller = jaxbc.createMarshaller();
			
			marshaller.setProperty(Marshaller.JAXB_FORMATTED_OUTPUT, true);
			marshaller.marshal(object, new File(file));
		}catch (Exception e) {
			e.printStackTrace();
		}
	}

	@Override
	public int getElementNumber(String element) {
		// TODO Auto-generated method stub
		return 0;
	}

	@Override
	public String print() {
		try {
			//JAXBContext jaxbc = JAXBContext.newInstance(StaticModules.class);
			//Marshaller marshaller = jaxbc.createMarshaller();
		}catch (Exception e) {
			e.printStackTrace();
		}
		// TODO Auto-generated method stub
		return null;
	}
	
	@Override
	public String printOutputExt() {
		return ".xml";
	}
	
}