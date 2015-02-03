/**
 * 
 */
package com.hummingbird.smsmarketing.rules;

import com.hummingbird.smsmarketing.exception.RuleException;
import com.hummingbird.smsmarketing.vo.RuleRequest;

/**
 * 规则接口，
 * @author huangjiej_2
 * 2014年12月6日 上午10:24:48
 */
public interface Rule {

	/**
	 * 规则标识
	 * @return
	 */
	public String getRuleId();
	
	/**
	 * 规则名称
	 * @return
	 */
	public String getRuleName();
	
	/**
	 * 执行规则
	 * @param ruleReq 请求
	 * @return 是否中断，返回false时不往下执行
	 * @throws RuleException 规则执行异常
	 */
	public boolean execute(RuleRequest ruleReq) throws RuleException;

	/**
	 * @return
	 */
	public boolean isRuleEnabled();
	
	
}
