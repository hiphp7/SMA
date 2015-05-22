package com.hummingbird.smsmarketing.service;

import com.hummingbird.smsmarketing.exception.QueueException;
import com.hummingbird.smsmarketing.vo.ITask;
import com.hummingbird.smsmarketing.vo.ITaskSet;
import com.hummingbird.smsmarketing.vo.IssueSendRequest;

/**
 * 队列管理者，主要维护队列，一个队列就是一个待分配的任务（任务为“发布”的数据）
 * @author huangjiej_2
 * 2014年12月6日 上午9:47:01
 */
public interface QueueManager {

	/**
	 * 获取队列中的请求
	 * @param sendReq
	 * @return
	 */
	ITaskSet getTasks(IssueSendRequest sendReq) throws QueueException;
	
	/**
	 * 确认任务
	 * @param task
	 * @throws QueueException
	 */
	void confirmTask(ITask task)throws QueueException;

	/**
	 * 是否支持该请求，这里只是根据任务的属性作为判断
	 * @param sendReq
	 * @return
	 */
	boolean support(IssueSendRequest sendReq);

	/**
	 * 队列ID
	 * @return
	 */
	String getQueueId();

	public abstract void resetLoadFlag();

	/**
	 * 中止队列
	 */
	void cancle();

	/**
	 * 移除的准备工作
	 */
	void preRemove();

	
}
