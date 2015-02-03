/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import java.util.List;

/**
 * 任务组
 * @author huangjiej_2
 * 2014年12月7日 上午8:32:46
 */
public interface ITaskSet {

	/**
	 * 获取任务记录数
	 * @return
	 */
	int getTaskcount();
	
	/**
	 * 发送者
	 * @return
	 */
	IssueSender getIssueSender();

	/**
	 * 合并任务
	 * @param tasks
	 * @return
	 */
	boolean merge(ITaskSet tasks);
	
	/**
	 * 获取任务
	 * @return
	 */
	public List<ITask> getTasks();
	
	
}
