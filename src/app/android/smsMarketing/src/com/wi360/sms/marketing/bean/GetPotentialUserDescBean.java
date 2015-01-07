package com.wi360.sms.marketing.bean;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.DeviceUtil;

/**
 * 8 获取潜在客户访问详情接口
 * 
 * @当查看潜在客户列表后，可以更进一步查看客户对某个内容的访问详细情况。
 * 
 * @author Administrator
 * 
 */
public class GetPotentialUserDescBean extends BaseBean {

	public String issueId;
	public String customerMobile;

	public GetPotentialUserDescBean(String issueId, String customerMobile) {
		this.issueId = issueId;
		this.customerMobile = customerMobile;
	}

}
