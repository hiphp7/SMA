package com.wi360.sms.marketing.utils;

import android.net.Uri;

/**
 * 常量类
 * 
 * @author Administrator
 * 
 */
public class Constants {
	public static String charset = "UTF-8";
	public static String token = "token";
	public static String mobileNum = "mobileNum";
	public static String name = "username";
	public static String get_sms_task_time = "get_sms_task_time";
	public static Uri intent_uri = Uri.parse("content://https://www.51pme.com/auth/");
	public static String FLS = "RPF";
	// public static String FLS = "RPS";
	public static String OK = "RPS";

	public static String LOG_INFO = "LOG_INFO";
	// 是否登陆
	public static String is_login = "is_login";
	// 发送短信条数
	public static String send_sms_repquest_sum = "send_sms_repquest_sum";
	// 接收短信数量
	public static String send_sms_response_sum = "send_sms_response_sum";
	// 需要处理短信内容条数
	public static String sms_content_size = "sms_content_size";

	// 登陆验证码的最后发送时间
	public static String key_end_send_login_coed_time = "key_end_send_login_coed_time";
	// 保存需要上报服务器的短信的内容,没有发送的
	public static String key_report_sms_info_json = "key_report_sms_info_json";

	/**
	 * url
	 */
	// 域名 https://115.29.249.236:8080/auth/user/login
	public static String COM_URL = "http://121.199.3.89:80/";
	// http://115.29.7.155:8088
	// public static String COM_LOGIN_URL = "http://115.29.7.155:8088/";
	public static String COM_LOGIN_URL = "http://121.199.3.89:8090/";

	// 发送登陆短信验证码URL
	public static String SEND_LOGIN_SMS_CODE_URL = COM_LOGIN_URL + "if/roujiauth/user/get_smscode";
	// 登录url
	public static String LOGIN_URL = COM_LOGIN_URL + "if/roujiauth/user/login";
	// 3 获取短信发送任务接口
	public static String FIND_SMS_SEND_INFO = COM_LOGIN_URL + "if/smstaskcontrol/get_task";
	// 4 上报短信发送任务执行接口
	public static String UP_SMS_INFO = COM_LOGIN_URL + "if/smstaskcontrol/update_task_status";

	// 5 获取肉鸡执行信息接口
	public static String FIND_INFO_URL = COM_URL + "if/smstaskcontrol/get_rouji_info";
	// 6 获取未读潜在客户数接口
	public static String FIND_POTENTIAL_USER_URL = COM_URL + "if/customer/how_many_unread";
	// 7 获取潜在客户列表接口
	public static String FIND_POTENTIAL_USER_LIST_URL = COM_URL + "if/customer/get_customer_list";
	// 8 获取潜在客户访问详情接口
	public static String FIND_POTENTIAL_USER_DESC_URL = COM_URL + "if/customer/get_datail";
	// 9 更新拜访笔记接口
	public static String SAVE_RECORD_DESC_URL = COM_URL + "if/note/update";
	// 10 查询有笔记的发布号列表
	public static String FIND_NOTES_LIST_URL = COM_URL + "if/note/get_issue_list";
	// 11 获取笔记详细
	public static String FIND_BACK_RESCORD_DESC_URL = COM_URL + "if/note/get_issue_note";
	// 11 获取笔记详细
	public static String FIND_BACK_RESCORD_DESC_URL12 = COM_URL + "if/note/get_note_by_rouji";

	// 推广页面url
	public static String WAIT_EXTENSION_URL = COM_URL + "auth/user/get_smscode";

}
