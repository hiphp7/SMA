package com.hummingbird.smsmarketing.mapper;

import com.hummingbird.smsmarketing.entity.Note;

public interface NoteMapper {
    int deleteByPrimaryKey(Integer idtNote);

    int insert(Note record);

    int insertSelective(Note record);

    Note selectByPrimaryKey(Integer idtNote);

    int updateByPrimaryKeySelective(Note record);

    int updateByPrimaryKey(Note record);
}