package com.hummingbird.smsmarketing.mapper;

import java.util.List;

import com.hummingbird.smsmarketing.entity.DBRule;

public interface DBRuleMapper {
    int deleteByPrimaryKey(String code);

    int insert(DBRule record);

    int insertSelective(DBRule record);

    DBRule selectByPrimaryKey(String code);
    /**
     * 加载所有的规则
     * @return
     */
    List<DBRule> selectAllRules();

    int updateByPrimaryKeySelective(DBRule record);

    int updateByPrimaryKey(DBRule record);
}