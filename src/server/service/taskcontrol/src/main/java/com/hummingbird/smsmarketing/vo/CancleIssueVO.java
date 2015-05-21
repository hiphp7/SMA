package com.hummingbird.smsmarketing.vo;


/**
 * 取消发布的vo
 * @author huangjiej_2
 * 2014年12月10日 上午1:22:04
 */
public class CancleIssueVO {

	/**
	 * 发布号
	 */
	protected String issueId;

	/**
	 * @return the issueId
	 */
	public String getIssueId() {
		return issueId;
	}

	/**
	 * @param issueId the issueId to set
	 */
	public void setIssueId(String issueId) {
		this.issueId = issueId;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "CancleIssueVO [issueId=" + issueId + "]";
	}
	
	
	
}
