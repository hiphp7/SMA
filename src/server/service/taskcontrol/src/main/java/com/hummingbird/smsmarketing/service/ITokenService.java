package com.hummingbird.smsmarketing.service;

import com.hummingbird.commonbiz.exception.TokenException;
import com.hummingbird.commonbiz.vo.UserToken;

/**
 * 令牌服务service
 * @author huangjiej_2
 * 2014年10月18日 下午12:17:49
 */
public interface ITokenService {

	/**
	 * 验证用户令牌
	 * @param token
	 * @return
	 * @throws TokenException 
	 */
	public boolean validateToken(UserToken token) throws  TokenException;
	
	/**
	 * 创建用户令牌，主要用于登录
	 * @param appId
	 * @param mobileNum
	 * @return
	 */
	public UserToken createToken(String appId,String mobileNum);
	
	/**
	 * 查询用户令牌
	 * @return
	 */
	public UserToken queryToken(String appId,String mobileNum);
	
	
}
