/**
 * 
 */
package com.hummingbird.smsmarketing.constant;

/**
 * 任务类型常量
 * @author huangjiej_2
 * 2014年12月8日 下午11:17:12
 */
public class TaskStatusConst {

	/**
	 * 等待发送，刚创建时就是这个状态
	 */
	public static final String TASK_STATUS_CREATE="CRT";
	/**
	 * 已指派，有app来发送这条信息
	 */
	public static final String TASK_STATUS_ASSIGNED="ASG";
	/**
	 * 已上报且发送成功
	 */
	public static final String TASK_STATUS_SUCCESSED="RPS";
	/**
	 * 已上报且发送失败，这种状态不需要重置
	 */
	public static final String TASK_STATUS_FAILED="RPF";
	/**
	 * 发送撤消
	 */
	public static final String TASK_STATUS_CANCLE="RVK";
	
	
}
