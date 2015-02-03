package com.hummingbird.smsmarketing.entity;

public class IssueRouji {
    private Integer idtIssueRouji;

    private Integer issueid;

    private String roujimobilenum;

    private String status;

    private Integer groupid;

    public Integer getIdtIssueRouji() {
        return idtIssueRouji;
    }

    public void setIdtIssueRouji(Integer idtIssueRouji) {
        this.idtIssueRouji = idtIssueRouji;
    }

    public Integer getIssueid() {
        return issueid;
    }

    public void setIssueid(Integer issueid) {
        this.issueid = issueid;
    }

    public String getRoujimobilenum() {
        return roujimobilenum;
    }

    public void setRoujimobilenum(String roujimobilenum) {
        this.roujimobilenum = roujimobilenum == null ? null : roujimobilenum.trim();
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status == null ? null : status.trim();
    }

    public Integer getGroupid() {
        return groupid;
    }

    public void setGroupid(Integer groupid) {
        this.groupid = groupid;
    }
}