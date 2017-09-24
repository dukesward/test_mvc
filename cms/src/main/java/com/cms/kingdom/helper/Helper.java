package com.cms.kingdom.helper;

import com.utils.general.Action;

interface Helper {
	
	abstract void init();
	
	public void takeAction();
	
	public Action prepareAction();
	
	public void logAction();
}