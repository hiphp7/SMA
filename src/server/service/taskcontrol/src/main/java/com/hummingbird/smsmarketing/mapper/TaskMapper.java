package com.hummingbird.smsmarketing.mapper;

import java.util.List;
import java.util.Map;

import org.apache.ibatis.annotations.Param;

import com.hummingbird.smsmarketing.entity.Task;
import com.hummingbird.smsmarketing.vo.ITask;

public interface TaskMapper {
    int deleteByPrimaryKey(Integer taskid);

    int insert(Task record);

    int insertSelective(Task record);

    Task selectByPrimaryKey(Integer taskid);

    int updateByPrimaryKeySelective(Task record);

    int updateByPrimaryKey(Task record);

	/**
	 * 获取未处理的任务
	 * @param querytask 内容包括发布号，状态，如果taskid有值，则以该值为起点进行查找
	 * @return
	 */
	List<Task> selectUnsendTask(ITask querytask);

	/**
	 * 更新任务状态
	 * @param taskId
	 * @param status
	 */
	void updateTaskStatus(Integer taskId, @Param("status") String status);
	/**
	 * 重设已分配但长时间没有返回状态的任务，让他们可以再尝试发送，小心这个动作应该其它的分派的任务都已返回结果（所有肉鸡都没有请求它的任务）的情况下使用，不然会有重复分派的问题
	 * @param taskId
	 * @param status
	 */
	void resetFailTask(Integer issueId);
	
	
	/**
	 * 统计发送 的任务数
	 * @param map issueId-发布号，mobileNum-手机号,status-状态,type-类别,onehour-发送时间=指定值，值格式为2014120112 ，oneday-发送时间为指定值，格式20141201 ,statusArr-状态数组
	 * @return
	 */
	int  statCount(Map map);

	/**
	 * 批量更新任务
	 * @param taskids
	 * @param status
	 * @param string 
	 */
	void updateTasks(@Param("taskIds") List<Integer> taskids,@Param("status") String status,@Param("mobileNum") String mobileNum);
	
	/**
	 * 记录分配任务
	 * @param taskid
	 * @param mobileNum
	 */
	void assignTask(@Param("taskId") Integer taskid,@Param("roujiMobileNum") String mobileNum);
	
}