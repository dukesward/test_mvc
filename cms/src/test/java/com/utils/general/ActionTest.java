package com.utils.general;

import static org.junit.Assert.*;

import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.test.context.ContextConfiguration;
import org.springframework.test.context.junit4.SpringJUnit4ClassRunner;
import org.springframework.test.context.web.WebAppConfiguration;
import org.springframework.web.servlet.config.annotation.EnableWebMvc;

//@RunWith(SpringJUnit4ClassRunner.class)
//@EnableWebMvc
//@WebAppConfiguration("src/test/java/com")
//@ContextConfiguration("file:src/test/resources/cms-servlet.xml")
//@ComponentScan("com.cms.kingdom")
public class ActionTest {
	
	private Action action;
	
	//@Before
	public void setupTest() {
		this.action = new Action("Test Action");
	}
	
	//@Test
	public void hasStep_ShouldReturnTrue() {
		this.action.registerStep("Test Step");
		assertEquals(true, action.hasStep("Test Step"));
	}
	
}