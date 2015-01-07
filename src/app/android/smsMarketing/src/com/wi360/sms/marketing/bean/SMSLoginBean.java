package com.wi360.sms.marketing.bean;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.L;

public class SMSLoginBean extends BaseBean {
	/*
	 * { "appId": "smsmarketing", "mobileNum": "13912345678", "nonce": "NONCE",
	 * "signature": "SIGNATURE", "timeStamp": "TIMESTAMP" }
	 */
	public String appId;
	public String mobileNum;
	public String nonce;
	public String signature;
	public String timeStamp;

	public SMSLoginBean(Activity context, String phone) {
		appId = getAppId(context);
		mobileNum = phone;
		nonce = getRandNum(12) + "";
		timeStamp = getTimeStamp();
		String md5 = getMD5Str(getSort(appId, getAppKey(context), timeStamp, nonce));
		L.i(md5);
		signature = md5;

	}

}
