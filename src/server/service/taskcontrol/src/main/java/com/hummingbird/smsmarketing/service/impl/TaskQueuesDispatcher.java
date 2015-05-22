/**
 * 
 */
package com.hummingbird.smsmarketing.service.impl;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;
import java.util.ListIterator;
import java.util.Timer;
import java.util.TimerTask;
import java.util.concurrent.locks.ReentrantLock;

import javax.annotation.PostConstruct;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.context.ApplicationContext;
import org.springframework.context.annotation.Scope;
import org.springframework.stereotype.Service;

import com.hummingbird.common.util.SpringBeanUtil;
import com.hummingbird.common.vo.StatusCheckResult;
import com.hummingbird.common.vo.StatusChecker;
import com.hummingbird.smsmarketing.constant.TaskTypeConst;
import com.hummingbird.smsmarketing.entity.Content;
import com.hummingbird.smsmarketing.entity.Issue;
import com.hummingbird.smsmarketing.exception.QueueException;
import com.hummingbird.smsmarketing.exception.TaskDispatchException;
import com.hummingbird.smsmarketing.mapper.ContentMapper;
import com.hummingbird.smsmarketing.mapper.IssueMapper;
import com.hummingbird.smsmarketing.mapper.IssueRoujiMapper;
import com.hummingbird.smsmarketing.service.IssueSendDispatcher;
import com.hummingbird.smsmarketing.service.QueueManager;
import com.hummingbird.smsmarketing.vo.ITask;
import com.hummingbird.smsmarketing.vo.ITaskSet;
import com.hummingbird.smsmarketing.vo.IssueSendRequest;
import com.hummingbird.smsmarketing.vo.IssueSendRequestSet;
import com.hummingbird.smsmarketing.vo.IssueSender;
import com.hummingbird.smsmarketing.vo.RequestAttrSet;

/**
 * 任务列表管理器
 * @author huangjiej_2
 * 2014年12月8日 下午7:42:12
 */
@Service
@Scope("singleton")
public class TaskQueuesDispatcher implements IssueSendDispatcher,StatusChecker{

	private static TaskQueuesDispatcher self;
	
	/**
	 * 定时处理器
	 */
	private static Timer timer =  new Timer(true);
	
	Log log = LogFactory.getLog(TaskQueuesDispatcher.class);
	private List<QueueManager> queuelist = new ArrayList<QueueManager>();
	
	@Autowired
	private IssueMapper issueMapper;
	@Autowired
	private ContentMapper contentMapper;
	
	@Autowired
	private ApplicationContext ac;
	
	public TaskQueuesDispatcher(){
		//initAllQueue();
	}
	
	static{
		//加载定时任务
		timer.schedule(QueueGCService.getInstance(),60*1000,86400*1000);
//		timer.schedule(QueueGCService.getInstance(),30*1000,60*1000);
	}
	
	/**
	 * 获取实例
	 * @return
	 */
	
//	public static TaskQueuesDispatcher getInstance(){
//	if(self==null){
//		ReentrantLock lock = new ReentrantLock();
//		try {
//			lock.lock();
//			if(self==null){
//				self = new TaskQueuesDispatcher();
//				self.initAllQueue();
//			}
//			
//		} finally  {
//			lock.unlock();
//		}
//	}
//	return self;
//}
	
	/**
	 * 获取app下可用的队列，因为手机app与队列是多对多关系，一个手机不会对应所有的队列
	 * @param issueSender
	 * @return
	 */
	public List<QueueManager> getQueuesWithIssueSender(IssueSender issueSender){
		if(log.isDebugEnabled())
		{
			log.debug(String.format("根据app【号码%s】获取可请求的队列",issueSender.getMobileNum()));
		}
//		IssueMapper issueMapper = SpringBeanUtil.getInstance().getBean(IssueMapper.class);
		List<Issue> effectiveIssue =issueMapper.getEffectiveIssueByMobile(issueSender.getMobileNum());
		//通过比较issueid 以得到队列列表
		List<QueueManager> effectiveqms = new ArrayList<QueueManager>(30);
		for (Iterator iterator = effectiveIssue.iterator(); iterator.hasNext();) {
			Issue issue = (Issue) iterator.next();
			boolean notfount = true;
			
			for (Iterator iterator2 = queuelist.iterator(); iterator2
					.hasNext();) {
				QueueManager queueManager = (QueueManager) iterator2.next();
				if(queueManager.getQueueId().equals(issue.getIssueid().toString())){
					if(log.isTraceEnabled())
					{
						log.trace(String.format("以下队列满足要求:%s",queueManager));
					}
					effectiveqms.add(queueManager);
					notfount=false;
					break;
				}
			}
			if(notfount)
			{
				QueueManager initqm = initQueue(issue.getIssueid().toString());
				if(log.isTraceEnabled())
				{
					log.trace(String.format("以下队列满足要求:%s",initqm));
				}
				effectiveqms.add(initqm);
			}
			
		}
		if(log.isDebugEnabled())
		{
			log.debug(String.format("根据app【号码%s】获取可请求的队列，队列数为%s",issueSender.getMobileNum(),effectiveqms.size()));
		}
		return effectiveqms;
	}
	
	
	
