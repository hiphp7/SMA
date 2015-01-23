/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

/**
 * 短信任务返回对象，主要是controller返回
 * @author huangjiej_2
 * 2014年12月9日 下午10:30:24
 */
public class SmsTaskVo {
	
	public SmsTaskVo(List<? extends ITask> tasks) {
		if(tasks!=null){
			
			for (Iterator iterator = tasks.iterator(); iterator.hasNext();) {
				ITask iTask = (ITask) iterator.next();
				task.add(new SmsTaskDatailVO(iTask));
			}
		}
	}

	private List<SmsTaskDatailVO> task=new ArrayList();

	/**
	 * @return the task
	 */
	public List<SmsTaskDatailVO> getTask() {
		return task;
	}

	/**
	 * @param task the task to set
	 */
	public void setTask(List<SmsTaskDatailVO> task) {
		this.task = task;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "SmsTaskVo [task=" + task + "]";
	}
	
	
}
