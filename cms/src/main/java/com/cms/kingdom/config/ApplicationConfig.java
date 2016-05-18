package com.cms.kingdom.config;

import java.util.Properties;

import javax.annotation.Resource;
import javax.sql.DataSource;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.PropertySource;
import org.springframework.context.support.PropertySourcesPlaceholderConfigurer;

@Configuration
@ComponentScan(basePackages = "com.cms.kingdom")
@PropertySource(value = { "classpath:application.properties" })
public class ApplicationConfig {
	private static final String SQL_DRIVER = "db.driver";

	@Bean
	public static PropertySourcesPlaceholderConfigurer Pspc() {
		return new PropertySourcesPlaceholderConfigurer();
	}
}