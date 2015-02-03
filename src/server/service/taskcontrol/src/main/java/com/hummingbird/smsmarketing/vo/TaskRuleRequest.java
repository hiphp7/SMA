/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import org.apache.commons.collections.keyvalue.DefaultKeyValue;
import org.apache.commons.lang.ObjectUtils;
import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;

/**
 * 任务规则请求
 * @author huangjiej_2
 * 2014年12月8日 下午9:53:03
 */
public class TaskRuleRequest implements RuleRequest {

	Log log = LogFactory.getLog(this.getClass());
	
	private IssueSender sender;
	
	private DefaultIssueSendRequestSet reqset ;

	public TaskRuleRequest(IssueSender sender) {
		this.sender = sender;
		reqset = new DefaultIssueSendRequestSet(sender);
	}

	@Override
	public IssueSendRequestSet getIssueSendRequestSet() {
		return reqset;
	}

	@Override
	public void addOrMergeRequest(int count,
			DefaultKeyValue ... defaultKeyValueArr) {
		
		DefaultRequestAttrSet attrset = new DefaultRequestAttrSet();
		for (int i = 0; i < defaultKeyValueArr.length; i++) {
			DefaultKeyValue kv = defaultKeyValueArr[i];
			attrset.put(ObjectUtils.toString(kv.getKey()), kv.getValue());
		}
		if (log.isDebugEnabled()) {
			log.debug(String.format("设置符合条件的任务[%s]的任务数限制为%s",attrset, count));
		}
		IssueSendRequest request = reqset.findRequest(attrset);
		if(request==null){
			if(log.isTraceEnabled()){
				log.trace(String.format("请求任务【属性=%s】尚未建立，现在创建新的请求任务,请求数为%s",attrset,count));
			}
			DefaultIssueSendRequest request1 = new DefaultIssueSendRequest();
			request1.setIssueSender(sender);
			request1.setRequestAttrSet(attrset);
			request1.setRequestTaskCount(count);
			request = request1;
			reqset.addIssueSendRequest(request1);
		}
		else{
			if(log.isTraceEnabled()){
				log.trace(String.format("请求任务【属性=%s】已建立，现在取数据小的值为任务请求数",attrset));
			}
			if(request.getRequestTaskCount()>count){
				((DefaultIssueSendRequest)request).setRequestTaskCount(count);
			}
			if(log.isTraceEnabled()){
				log.trace(String.format("请求任务【属性=%s】的合并后请求数为%s",attrset,count));
			}
			
		}
		
		
	}
	
	

}