	/**
	 * 根据发布号初始化队列
	 * @param issueId
	 * @return 
	 */
	public QueueManager initQueue(String issueId){
		if(log.isDebugEnabled())
		{
			log.debug(String.format("初始化id为【%s】的队列",issueId));
		}
		for (Iterator iterator = queuelist.iterator(); iterator.hasNext();) {
			QueueManager qm = (QueueManager) iterator.next();
			String queueId = qm.getQueueId();
			if(issueId.equals(queueId)){
				//已初始化
				if(log.isTraceEnabled())
				{
					log.trace(String.format("队列【%s】已经初始化过了",issueId));
				}
				return qm;
			}
		}
		Content content = contentMapper.selectByIssueId(issueId);
		TaskQueueManager tqm = ac.getBean(TaskQueueManager.class);
		tqm.setQueueId(issueId);
		tqm.addAttr(RequestAttrSet.REQUEST_TASK_TYPE, content.getType());
		tqm.rechargeQueue();
		queuelist.add(tqm);
		if(log.isTraceEnabled())
		{
			log.trace(String.format("队列初始化成功【%s】",tqm));
		}
		return tqm;
	}
	
	/**
	 * 初始化整个队列
	 */
	@PostConstruct
	public void initAllQueue(){
		if(log.isDebugEnabled())
		{
			log.debug(String.format("初始化所有队列"));
		}
		
		//获取所有的发布id
		//IssueMapper issueMapper = SpringBeanUtil.getInstance().getBean(IssueMapper.class);
		List<Issue> effectiveIssue = issueMapper.getEffectiveIssue();
		for (Iterator iterator = effectiveIssue.iterator(); iterator.hasNext();) {
			Issue issue = (Issue) iterator.next();
			Content content = contentMapper.selectByPrimaryKey(issue.getContentid());
			if(log.isDebugEnabled())
			{
				log.debug(String.format("初始化id为【%s】的队列",issue.getIssueid()));
			}
			TaskQueueManager tqm = ac.getBean(TaskQueueManager.class);
//			TaskQueueManager tqm = new TaskQueueManager();
			tqm.setQueueId(issue.getIssueid().toString());
			tqm.addAttr(RequestAttrSet.REQUEST_TASK_TYPE, content.getType());
			tqm.rechargeQueue();
			if(log.isTraceEnabled())
			{
				log.trace(String.format("队列初始化成功【%s】",tqm));
			}
			queuelist.add(tqm);
		}
	}
	
	
	
	
	@Override
	public Object getTasks(IssueSendRequestSet sendReqSet)
			throws TaskDispatchException {
		if(log.isDebugEnabled())
		{
			log.debug(String.format("根据请求【%s】从队列中分配任务",sendReqSet));
		}
		IssueSender issueSender = sendReqSet.getIssueSender();
		List<IssueSendRequest> requestlist = sendReqSet.getRequests();
		if(requestlist!=null){
			for (Iterator iterator = requestlist.iterator(); iterator.hasNext();) {
				IssueSendRequest sendReq = (IssueSendRequest) iterator.next();
				if(log.isTraceEnabled())
				{
					log.trace(String.format("使用任务请求器【%s】向各队列请求任务",sendReq));
				}
				if(sendReq.getRequestTaskCount()<=0){
					if(log.isTraceEnabled())
					{
						log.trace(String.format("使用任务请求器【%s】请求数为0，不再执行请求，返回结果",sendReq));
					}
					return "ok";
				}
				//每一个请求可以有多个符合条件的列表，依次获取，获取到后进行合并
				List<QueueManager> effectivequeues = getQueuesWithIssueSender(issueSender);
				for (Iterator iterator2 =effectivequeues .iterator(); iterator2
						.hasNext();) {
					QueueManager qm = (QueueManager) iterator2.next();
					if(log.isTraceEnabled())
					{
						log.trace(String.format("从队列[%s]尝试分配请求给任务请求器【%s】",qm,sendReq));
					}
					if(qm.support(sendReq)){
						ITaskSet tasks;
						try {
							tasks = qm.getTasks(sendReq);
							//如果qm为空了，则停用此qm
							//在这里好像又不需要，因为qm要等待数据确认的，
							boolean ifok = sendReq.merge(tasks);//
							if(log.isTraceEnabled()){
								log.trace(String.format("队列%s返回任务%s条，合并到任务请求器【%s】",qm,tasks.getTaskcount(),sendReq));
							}
							if(ifok && sendReq.getRequestTaskCount() ==0){
								break;
							}
						} catch (QueueException e) {
							log.error(String.format("从队列【%s】获取任务出错",qm),e);
						}
					}else{
						if(log.isTraceEnabled())
						{
							log.trace(String.format("从队列[%s]不支持该请求【%s】",qm,sendReq));
						}
					}
				}
				
			}
		}
		return "ok";
	}

