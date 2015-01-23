/**
 * 
 * SmsSender.java
 * 版本所有 深圳市蜂鸟娱乐有限公司 2013-2014
 */
package com.hummingbird.smsmarketing.service;

import com.hummingbird.common.vo.ResultModel;

/**
 * @author huangjiej_2
 * 2015年1月23日 上午11:56:02
 * 本类主要做为
 */
public interface SmsSender {

	/**
	 * 下行短信
	 * @param mobileNum
	 * @param context
	 */
	public abstract ResultModel sendMsg(String mobileNum, String context);

}