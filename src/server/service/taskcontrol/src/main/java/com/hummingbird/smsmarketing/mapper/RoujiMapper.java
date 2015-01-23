package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.Rouji;

public interface RoujiMapper {
    int deleteByPrimaryKey(String mobilenum);

    int insert(Rouji record);

    int insertSelective(Rouji record);

    Rouji selectByPrimaryKey(String mobilenum);

    int updateByPrimaryKeySelective(Rouji record);

    int updateByPrimaryKey(Rouji record);
}