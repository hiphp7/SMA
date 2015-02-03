package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.Group;

public interface GroupMapper {
    int deleteByPrimaryKey(Integer groupid);

    int insert(Group record);

    int insertSelective(Group record);

    Group selectByPrimaryKey(Integer groupid);

    int updateByPrimaryKeySelective(Group record);

    int updateByPrimaryKey(Group record);
}