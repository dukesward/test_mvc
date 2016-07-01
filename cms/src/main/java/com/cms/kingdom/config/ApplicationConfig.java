package com.cms.kingdom.config;

import java.util.Properties;

import javax.annotation.Resource;
import javax.sql.DataSource;

import org.apache.tomcat.dbcp.dbcp.BasicDataSource;

import org.hibernate.SessionFactory;

import org.springframework.beans.factory.annotation.Autowired;

import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.ComponentScan;
import org.springframework.context.annotation.Configuration;
import org.springframework.context.annotation.PropertySource;
import org.springframework.context.support.PropertySourcesPlaceholderConfigurer;

import org.springframework.core.env.Environment;

import org.springframework.orm.hibernate4.HibernateTransactionManager;
import org.springframework.orm.hibernate4.LocalSessionFactoryBean;
import org.springframework.transaction.annotation.EnableTransactionManagement;

import com.mysql.jdbc.Driver;

@Configuration
@EnableTransactionManagement
@PropertySource(value = "/resources/config/application.properties")
public class ApplicationConfig {

	@Autowired
	private Environment environment;
	private static final String SQL_DRIVER = "db.driver";
	private static final String SQL_URL = "db.url";
	private static final String SQL_USER = "db.username";
	private static final String SQL_PASSWORD = "db.password";

	@Bean
	public LocalSessionFactoryBean sessionFactory() {
		LocalSessionFactoryBean sessionFactory = new LocalSessionFactoryBean();
		sessionFactory.setDataSource(cmsDataSource());
		sessionFactory.setPackagesToScan(new String[] { "com.cms.kingfom.model" });
		sessionFactory.setHibernateProperties(hibernateProperties());

		return sessionFactory;
	}

	@Bean
	public DataSource cmsDataSource() {
		BasicDataSource dataSource = new BasicDataSource();
		dataSource.setDriverClassName(environment.getProperty(SQL_DRIVER));
		dataSource.setUrl(environment.getProperty(SQL_URL));
		dataSource.setUsername(environment.getProperty(SQL_USER));
		dataSource.setPassword(environment.getProperty(SQL_PASSWORD));

		return dataSource;
	}

	Properties hibernateProperties() {
		//create an anonymouse class Properties to handle the hibernate property config
		return new Properties() {
			{
				setProperty("hibernate.hbm2ddl.auto", environment.getProperty("hibernate.hbm2ddl.auto"));
				setProperty("hibernate.dialect", environment.getProperty("hibernate.dialect"));
				setProperty("hibernate.globally_quoted_identifiers", "true");
			}
		};
	}
}
