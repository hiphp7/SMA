package com.wi360.sms.marketing.interfaces;

import java.net.URLConnection;

/*
 * 连接网络回调方法
 */
public abstract class MyResponseCallBack {
	public abstract void onFailure(Exception error, String msg);

	/**
	 * 成功返回json数据
	 * 
	 * @param responseInfo
	 * @return
	 */
	public abstract void onSuccess(int statusCode, String responseInfo);

	/**
	 * 向URLConnection添加cookie信息
	 * 
	 * @return false不向URLConnection添加cookie(默认)
	 */
	public void addCookie(final URLConnection conn) {
	};
}