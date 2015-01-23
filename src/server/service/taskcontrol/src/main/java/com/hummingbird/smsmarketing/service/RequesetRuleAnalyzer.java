package com.hummingbird.smsmarketing.service;

import com.hummingbird.smsmarketing.exception.RuleException;
import com.hummingbird.smsmarketing.vo.IssueSendRequestSet;
import com.hummingbird.smsmarketing.vo.IssueSender;

/**
 * 请求规则分析器，主要是根据手机号，分析出他应该发送什么请求
 * @author huangjiej_2
 * 2014年12月6日 上午10:15:23
 */
public interface RequesetRuleAnalyzer {
	
	
	/**
	 * 分析请求
	 * @param sender
	 * @return
	 */
	public IssueSendRequestSet analyseRequeset(IssueSender sender) throws RuleException;
	
	
}
