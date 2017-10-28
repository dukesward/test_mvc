package com.utils.general;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;

public class FileUtils {
	
	protected File file;
	protected String filename;
	protected String ext;
	protected String content;
	
	public FileUtils() {}
	
	public FileUtils(String file, String ext) {
		this.bind(file, ext);
	}
	
	public void bind(String file, String ext) {
		this.filename = SystemUtils.getSystemPath() + file;
		this.ext = ext;
		//System.out.println("filename: " + this.filename + "." + this.ext);
	}
	
	public String fileName() {
		String []tokens = this.filename.split("/");
		return tokens[tokens.length - 1];
	}
	
	public void readContent() {
		String filePath = this.filename + "." + this.ext;
		try {
			String content = new String(Files.readAllBytes(Paths.get(filePath)));
			this.content = content;
		}catch (IOException ioe) {
			System.err.println("Failed reading file: " + filePath);
		}
	}
	
	public void writeContent() {
		String filePath = this.filename + this.ext;
	}
	
	public void makeFile() {
		
	}
	
	public String getRawContent() {
		return this.content;
	}
}