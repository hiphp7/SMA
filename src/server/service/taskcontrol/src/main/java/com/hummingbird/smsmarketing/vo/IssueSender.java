package com.hummingbird.smsmarketing.vo;

/**
 * 内容发送者，一般是把安装了神器的手机，用于发送内容，也称肉鸡
 * @author huangjiej_2
 * 2014年12月6日 上午10:05:39
 */
public interface IssueSender {

	/**
	 * 获取手机号
	 * @return
	 */
	public String getMobileNum();
	
	
}
