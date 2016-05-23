package com.cms.kingdom.config;

import java.util.Properties;

import javax.annotation.Resource;
import javax.sql.DataSource;

import org.springframework.web.servlet.ViewResolver;
import org.springframework.web.servlet.view.InternalResourceViewResolver;
import org.springframework.web.servlet.config.annotation.EnableWebMvc;
import org.springframework.web.servlet.config.annotation.ResourceHandlerRegistry;
import org.springframework.web.servlet.config.annotation.WebMvcConfigurerAdapter;

import org.springframework.beans.factory.annotation.Value;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.PropertySource;
import org.springframework.context.support.PropertySourcesPlaceholderConfigurer;

@Configuration
@ComponentScan(basePackages = "com.cms.kingdom")
//@PropertySource(value = { "file:///C:/wamp/www/projects/mvc/test/cms/src/main/webapp/resources/config/application.properties" })
@PropertySource(value = "/resources/config/application.properties")
@EnableWebMvc
public class ApplicationConfig extends WebMvcConfigurerAdapter {

	@Value("${db.driver}")
	private String dbDriver;

	@Bean
	public ViewResolver getViewResolver() {
		InternalResourceViewResolver resolver = new InternalResourceViewResolver();
		resolver.setPrefix("/WEB-INF/views/");
		resolver.setSuffix(".jsp");
		return resolver;
	}

	@Override
	public void addResourceHandlers(ResourceHandlerRegistry registry) {
		registry.addResourceHandler("/utils/**").addResourceLocations("/resources/static/lib/");
		registry.addResourceHandler("/css/**").addResourceLocations("/resources/static/styles/");
		registry.addResourceHandler("/js/**").addResourceLocations("/resources/static/scripts/");
	}

	@Bean
	public static PropertySourcesPlaceholderConfigurer properties() {
		return new PropertySourcesPlaceholderConfigurer();
	}
}