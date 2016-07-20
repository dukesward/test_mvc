package com.cms.kingdom.model;

import java.util.Date;

public class Node {
	
	private int nid;
	
	private int type_id;
	
	private String type;
	
	private String title;
	
	private Date pub_date;
	
	public Node(int nid, int type_id, String type, String title, Date pub_date) {
		this.nid = nid;
		this.type_id = type_id;
		this.type = type;
		this.title = title;
		this.pub_date = pub_date;
	}
}

