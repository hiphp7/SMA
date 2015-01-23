package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.Agent;

public interface AgentMapper {
    int deleteByPrimaryKey(String agentid);

    int insert(Agent record);

    int insertSelective(Agent record);

    Agent selectByPrimaryKey(String agentid);

    int updateByPrimaryKeySelective(Agent record);

    int updateByPrimaryKey(Agent record);
}