package com.wi360.sms.marketing.bean;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.DeviceUtil;

/**
 * 10 查询有笔记的发布号列表
 * 
 * @author Administrator
 * 
 */
public class FindNotesReleaseNumberListBean extends BaseBean {

	public int pageIndex;
	public int pageSize;

	// public String sort;

	public FindNotesReleaseNumberListBean(int pageIndex, int pageSize) {
		this.pageIndex = pageIndex;
		this.pageSize = pageSize;
		// this.sort = sort;
	}

}
