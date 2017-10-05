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
import org.springframework.orm.hibernate4.LocalSessionFactoryBuilder;
import org.springframework.transaction.annotation.EnableTransactionManagement;

import com.cms.kingdom.model.Word;

@Configuration
@EnableTransactionManagement
@PropertySource(value = "classpath:config/application.properties")
@ComponentScan("com.cms.kingdom.config")
public class HibernateConfig {

	@Autowired
	private Environment environment;
	private static final String SQL_DRIVER = "jdbc.driver";
	private static final String SQL_URL = "jdbc.url";
	private static final String SQL_USER = "jdbc.username";
	private static final String SQL_PASSWORD = "jdbc.password";

	@Bean
	public LocalSessionFactoryBean sessionFactory() {
		LocalSessionFactoryBean sessionFactory = new LocalSessionFactoryBean();
		sessionFactory.setDataSource(dataSource());
		sessionFactory.setPackagesToScan(new String[] { "com.cms.kingdom.model" });
		sessionFactory.setHibernateProperties(hibernateProperties());

		return sessionFactory;
	}
	
	/*@Autowired
	@Bean(name = "sessionFactory")
	public SessionFactory getSessionFactory(DataSource dataSource) {
		LocalSessionFactoryBuilder sessionBuilder = new LocalSessionFactoryBuilder(dataSource);
		//sessionBuilder.scanPackages("com.cms.kingdom.model");
		sessionBuilder.addAnnotatedClass(Word.class);
		sessionBuilder.setProperties(hibernateProperties());

		return sessionBuilder.buildSessionFactory();
	}*/

	@Bean(name = "dataSource")
	public DataSource dataSource() {
		BasicDataSource dataSource = new BasicDataSource();
		dataSource.setDriverClassName(environment.getProperty(SQL_DRIVER));
		dataSource.setUrl(environment.getProperty(SQL_URL));
		dataSource.setUsername(environment.getProperty(SQL_USER));
		dataSource.setPassword(environment.getProperty(SQL_PASSWORD));

		return dataSource;
	}

	private Properties hibernateProperties() {
		//create an anonymouse class Properties to handle the hibernate property config
		Properties properties = new Properties();
		properties.put("hibernate.dialect", environment.getProperty("hibernate.dialect"));
		properties.put("hibernate.show_sql", environment.getProperty("hibernate.show_sql"));
		properties.put("hibernate.format_sql", environment.getProperty("hibernate.format_sql"));
		return properties;
	}
	
	@Bean(name = "transactionManager")
    @Autowired
    public HibernateTransactionManager getTransactionManager(SessionFactory sessionFactory) {
       HibernateTransactionManager transactionManager = new HibernateTransactionManager();
       transactionManager.setSessionFactory(sessionFactory);
       return transactionManager;
    }
}
