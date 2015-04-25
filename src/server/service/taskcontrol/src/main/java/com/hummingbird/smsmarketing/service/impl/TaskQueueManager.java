/**
 * 
 */
package com.hummingbird.smsmarketing.service.impl;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;
import java.util.concurrent.CopyOnWriteArraySet;
import java.util.concurrent.locks.ReentrantLock;

import org.apache.commons.lang.ObjectUtils;
import org.apache.commons.lang.math.NumberUtils;
import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.beans.factory.annotation.Value;
import org.springframework.context.annotation.Scope;
import org.springframework.stereotype.Service;

import com.hummingbird.common.vo.StatusCheckResult;
import com.hummingbird.common.vo.StatusChecker;
import com.hummingbird.smsmarketing.constant.QueueStatus;
import com.hummingbird.smsmarketing.constant.TaskStatusConst;
import com.hummingbird.smsmarketing.entity.Issue;
import com.hummingbird.smsmarketing.entity.Task;
import com.hummingbird.smsmarketing.exception.QueueException;
import com.hummingbird.smsmarketing.mapper.IssueMapper;
import com.hummingbird.smsmarketing.mapper.TaskMapper;
import com.hummingbird.smsmarketing.service.QueueManager;
import com.hummingbird.smsmarketing.vo.DefaultRequestAttrSet;
import com.hummingbird.smsmarketing.vo.DefaultTaskSet;
import com.hummingbird.smsmarketing.vo.ITask;
import com.hummingbird.smsmarketing.vo.ITaskSet;
import com.hummingbird.smsmarketing.vo.IssueSendRequest;
import com.hummingbird.smsmarketing.vo.RequestAttrSet;

/**
 * @author huangjiej_2
 * 2014年12月6日 下午3:17:03
 */
@Service
@Scope("prototype")
public class TaskQueueManager implements QueueManager,StatusChecker {

	Log log = LogFactory.getLog(TaskQueueManager.class);
	
	@Autowired
	TaskMapper taskdao;
	
	@Autowired
	IssueMapper issueDao;
	
	private QueueStatus status = QueueStatus.RUNNING;
	
	/**
	 * 最后加载的结果ID，主要用于避免重复加载,每次会取从数据库加载的最大值作为新值，当加载的数据不足了，而重试数据》0，则会把它置为0 
	 */
	private int lastLoadedTaskId=0;
	
	/**
	 * 重试次数，为了使失败的数据能重新被尝试发送，这里有个重试次数，当重试次数减1时，会使用 lastLoadedTaskId=0，当重试次数为0，而又没有更多数据时，会变更canload为false
	 */
	private int TASK_RETRY_COUNT=2;
	
	/**
	 * 每次加载的任务数
	 */
	@Value("${taskqueue.load.count}")
	public int LOAD_TASK_COUNT;
	
	/**
	 * 最后更新时间
	 */
	private long lastupdatetime=0;
	
	/**
	 * 没有更多的数据，如为true，则队列不会再从数据库进行查询
	 */
	private boolean nomoredata = false;
	
//	private static TaskQueueManager self = null;
	
	/**
	 * 队列信息，与发布相关
	 */
	private Issue issue;
	
	/**
	 * 队列的属性
	 */
	private DefaultRequestAttrSet attrs=new DefaultRequestAttrSet();
	

	/**
	 * 待发队列
	 */
	java.util.concurrent.CopyOnWriteArraySet<Object> queue=new CopyOnWriteArraySet<Object>();
	
	/**
	 * 已分配队列
	 */
	private static Map<Integer,ITask> hadassignList = new HashMap<Integer,ITask>();

	private String queueId;
	

