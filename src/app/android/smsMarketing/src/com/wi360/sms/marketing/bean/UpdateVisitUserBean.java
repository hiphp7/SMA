package com.wi360.sms.marketing.bean;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.DeviceUtil;

/**
 * 9 更新拜访笔记接口
 * 
 * @author Administrator
 * 
 */
public class UpdateVisitUserBean extends BaseBean {
	// 发布号
	public String issueId;
	// 客户手机号码
	public String customerMobile;
	// 笔记内容
	public String content;
	// 称呼
	public String call;

	public UpdateVisitUserBean(String issueId, String customerMobile, String content, String call) {
		this.issueId = issueId;
		this.customerMobile = customerMobile;
		this.content = content;
		this.call = call;
	}

}
