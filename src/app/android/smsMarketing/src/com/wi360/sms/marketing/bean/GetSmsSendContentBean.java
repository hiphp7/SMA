package com.wi360.sms.marketing.bean;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.DeviceUtil;

/**
 * 3 获取短信发送任务接口
 * 
 * APP登录成功后，可以使用该接口向后台获取短信发送任务。
 * 
 * 前置条件：要求必须登录成功。
 * 
 * @author Administrator
 * 
 */
public class GetSmsSendContentBean extends BaseBean {

	public String appId;
	public String nonce;
	public String signature;
	public String timeStamp;
	public DeviceInfo deviceInfo;
	public LoginInfo loginInfo;

	public GetSmsSendContentBean(Activity context, String phone, String smsCode) {
		appId = getAppId(context);
		nonce = getRandNum(12) + "";
		timeStamp = getTimeStamp();
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
