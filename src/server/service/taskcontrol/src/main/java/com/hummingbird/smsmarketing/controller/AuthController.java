package com.hummingbird.smsmarketing.controller;



import java.util.HashMap;

import org.apache.commons.lang.ObjectUtils;
import org.apache.commons.lang.StringUtils;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;

import com.hummingbird.common.controller.BaseController;
import com.hummingbird.common.exception.ValidateException;
import com.hummingbird.common.util.Md5Util;
import com.hummingbird.common.util.SmsSenderUtil;
import com.hummingbird.common.util.ValidateUtil;
import com.hummingbird.common.vo.ResultModel;
import com.hummingbird.commonbiz.mapper.BlackBuyerMapper;
import com.hummingbird.commonbiz.service.IAuthenticationService;
import com.hummingbird.commonbiz.service.ISmsCodeService;
import com.hummingbird.commonbiz.util.AuthCodeUtil;
import com.hummingbird.commonbiz.vo.UserToken;
import com.hummingbird.smsmarketing.entity.Rouji;
import com.hummingbird.smsmarketing.mapper.RoujiMapper;
import com.hummingbird.smsmarketing.service.ITokenService;
import com.hummingbird.smsmarketing.service.SmsSender;
import com.hummingbird.smsmarketing.vo.GetSmsVo;
import com.hummingbird.smsmarketing.vo.LoginVo;

/**
 * 短信营销，登录相关控制器
 * 
 * @author huangjiej_2 2014年11月10日 下午11:42:07
 */
@Controller
@RequestMapping("/roujiauth/user")
public class AuthController extends BaseController {

//	@Autowired(required = true)
//	private IOrderPayService orderPayService;
//	@Autowired(required = true)
//	private AppInfoServiceImpl appService;
//	@Autowired(required = true)
//	private SellerServiceImpl sellerService;
	@Autowired(required = true)
	private BlackBuyerMapper bbuyerMapper;
	@Autowired(required = true)
	private IAuthenticationService authService;
	@Autowired(required = true)
	private ISmsCodeService smsService;
	@Autowired(required = true)
	private ITokenService  tokensrv;
	@Autowired(required = true)
	private RoujiMapper roujiDao;
	
	@Autowired(required = true)
	private SmsSender smssender;

	/**
	 * 向服务器请求下发验证码
	 * @param getsmsvo
	 * @return
	 */
	@RequestMapping("/get_smscode")
	public @ResponseBody Object getSmsCode(@RequestBody GetSmsVo getsmsvo) {
//		{"appId":"smsmarketing","timeStamp":"TIMESTAMP", "nonce":"NONCE","signature":"SIGNATURE","mobileNum":"13912345678"}
		if(log.isDebugEnabled()){
			log.debug("向服务器请求下发验证码："+getsmsvo);
		}
		//捕捉所有异常,不要由于有异常而不返回信息
		ResultModel rm = new ResultModel();
		rm.setErrmsg("发送短信验证码成功");
		try {
			ValidateUtil.validateMobile(getsmsvo.getMobileNum());
			//校验手机号码是否在肉鸡列表中
			Rouji rj = roujiDao.selectByPrimaryKey(getsmsvo.getMobileNum());
			if(rj==null){
				throw new ValidateException(1004, "手机号码不存在，非法用户访问");
			}
			//校验token
			Object validateAuth = authService.validateAuth(getsmsvo);
//			if(!authsuccessed){
//				if(log.isDebugEnabled()){
//					log.debug(String.format("校验用户签名不通过,%s",getsmsvo));
//				}
//				rm.setErr(1013, "用户签名验签失败");
//				return rm;
//			}
			if(log.isDebugEnabled()){
				log.debug("检验通过，发送验证码");
			}
			
			UserToken createToken = smsService.createToken(getsmsvo.getAppId(), getsmsvo.getMobileNum(), 6);
			String content = AuthCodeUtil.getAuthCodeByTemplate(createToken.getToken(),"sms.authcode");
			//调用webservice 发送模板
			if(log.isDebugEnabled())
			{
				log.debug("发送手机验证码请求:"+content);
			}
			
			try{
//				SmsSenderUtil.sendSms(getsmsvo.getMobileNum(), content);
				ResultModel sendMsgRst = smssender.sendMsg(getsmsvo.getMobileNum(), content);
				if(sendMsgRst.isSuccessed()){
					if(log.isDebugEnabled())
					{
						log.debug("验证码发送成功");
					}
					
				}
				else{
					if(log.isDebugEnabled())
					{
						log.debug("验证码发送失败:"+sendMsgRst.getErrmsg());
					}
					
					rm.setErr(1014, "发送验证码失败:"+sendMsgRst.getErrmsg());
				}
				
			}catch(Exception e){
				log.error("发送验证码出错:",e);
				rm.setErr(1014, "发送验证码出错:"+e.getMessage());
			}
				
		} catch (Exception e1) {
			log.error(String.format("发送短信验证码[%s]，处理失败",getsmsvo),e1);
			rm.mergeException(e1);
		}
		finally{
			if(log.isDebugEnabled()){
				log.debug("向服务器请求下发验证码完成");
			}
		}
		return rm;
	}
	
