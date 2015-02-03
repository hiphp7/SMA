package com.hummingbird.smsmarketing.entity;

import java.util.Date;

public class Agent {
    private String agentid;

    private String agentname;

    private String shortname;

    private String mobilenum;

    private String password;

    private String qq;

    private Date createtime;

    private Date updatetime;

    private String superiorid;

    private String contact;

    private String status;

    public String getAgentid() {
        return agentid;
    }

    public void setAgentid(String agentid) {
        this.agentid = agentid == null ? null : agentid.trim();
    }

    public String getAgentname() {
        return agentname;
    }

    public void setAgentname(String agentname) {
        this.agentname = agentname == null ? null : agentname.trim();
    }

    public String getShortname() {
        return shortname;
    }

    public void setShortname(String shortname) {
        this.shortname = shortname == null ? null : shortname.trim();
    }

    public String getMobilenum() {
        return mobilenum;
    }

    public void setMobilenum(String mobilenum) {
        this.mobilenum = mobilenum == null ? null : mobilenum.trim();
    }

    public String getPassword() {
        return password;
    }

    public void setPassword(String password) {
        this.password = password == null ? null : password.trim();
    }

    public String getQq() {
        return qq;
    }

    public void setQq(String qq) {
        this.qq = qq == null ? null : qq.trim();
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

    public String getSuperiorid() {
        return superiorid;
    }

    public void setSuperiorid(String superiorid) {
        this.superiorid = superiorid == null ? null : superiorid.trim();
    }

    public String getContact() {
        return contact;
    }

    public void setContact(String contact) {
        this.contact = contact == null ? null : contact.trim();
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status == null ? null : status.trim();
    }
}