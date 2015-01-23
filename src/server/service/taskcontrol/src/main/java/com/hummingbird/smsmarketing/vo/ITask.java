/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import java.util.Date;

import com.hummingbird.smsmarketing.entity.Content;

/**
 * 具体任务，每条任务已包含发送的信息
 * @author huangjiej_2
 * 2014年12月6日 上午10:24:01
 */
public interface ITask {

	/**
	 * 任务状态
	 * @return
	 */
	public abstract String getStatus();

	/**
	 * 
	 * @return
	 */
	public abstract Integer getContentId();

	/**
	 * 任务类型
	 * @return
	 */
	public abstract String getType();

	/**
	 * 目标手机
	 * @return
	 */
	public abstract String getSendsmobileNum();
	
	

	public abstract Integer getIssueId();

	/**
	 * 任务ID
	 * @return
	 */
	public abstract Integer getTaskId();

	public abstract Date getEndTime();

	public abstract Date getStartTime();

	/**
	 * 内容对象
	 * @return
	 */
	public Content getContent();
	
	/**
	 * 发送的手机app应用
	 * @return
	 */
	public String getRoujiMobileNum();

}
