package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.Content;

public interface ContentMapper {
    int deleteByPrimaryKey(Integer contentid);

    int insert(Content record);

    int insertSelective(Content record);

    Content selectByPrimaryKey(Integer contentid);

    int updateByPrimaryKeySelective(Content record);

    int updateByPrimaryKey(Content record);

	/**
	 * 根据发布号查询内容
	 * @param issueId
	 * @return
	 */
	Content selectByIssueId(String issueId);
}