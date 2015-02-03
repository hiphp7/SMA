package com.hummingbird.smsmarketing.service;

import com.hummingbird.smsmarketing.entity.Task;
import com.hummingbird.smsmarketing.exception.TaskDispatchException;
import com.hummingbird.smsmarketing.vo.ITask;
import com.hummingbird.smsmarketing.vo.IssueSendRequestSet;

/**
 * 发布调度器，主要是根据请求的内容，协调多个队列实现获取任务
 * @author huangjiej_2
 * 2014年12月6日 上午10:08:52
 */
public interface IssueSendDispatcher {

	
	/**
	 * 请求发送任务
	 * @param sendReq 需要获取的目标内容，任务列表会保存到sendReq中
	 * @return 无
	 */
	Object getTasks(IssueSendRequestSet sendReqSet) throws TaskDispatchException;

	/**
	 * 
	 * @param task
	 * @throws TaskDispatchException
	 */
	void comfirmTask(ITask task)throws TaskDispatchException;
	
	
}
