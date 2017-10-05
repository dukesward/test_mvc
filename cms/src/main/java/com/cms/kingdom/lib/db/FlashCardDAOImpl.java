package com.cms.kingdom.lib.db;

import java.io.File;
import java.io.IOException;
import java.nio.file.Files;
import java.nio.file.Paths;
import java.util.List;

import com.cms.kingdom.model.Word;

import org.hibernate.Criteria;
import org.springframework.stereotype.Repository;

@Repository("flashCardDAO")
public class FlashCardDAOImpl extends AbstractDAO implements FlashCardDAO {

	public void testFilePath() {
		try {
			File file = new File("test.txt");
			String path = file.getAbsolutePath();
			System.out.println("File base path: " + path);
		}catch (Exception e) {
			System.err.println("Failed creating file: " + e);
		}
	}

	@Override
	public List<Word> findAllCards() {
		Criteria criteria = getSession().createCriteria(Word.class);
		return (List<Word>)criteria.list();
	}

	@Override
	public void findCardById(int id) {
		// TODO Auto-generated method stub
		
	}

	/*protected SessionFactory buildSessionFactory() {
		try {
			String filePath = SystemUtils.getSystemPath() + SystemConstants.CMS_CONFIG_SOURCE + HIBERNATE_CONFIG;
			System.out.println("///print file path: " + filePath + "///");
			try {
				String content = new String(Files.readAllBytes(Paths.get(filePath)));
				//System.err.println(content);
			}catch (IOException ioe) {
				System.err.println("Failed reading file: " + ioe);
			}
			return new AnnotationConfiguration().configure(filePath).buildSessionFactory();
		}catch (Throwable ex) {
			System.err.println("Initial SessionFactory creation failed." + ex);
			throw new ExceptionInInitializerError(ex);
		}
	}*/
}
