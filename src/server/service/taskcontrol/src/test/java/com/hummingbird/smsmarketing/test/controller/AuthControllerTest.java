/**
 * 
 */
package com.hummingbird.smsmarketing.test.controller;

import static org.junit.Assert.*;

import org.junit.Before;
import org.junit.Test;
import org.junit.runner.RunWith;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.jdbc.core.JdbcTemplate;
import org.springframework.mock.web.MockHttpServletRequest;
import org.springframework.mock.web.MockHttpServletResponse;
import org.springframework.test.context.junit4.SpringJUnit4ClassRunner;
import org.springframework.test.web.servlet.MockMvc;

import com.hummingbird.common.util.SpringBeanUtil;
import com.hummingbird.common.vo.ResultModel;
import com.hummingbird.smsmarketing.controller.AuthController;
import com.hummingbird.smsmarketing.vo.DeviceInfo;
import com.hummingbird.smsmarketing.vo.GetSmsVo;
import com.hummingbird.smsmarketing.vo.LoginVo;
import com.hummingbird.smsmarketing.vo.SmsCodeVo;
import com.hummingbird.test.controller.AbstractContextControllerTests;

/**
 * @author huangjiej_2
 * 2014年12月3日 下午10:13:26
 */
@RunWith(SpringJUnit4ClassRunner.class)
public class AuthControllerTest extends AbstractContextControllerTests{

	  
    private MockMvc mockMvc;  
      
  
    @Before  
    public void setup() {  
//        //this.mockMvc = webAppContextSetup(this.wac).alwaysExpect(status().isOk()).build();  
//        this.mockMvc = MockMvcBuilders.standaloneSetup(personalDiskService).build();
    	SpringBeanUtil.init(wac);
    }  
    
//    @Test
    public void getsms_code(){
    	AuthController authctrl = (AuthController) this.wac.getBean("authController");  
//        MockHttpServletRequest request = new MockHttpServletRequest();  
//        MockHttpServletResponse response = new MockHttpServletResponse();  
//        request.setMethod("POST");  
//        request.addParameter("username", "aa");  
//        request.addParameter("password", "bb");  
        GetSmsVo getSmsVo=new GetSmsVo();
        getSmsVo.setAppId("smsmarketing");
        getSmsVo.setMobileNum("18922260815");
        getSmsVo.setNonce("NONCE");
        getSmsVo.setSignature("cafc71a87d04c3dc94f960782d3893d8");
        getSmsVo.setTimeStamp("TIMESTAMP");
		Object returnobj = authctrl.getSmsCode(getSmsVo);
		System.out.println(returnobj);
    	assertEquals(0,((ResultModel)returnobj).getErrcode());
    }
    @Test
    public void testlogin(){
    	
    	JdbcTemplate jdbc = (JdbcTemplate) this.wac.getBean(JdbcTemplate.class);
    	jdbc.update("delete from t_smscode where mobilenum='18922260815'");
    	jdbc.update("insert into t_smscode (mobilenum,smscode,expireIn,sendTime) values ('18922260815','123456',60,now())");
    	
//    	AuthController authctrl = (AuthController) this.wac.getBean("authController");  
//    	LoginVo loginvo=new LoginVo();
//    	loginvo.setAppId("smsmarketing");
//    	loginvo.setMobileNum("18922260815");
//    	loginvo.setNonce("NONCE");
//    	loginvo.setSignature("cafc71a87d04c3dc94f960782d3893d8");
//    	loginvo.setTimeStamp("TIMESTAMP");
//    	SmsCodeVo loginInfo=new SmsCodeVo();
//    	loginInfo.setMobileNum("18922260815");
//    	loginInfo.setSmsCode("123456");
//		loginvo.setLoginInfo(loginInfo);
//		DeviceInfo deviceInfo=new DeviceInfo();
//		deviceInfo.setDeviceId("设备标识");
//		deviceInfo.setImsi("12345123451");
//		deviceInfo.setMac("mac12345123451");
//		loginvo.setDeviceInfo(deviceInfo);
//		Object returnobj = authctrl.login(loginvo);
//    	System.out.println(returnobj);
//    	assertEquals(0,((ResultModel)returnobj).getErrcode());
    }
    
	
}
