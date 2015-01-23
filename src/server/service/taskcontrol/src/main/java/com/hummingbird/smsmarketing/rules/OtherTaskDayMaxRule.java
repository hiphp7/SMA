/**
 * 
 */
package com.hummingbird.smsmarketing.rules;

import java.util.HashMap;
import java.util.Map;

import org.apache.commons.collections.keyvalue.DefaultKeyValue;

import com.hummingbird.common.util.DateUtil;
import com.hummingbird.common.util.SpringBeanUtil;
import com.hummingbird.smsmarketing.constant.TaskStatusConst;
import com.hummingbird.smsmarketing.constant.TaskTypeConst;
import com.hummingbird.smsmarketing.exception.RuleException;
import com.hummingbird.smsmarketing.mapper.TaskMapper;
import com.hummingbird.smsmarketing.vo.RequestAttrSet;
import com.hummingbird.smsmarketing.vo.RuleRequest;

/**
 * 非广告类的规则
 * @author huangjiej_2
 * 2014年12月8日 下午10:03:07
 */
public class OtherTaskDayMaxRule extends AbstractRule{

	
	public OtherTaskDayMaxRule() {
		super();
//		this.enabled=false;
	}

	public int maxcount=25;
	
	@Override
	public String getRuleId() {
		return "OtherTaskDayMaxRule";
	}

	@Override
	public String getRuleName() {
		return "非广告类短信1天不能发超过"+maxcount+"条";
	}

	@Override
	public boolean execute(RuleRequest ruleReq) throws RuleException {
		//一个天内不能超过50条，需要判断是否最近1天有没有发送过同类广告，如果有则按最大值减去已发的，取大于0的值
		
		int onehoursendcount = getOneDaySendCount(ruleReq);
		if(log.isTraceEnabled())
		{
			log.trace(String.format("当前手机号码%s1天内发送类型%s的短信%s条", ruleReq.getIssueSendRequestSet().getIssueSender().getMobileNum(),TaskTypeConst.TASK_TYPE_NOT_ADV,onehoursendcount));
		}
		if(maxcount-onehoursendcount>0){
			if(log.isTraceEnabled())
			{
				log.trace(String.format("当前手机号码%s对类型%s的短信还可发送短信%s条", ruleReq.getIssueSendRequestSet().getIssueSender().getMobileNum(),TaskTypeConst.TASK_TYPE_NOT_ADV,maxcount-onehoursendcount));
			}
			ruleReq.addOrMergeRequest(maxcount-onehoursendcount,new DefaultKeyValue(RequestAttrSet.REQUEST_TASK_TYPE, TaskTypeConst.TASK_TYPE_NOT_ADV));
			
		}
		return true;
	}

	/**
	 * 获取一个小时内发送的记录数
	 * @param ruleReq
	 * @return
	 */
	private int getOneDaySendCount(RuleRequest ruleReq) {
		TaskMapper taskMapper = SpringBeanUtil.getInstance().getBean(TaskMapper.class);
		Map map=new HashMap();
		map.put("mobileNum", ruleReq.getIssueSendRequestSet().getIssueSender().getMobileNum());
		map.put("nottype", TaskTypeConst.TASK_TYPE_ADVERTISEMENT);
		map.put("statusArr", new String[]{TaskStatusConst.TASK_STATUS_ASSIGNED,TaskStatusConst.TASK_STATUS_SUCCESSED});
		map.put("oneday", DateUtil.formatToday("yyyyMMdd"));
		int statCount = taskMapper.statCount(map);
		return statCount;
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.rules.AbstractRule#isEnabled()
	 */
	@Override
	public boolean isRuleEnabled() {
		// TODO Auto-generated method stub
		return false;
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.rules.AbstractRule#getPriority()
	 */
	@Override
	public Integer getPriority() {
		// TODO Auto-generated method stub
		return null;
	}

}
