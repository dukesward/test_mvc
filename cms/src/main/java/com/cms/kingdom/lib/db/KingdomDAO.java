package com.cms.kingdom.lib.db;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;

import com.cms.kingdom.lib.util.SystemConstants;

import org.hibernate.Session;
import org.hibernate.SessionFactory;
import org.hibernate.cfg.AnnotationConfiguration;

public class KingdomDAO {
	//private static final String HIBERNATE_CONFIG = "resources/config/hibernate.cfg.xml";
	private static KingdomDAO instance;
	private static final String HIBERNATE_CONFIG = "hibernate.cfg.xml";
	private File configFile;
	private SessionFactory sessionFactory;
	
	public static KingdomDAO getInstance() {
		if(instance == null) {
			instance = new KingdomDAO();
		}
		return instance;
	}

	public KingdomDAO() {
		this.sessionFactory = buildSessionFactory();
	}

	public void setConfigFile(File configFile) {
		this.configFile = configFile;
	}

	public Session prepareSession() {
		return this.sessionFactory.openSession();
	}

	public void shutDown() {
		this.sessionFactory.close();
	}

	public void testFilePath() {
		try {
			File file = new File("test.txt");
			String path = file.getAbsolutePath();
			System.out.println("File base path: " + path);
		}catch (Exception e) {
			System.err.println("Failed creating file: " + e);
		}
	}

	public void testFileContent() {
		String filePath = SystemConstants.CMS_BASE_PATH + HIBERNATE_CONFIG;
		try {
			String content = new String(Files.readAllBytes(Paths.get(filePath)));
			System.out.println(content);
		}catch (IOException ioe) {
			System.err.println("Failed reading file: " + ioe);
		}
	}

	protected SessionFactory buildSessionFactory() {
		try {
			String filePath = SystemConstants.CMS_CONFIG_PATH + HIBERNATE_CONFIG;
			try {
				String content = new String(Files.readAllBytes(Paths.get(filePath)));
				//System.err.println(content);
			}catch (IOException ioe) {
				System.err.println("Failed reading file: " + ioe);
			}
			return new AnnotationConfiguration().configure(filePath).buildSessionFactory();
		}catch (Throwable ex) {
			System.err.println("Initial SessionFactory creation failed." + ex);
			throw new ExceptionInInitializerError(ex);
		}
	}
}
