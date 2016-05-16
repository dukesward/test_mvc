package com.cms.kingdom.config;

import java.util.Properties;

import javax.annotation.Resource;
import javax.sql.DataSource;

import org.springframwork.context.annotation.Configuration;

@Configuration
@PropertySource("classpath:application.properties")
public class ApplicationConfig {
	private static final String SQL_DRIVER = "db.driver";
}