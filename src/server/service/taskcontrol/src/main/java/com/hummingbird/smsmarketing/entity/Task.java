package com.hummingbird.smsmarketing.entity;

import java.util.Date;

import com.hummingbird.common.util.DateUtil;
import com.hummingbird.smsmarketing.vo.ITask;


/**
 * 数据库中的任务数据
 * @author huangjiej_2
 * 2014年12月9日 上午10:30:59
 */
public class Task implements ITask{
    private Integer taskId;

    private Integer issueId;

    private String sendsmobileNum;
    private String roujiMobileNum;

    private Date startTime;

    private String type;

    private Integer contentId;

    private String title;

    private String status;
    
	protected Date endTime;
	/**
	 * 如状态为已分派，则为分派的时间，如状态为已上报发送成功，则为发送短信的时间
	 */
	protected Date sentTime;
	
	/**
	 * 内容
	 */
	protected Content content; 

    @Override
	public Integer getTaskId() {
        return taskId;
    }

    public void setTaskId(Integer taskId) {
        this.taskId = taskId;
    }

    @Override
	public Integer getIssueId() {
        return issueId;
    }

    public void setIssueId(Integer issueId) {
        this.issueId = issueId;
    }

    @Override
	public String getSendsmobileNum() {
        return sendsmobileNum;
    }

    public void setSendsmobileNum(String sendsmobileNum) {
        this.sendsmobileNum = sendsmobileNum == null ? null : sendsmobileNum.trim();
    }

    @Override
	public Date getStartTime() {
        return startTime;
    }

    public void setStartTime(Date startTime) {
        this.startTime = startTime;
    }

    @Override
	public Date getEndTime() {
        return endTime;
    }

    public void setEndTime(Date endTime) {
        this.endTime = endTime;
    }

    @Override
	public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type == null ? null : type.trim();
    }

    @Override
	public Integer getContentId() {
        return contentId;
    }

    public void setContentId(Integer contentId) {
        this.contentId = contentId;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title == null ? null : title.trim();
    }

    @Override
	public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status == null ? null : status.trim();
    }

	/* (non-Javadoc)
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		final int prime = 31;
		int result = 1;
		result = prime * result + ((taskId == null) ? 0 : taskId.hashCode());
		return result;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#equals(java.lang.Object)
	 */
	@Override
	public boolean equals(Object obj) {
		if (this == obj)
			return true;
		if (obj == null)
			return false;
		if (!(obj instanceof Task))
			return false;
		Task other = (Task) obj;
		if (taskId == null) {
			if (other.taskId != null)
				return false;
		} else if (!taskId.equals(other.taskId))
			return false;
		return true;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "Task [taskId=" + taskId + ", issueId=" + issueId
				+ ", sendsmobileNum=" + sendsmobileNum + ", startTime="
				+ DateUtil.formatCommonDateorNull(startTime) + ", type=" + type + ", contentId=" + contentId
				+ ", title=" + title + ", status=" + status + ", endTime="
				+ DateUtil.formatCommonDateorNull(endTime) +", senttime=" +DateUtil.formatCommonDateorNull(sentTime)+"]";
	}

	/**
	 * @return the content
	 */
	public Content getContent() {
		return content;
	}

	/**
	 * @param content the content to set
	 */
	public void setContent(Content content) {
		this.content = content;
	}

	/**
	 * @return the roujiMobileNum
	 */
	public String getRoujiMobileNum() {
		return roujiMobileNum;
	}

	/**
	 * @param roujiMobileNum the roujiMobileNum to set
	 */
	public void setRoujiMobileNum(String roujiMobileNum) {
		this.roujiMobileNum = roujiMobileNum;
	}

	/**
	 * 如状态为已分派，则为分派的时间，如状态为已上报发送成功，则为发送短信的时间
	 */
	public Date getSentTime() {
		return sentTime;
	}

	/**
	 * 如状态为已分派，则为分派的时间，如状态为已上报发送成功，则为发送短信的时间
	 */
	public void setSentTime(Date sentTime) {
		this.sentTime = sentTime;
	}
	
	
}