/**
 * 
 */
package com.hummingbird.smsmarketing.service.impl;

import java.util.ArrayList;
import java.util.Iterator;
import java.util.List;

import org.apache.commons.beanutils.BeanUtils;
import org.apache.commons.lang.StringUtils;
import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;

import com.hummingbird.common.util.SpringBeanUtil;
import com.hummingbird.smsmarketing.entity.DBRule;
import com.hummingbird.smsmarketing.exception.RuleException;
import com.hummingbird.smsmarketing.mapper.DBRuleMapper;
import com.hummingbird.smsmarketing.rules.Rule;
import com.hummingbird.smsmarketing.service.RequesetRuleAnalyzer;
import com.hummingbird.smsmarketing.vo.IssueSendRequestSet;
import com.hummingbird.smsmarketing.vo.IssueSender;
import com.hummingbird.smsmarketing.vo.RuleRequest;
import com.hummingbird.smsmarketing.vo.TaskRuleRequest;

/**
 * @author huangjiej_2
 * 2014年12月8日 下午7:55:48
 */
public class RequestRuleAnalyzerImpl implements RequesetRuleAnalyzer {

	Log log = LogFactory.getLog(this.getClass());
	
	@Override
	public IssueSendRequestSet analyseRequeset(IssueSender sender)
			throws RuleException {
		if(log.isDebugEnabled())
		{
			log.debug(String.format("分析来自app[号码%s]的请求",sender.getMobileNum()));
		}
		//加载规则
		List<? extends Rule> rules = loadRules();
		//按规则进行计算,以获得请求
		RuleRequest rr = new TaskRuleRequest(sender);
		for (Iterator iterator = rules.iterator(); iterator.hasNext();) {
			Rule rule = (Rule) iterator.next();
			if(!rule.isRuleEnabled()){
				if(log.isTraceEnabled()){
					log.trace(String.format("规则：%s不生效",rule.getRuleName()));
				}
			}
			if(log.isTraceEnabled()){
				log.trace(String.format("对app[号码%s]进行以下规则的分析：%s",sender.getMobileNum(),rule));
			}
			boolean gonext = rule.execute(rr);
			if(!gonext){
				break;
			}
		}
		
		return rr.getIssueSendRequestSet();
	}

	/**
	 * 加载规则
	 * @return
	 */
	private List<? extends Rule> loadRules() {
//		List<Rule> list = new ArrayList<Rule>(); 
//		list.add(new AdvertisementRule());
//		list.add(new OtherTaskHourMaxRule());
//		list.add(new OtherTaskDayMaxRule());
		
		DBRuleMapper ruledao = SpringBeanUtil.getInstance().getBean(DBRuleMapper.class);
		List<DBRule> selectAllRules = ruledao.selectAllRules();
		List<Rule> executablerules = new ArrayList<Rule>();
		for (Iterator iterator = selectAllRules.iterator(); iterator.hasNext();) {
			DBRule dbRule = (DBRule) iterator.next();
			if(log.isTraceEnabled()){
				log.trace(String.format("加载到数据库规则%s",dbRule));
			}
			if(!dbRule.isRuleEnabled()){
				if(log.isTraceEnabled()){
					log.trace(String.format("规则：%s不生效",dbRule.getName()));
				}
				continue;
			}
			if(StringUtils.isNotBlank(dbRule.getImplClass())){
				if(log.isTraceEnabled()){
					log.trace(String.format("规则：%s指定使用规则实现%s",dbRule.getName(),dbRule.getImplClass()));
				}
				try {
					Rule rule = (Rule) Class.forName(dbRule.getImplClass()).newInstance();
					BeanUtils.copyProperties(rule, dbRule);
					executablerules.add(rule);
				} catch (Exception e) {
					log.error(String.format("创建规则实现%s出错",dbRule.getImplClass()),e);
				}
			}
			else
			{
				executablerules.add(dbRule);
			}
		}
		return executablerules;
	}

}
