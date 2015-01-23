/**
 * 
 */
package com.hummingbird.smsmarketing.service.impl;

import java.util.Date;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.util.StringUtils;

import com.hummingbird.common.util.Md5Util;
import com.hummingbird.commonbiz.exception.TokenException;
import com.hummingbird.commonbiz.vo.BaseUserToken;
import com.hummingbird.commonbiz.vo.UserToken;
import com.hummingbird.smsmarketing.entity.Token;
import com.hummingbird.smsmarketing.mapper.TokenMapper;
import com.hummingbird.smsmarketing.service.ITokenService;

/**
 * @author huangjiej_2
 * 2014年10月18日 下午12:21:17
 */
@Service
public class TokenService implements ITokenService {

	protected  final Log log = LogFactory.getLog(getClass());
	@Autowired
	TokenMapper tokenmapper;
	
	/* (non-Javadoc)
	 * @see com.pay2b.service.ITokenService#validateToken(com.pay2b.vo.UserToken)
	 */
	@Override
	public boolean validateToken(UserToken token)throws TokenException {
		//如果appid，手机，令牌都存在，就校验这3者有没有关联
		String appId = token.getAppId();
		String mobileNum = token.getMobileNum();
		String tokenstr = token.getToken();
		if(StringUtils.hasText(tokenstr)){
			if(StringUtils.hasText(appId)&&StringUtils.hasText(mobileNum)){
				Token relationtoken = tokenmapper.selectByToken(token);
				if(relationtoken==null){
					if(log.isDebugEnabled())
					{
						log.debug(String.format("appid[%s],mobilenum[%s]和token[%s]没有关联，校验不通过",appId,mobileNum,tokenstr));
					}
					return false;
				}
			}
			//判断token在数据库中是否存在
			Token selectByTokenStr = tokenmapper.selectByTokenStr(tokenstr);
			if(selectByTokenStr==null){
				if(log.isDebugEnabled())
				{
					log.debug(String.format("token[%s]在系统中不存在，校验不通过",tokenstr));
				}
				return false;
			}
			//更新手机号
			token.setMobileNum(selectByTokenStr.getMobileNum());
			
			return true;
		}
		
		return false;
	}

	/* (non-Javadoc)
	 * @see com.pay2b.service.ITokenService#createToken(java.lang.String, java.lang.String)
	 */
	@Override
	public UserToken createToken(String appId, String mobileNum) {
		String token =new Md5Util().Encrypt(appId+mobileNum+System.currentTimeMillis());
		Token record=new Token();
		record.setAppId(appId);
		record.setMobileNum(mobileNum);
		record.setToken(token);
		record.setCreatetime(new Date());
		record.setExpireIn(0);
		tokenmapper.insert(record);
		return new BaseUserToken(appId,mobileNum,token);
	}

	/* (non-Javadoc)
	 * @see com.pay2b.service.ITokenService#queryToken()
	 */
	@Override
	public UserToken queryToken(String appId,String mobileNum) {
		Token selectByAppAndMobile = tokenmapper.selectByToken(new BaseUserToken(appId, mobileNum, null));
		if(selectByAppAndMobile==null)
		{
			return null;
		}
		return new BaseUserToken(appId, mobileNum, selectByAppAndMobile.getToken());
	}

}
