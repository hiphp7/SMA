/**
 * 
 * QueueGCService.java
 * 版本所有 深圳市蜂鸟娱乐有限公司 2013-2014
 */
package com.hummingbird.smsmarketing.service.impl;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.ListIterator;
import java.util.Map;
import java.util.TimerTask;
import java.util.concurrent.locks.ReentrantLock;

import com.hummingbird.common.util.SpringBeanUtil;
import com.hummingbird.smsmarketing.entity.Issue;
import com.hummingbird.smsmarketing.mapper.IssueMapper;

/**
 * @author john huang
 * 2015年5月21日 下午4:13:00
 * 本类主要做为 队列移除器
 */
public class QueueGCService extends TimerTask {

	org.apache.commons.logging.Log log = org.apache.commons.logging.LogFactory
			.getLog(this.getClass());
	/**
	 * 移除等待时间
	 */
	private long removetime = 3*86400*1000;
	
	/**
	 * 移除队列
	 */
	List<Map> removelist = new ArrayList<Map>();
	Map queuemap = new HashMap();
	
	private QueueGCService(){
//		removetime = 1*60*1000;
	}
	
	private static QueueGCService self = null;
	
	/**
	 * 获取单例
	 * @return
	 */
	public static QueueGCService getInstance(){
		if(self==null){
			
			ReentrantLock lock = new ReentrantLock();
			try{
				lock.lock();
				if(self==null){
					self = new QueueGCService();
				}
			}
			finally{
				lock.unlock();
			}
			
			
		}
		return self;
	}
	
	public void add2remove(String queueId){
		if (log.isDebugEnabled()) {
			log.debug(String.format("添加队列%s去移除",queueId));
		}
		Map map = new HashMap();
		map.put("queueId", queueId);
		map.put("removetime", System.currentTimeMillis()+removetime);
		if(!queuemap.containsKey(map)){
			removelist.add(map);
			queuemap.put(map, null);
		}
	}

	/* (non-Javadoc)
	 * @see java.util.TimerTask#run()
	 */
	@Override
	public void run() {
		if (log.isDebugEnabled()) {
			log.debug(String.format("开始移除队列,当时时间戳是%s",System.currentTimeMillis()));
		}
		long now = System.currentTimeMillis();
		TaskQueuesDispatcher taskQueuesDispatcher = SpringBeanUtil.getInstance().getBean(TaskQueuesDispatcher.class);
		for (ListIterator iterator = removelist.listIterator(); iterator.hasNext();) {
			Map map = (Map) iterator.next();
			String queueId = (String) map.get("queueId" );
			long toremovetime = (long) map.get("removetime");
			if(toremovetime<now){
				if (log.isDebugEnabled()) {
					log.debug(String.format("列队%s符合条件，进行移除",queueId));
				}
				taskQueuesDispatcher.removeQueue(queueId);
				iterator.remove();
			}
		}
		//获取过期的发布
		if (log.isDebugEnabled()) {
			log.debug(String.format("检查过期发布的进行中止"));
		}
		IssueMapper issueDao = SpringBeanUtil.getInstance().getBean(IssueMapper.class);
		List<Issue> issueslist = issueDao.selectOvertimeIssue();
		for (Iterator iterator = issueslist.iterator(); iterator.hasNext();) {
			Issue issue = (Issue) iterator.next();
			issue.setStatus("DON");
			issueDao.updateByPrimaryKey(issue);
			add2remove(issue.getIssueid().toString());
		}
		
	}
	
	
}
