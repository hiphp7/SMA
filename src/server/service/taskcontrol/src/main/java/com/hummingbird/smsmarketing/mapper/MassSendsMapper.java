package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.MassSends;

public interface MassSendsMapper {
    int deleteByPrimaryKey(Integer idtMassSends);

    int insert(MassSends record);

    int insertSelective(MassSends record);

    MassSends selectByPrimaryKey(Integer idtMassSends);

    int updateByPrimaryKeySelective(MassSends record);

    int updateByPrimaryKey(MassSends record);
}