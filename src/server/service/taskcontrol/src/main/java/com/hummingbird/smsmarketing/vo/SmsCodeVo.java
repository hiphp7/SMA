package com.hummingbird.smsmarketing.vo;

/**
 * 验证码对象
 * @author huangjiej_2
 * 2014年10月18日 下午10:07:20
 */
public class SmsCodeVo {

	/**
	 * 手机号
	 */
	private String mobileNum;
	/**
	 * 验证码
	 */
	private String smsCode;
	/**
	 * 手机号
	 */
	public String getMobileNum() {
		return mobileNum;
	}
	/**
	 * 手机号
	 */
	public void setMobileNum(String mobileNum) {
		this.mobileNum = mobileNum;
	}
	/**
	 * 验证码
	 */
	public String getSmsCode() {
		return smsCode;
	}
	/**
	 * 验证码
	 */
	public void setSmsCode(String smsCode) {
		this.smsCode = smsCode;
	}
	@Override
	public String toString() {
		return "SmsCodeVo [mobileNum=" + mobileNum + ", smsCode=" + smsCode
				+ "]";
	}
	
	
	
}
