/**
 * smsmarketing
 * 版本所有 深圳市蜂鸟娱乐有限公司 2013-2014
 */
package com.hummingbird.smsmarketing.rules;

import java.util.Calendar;
import java.util.Date;
import java.util.Map;

import org.apache.commons.collections.keyvalue.DefaultKeyValue;
import org.apache.commons.lang.ObjectUtils;
import org.apache.commons.lang.math.NumberUtils;

import com.hummingbird.smsmarketing.constant.TaskTypeConst;
import com.hummingbird.smsmarketing.entity.DBRule;
import com.hummingbird.smsmarketing.exception.RuleException;
import com.hummingbird.smsmarketing.vo.RequestAttrSet;
import com.hummingbird.smsmarketing.vo.RuleRequest;

/**
 * @author huangjiej_2
 * 2014年12月17日 下午11:26:43
 * 本类主要做为 控制在休息时间不能发任务
 */
public class TimeRangeRule extends DBRule {

	@Override
	public boolean execute(RuleRequest ruleReq) throws RuleException {
		Map param = getStatParams(ruleReq);
		int beginworkhour = NumberUtils.toInt(ObjectUtils.toString(param.get("begin"))); 
		int endworkhour = NumberUtils.toInt(ObjectUtils.toString(param.get("end"))); 
		int hours = Calendar.getInstance().get(Calendar.HOUR_OF_DAY);
		if(log.isTraceEnabled())
		{
			log.trace(String.format("工作时间由%s点到%s点，当前时间为%s时", beginworkhour,endworkhour,hours));
		}
		if(hours>=beginworkhour&&hours<=endworkhour){
			return true;
		}
		else{
			if(log.isTraceEnabled())
			{
				log.trace(String.format("设置所有队列的可用请求为0"));
			}
			ruleReq.addOrMergeRequest(0,new DefaultKeyValue(RequestAttrSet.REQUEST_TASK_TYPE, TaskTypeConst.TASK_TYPE_ANY));
		}
		return false;
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.rules.TaskTypeLimitRule#getStatParams(com.hummingbird.smsmarketing.vo.RuleRequest)
	 */
	@Override
	public Map getStatParams(RuleRequest ruleReq) {
		return super.getStatParams(ruleReq);
	}



}
