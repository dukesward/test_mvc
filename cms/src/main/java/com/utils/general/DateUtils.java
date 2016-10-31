package com.utils.general;

import java.text.SimpleDateFormat;
import java.util.Date;

public class DateUtils {
	
	public static String getTimeStamp() {
		Date date = new Date();
		String timeStamp = new SimpleDateFormat("yyyy/MM/dd - HH:mm:ss").format(date);
		return timeStamp;
	}
}