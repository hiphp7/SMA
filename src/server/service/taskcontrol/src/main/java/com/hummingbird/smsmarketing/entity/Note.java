package com.hummingbird.smsmarketing.entity;

import java.util.Date;

public class Note {
    private Integer idtNote;

    private String roujimobile;

    private Integer issueid;

    private String customermobile;

    private String note;

    private Date createtime;

    private Date updatetime;

    public Integer getIdtNote() {
        return idtNote;
    }

    public void setIdtNote(Integer idtNote) {
        this.idtNote = idtNote;
    }

    public String getRoujimobile() {
        return roujimobile;
    }

    public void setRoujimobile(String roujimobile) {
        this.roujimobile = roujimobile == null ? null : roujimobile.trim();
    }

    public Integer getIssueid() {
        return issueid;
    }

    public void setIssueid(Integer issueid) {
        this.issueid = issueid;
    }

    public String getCustomermobile() {
        return customermobile;
    }

    public void setCustomermobile(String customermobile) {
        this.customermobile = customermobile == null ? null : customermobile.trim();
    }

    public String getNote() {
        return note;
    }

    public void setNote(String note) {
        this.note = note == null ? null : note.trim();
    }

    public Date getCreatetime() {
        return createtime;
    }

    public void setCreatetime(Date createtime) {
        this.createtime = createtime;
    }

    public Date getUpdatetime() {
        return updatetime;
    }

    public void setUpdatetime(Date updatetime) {
        this.updatetime = updatetime;
    }
}