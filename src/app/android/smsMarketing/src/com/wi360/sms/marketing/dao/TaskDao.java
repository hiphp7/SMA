package com.wi360.sms.marketing.dao;

import java.util.ArrayList;
import java.util.List;

import com.wi360.sms.marketing.bean.UploadSmsTaskBean;
import com.wi360.sms.marketing.bean.UploadSmsTaskBean.UploadInnerSmsTaskBean;

import android.content.ContentValues;
import android.content.Context;
import android.database.Cursor;
import android.database.sqlite.SQLiteDatabase;

public class TaskDao {

	private TaskDBHelper helper;

	public TaskDao(Context context) {
		super();
		
		helper = new TaskDBHelper(context);
		SQLiteDatabase db = helper.getWritableDatabase();
	}


	
	/**
	 * 添加上报失败任务缓存
	 * 
	 * @return
	 */
	public boolean addsubtaskfailed(UploadInnerSmsTaskBean info) {

		SQLiteDatabase db = helper.getWritableDatabase();

		ContentValues values = new ContentValues();
		
		values.put("adtaskId", info.taskId);

		values.put("subtaskId", info.issueId);

		values.put("status", info.status);
		
		
		long rowid = db.insert("subtaskfailed", null, values);

		if (rowid == -1) {
			return false;
		} else {
			return true;
		}

	}
	
	
	
	//根据任务ID删除缓存
	public boolean deletefailedbyid(String taskId) {

		SQLiteDatabase db = helper.getWritableDatabase();

		int rowid = db.delete("subtaskfailed", "taskId = ?",
				new String[] { taskId});
		if (rowid == 0) {
			return false;
		} else {
			return true;
		}
	}
	
	
	
	/**
	 * 返回缓存的未上报任务
	 * 
	 * @return
	 */
	public List<UploadInnerSmsTaskBean> findAllfailed() {

		SQLiteDatabase db = helper.getReadableDatabase();

		Cursor cursor = db
				.query("subtaskfailed", new String[] { "taskId",
						"issueId","status"},null,
						null, null, null, null);
	
		List<UploadInnerSmsTaskBean> lists = new ArrayList<UploadInnerSmsTaskBean>();
		while (cursor.moveToNext()) {
		
			UploadInnerSmsTaskBean info = new UploadSmsTaskBean().new UploadInnerSmsTaskBean();
			info.taskId=cursor.getString(0);
			info.issueId=cursor.getString(1);
			info.status=cursor.getString(2);
			
			
			lists.add(info);
		}
		
		cursor.close();
		db.close();
		return lists;
	}

	
}
