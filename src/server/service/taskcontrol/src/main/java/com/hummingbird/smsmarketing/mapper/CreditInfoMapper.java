package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.CreditInfo;

public interface CreditInfoMapper {
    int deleteByPrimaryKey(Integer idtCreditInfo);

    int insert(CreditInfo record);

    int insertSelective(CreditInfo record);

    CreditInfo selectByPrimaryKey(Integer idtCreditInfo);

    int updateByPrimaryKeySelective(CreditInfo record);

    int updateByPrimaryKey(CreditInfo record);
}