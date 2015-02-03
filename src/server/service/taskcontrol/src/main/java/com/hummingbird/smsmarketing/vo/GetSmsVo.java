package com.hummingbird.smsmarketing.vo;

import com.hummingbird.common.util.Md5Util;
import com.hummingbird.common.util.ValidateUtil;
import com.hummingbird.commonbiz.vo.BaseUserDecidable;

/**
 * app使用的认证基类
 * @author huangjiej_2
 * 2014年10月18日 上午9:36:53
 */
public class GetSmsVo extends BaseUserDecidable {
	
	@Override
	public String toString() {
		return super.toString();
	}
	@Override
	public boolean isAuthed() {
		
		String text = ValidateUtil.sortbyValues(getAppId(),getAppKey(),getNonce(),getTimeStamp());
		String encrypt = Md5Util.Encrypt(text);
		System.out.println("getsms="+text+":"+encrypt);
		return encrypt.equals(getSignature());
	}
}
