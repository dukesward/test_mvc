package com.widgets.general.test;

public class TestClass {
	
	private static TestClass tc;
	private String code;
	
	public TestClass() {
		this.startTest();
	}
	
	private void startTest() {
		this.code = "Successful";
	}
	
	public String getCode() {
		String codeCopy = this.code;
		//this will erase the last testing code
		this.code = null;
		return codeCopy;
	}
}