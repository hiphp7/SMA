package com.wi360.sms.marketing.bean;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.DeviceUtil;
import com.wi360.sms.marketing.utils.L;

public class LoginBean extends BaseBean {

	public String appId;
	public String nonce;
	public String signature;
	public String timeStamp;
	public DeviceInfo deviceInfo;
	public LoginInfo loginInfo;

	public LoginBean(Activity context, String phone, String smsCode) {
		appId = getAppId(context);
		nonce = getRandNum(12) + "";
		timeStamp = getTimeStamp();
		String md5 = getMD5Str(getSort(appId, getAppKey(context), timeStamp, nonce));
		L.i(md5);
		signature = md5;
		deviceInfo = new DeviceInfo(context);
		loginInfo = new LoginInfo(phone, smsCode);
	}

	public class DeviceInfo {
		// 设备号
		public String deviceId;
		// SIM卡设备号
		public String imsi;
		// 设备的MCA地址
		public String mac;

		public DeviceInfo(Activity context) {
			deviceId = DeviceUtil.getDeviceId(context);
			imsi = DeviceUtil.getImsi(context);
			mac = DeviceUtil.getMacAddress(context);
		}
	}

	public class LoginInfo {
		public LoginInfo(String phone, String smsCode) {
			this.mobileNum = phone;
			this.smsCode = smsCode;
		}

		public String mobileNum;
		public String smsCode;
	}
}
