package com.wi360.sms.marketing.dao;

import android.content.Context;
import android.database.sqlite.SQLiteDatabase;
import android.database.sqlite.SQLiteOpenHelper;

/**
 * @author fn
 *	
 */
public class TaskDBHelper extends SQLiteOpenHelper{

	public TaskDBHelper(Context context) {
		super(context, "smstaskinfo.db", null, 1);
		
		// TODO Auto-generated constructor stub
	}
    
	@Override
	public void onCreate(SQLiteDatabase db) {
	
		db.execSQL("create table subtaskfailed (_id Integer primary key autoincrement,taskId varchar(100),issueId varchar(100),status varchar(20))");
	
	}

	@Override
	public void onUpgrade(SQLiteDatabase db, int oldVersion, int newVersion) {
		// TODO Auto-generated method stub
		
	}

}
