package com.utils.general;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;

class FileUtils {
	
	public File file;
	
	private String filename;
	private String content;
	
	public FileUtils() {}
	
	public FileUtils(String file) {
		this.bind(file);
	}
	
	public void bind(String file) {
		this.filename = SystemUtils.getSystemPath() + file;
		this.file = new File(this.filename);
	}
	
	public void readContent() {
		String filePath = this.filename;
		try {
			String content = new String(Files.readAllBytes(Paths.get(filePath)));
			this.content = content;
		}catch (IOException ioe) {
			System.err.println("Failed reading file: " + filePath);
		}
	}
	
	public void makeFile() {
		
	}
	
	public String getRawContent() {
		return this.content;
	}
}