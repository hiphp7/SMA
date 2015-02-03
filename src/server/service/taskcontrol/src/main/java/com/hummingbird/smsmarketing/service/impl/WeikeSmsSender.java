/**
 * 
 * SmsSender.java
 * 版本所有 深圳市蜂鸟娱乐有限公司 2013-2014
 */
package com.hummingbird.smsmarketing.service.impl;

import java.util.HashMap;
import java.util.Map;

import org.apache.commons.lang.StringUtils;
import org.apache.commons.lang.math.NumberUtils;
import org.springframework.stereotype.Service;

import com.hummingbird.common.util.DateUtil;
import com.hummingbird.common.util.Md5Util;
import com.hummingbird.common.util.PropertiesUtil;
import com.hummingbird.common.util.http.HttpRequester;
import com.hummingbird.common.util.json.JSONObject;
import com.hummingbird.common.vo.ResultModel;
import com.hummingbird.smsmarketing.service.SmsSender;

/**
 * @author huangjiej_2
 * 2015年1月21日 下午3:58:16
 * 本类主要做为短信发送程序
 */
@Service
public class WeikeSmsSender implements SmsSender {

	org.apache.commons.logging.Log log = org.apache.commons.logging.LogFactory
			.getLog(this.getClass());
	public String url;
	public String unitid;
	public String username;
	public String passwd;
	public String port;

	/**
	 * 初始化
	 */
	public void init(){
		
		if(StringUtils.isBlank(url)){
			if (log.isDebugEnabled()) {
				log.debug(String.format("初始化短信参数"));
			}
			PropertiesUtil pu = new PropertiesUtil();
			url = pu.getProperty("smsWS");
			username = pu.getProperty("user");
			passwd = pu.getProperty("password");
			unitid = pu.getProperty("unitId");
			port = pu.getProperty("port");
		}
		
	}
	
	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.service.impl.SmsSender#SendMsg(java.lang.String, java.lang.String)
	 */
	@Override
	public ResultModel sendMsg(String mobileNum,String context){
		
//		http://14.23.153.70:9999/smshttp?act=sendmsg&unitid=100&username=test&passwd=test&msg=text&phone=13911111111,18911111111&port=&sendtime=2008-01-01 12:00:00
//			说明：
//			unitid	：	企业代码
//			username	：	用户帐号
//			passwd	：	用户密码(MD5加密，32位小写)
//			msg	：	发送的短信内容（utf-8编码）
//			phone	：	发送号码（号码以’,’号分隔，100个号码以内）
//			port	：	扩展端口号
//			sendtime	：	发送时间( 格式1900-01-01 00:00:00)
		//使用客户自己的短信发送
		init();
		final Map param = new HashMap();
		param.put("act", "sendmsg");
		param.put("unitid",unitid);
		param.put("username", username);
		param.put("passwd", passwd);
		param.put("port", port);
		param.put("phone", mobileNum);
		param.put("msg", context);
		param.put("sendtime", DateUtil.formatToday("yyyy-MM-dd HH:mm:ss"));
		//采用同步方式发送，
		final ResultModel rm = new ResultModel();
//		new Thread(){
//			
//			public void run() {
				try{
					rm.setErrmsg("短信发送成功");
					String result = new HttpRequester().sendPost(url,param);
					if(log.isDebugEnabled())
					{
						log.debug("发送短信,返回结果为:"+result);
					}
					if(StringUtils.isBlank(result)){
						if (log.isDebugEnabled()) {
							log.debug(String.format("结果无内容，认为发送失败"));
						}
						rm.setErr(1002, "短信发送失败");
					}
					else{
						String[] resultarr = result.split(",");
						String wsresult = resultarr[0];
						if(NumberUtils.toLong(wsresult,-1L)<0){
							log.error("结果报告短信发送失败:"+result);
							rm.setErr(1002, "短信发送失败");
						}
						else{
							
							if(log.isDebugEnabled())
							{
								log.debug("短信发送成功");
							}
						}
					}
				}catch(Exception e){
					log.error("短信发送出错:",e);
					rm.setErr(1002, "短信发送失败");
				}
//			};
//		}.start();
		return rm; 
		
	}
	
	public static void main(String[] args) {
		System.out.println(Md5Util.Encrypt("weikecs.1"));
	}
	
}
