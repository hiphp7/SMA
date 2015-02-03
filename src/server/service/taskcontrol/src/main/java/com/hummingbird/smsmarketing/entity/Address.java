package com.hummingbird.smsmarketing.entity;

import com.hummingbird.smsmarketing.vo.ITask;

public class Address {
    private String suffix;

    private String roujimobile;

    private String sendsmobilenum;

    private Integer taskid;

    private Integer issueid;

    /**
	 * 构造函数
	 */
	public Address(ITask task, String genShortUrl) {
		suffix=genShortUrl;
		roujimobile=task.getRoujiMobileNum();
		sendsmobilenum=task.getSendsmobileNum();
		taskid=task.getTaskId();
		issueid=task.getIssueId();
	}
	
	

	public Address() {
		super();
	}



	public String getSuffix() {
        return suffix;
    }

    public void setSuffix(String suffix) {
        this.suffix = suffix == null ? null : suffix.trim();
    }

    public String getRoujimobile() {
        return roujimobile;
    }

    public void setRoujimobile(String roujimobile) {
        this.roujimobile = roujimobile == null ? null : roujimobile.trim();
    }

    public String getSendsmobilenum() {
        return sendsmobilenum;
    }

    public void setSendsmobilenum(String sendsmobilenum) {
        this.sendsmobilenum = sendsmobilenum == null ? null : sendsmobilenum.trim();
    }

    public Integer getTaskid() {
        return taskid;
    }

    public void setTaskid(Integer taskid) {
        this.taskid = taskid;
    }

    public Integer getIssueid() {
        return issueid;
    }

    public void setIssueid(Integer issueid) {
        this.issueid = issueid;
    }
}