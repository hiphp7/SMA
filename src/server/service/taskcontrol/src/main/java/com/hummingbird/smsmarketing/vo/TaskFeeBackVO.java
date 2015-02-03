package com.hummingbird.smsmarketing.vo;

import java.util.ArrayList;
import java.util.List;

import com.hummingbird.smsmarketing.entity.Task;

/**
 * 任务反馈vo，主要是任务成功或失败的信息处理
 * @author huangjiej_2
 * 2014年12月10日 上午1:22:04
 */
public class TaskFeeBackVO {

	private List<Task> task = new ArrayList<Task>();

	/**
	 * @return the task
	 */
	public List<Task> getTask() {
		return task;
	}

	/**
	 * @param task the task to set
	 */
	public void setTask(List<Task> task) {
		this.task = task;
	}
	
	
	
	
}
