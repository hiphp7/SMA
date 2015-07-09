package com.wi360.sms.marketing.bean;

import java.util.ArrayList;
import java.util.List;

import android.app.Activity;

import com.wi360.sms.marketing.base.BaseBean;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.DeviceUtil;

/**
 * 4 上报短信发送任务执行接口
 * 
 * @author Administrator
 * 
 */
public class UploadSmsTaskBean extends BaseBean {

	public List<UploadInnerSmsTaskBean> task;

	public UploadSmsTaskBean() {
		this.task = new ArrayList<UploadSmsTaskBean.UploadInnerSmsTaskBean>();
	}

	public class UploadInnerSmsTaskBean {
		/**
		 * 4 上报短信发送任务执行接口
		 * 
		 * @author Administrator
		 * 
		 */
		public String taskId;
		public String issueId;
		public String status;

		public UploadInnerSmsTaskBean(String taskId, String issueId) {
			this.taskId = taskId;
			this.issueId = issueId;
//			this.status = Constants.FLS + taskId;
			this.status = Constants.FLS;
		}
		
		public UploadInnerSmsTaskBean(){
			
		}
	}

}
