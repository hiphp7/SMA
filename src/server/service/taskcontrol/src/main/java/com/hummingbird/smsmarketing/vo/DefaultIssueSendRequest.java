/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import com.hummingbird.smsmarketing.constant.TaskTypeConst;

/**
 * 任务请求器的实现
 * @author huangjiej_2
 * 2014年12月8日 下午11:35:27
 */
public class DefaultIssueSendRequest implements IssueSendRequest {

	/**
	 * 发送者
	 */
	private IssueSender issueSender;
	/**
	 * 任务属性
	 */
	private RequestAttrSet requestAttrSet=new DefaultRequestAttrSet();
	
	private ITaskSet responses = new DefaultTaskSet();
	
	/**
	 * 原始的请求数
	 */
	private int rawRequestTaskCount;
	/**
	 * 任务请求数
	 */
	private int requestTaskCount;

	@Override
	public IssueSender getIssueSender() {
		return issueSender;
	}

	@Override
	public RequestAttrSet getRequestAttrs() {
		
		return requestAttrSet;
	}

	/**
	 * @return the requestAttrSet
	 */
	public RequestAttrSet getRequestAttrSet() {
		return requestAttrSet;
	}

	/**
	 * @param requestAttrSet the requestAttrSet to set
	 */
	public void setRequestAttrSet(RequestAttrSet requestAttrSet) {
		this.requestAttrSet = requestAttrSet;
	}

	/**
	 * @param issueSender the issueSender to set
	 */
	public void setIssueSender(IssueSender issueSender) {
		this.issueSender = issueSender;
	}

	/**
	 * @return the requestTaskCount
	 */
	public int getRequestTaskCount() {
		return requestTaskCount;
	}

	/**
	 * @param requestTaskCount the requestTaskCount to set
	 */
	public void setRequestTaskCount(int requestTaskCount) {
		this.requestTaskCount = requestTaskCount;
		rawRequestTaskCount = requestTaskCount;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "任务请求 [issueSender=" + (issueSender!=null?issueSender.getMobileNum():"")
				+ ", 任务属性=" + requestAttrSet + ", 请求数="
				+ requestTaskCount + ",得到的任务数为:"+(responses==null?0:responses.getTaskcount())+"]";
	}
	
	/**
	 * 是否包含指定属性
	 * @param attrset
	 * @return
	 */
	public boolean hasAttr(RequestAttrSet attrset){
		if(attrset.get(RequestAttrSet.REQUEST_TASK_TYPE).equals(TaskTypeConst.TASK_TYPE_ANY)
				||attrset.get(RequestAttrSet.REQUEST_TASK_TYPE).equals(TaskTypeConst.TASK_TYPE_ALL)
				){
			//任意属性的和所有属性的都返回true
			return true;
		}
		return this.requestAttrSet.equals(attrset);
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.vo.IssueSendRequest#merge(com.hummingbird.smsmarketing.vo.ITaskSet)
	 */
	@Override
	public boolean merge(ITaskSet tasks) {
		
		if( responses.merge(tasks)){
			requestTaskCount-=tasks.getTaskcount();
			return true;
		}
		return false;
	}

	/**
	 * @return the responses
	 */
	public ITaskSet getResponses() {
		return responses;
	}

	/**
	 * @return the rawRequestTaskCount
	 */
	public int getRawRequestTaskCount() {
		return rawRequestTaskCount;
	}
	


}
