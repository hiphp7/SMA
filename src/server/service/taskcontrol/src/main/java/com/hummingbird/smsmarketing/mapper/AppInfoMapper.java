package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.AppInfo;

public interface AppInfoMapper {
    int deleteByPrimaryKey(String appId);

    int insert(AppInfo record);


    AppInfo selectByPrimaryKey(String appId);


    int updateByPrimaryKey(AppInfo record);

}