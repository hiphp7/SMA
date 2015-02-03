/**
 * 
 */
package com.hummingbird.smsmarketing.service.impl;

import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import com.hummingbird.common.exception.DataInvalidException;
import com.hummingbird.common.exception.ValidateException;
import com.hummingbird.common.util.ValidateUtil;
import com.hummingbird.common.vo.ValidateResult;
import com.hummingbird.smsmarketing.entity.AppInfo;
import com.hummingbird.smsmarketing.mapper.AppInfoMapper;

/**
 * 应用service
 * @author huangjiej_2
 * 2014年11月11日 下午12:59:47
 */
@Service
public class AppInfoServiceImpl {
	
	@Autowired
	AppInfoMapper appdao;
	
	
	/**
	 * 获取app
	 * @param appId
	 * @return
	 */
	public AppInfo getAppByAppid(String appId){
		return appdao.selectByPrimaryKey(appId);
	}


//	/**
//	 * 校验app网站信息
//	 * @param appvo
//	 * @return
//	 * @throws ValidateException
//	 */
//	public ValidateResult validate(AppVo appvo) throws DataInvalidException {
//		ValidateResult vr = new ValidateResult();
//		ValidateUtil.assertNull(appvo.getAppId(), "appId为空");
//		AppInfo app = getAppByAppid(appvo.getAppId());
//		ValidateUtil.assertNull(app, "APP不存在");
//		ValidateUtil.assertNotEqual(app.getStatus(),"ON","APP已下线",ValidateException.ERRCODE_APP_INVALID);
//		appvo.setAppKey(app.getAppkey());
//		vr.setValidateObj(app);
//		return vr;
//	}
	
	
}
