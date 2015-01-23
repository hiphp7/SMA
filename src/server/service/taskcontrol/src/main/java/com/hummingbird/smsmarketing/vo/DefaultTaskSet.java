/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import java.util.ArrayList;
import java.util.List;

/**
 * 任务组实现
 * @author huangjiej_2
 * 2014年12月9日 上午8:25:46
 */
public class DefaultTaskSet implements ITaskSet {

	private IssueSender issueSender;

	/**
	 * 任务列表
	 */
	private List<ITask> tasklist = new ArrayList<ITask>(20);
	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.vo.ITaskSet#getIssueSender()
	 */
	@Override
	public IssueSender getIssueSender() {
		return issueSender;
	}
	@Override
	public int getTaskcount() {
		
		return tasklist.size();
	}
	@Override
	public boolean merge(ITaskSet tasks) {
		
		return tasklist.addAll(tasks.getTasks());
	}
	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.vo.ITaskSet#getTasks()
	 */
	@Override
	public List<ITask> getTasks() {
		return tasklist;
	}
	/**
	 * 手机app
	 * @param issueSender the issueSender to set
	 */
	public void setIssueSender(IssueSender issueSender) {
		this.issueSender = issueSender;
	}
	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "DefaultTaskSet [issueSender=" + (issueSender!=null?issueSender.getMobileNum():"") + ", tasklist="
				+ tasklist + "]";
	}
	/**
	 * 添加任务
	 * @param obj
	 */
	public void addTask(ITask obj) {
		tasklist.add(obj);
		
	}
	
	
	

}
