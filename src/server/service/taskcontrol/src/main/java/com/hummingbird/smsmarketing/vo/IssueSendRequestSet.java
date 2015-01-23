package com.hummingbird.smsmarketing.vo;

import java.util.List;

/**
 * 任务请求器列表
 * @author huangjiej_2
 * 2014年12月6日 上午10:17:48
 */
public interface IssueSendRequestSet extends Iterable<IssueSendRequest> {

	/**
	 * 发布者
	 * @return
	 */
	IssueSender getIssueSender();
	
	/**
	 * 发送请求序列
	 * @return
	 */
	List<IssueSendRequest> getRequests();
	
	
	/**
	 * 获取请求到的所有任务
	 * @return
	 */
	List<ITask> getTasks();
	
}
