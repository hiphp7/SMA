package com.wi360.sms.marketing.bean;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.DeviceUtil;

/**
 * 12 获取笔记详细(肉鸡)
 * 
 * @author Administrator
 * 
 */
public class GetNotesDescBean12 extends BaseBean {

	public String customerMobileNum;
	public String issueId;

	public GetNotesDescBean12(String customerMobileNum, String issueId) {
		this.customerMobileNum = customerMobileNum;
		this.issueId = issueId;
	}

}
