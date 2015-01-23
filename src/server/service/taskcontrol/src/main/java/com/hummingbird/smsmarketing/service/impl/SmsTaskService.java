/**
 * 
 */
package com.hummingbird.smsmarketing.service.impl;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

import org.apache.commons.lang.ObjectUtils;
import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.hummingbird.common.exception.DataInvalidException;
import com.hummingbird.common.util.PropertiesUtil;
import com.hummingbird.common.vo.ResultModel;
import com.hummingbird.commonbiz.util.ShortUrlGenerator;
import com.hummingbird.smsmarketing.entity.Address;
import com.hummingbird.smsmarketing.entity.Content;
import com.hummingbird.smsmarketing.entity.Rouji;
import com.hummingbird.smsmarketing.entity.Task;
import com.hummingbird.smsmarketing.exception.RuleException;
import com.hummingbird.smsmarketing.exception.TaskDispatchException;
import com.hummingbird.smsmarketing.mapper.AddressMapper;
import com.hummingbird.smsmarketing.service.IssueSendDispatcher;
import com.hummingbird.smsmarketing.service.RequesetRuleAnalyzer;
import com.hummingbird.smsmarketing.vo.ITask;
import com.hummingbird.smsmarketing.vo.IssueSendRequestSet;
import com.hummingbird.smsmarketing.vo.TaskFeeBackVO;

/**
 * 短信任务service，主要是管理短信和任务的关系
 * @author huangjiej_2
 * 2014年12月9日 上午7:40:10
 */
@Service
public class SmsTaskService {

	Log log = LogFactory.getLog(this.getClass());
	@Autowired
	IssueSendDispatcher dispatcher ;
	/**
	 * 短链
	 */
	@Autowired
	AddressMapper addrDao ;
	
	public List<ITask> getTasks(Rouji mobile) throws RuleException, TaskDispatchException{
		//分析了肉鸡获取什么请求，并去获取请求
		if(log.isDebugEnabled())
		{
			log.debug(String.format("分析肉鸡[%s]的请求",mobile.getMobileNum()));
		}
		RequesetRuleAnalyzer analyzer = new RequestRuleAnalyzerImpl();
		try {
			IssueSendRequestSet analyseRequeset = analyzer.analyseRequeset(mobile);
			if(analyseRequeset!=null){
				//获取相关任务
				if(log.isDebugEnabled())
				{
					log.debug(String.format("根据肉鸡[%s]的请求去获取相关任务",mobile.getMobileNum()));
				}
				dispatcher.getTasks(analyseRequeset);
				List<ITask> tasks2 = analyseRequeset.getTasks();
				if(tasks2!=null){
					if(log.isDebugEnabled())
					{
						log.debug(String.format("为任务生成短链"));
					}
					PropertiesUtil pu = new PropertiesUtil();
					String linkbase = pu.getProperty("shortlink.linkbase");
					for (Iterator iterator = tasks2.iterator(); iterator
							.hasNext();) {
						ITask task = (ITask) iterator.next();
						genShortLink(task,linkbase);
					}
				}
				if(log.isDebugEnabled())
				{
					log.debug(String.format("根据肉鸡[%s]的请求去获取相关任务,结果为",mobile,tasks2==null||tasks2.isEmpty()?"无任务":("获得"+tasks2.size()+"条任务")));
				}
				return tasks2;
			}
		} catch (RuleException e) {
//			log.error("通过规则分析请求失败",e);
			throw e;
		} catch (TaskDispatchException e) {
			throw e;
		}
		
		return null;
		
	}

	/**
	 * 创建短链
	 * @param task
	 * @param linkbase 短链的前面部分
	 * @param append2text 是否追加到任务中
	 */
	private void genShortLink(ITask task, String linkbase) {
		
		if (log.isTraceEnabled()) {
			log.trace(String.format("为任务【%s】生成短链", task));
		}
		String roujimobile=task.getRoujiMobileNum();
		String mobilenum= task.getSendsmobileNum();
		String issueId = ObjectUtils.toString(task.getIssueId());
		String taskId = ObjectUtils.toString(task.getTaskId());
		String painttext = roujimobile+mobilenum+taskId;
		if (log.isTraceEnabled()) {
			log.trace(String.format("明文数据%s", painttext));
		}
		try {
			String genShortUrl = ShortUrlGenerator.genShortUrl(painttext);
			if (log.isTraceEnabled()) {
				log.trace(String.format("短链suffer[%s]", genShortUrl));
			}
			Address addshorturl = addrDao.selectByPrimaryKey(genShortUrl);
			if(addshorturl==null){
				Address addr=new Address(task,genShortUrl);
				addrDao.insert(addr);
				
			}
			//为文本设置短链
			appendLink(task,genShortUrl,linkbase);
				
		} catch (DataInvalidException e) {
			log.error(String.format("生成短链出错"),e);
		}
		
	}

	/**
	 * 追加文本
	 * @param taskimpl
	 * @param genShortUrl
	 */
	private void appendLink(ITask taskimpl, String genShortUrl,String linkbase) {
		Content content = taskimpl.getContent();
		if (log.isTraceEnabled()) {
			log.trace(String.format("内容是否显示短链%s",content.getShorturl()));
		}
		if(content.isShowUrl()){
			
			if (log.isTraceEnabled()) {
				log.trace(String.format("把短链附加到内容后面"));
			}
			
			content.setSmscontent(content.getSmscontent()+" "+getLink(linkbase,genShortUrl));
		}
		else{
			if (log.isTraceEnabled()) {
				log.trace(String.format("不把短链附加到内容后面"));
			}
		}
	}

	/**
	 * 获取访问地址
	 * @param genShortUrl
	 * @return
	 */
	private String getLink(String linkbase,String genShortUrl) {
		
		if(!linkbase.endsWith("/"))
		{
			linkbase=linkbase+"/";
		}
		return linkbase+genShortUrl;
	}

	/**
	 * 上报短信任务执行信息
	 * @param mobileNum 
	 * @param taskFeeBackVO
	 * @return 返回上报失败的任务
	 */
	public List<ResultModel> updateTaskStatus(String mobileNum, TaskFeeBackVO taskFeeBackVO)  {
		List<ResultModel> errortasks = new ArrayList<ResultModel>();
		List<Task> tasks = taskFeeBackVO.getTask();
		ResultModel rm = new ResultModel();
		
		for (Iterator iterator = tasks.iterator(); iterator.hasNext();) {
			Task task = (Task) iterator.next();
			//把肉鸡手机加入到任务中，后面进行校验
			task.setRoujiMobileNum(mobileNum);
			try {
				dispatcher.comfirmTask(task);
				rm.setErrmsg("上报短信任务"+task.getTaskId()+"执行信息完成");
			} catch (TaskDispatchException e) {
				log.error(String.format("任务%s上报出错",e));
				rm.mergeException(e);
				rm.setErrcode(1040);
				//rm.setErrmsg("上报短信任务"+task.getTaskId()+"执行信息失败，其它原因");
				
			}
			finally{
				errortasks.add(rm);
			}
		}
		return errortasks;
	}
	
}
