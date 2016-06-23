package com.cms.kingdom.config;

import java.util.Properties;

import javax.annotation.Resource;
import javax.sql.DataSource;

import org.hibernate.SessionFactory;

import org.springframework.beans.factory.annotation.Autowired;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.PropertySource;
import org.springframework.context.support.PropertySourcesPlaceholderConfigurer;

import org.springframework.core.env.Environment;

@Configuration
@PropertySource(value = "/resources/config/application.properties")
public class ApplicationConfig {

	@Autowired
	private Environment environment;
	private static final String SQL_DRIVER = "db.driver";


}
