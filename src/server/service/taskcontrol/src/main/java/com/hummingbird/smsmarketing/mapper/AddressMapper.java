package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.Address;

public interface AddressMapper {
    int deleteByPrimaryKey(String suffix);

    int insert(Address record);

    int insertSelective(Address record);

    Address selectByPrimaryKey(String suffix);

    int updateByPrimaryKeySelective(Address record);

    int updateByPrimaryKey(Address record);
}