/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

/**
 * 默认的任务请求器列表
 * @author huangjiej_2
 * 2014年12月8日 下午11:53:44
 */
public class DefaultIssueSendRequestSet implements IssueSendRequestSet {

	private IssueSender sender;
	
	/**
	 * 请求器列表
	 */
	private List<IssueSendRequest> requestlist = new ArrayList<IssueSendRequest>();

	public DefaultIssueSendRequestSet(IssueSender sender) {
		this.sender = sender;
	}

	/* (non-Javadoc)
	 * @see java.lang.Iterable#iterator()
	 */
	@Override
	public Iterator<IssueSendRequest> iterator() {
		return requestlist.iterator();
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.vo.IssueSendRequestSet#getIssueSender()
	 */
	@Override
	public IssueSender getIssueSender() {
		return sender;
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.vo.IssueSendRequestSet#getRequest()
	 */
	@Override
	public List<IssueSendRequest> getRequests() {
		return requestlist;
	}

	public int getRequestCount(){
		return requestlist.size();
	}

	/**
	 * 添加一个请求任务
	 * @param request
	 */
	public void addIssueSendRequest(DefaultIssueSendRequest request) {
		requestlist.add(request);
		
	}

	/**
	 * 通过请求任务属性查询请求任务
	 * @param attrset
	 * @return
	 */
	public IssueSendRequest findRequest(RequestAttrSet attrset) {
		for (Iterator iterator = requestlist.iterator(); iterator.hasNext();) {
			IssueSendRequest isr = (IssueSendRequest) iterator.next();
			if(isr.hasAttr(attrset)){
				return isr;
			}
		}
		return null;
	}
	
	/**
	 * 获取请求到的所有任务
	 * @return
	 */
	public List<ITask> getTasks(){
		DefaultTaskSet taskset = new DefaultTaskSet();
		for (Iterator iterator = requestlist.iterator(); iterator.hasNext();) {
			IssueSendRequest isr = (IssueSendRequest) iterator.next();
			ITaskSet itaskset = isr.getResponses();
			taskset.merge(itaskset);
		}
		
		return taskset.getTasks();
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "DefaultIssueSendRequestSet [sender=" +(sender!=null?sender.getMobileNum():"")
				+ ", requestlist=" + requestlist + "]";
	}
	
	
}
