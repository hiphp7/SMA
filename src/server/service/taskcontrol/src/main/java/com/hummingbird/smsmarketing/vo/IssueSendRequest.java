package com.hummingbird.smsmarketing.vo;

/**
 * 任务请求器，返回每一条的请求，每一条请求包含一个满足条件的目标。
 * @author huangjiej_2
 * 2014年12月6日 上午10:21:06
 */
public interface IssueSendRequest {

	/**
	 * 发布者
	 * @return
	 */
	IssueSender getIssueSender();
	
	/**
	 * 获取请求属性
	 * @return 属性集合
	 */
	RequestAttrSet getRequestAttrs();
	
	/**
	 * 获取请求任务数
	 * @return
	 */
	public int getRequestTaskCount();

	/**
	 * 是否包含指定属性
	 * @param attrset
	 * @return
	 */
	boolean hasAttr(RequestAttrSet attrset);

	/**
	 * 合并请求
	 * @param tasks
	 * @return 合并成功
	 */
	boolean merge(ITaskSet tasks);
	
	/**
	 * @return the responses
	 */
	public ITaskSet getResponses();

	/**
	 * @return the rawRequestTaskCount
	 */
	public int getRawRequestTaskCount();
}
