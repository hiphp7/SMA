/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import java.util.Map;

/**
 * 请求属性
 * @author huangjiej_2
 * 2014年12月8日 下午1:50:15
 */
public interface RequestAttrSet {
	
	
	/**
	 * 请求任务数
	 */
	public static  final String REQUEST_TASK_TYPE= "REQUEST_TASK_TYPE";
	
	
	/**
	 * 获取属性
	 * @param key
	 * @return
	 */
	public Object get(String key);
	
	/**
	 * 获取属性
	 * @return
	 */
	public Map<String,Object> getAllAttr();
	
	
}
