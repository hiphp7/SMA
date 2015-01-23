/**
 * 
 */
package com.hummingbird.smsmarketing.service.impl;

import java.util.HashMap;
import java.util.Map;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.hummingbird.common.exception.AuthenticationException;
import com.hummingbird.common.exception.SignatureException;
import com.hummingbird.commonbiz.service.IAuthenticationService;
import com.hummingbird.commonbiz.vo.BaseUserDecidable;
import com.hummingbird.commonbiz.vo.Decidable;
import com.hummingbird.smsmarketing.entity.AppInfo;
import com.hummingbird.smsmarketing.entity.Token;
import com.hummingbird.smsmarketing.mapper.AppInfoMapper;
import com.hummingbird.smsmarketing.mapper.TokenMapper;

/**
 * 验证service
 * 
 * @author huangjiej_2 2014年10月16日 上午7:55:54
 */
@Service
public class AuthenticationService implements IAuthenticationService {

	protected Log log = LogFactory.getLog(getClass());

	// @Autowired(required = true)
	// private SellerMapper sellerMapper;

	@Autowired(required = true)
	private AppInfoMapper appInfoMapper;
	@Autowired(required = true)
	private TokenMapper tokenMapper;

	/*
	 * (non-Javadoc)
	 * 
	 * @see
	 * com.pay2b.service.IAuthenticationService#validateToken(com.pay2b.vo.Decidable
	 * )
	 */
	@Override
	public Object validateAuth(Decidable authObj)
			throws AuthenticationException {

		Map map = new HashMap();
		// 用户，根据appid进行查询
		if (authObj instanceof BaseUserDecidable) {
			BaseUserDecidable baseuserdecide = (BaseUserDecidable) authObj;

			String appid= baseuserdecide.getAppId();
			AppInfo info = appInfoMapper.selectByPrimaryKey(appid);
			if (info == null) {
				log.error("根据appid:" + appid + "无法在系统中查询到相关数据");
				throw new AuthenticationException("appId不存在");
			}
			map.put("appKey", info.getAppKey());
			Token to = tokenMapper.selectByToken(baseuserdecide);
			if (to != null) {
				map.put("token", to.getToken());
			}
		} else {
			log.error("认证中无法取得appid和手机号，无法进行认证");
			throw new AuthenticationException("签名不一致");
		}
		authObj.setOtherParam(map);
		boolean authed;
		try {
			authed = authObj.isAuthed();
		} catch (SignatureException e) {
			log.error("校验签名失败",e);
			throw new AuthenticationException("签名不一致");
		}
		if(!authed){
			throw new AuthenticationException("签名不一致");
		}
		return map;
	}

}
