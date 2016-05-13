package com.cms.kingdom;

import org.springframework.web.servlet.DispatcherServlet;
import org.springframework.web.WebApplicationInitializer;

import javax.servlet.ServletContext;
import javax.servlet.ServletException;
import javax.servlet.ServletRegistration;

public class BootstrapInitializer implements WebApplicationInitializer {
	
	@Override
	public void onStartup(ServletContext context) throws ServletException {
		System.out.println("hello");
		ServletRegistration.Dynamic registration = context.addServlet("dispatcher", new DispatcherServlet());
		registration.setLoadOnStartup(1);
		registration.addMapping("/cms/*");
	}
}