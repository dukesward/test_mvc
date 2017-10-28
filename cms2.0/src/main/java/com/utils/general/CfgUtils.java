package com.utils.general;

import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;
import java.util.Map;
import java.util.stream.Stream;

public class CfgUtils extends ConfigFileUtils {
	
	private Map<String, String> config;
	
	public CfgUtils(String file, String ext) {
		super(file, ext);
		this.readContent();
	}
	
	@Override
	public void readContent() {
		String filePath = this.filename + "." + this.ext;
		try(Stream<String> s = Files.lines(Paths.get(filePath))) {
			this.config = new HashMap<String, String>();
			this.configCount = 0;
			Map<Integer, String> prefixes = new HashMap<Integer, String>();
			//AtomicInteger counter = new AtomicInteger(1);
			s.forEach(l -> {
				//System.out.println("test loop");
				int tabs = l.split("\t").length - 1;
				
				String []tokens = l.trim().split("=");
				String key = tokens[0].trim();
				
				if(tokens.length == 1) {
					prefixes.put(0, key);
					this.configCount++;
					this.config.put("name_" + this.configCount, key);
				}else if(tokens.length == 2) {
					String val = tokens[1].trim();
					prefixes.put(tabs, key);
					this.config.put(prefixes.get(tabs - 1) + "_" + key, val);
				}
			});
		}catch (IOException ioe) {
			System.err.println("Failed reading file: " + filePath);
		}
	}
	
	@Override
	public Object parse() {
		return this.config;
	}
}
