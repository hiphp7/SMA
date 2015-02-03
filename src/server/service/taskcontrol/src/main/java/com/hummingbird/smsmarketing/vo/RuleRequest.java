package com.hummingbird.smsmarketing.vo;

import org.apache.commons.collections.keyvalue.DefaultKeyValue;

/**
 * 规则请求
 * @author huangjiej_2
 * 2014年12月6日 下午2:31:57
 */
public interface RuleRequest {

	/**
	 * 获取任务请求器
	 * @return
	 */
	IssueSendRequestSet getIssueSendRequestSet();

	/**
	 * 添加或合并请求，取请求中数量小的为新的请求
	 * @param count
	 * @param defaultKeyValueArr 请求的属性，属性在set中是唯一的，相同属性的可以进行比较和合并
	 */
	void addOrMergeRequest(int count, DefaultKeyValue ... defaultKeyValueArr);
	
	

}
