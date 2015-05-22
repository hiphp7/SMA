package com.hummingbird.smsmarketing.controller;



import java.util.HashMap;
import java.util.Iterator;
import java.util.List;

import org.apache.commons.lang.ObjectUtils;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.CookieValue;
import org.springframework.web.bind.annotation.RequestBody;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.ResponseBody;

import com.hummingbird.common.controller.BaseController;
import com.hummingbird.common.exception.ValidateException;
import com.hummingbird.common.ext.AccessRequered;
import com.hummingbird.common.util.SpringBeanUtil;
import com.hummingbird.common.util.ValidateUtil;
import com.hummingbird.common.util.json.JSONObject;
import com.hummingbird.common.vo.BatchResultModel;
import com.hummingbird.common.vo.ResultModel;
import com.hummingbird.commonbiz.service.IAuthenticationService;
import com.hummingbird.commonbiz.util.ShortUrlGenerator;
import com.hummingbird.smsmarketing.entity.Rouji;
import com.hummingbird.smsmarketing.mapper.RoujiMapper;
import com.hummingbird.smsmarketing.mapper.TaskMapper;
import com.hummingbird.smsmarketing.service.impl.SmsTaskService;
import com.hummingbird.smsmarketing.service.impl.TaskQueuesDispatcher;
import com.hummingbird.smsmarketing.vo.CancleIssueVO;
import com.hummingbird.smsmarketing.vo.ITask;
import com.hummingbird.smsmarketing.vo.SmsTaskVo;
import com.hummingbird.smsmarketing.vo.TaskFeeBackVO;

/**
 * 短信营销，短信任务控制器
 * 
 * @author huangjiej_2 2014年11月10日 下午11:42:07
 */
@Controller
@RequestMapping("/smstaskcontrol")
public class SmsTaskController extends BaseController {

//	@Autowired(required = true)
//	private IOrderPayService orderPayService;
//	@Autowired(required = true)
//	private AppInfoServiceImpl appService;
//	@Autowired(required = true)
//	private SellerServiceImpl sellerService;
	@Autowired(required = true)
	private TaskMapper taskMapper;
	@Autowired(required = true)
	private IAuthenticationService authService;
	@Autowired(required = true)
	private RoujiMapper roujiDao;
	@Autowired(required = true)
	private SmsTaskService  smstaskSrv;
	

	/**
	 * 队列状态报告
	 * @param getsmsvo
	 * @return
	 */
	@RequestMapping("/queuestatus")
	public @ResponseBody Object getQueueStatus() {
		TaskQueuesDispatcher bean = SpringBeanUtil.getInstance().getBean(TaskQueuesDispatcher.class);
		return bean.statusCheck();
	}
	/**
	 * 重置数据库中无数据的标识
	 * @param getsmsvo
	 * @return
	 */
	@RequestMapping("/resetnomoredata")
	public @ResponseBody Object resetNoMoreDataFlag() {
		TaskQueuesDispatcher bean = SpringBeanUtil.getInstance().getBean(TaskQueuesDispatcher.class);
		bean.resetLoadFlag(null);
		ResultModel rm = new ResultModel();
		rm.setErrmsg("重置数据库中无数据的标识完成");
		return rm;
	}
	/**
	 * 获取任务
	 * @param getsmsvo
	 * @return
	 */
	@RequestMapping("/get_task")
	@AccessRequered(methodName="手机app（肉鸡）获取任务")
	public @ResponseBody Object getTask(@CookieValue("mobileNum") String mobileNum) {
//		{"mobileNum":"13912345678"}
		if(log.isDebugEnabled()){
			log.debug("获取任务的手机号码为："+mobileNum);
		}
		//捕捉所有异常,不要由于有异常而不返回信息
		ResultModel rm = new ResultModel();
		rm.setErrmsg("获取任务成功");
		try {
			ValidateUtil.validateMobile(mobileNum);
			//校验手机号码是否在肉鸡列表中
			Rouji rj = roujiDao.selectByPrimaryKey(mobileNum);
			if(rj==null){
				throw new ValidateException(ValidateException.ERRCODE_MOBILE_INVALID, "手机号码不存在，非法用户访问");
			}
			if(log.isDebugEnabled()){
				log.debug("检验通过，获取请求");
			}
//			Task qt = new Task();
//			qt.setIssueId(Integer.parseInt("123"));
//			qt.setStatus(TaskStatusConst.TASK_STATUS_CREATE);
//			List<Task> tasks = taskMapper.selectUnsendTask(qt);
//			tasks=tasks.subList(0, 2);
			List<ITask> tasks = smstaskSrv.getTasks(rj);
			SmsTaskVo out = new SmsTaskVo(tasks);
			if (log.isTraceEnabled()) {
				log.trace(String.format("输出任务内容%s", new JSONObject(out).toString()));
			}
			
			return out;
		} catch (Exception e1) {
			log.error(String.format("获取任务处理失败"),e1);
			rm.mergeException(e1);
			return rm;
		}
		finally{
			
		}
	}
	
