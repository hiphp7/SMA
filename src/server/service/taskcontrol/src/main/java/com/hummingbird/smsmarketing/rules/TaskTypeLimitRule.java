/**
 * smsmarketing
 * 版本所有 深圳市蜂鸟娱乐有限公司 2013-2014
 */
package com.hummingbird.smsmarketing.rules;

import java.util.Map;

import org.apache.commons.collections.keyvalue.DefaultKeyValue;

import com.hummingbird.common.util.SpringBeanUtil;
import com.hummingbird.smsmarketing.exception.RuleException;
import com.hummingbird.smsmarketing.mapper.TaskMapper;
import com.hummingbird.smsmarketing.vo.RequestAttrSet;
import com.hummingbird.smsmarketing.vo.RuleRequest;

/**
 * @author huangjiej_2
 * 2014年12月17日 下午12:39:57
 * 本类主要做为 按任务类型对任务进行限制设置
 */
public abstract class TaskTypeLimitRule extends AbstractRule {

	
	
	
	@Override
	public boolean execute(RuleRequest ruleReq) throws RuleException {
		//一个小时内不能超过20条，需要判断是否最近1小时有没有发送过同类广告，如果有则按最大值减去已发的，取大于0的值
		
		int onehoursendcount = statcount(ruleReq);
		if(log.isTraceEnabled())
		{
			log.trace(String.format("当前手机号码%s在条件内发送类型%s的短信%s条", ruleReq.getIssueSendRequestSet().getIssueSender().getMobileNum(),getTargetType(),onehoursendcount));
		}
		if(getMaxRequest()-onehoursendcount>0){
			if(log.isTraceEnabled())
			{
				log.trace(String.format("当前手机号码%s在条件内对类型%s的短信还可发送短信%s条", ruleReq.getIssueSendRequestSet().getIssueSender().getMobileNum(),getTargetType(),getMaxRequest()-onehoursendcount));
			}

			ruleReq.addOrMergeRequest(getMaxRequest()-onehoursendcount,new DefaultKeyValue(RequestAttrSet.REQUEST_TASK_TYPE, getTargetType()));
			
		}
		else{
			if(log.isTraceEnabled())
			{
				log.trace(String.format("当前手机号码%s在条件内对类型%s的短信已不可再发送了",ruleReq.getIssueSendRequestSet().getIssueSender().getMobileNum(),getTargetType()));
			}
			
		}
		return true;
	}
	
	/**
	 * 获取一个小时内发送的记录数
	 * @param ruleReq
	 * @return
	 */
	protected int statcount(RuleRequest ruleReq) {
		TaskMapper taskMapper = SpringBeanUtil.getInstance().getBean(TaskMapper.class);
//		Map map=new HashMap();
//		map.put("mobileNum", ruleReq.getIssueSendRequestSet().getIssueSender().getMobileNum());
//		map.put("nottype", TaskTypeConst.TASK_TYPE_ADVERTISEMENT);
//		map.put("statusArr", new String[]{TaskStatusConst.TASK_STATUS_ASSIGNED,TaskStatusConst.TASK_STATUS_SUCCESSED});
//		map.put("onehour", DateUtil.formatToday("yyyyMMddHH"));
		int statCount = taskMapper.statCount(getStatParams(ruleReq));
		return statCount;
	}
	
	/**
	 * 获取要控制的请求目标
	 * @return
	 */
	public abstract String getTargetType();
	
	 /**
	  * 最大请求数
	 * @return
	 */
	public abstract Integer getMaxRequest() ;
	
	/**
	 * 获得统计参数
	 * @param ruleReq 
	 * @return
	 */
	public abstract Map getStatParams(RuleRequest ruleReq);
	
	
}
