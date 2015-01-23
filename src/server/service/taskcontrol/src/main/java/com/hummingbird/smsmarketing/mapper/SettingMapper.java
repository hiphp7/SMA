package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.Setting;

public interface SettingMapper {
    int deleteByPrimaryKey(String settingname);

    int insert(Setting record);

    int insertSelective(Setting record);

    Setting selectByPrimaryKey(String settingname);

    int updateByPrimaryKeySelective(Setting record);

    int updateByPrimaryKey(Setting record);
}