	@Override
	public ITaskSet getTasks(IssueSendRequest sendReq) {
		DefaultTaskSet ts = new DefaultTaskSet();
		if(this.status!=QueueStatus.RUNNING){
			if(log.isTraceEnabled()){
				log.trace(String.format("队列[%s]状态不为运行中，不响应执行请求",this));
			}
			return ts;
		}
		
		int reqtaskcount = sendReq.getRequestTaskCount();
		int oldcount= reqtaskcount;
		if(log.isTraceEnabled()){
			log.trace(String.format("队列%s请求%s条任务",this,reqtaskcount));
		}
		ts.setIssueSender(sendReq.getIssueSender());
		if(reqtaskcount<=0){
			return ts;
		}
//		List returnlist = new ArrayList(reqtaskcount);//返回结果集
		List<Integer> taskids = new ArrayList<Integer>(reqtaskcount);//用于更新数据库
		boolean canload = true;
		do{
			
			//如果队列任务较少，且能进行加载时（未取完），加载数据
			if(queue.size()<LOAD_TASK_COUNT*0.4 && canload){
				//加载数据
				canload = (rechargeQueue());
			}
			//取数据
			for (Iterator iterator = queue.iterator(); iterator
					.hasNext();) {
				Task obj = (Task) iterator.next();
				boolean removeok = queue.remove(obj);
				if(removeok)
				{
					ts.addTask(obj);
					taskids.add(obj.getTaskId());
					//移到已分配队列
					obj.setRoujiMobileNum(sendReq.getIssueSender().getMobileNum());
					hadassignList.put(obj.getTaskId(), obj);
					//更新任务状态为分配 modify by huangjiej 改为取一条改一条
					if (log.isTraceEnabled()) {
						log.trace(String.format("更新任务%s为已分配", obj.getTaskId()));
					}
					
					taskdao.assignTask(obj.getTaskId(), sendReq.getIssueSender().getMobileNum());
					reqtaskcount--;
					if(reqtaskcount==0){
						if(log.isTraceEnabled()){
							log.trace(String.format("请求[%s]的目标已满足",sendReq));
						}
						break;
					}
					
				}
			}
			//如果取完了，但请求数未达，如果它不允许再加载了，则只能返回那么多，否则尝试加载
			if(reqtaskcount>0){
				if(log.isTraceEnabled()){
					log.trace(String.format("队列%s距离目标还有%s条",this,reqtaskcount));
				}
				if(!canload){
					if(log.isTraceEnabled()){
						log.trace(String.format("队列%s待分配任务已完，不能再获取到本队列的请求了,得到任务%s条",this,oldcount-reqtaskcount));
					}
					if (log.isDebugEnabled()) {
						log.debug(String.format("队列%s的任务均已分配，设置队列为完成",this));
					}
					//modify by huangjiejun 把队列状态置为已完成
					issueDao.finishIssue(NumberUtils.toInt(this.queueId));
					break;
				}
			}
				
				
		}while(reqtaskcount>0);
		//修改为已分配的
//		if(!taskids.isEmpty())
//		{
//			
//			taskdao.updateTasks(taskids,TaskStatusConst.TASK_STATUS_ASSIGNED,sendReq.getIssueSender().getMobileNum());
//		}
		return ts;
	};
	
	
	
	/**
	 * 获取未处理的任务，并且添加到队列中
	 * 返回值主要用来标识，防止不停地加载数据,为true时为加载成功，为false为不需要重新加载（无数据，或太频繁）
	 */
	public boolean rechargeQueue(){
		if(log.isDebugEnabled())
		{
			log.debug(String.format("队列%s从数据库加载待分配任务",this));
		}
		if(log.isTraceEnabled()){
			log.trace(String.format("队列%s数量当前任务数为%s",this,queue.size()));
		}
		if(!nomoredata){
			if(queue.size()>LOAD_TASK_COUNT){
				if(log.isTraceEnabled()){
					log.trace(String.format("无需要再加载"));
				}
				return false;
			}
			ReentrantLock lock = new ReentrantLock();
			try {
				lock.lock();
				Task querytask=new Task(); 
				querytask.setIssueId(Integer.parseInt(this.getQueueId()));
				querytask.setStatus(TaskStatusConst.TASK_STATUS_CREATE);
				//querytask.setTaskId( lastLoadedTaskId);
				//以某个task为起点来找，task是增长的，所以不会再加载已加载的数据，但是有些数据会报失败或某些原因没有分配
				//这时就要把task置0,让它从头找
				List<Task> tasks = taskdao.selectUnsendTask(querytask);
				lastupdatetime = System.currentTimeMillis();
				boolean hadreset = false;
				if(tasks.size()<LOAD_TASK_COUNT){
					//在多个线程都从列队中获取一部分任务，但任务又不满足它们的要求且队列需要加载新数据时，由于这些任务的状态并没有改变，
					//再者，加载的数据数据量小于LOAD_TASK_COUNT，又会触发lastLoadedTaskId置为0
					//数据库会重新load这部分的数据,同时,由于内部queue已删除了这些数据,就导致了查询的重复数据又能进行分配了
					//所以这里只做lastLoadedTaskId 增量算了,后面手工重置任务后,再进行处理.
//					if(TASK_RETRY_COUNT>0){
//						if(log.isTraceEnabled()){
//							log.trace(String.format("队列%s完成对队列数据的加载了，现在重置失败的数据，并可以从头再加载",this));
//						}
//						lastLoadedTaskId = 0;
//						TASK_RETRY_COUNT--;
//						taskdao.resetFailTask(Integer.parseInt(this.getQueueId()));
//						hadreset=true;
//					}
//					else{
						
						//没有更多数据了,就算还有失败都不管
						if(log.isTraceEnabled()){
							log.trace(String.format("队列%s已从数据库中加载所有数据",this));
						}
						nomoredata=true;
//					}
				}
//				if(hadreset)
//				{
//					queue.addAll(tasks);
//				}
//				else{
//					
					//因为任务id唯一，所以使用任务id去判断有没有重复加载,
					for (Iterator iterator = tasks.iterator(); iterator.hasNext();) {
						Task task = (Task) iterator.next();
						queue.add(task);
//						if(lastLoadedTaskId<task.getTaskId()){
//							lastLoadedTaskId=task.getTaskId();
//						}
					}
//				}
				//queue.addAll(tasks);
				lastupdatetime = System.currentTimeMillis();
				if(log.isTraceEnabled()){
					log.trace(String.format("队列%s加载到最大的taskid为",this,lastLoadedTaskId));
				}
				return !nomoredata;
			} finally  {
				lock.unlock();
			}
		}
		else{
			if(log.isTraceEnabled()){
				log.trace(String.format("队列%s已从数据库中加载所有数据",this));
			}
		}
		return false;
	}

