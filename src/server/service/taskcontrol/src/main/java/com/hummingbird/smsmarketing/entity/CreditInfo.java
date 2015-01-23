package com.hummingbird.smsmarketing.entity;

import java.util.Date;

public class CreditInfo {
    private Integer idtCreditInfo;

    private String mobilenum;

    private String appid;

    private Integer creditlimit;

    private Integer usedcredit;

    private Date createtime;

    private Date updatetime;

    public Integer getIdtCreditInfo() {
        return idtCreditInfo;
    }

    public void setIdtCreditInfo(Integer idtCreditInfo) {
        this.idtCreditInfo = idtCreditInfo;
    }

    public String getMobilenum() {
        return mobilenum;
    }

    public void setMobilenum(String mobilenum) {
        this.mobilenum = mobilenum == null ? null : mobilenum.trim();
    }

    public String getAppid() {
        return appid;
    }

    public void setAppid(String appid) {
        this.appid = appid == null ? null : appid.trim();
    }

    public Integer getCreditlimit() {
        return creditlimit;
    }

    public void setCreditlimit(Integer creditlimit) {
        this.creditlimit = creditlimit;
    }

    public Integer getUsedcredit() {
        return usedcredit;
    }

    public void setUsedcredit(Integer usedcredit) {
        this.usedcredit = usedcredit;
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