/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import com.hummingbird.common.util.ValidateUtil;
import com.hummingbird.commonbiz.vo.BaseUserDecidable;

/**
 * 登录vo
 * @author huangjiej_2
 * 2014年10月18日 下午10:09:58
 */
public class LoginVo extends BaseUserDecidable  {

//	{"appId":"zjhtwallet","appKeyHash":"23werwere3erewfffereee","timeStamp":"TIMESTAMP", "nonce":"NONCE","signature":"MD5(appId+appKey+nonce+timeStamp)",
//		"loginInfo":{"mobileNum":"13912345678","smsCode":"223344"},
//		"deviceInfo":{"mac":"3ere:eee:3434:34434","deviceId":"设备标识","imsi":"SIM卡设备号"}}  
	/**
	 * 验证码对象
	 */
	private  SmsCodeVo loginInfo;
	/**
	 * 设备
	 */
	private  DeviceInfo deviceInfo;
	
	/**
	 * 验证码对象
	 */
	public SmsCodeVo getLoginInfo() {
		return loginInfo;
	}
	/**
	 * 验证码对象
	 */
	public void setLoginInfo(SmsCodeVo loginInfo) {
		this.loginInfo = loginInfo;
	}
	/**
	 * 设备
	 */
	public DeviceInfo getDeviceInfo() {
		return deviceInfo;
	}
	/**
	 * 设备
	 */
	public void setDeviceInfo(DeviceInfo deviceInfo) {
		this.deviceInfo = deviceInfo;
	}
	@Override
	public String toString() {
		return "LoginVo [loginInfo=" + loginInfo + ", deviceInfo=" + deviceInfo
				+ ", getTimeStamp()=" + getTimeStamp() + ", getNonce()="
				+ getNonce() + ", getSignature()=" + getSignature()
				+ ", getToken()=" + getToken() + ", getAppId()=" + getAppId()
				+ "]";
	}
	
	public String getMobileNum(){
		if(loginInfo!=null){
			return loginInfo.getMobileNum();
		}
		return null;
	}
	@Override
	public String getPlaintext() {
		
		return ValidateUtil.sortbyValues(getAppId(),getAppKey(),getNonce(),getTimeStamp());
	}
	
	
	
	
}