	@Override
	public void comfirmTask(ITask task) throws TaskDispatchException {
		if (log.isDebugEnabled()) {
			log.debug(String.format("确认任务%s",task));
		}
		Integer issueId = task.getIssueId();
		boolean hadconfirm = false;
		for (Iterator iterator = queuelist.iterator(); iterator.hasNext();) {
			QueueManager qm = (QueueManager) iterator.next();
			if(qm.getQueueId().equals(issueId.toString())){
				if (log.isTraceEnabled()) {
					log.trace(String.format("队列[%s]确认任务%s", qm,task));
				}
				
				try {
					qm.confirmTask(task);
					hadconfirm = true;
				} catch (QueueException e) {
					log.error(String.format("队列[%s]确认任务%s出错", qm,task),e);
					throw new TaskDispatchException(e.getMessage(),e);
				}
			}
			
		}
		if(!hadconfirm)
		{
			throw new TaskDispatchException("发布号"+issueId+"不存在或不是在发布中");
		}
		
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.common.vo.StatusChecker#statusCheck()
	 */
	@Override
	public StatusCheckResult statusCheck() {
		
		StringBuilder sb = new StringBuilder();
		StatusCheckResult sr = new StatusCheckResult();
		//任务状态
		sr.put("任务队列数", this.queuelist.size());
		
		
		//各队列的具体内容
		int i=0;
		for (Iterator iterator = queuelist.iterator(); iterator.hasNext();) {
			QueueManager queueManager = (QueueManager) iterator.next();
			if (queueManager instanceof StatusChecker) {
				StatusChecker qsc = (StatusChecker) queueManager;
				
				StatusCheckResult qsr = qsc.statusCheck();
				sr.put("队列"+i++, qsr);
			}
			
		}
		
		return sr;
	}
	
	
	/**
	 * 中止队列，不处理
	 * @param issueId
	 */
	public void cancleQueue(String issueId){
		if(log.isDebugEnabled())
		{
			log.debug(String.format("终止id为【%s】的队列",issueId));
		}
		for (Iterator iterator = queuelist.iterator(); iterator.hasNext();) {
			QueueManager qm = (QueueManager) iterator.next();
			String queueId = qm.getQueueId();
			if(issueId.equals(queueId)){
				//调整队列的状态
				qm.cancle();
			}
		}
	}
	
	/**
	 * 设置可以从数据库重新加载
	 */
	public void resetLoadFlag(String reloadqueue){
		if (log.isTraceEnabled()) {
			log.trace(String.format("设置任务可以重新从数据库加载", this));
		}
		
		for (Iterator iterator = queuelist.iterator(); iterator.hasNext();) {
			QueueManager queueManager = (QueueManager) iterator.next();
			String queueid = queueManager.getQueueId();
			if (reloadqueue==null || reloadqueue.equals(queueid)) {
				queueManager.resetLoadFlag();
			}
			
		}
	}

	/**
	 * 移除队列
	 * @param queueId
	 */
	public void removeQueue(String issueId) {
		
		if(log.isDebugEnabled())
		{
			log.debug(String.format("移除id为【%s】的队列",issueId));
		}
		for (ListIterator iterator = queuelist.listIterator(); iterator.hasNext();) {
			QueueManager qm = (QueueManager) iterator.next();
			String queueId = qm.getQueueId();
			if(issueId.equals(queueId)){
				//调整队列的状态
				qm.preRemove();
				iterator.remove();
			}
		}
	}

}
