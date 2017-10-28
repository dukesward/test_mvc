package com.cms.kingdom.test;

import java.lang.reflect.InvocationTargetException;
import java.lang.reflect.Method;

import com.utils.general.StringUtils;

import com.widgets.general.test.TestClass;

public class UnitTest {
	private static ClassLoader cl = UnitTest.class.getClassLoader();
	private static String testCode;

	public static void prepareTesting(String pkg, String name) throws ClassNotFoundException {
		Class c = cl.loadClass(StringUtils.buildClassName(pkg, name));
		
		try {
			Object object = c.newInstance();
			test(object);
		}catch (Exception e) {
			e.getStackTrace();
		}
	}
	
	public static void test(Object obj) throws Exception {
		if(obj instanceof TestClass) {
			Class c = obj.getClass();
			//Method m = c.getDeclaredMethod("test", null);
			TestClass tc = (TestClass)c.newInstance();
			testCode = tc.getCode();
		}
	}
	
	public static String getTestCode() {
		return testCode;
	}
}