package com.utils.general;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.stream.Stream;

public class ConfigFileUtils extends FileUtils implements FileParser {
	
	protected Stream<String> lines;
	protected int configCount;
	
	public ConfigFileUtils() {
		
	}
	
	public ConfigFileUtils(String file, String ext) {
		super(file, ext);
	}
	
	@Override
	public void readContent() {
		String filePath = this.filename + "." + this.ext;
		try {
			this.lines = Files.lines(Paths.get(filePath));
		}catch (IOException ioe) {
			System.err.println("Failed reading file: " + filePath);
		}
	}
	
	public int getConfigCount() {
		return this.configCount;
	}
	
	@Override
	public <T> T InitFactory() {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public Object parse() {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public Object parse(String file, Class<?> object) {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public void antiParse(String file, Object object) {
		// TODO Auto-generated method stub
		
	}

	@Override
	public int getElementNumber(String element) {
		// TODO Auto-generated method stub
		return 0;
	}

	@Override
	public String print() {
		// TODO Auto-generated method stub
		return null;
	}

	@Override
	public String printOutputExt() {
		// TODO Auto-generated method stub
		return null;
	}
	
	
}