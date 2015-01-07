package com.wi360.sms.marketing.bean;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.DeviceUtil;

/**
 * 11 获取笔记详细
 * 
 * @author Administrator
 * 
 */
public class GetNotesDescBean extends BaseBean {

	public int pageIndex;
	public int pageSize;
	public String issueId;

	public GetNotesDescBean(int pageIndex, int pageSize, String issueId) {
		this.pageIndex = pageIndex;
		this.pageSize = pageSize;
		this.issueId = issueId;
	}

}
