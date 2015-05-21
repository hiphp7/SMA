package com.hummingbird.smsmarketing.mapper;

import java.util.List;

import com.hummingbird.smsmarketing.entity.Issue;

public interface IssueMapper {
    int deleteByPrimaryKey(Integer issueid);

    int insert(Issue record);

    int insertSelective(Issue record);

    Issue selectByPrimaryKey(Integer issueid);

    int updateByPrimaryKeySelective(Issue record);

    int updateByPrimaryKey(Issue record);
    
    /**
     * 获取所有的发布状态的数据
     * @return
     */
    List<Issue> getEffectiveIssue();

	/**
	 * 查询指定手机可以使用的发布数据，使用的意思是发布与之相关的任务
	 * @param mobileNum
	 * @return
	 */
	List<Issue> getEffectiveIssueByMobile(String mobileNum);

	/**
	 * 更新发布为完成，当所有任务都完成
	 * @param queueId 
	 */
	int finishWhenAllFinish(Integer issueId);
	/**
	 * 更新发布为完成，不管任务是否完成
	 * @param queueId 
	 */
	int finishIssue(Integer issueId);

	/**
	 * 获取过期的发布
	 * @return
	 */
	List<Issue> selectOvertimeIssue();
}