	/**
	 * 上报短信发送任务执行
	 * @param getsmsvo
	 * @return
	 */
	@RequestMapping("/update_task_status")
	@AccessRequered(methodName="上报短信发送任务执行情况")
	public @ResponseBody Object updateTaskStatus(@CookieValue("mobileNum") String mobileNum,@RequestBody TaskFeeBackVO taskFeeBackVO) {
		if(log.isDebugEnabled()){
			log.debug("上报短信发送任务执行："+taskFeeBackVO+",app手机号码为"+mobileNum);
		}
		//捕捉所有异常,不要由于有异常而不返回信息
		BatchResultModel brm = new BatchResultModel();
		brm.setErrmsg("上报短信任务执行信息成功");
		brm.setCountName("updatecount");
		
		try {
			//检验手机
			ValidateUtil.validateMobile(mobileNum);
			//校验手机号码是否在肉鸡列表中
			Rouji rj = roujiDao.selectByPrimaryKey(mobileNum);
			if(rj==null){
				throw new ValidateException(2004, "手机号码不存在，非法用户访问");
			}
			
			List errortask = smstaskSrv.updateTaskStatus(mobileNum,taskFeeBackVO);
			for (Iterator iterator = errortask.iterator(); iterator
					.hasNext();) {
				ResultModel rm = (ResultModel) iterator.next();
				if(!rm.isSuccessed()){
					brm.addResultModel(rm);
				}
			}
		} catch (Exception e1) {
			log.error(String.format("上报短信任务执行信息出错[%s]",taskFeeBackVO),e1);
			brm.mergeException(e1);
			brm.setErrcode(1040);
			//brm.setErr(1040,"上报短信任务执行信息失败，其它原因");
		}
		finally{
			return brm;
		}
	}
	
	/**
	 * 取消发布
	 * @param getsmsvo
	 * @return
	 */
	@RequestMapping("/cancle_issue")
	@AccessRequered(methodName="取消发布")
	public @ResponseBody Object cancleIssue(@RequestBody CancleIssueVO cancleIssueVO) {
		if(log.isDebugEnabled()){
			log.debug("取消发布："+cancleIssueVO);
		}
		//捕捉所有异常,不要由于有异常而不返回信息
		ResultModel brm = new ResultModel();
		brm.setErrmsg("取消发布成功");
		
		try {
			TaskQueuesDispatcher taskqueuedispatcher = SpringBeanUtil.getInstance().getBean(TaskQueuesDispatcher.class);
			taskqueuedispatcher.cancleQueue(cancleIssueVO.getIssueId());
		} catch (Exception e1) {
			log.error(String.format("取消发布出错[%s]",cancleIssueVO),e1);
			brm.mergeException(e1);
			brm.setErrcode(1040);
		}
		finally{
			return brm;
		}
	}
	
	/**
	 * 生成短链
	 * @param getsmsvo
	 * @return
	 */
	@RequestMapping("/get_short_link")
	@AccessRequered(methodName="生成短链")
	public @ResponseBody Object getShortLink(@RequestBody HashMap params) {
		
		String roujimobile= ObjectUtils.toString(params.get("roujimobile"));
		String mobilenum= ObjectUtils.toString(params.get("mobilenum"));
		String issueId = ObjectUtils.toString(params.get("issueId"));
		//捕捉所有异常,不要由于有异常而不返回信息
		ResultModel brm = new ResultModel();
		brm.setErrmsg("生成短链成功");
		try {
			String painttext = roujimobile+mobilenum+issueId;
			brm.put("明文数据", painttext);
			if (log.isTraceEnabled()) {
				log.trace(String.format("明文数据%s", painttext));
			}
			String genShortUrl = ShortUrlGenerator.genShortUrl(painttext);
			brm.put("短链suffer", genShortUrl);
		} catch (Exception e1) {
			log.error(String.format("生成短链出错"),e1);
			brm.mergeException(e1);
			brm.setErrcode(1040);
			//brm.setErr(1040,"上报短信任务执行信息失败，其它原因");
		}
		finally{
			return brm;
		}
	}
}