	/**
	 * 添加属性
	 * @param key
	 * @param value
	 */
	public void addAttr(String key,Object value)
	{
		this.attrs.put(key, value);
	}

	@Override
	public void confirmTask(ITask task) throws QueueException {
		if(log.isDebugEnabled())
		{
			log.debug(String.format("从队列[%s]中确认以下数据",this,task));
		}
		//任务校验
		//任务是否是分配给当前肉鸡的
		hadassignList.remove(task.getTaskId());
		Task assignedtask = taskdao.selectByPrimaryKey(task.getTaskId());
		if(assignedtask==null){
			if (log.isDebugEnabled()) {
				log.debug(String.format("任务[%s]不存在", task.getTaskId()));
			}
			throw new QueueException(String.format("任务[%s]不存在", task.getTaskId()));
		}
		if(!ObjectUtils.equals(assignedtask.getRoujiMobileNum(), task.getRoujiMobileNum())){
			if (log.isDebugEnabled()) {
				log.debug(String.format("任务[%s]并非分派给手机号码[%s]", task.getTaskId(),task.getRoujiMobileNum()));
			}
			throw new QueueException(String.format("任务[%s]并非分派给手机号码[%s]", task.getTaskId(),task.getRoujiMobileNum()));
		}
		if(!assignedtask.getStatus().equals(TaskStatusConst.TASK_STATUS_ASSIGNED))
		{
			if (log.isDebugEnabled()) {
				log.debug(String.format("任务[%s]状态[%s]不正确", task.getTaskId(),assignedtask.getStatus()));
			}
			throw new QueueException(String.format("任务[%s]状态不正确", task.getTaskId()));
		}
		assignedtask.setStatus(task.getStatus());
		taskdao.updateByPrimaryKey(assignedtask);
		int finishcount = issueDao.finishWhenAllFinish(NumberUtils.toInt(this.queueId));
		if(finishcount==1){
			if (log.isDebugEnabled()) {
				log.debug(String.format("所有任务都已完成，本任务队列结束"));
			}
			this.status = QueueStatus.PAUSE;
		}
	}


	/**
	 * 是否支持该请求，这里只是根据任务的属性作为判断
	 * @param sendReq
	 * @return
	 */
	@Override
	public boolean support(IssueSendRequest sendReq) {
		RequestAttrSet reqattrs = sendReq.getRequestAttrs();
		
		return this.status!= QueueStatus.STOPED &&  attrs.supports(reqattrs);
	}
	
	



	@Override
	public String getQueueId() {
		return queueId;
	}



	/**
	 * @param queueId the queueId to set
	 */
	public void setQueueId(String queueId) {
		this.queueId = queueId;
	}



	/**
	 * @return the issue
	 */
	public Issue getIssue() {
		return issue;
	}



	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "TaskQueueManager [queueId=" + queueId + ", attrs=" + attrs
				+ ", status=" + status +",taskcount="+this.queue.size() +"]";
	}



	/* (non-Javadoc)
	 * @see com.hummingbird.common.vo.StatusChecker#statusCheck()
	 */
	@Override
	public StatusCheckResult statusCheck() {
		StatusCheckResult sr = new StatusCheckResult();
		List tasks = new ArrayList();
		for (Iterator iterator = queue.iterator(); iterator.hasNext();) {
			Task task = (Task) iterator.next();
			tasks.add(task.getTaskId());
		}
		sr.put(String.format("[发布号%s]，状态%s，内存待分配任务", queueId,status), tasks);
		
		return sr;
	}
	
	/**
	 * 设置可以从数据库重新加载
	 */
	@Override
	public void resetLoadFlag(){
		if (log.isTraceEnabled()) {
			log.trace(String.format("设置任务%s可以重新从数据库加载", this));
		}
		
		nomoredata=false;
		status = QueueStatus.RUNNING;
	}
	
}
