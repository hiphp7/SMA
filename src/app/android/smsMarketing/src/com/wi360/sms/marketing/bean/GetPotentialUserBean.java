package com.wi360.sms.marketing.bean;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.DeviceUtil;

/**
 * 7 获取潜在客户列表接口
 * 
 * @author Administrator
 * 
 */
public class GetPotentialUserBean extends BaseBean {

	public int pageIndex;
	public int pageSize;
	public String sort;

	public GetPotentialUserBean(int pageIndex, int pageSize, String sort) {
		this.pageIndex = pageIndex;
		this.pageSize = pageSize;
		this.sort = sort;
	}

}
