package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.IssueRouji;

public interface IssueRoujiMapper {
    int deleteByPrimaryKey(Integer idtIssueRouji);

    int insert(IssueRouji record);

    int insertSelective(IssueRouji record);

    IssueRouji selectByPrimaryKey(Integer idtIssueRouji);

    int updateByPrimaryKeySelective(IssueRouji record);

    int updateByPrimaryKey(IssueRouji record);
}