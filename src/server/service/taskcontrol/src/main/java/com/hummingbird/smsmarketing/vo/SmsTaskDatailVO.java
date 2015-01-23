/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import org.apache.commons.lang.time.DateUtils;

/**
 * 返回每一条任务的数据
 * @author huangjiej_2
 * 2014年12月9日 下午10:33:02
 */
public class SmsTaskDatailVO {

	
	private ITask it;
	public SmsTaskDatailVO(ITask it){
		this.it = it;
		
	}

	public int getTaskId(){
		return it.getTaskId();
	}
	public int getIssueId(){
		return it.getIssueId();
	}
	public String getDestMobile(){
		return it.getSendsmobileNum();
	}
	public String getContent(){
		return it.getContent().getSmscontent();
	}
	/**
	 * 失效时间
	 * @return
	 */
	public int getExpireIn(){
		return (int) ((it.getEndTime()!=null)?((it.getEndTime().getTime()-System.currentTimeMillis())/1000):0);
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "SmsTaskDatailVO [getTaskId()=" + getTaskId()
				+ ", getIssueId()=" + getIssueId() + ", getDestMobile()="
				+ getDestMobile() + ", getContent()=" + getContent()
				+ ", getExpireIn()=" + getExpireIn() + "]";
	}
	
	
	
}
