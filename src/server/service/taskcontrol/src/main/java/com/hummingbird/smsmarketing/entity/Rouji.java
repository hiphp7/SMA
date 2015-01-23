package com.hummingbird.smsmarketing.entity;

import java.util.Date;

import com.hummingbird.common.util.DateUtil;
import com.hummingbird.smsmarketing.vo.IssueSender;

public class Rouji implements IssueSender{
    private String mobilenum;

    private String username;

    private Date createtime;

    private Integer groupid;

    public String getMobilenum() {
        return mobilenum;
    }

    public void setMobilenum(String mobilenum) {
        this.mobilenum = mobilenum == null ? null : mobilenum.trim();
    }

    public String getUsername() {
        return username;
    }

    public void setUsername(String username) {
        this.username = username == null ? null : username.trim();
    }

    public Date getCreatetime() {
        return createtime;
    }

    public void setCreatetime(Date createtime) {
        this.createtime = createtime;
    }

    public Integer getGroupid() {
        return groupid;
    }

    public void setGroupid(Integer groupid) {
        this.groupid = groupid;
    }

	@Override
	public String getMobileNum() {
		return mobilenum;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "Rouji [mobilenum=" + mobilenum + ", username=" + username
				+ ", createtime=" + DateUtil.formatCommonDateorNull(createtime) + ", groupid=" + groupid + "]";
	}
}