	/**
	 * sdk注册手机号
	 * @param getsmsvo
	 * @return
	 */
	@RequestMapping("/login")
	public @ResponseBody Object login(@RequestBody LoginVo loginvo) {
//		{"appId":"smsmarketing","timeStamp":"TIMESTAMP", "nonce":"NONCE","signature":"MD5(appId+appKey+nonce+timeStamp)", "loginInfo":{"mobileNum":"13912345678","smsCode":"223344"},
//			"deviceInfo":{"mac":"3ere:eee:3434:34434","deviceId":"设备标识","imsi":"SIM卡设备号"}} 
		if(log.isDebugEnabled()){
			log.debug("手机号码登录："+loginvo);
		}
		//捕捉所有异常,不要由于有异常而不返回信息
		ResultModel rm = new ResultModel();
		rm.setErrmsg("手机号码登录成功");
		try {
			//检验手机
			ValidateUtil.validateMobile(loginvo.getMobileNum());
			//校验手机号码是否在肉鸡列表中
			Rouji rj = roujiDao.selectByPrimaryKey(loginvo.getMobileNum());
			if(rj==null){
				throw new ValidateException(1004, "手机号码不存在，非法用户访问");
			}
			//校验token
//			boolean validatesuccess = tokensrv.validateToken(loginvo);
//			if(!validatesuccess){
//				if(log.isDebugEnabled()){
//					log.debug(String.format("校验用户token不通过,%s",loginvo));
//				}
//				rm.setErr(1024, "用户token不正确");
//				return rm;
//			}
			//校验签名
			Object afterauth = authService.validateAuth(loginvo);
			if(log.isDebugEnabled()){
				log.debug("检验签名通过，验证验证码");
			}
			boolean authCodeSuccess = AuthCodeUtil.validateAuthCode(loginvo.getAppId(), loginvo.getLoginInfo().getMobileNum(),loginvo.getLoginInfo().getSmsCode());
			if(!authCodeSuccess){
				if(log.isDebugEnabled()){
					log.debug(String.format("验证码不正确,%s",loginvo));
				}
				rm.setErr(ValidateException.ERRCODE_SIGNATURE_FAIL, "短信验证码不正确");
				rm.setBaseErrorCode(2000);
				return rm;
			}
			
			UserToken selectToken=tokensrv.queryToken(loginvo.getAppId(), loginvo.getLoginInfo().getMobileNum());
			if(selectToken==null){
				//如果已记录就不处理
				UserToken createToken = tokensrv.createToken(loginvo.getAppId(), loginvo.getLoginInfo().getMobileNum());
				//返回token和信用信息
				rm.put("token", createToken.getToken());
//				//把appkeyhash记录到数据库中
//				AppInfo appInfo = appMapper.getAppInfoByappid(loginvo.getAppId());
//				appInfo.setAppkeyhash(loginvo.getAppKeyHash());
//				appMapper.updateByPrimaryKey(appInfo);
			}
			else{
				rm.put("token", selectToken.getToken());
			}
			rm.put("name", ""); 
			rm.put("headImage", ""); 
			rm.put("mobileNum", loginvo.getLoginInfo().getMobileNum()); 
			
		} catch (Exception e1) {
			log.error(String.format("手机号码登录出错[%s]",loginvo),e1);
			rm.mergeException(e1);
		}
		finally{
			if(log.isDebugEnabled()){
				log.debug("手机号码登录完成");
			}
			return rm;
		}
	}
	
	
	@RequestMapping("/user/genkey")
	public @ResponseBody Object genkey(@RequestBody HashMap map) {
		if(log.isDebugEnabled()){
			log.debug("生成key："+map);
		}
		//捕捉所有异常,不要由于有异常而不返回信息
		ResultModel rm = new ResultModel();
		rm.setErrmsg("成功");
		try {
			
			String nonce=ObjectUtils.toString(map.get("nonce"),"");
			String TIMESTAMP=ObjectUtils.toString(map.get("timeStamp"),"");
			String appid=ObjectUtils.toString(map.get("appId"),"");
			String appkey=ObjectUtils.toString(map.get("appKey"),"");
			String platformKey=ObjectUtils.toString(map.get("platformKey"),"");
			String token=ObjectUtils.toString(map.get("token"),"");
			String sum=ObjectUtils.toString(map.get("sum"),"");
			//登录key
			rm.put("登录key,Md5(appid+appkey+nonce+TIMESTAMP)",  Md5Util.Encrypt(appid+appkey+nonce+TIMESTAMP));
			//查询信用key
			rm.put("信用查询key,Md5(appid+appkey+nonce+TIMESTAMP+token)", Md5Util.Encrypt(appid+appkey+nonce+TIMESTAMP+token));
			//平台key
			rm.put("平台key,Md5(nonce+platformKey+TIMESTAMP)", Md5Util.Encrypt(nonce+platformKey+TIMESTAMP));
			
			//信用支付key
			rm.put("信用支付key", "无");
			//信用支付key
			rm.put("短信信用支付key,MD5(appId+appKey+sum)", Md5Util.Encrypt(appid+appkey+sum));
			
		} catch (Exception e1) {
			//log.error(String.format("手机号码登录出错[%s]",loginvo),e1);
			rm.mergeException(e1);
		}
		finally{
			if(log.isDebugEnabled()){
				log.debug("genkey完成");
			}
			return rm;
		}
	}
}
