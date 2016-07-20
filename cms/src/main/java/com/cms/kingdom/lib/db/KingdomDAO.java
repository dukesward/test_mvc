package com.cms.kingdom.lib.db;

import com.cms.kingdom.model.Node;

public class KingdomDAO extends AbstractDAO {

	protected KingdomDAO() {
		super();
	}
	
	public void storeNode(Node node) {
		saveOrUpdate(node);
	}
}