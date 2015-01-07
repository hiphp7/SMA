package com.wi360.sms.marketing.service;

import java.util.Date;
import java.util.List;

import android.app.AlarmManager;
import android.app.PendingIntent;
import android.app.Service;
import android.content.Context;
import android.content.Intent;
import android.os.Handler;
import android.os.IBinder;
import android.os.Message;
import android.os.SystemClock;
import android.util.Log;

import com.wi360.sms.marketing.base.MyAsyncTask;
import com.wi360.sms.marketing.bean.ResBean;
import com.wi360.sms.marketing.bean.UploadSmsTaskBean;
import com.wi360.sms.marketing.bean.UploadSmsTaskBean.UploadInnerSmsTaskBean;
import com.wi360.sms.marketing.receiver.Alarmreceiver;
import com.wi360.sms.marketing.utils.CommonUtil;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.DateUtils;
import com.wi360.sms.marketing.utils.GsonTools;
import com.wi360.sms.marketing.utils.L;
import com.wi360.sms.marketing.utils.SPUtils;
import com.wi360.sms.marketing.utils.SendSmsUtils;
import com.wi360.sms.marketing.utils.SendSmsUtils.SendSmsInfoCallback;
import com.wi360.sms.marketing.utils.StringUtils;

public class StartingUpService extends Service {

	private String TAG = "StartingUpService";
	private Context context;
	private boolean isSendNetWorkRequest = false;
	private boolean isUploadTask = false;

	private int task_length = 0;
	// 隔多长时间获取网络任务有效
	private Long long_time = new Long(1000 * 120);

	/**
	 * 保存获取到服务端的数据
	 */
	private List<ResBean.Task> resTaskBean;
	private UploadSmsTaskBean uploadTaskBean;

	@Override
	public void onCreate() {
		// Notification notification = new Notification();
		// startForeground(1, notification);
		Log.i(TAG, "onCreate");
		context = this;
		Intent intent = new Intent(this, Alarmreceiver.class);
		intent.setAction("repeating");
		PendingIntent sender = PendingIntent.getBroadcast(this, 0, intent, 0);
		// 开始时间
		// long firstime = SystemClock.currentThreadTimeMillis();
		long firstime = System.currentTimeMillis();
		// long firstime = new Date().getTime();
		AlarmManager am = (AlarmManager) getSystemService(Context.ALARM_SERVICE);
		// 5秒一个周期，不停的发送广播
		// am.setRepeating(AlarmManager.ELAPSED_REALTIME_WAKEUP, firstime, (60 *
		// 1000) * 22, sender);
		long waitTime = (1000 * 60 * 26);
		// long waitTime = (1000 * 20);
		am.setRepeating(AlarmManager.RTC_WAKEUP, firstime + 6 * 1000, waitTime, sender);

	}

	@Override
	public int onStartCommand(Intent intent, int flags, int startId) {
		Long time = (Long) SPUtils.get(context, Constants.get_sms_task_time, new Long(0));

		isSendNetWorkRequest = false;
		if (intent != null) {
			isSendNetWorkRequest = intent.getBooleanExtra("isSendNetWorkRequest", false);
		}
		L.i("StartingUpService service onStartCommand " + isSendNetWorkRequest);
		if (isSendNetWorkRequest) {
			resTaskBean = null;
			uploadTaskBean = null;
			accessNetwork();
		}
		flags = START_STICKY;
		int res = super.onStartCommand(intent, flags, startId);
		return res;
	}

	@Override
	public void onDestroy() {
		Log.i(TAG, "onDestroy");
		// Intent localIntent = new Intent();
		// localIntent.setClass(this, StartingUpService.class); //
		// // 销毁时重新启动Service
		// this.startService(localIntent);
		super.onDestroy();
	}

	@Override
	public IBinder onBind(Intent arg0) {
		return null;
	}

	/**
	 * 上报短信发送状态
	 */
	private Handler upLoadSmsHandler = new Handler() {
		@Override
		public void handleMessage(Message msg) {
			L.i("upLoadSmsHandler handleMessage");
			if (uploadTaskBean != null && uploadTaskBean.task.size() > 0) {
				String json = GsonTools.createGsonString(uploadTaskBean);
				saveLogInfo("--上报任务日志------: \n" + json);
				L.i("上报短信数据:  " + json);
				new MyAsyncTask(context, null) {
					@Override
					public String connectNetWorkSuccess(String... responseStr) {
						saveLogInfo("---上报任务返回的json数据------: \n" + responseStr[0]);
						if (isLoadNetWorkSuccess()) {
							// SPUtils.put(context,
							// Constants.key_report_sms_info_json, "");
							L.i("upLoadSmsHandler handleMessage isLoadNetWorkSuccess");
						} else {

						}
						L.i("上报短信发送状态  " + resBean.errcode + "   " + resBean.errmsg);
						return null;
					}
				}.execute(new String[] { Constants.UP_SMS_INFO, json });
			}

			// Object objJson = SPUtils.get(context,
			// Constants.key_report_sms_info_json, null);
			// if (objJson != null && !"".equals(objJson)) {
			// reportList = GsonTools.changeGsonToBean((String) objJson,
			// reportList.getClass());
			// String status = null;
			// // 轮询,改变失败的状态
			// for (UploadSmsTaskBean.ReportSmsTaskBean2 temp : reportList.list)
			// {
			// status = temp.status;
			// if (status.contains(Constants.FLS)) {
			// temp.status = Constants.FLS;
			// }
			// }
			// String json = GsonTools.createGsonString(reportList);
			// 发送上报请求

			// }

		}
	};
	/**
	 * 发送短信
	 */
	private Handler sendSmsHandler = new Handler() {
		@Override
		public void handleMessage(Message msg) {
			L.i("sendSmsHandler  handleMessage");
			new Thread() {
				public void run() {
					// 发送短信
					// if (uploadTaskBean.task.size() <= uploadSmsCount) {
					if (resTaskBean != null && resTaskBean.size() > 0) {
						L.i("sendSmsHandler  handleMessage if");
						ResBean.Task tempTask = resTaskBean.remove(0);
						// UploadInnerSmsTaskBean uploadInnerBean = new
						// UploadInnerSmsTaskBean(tempTask.taskId,
						// tempTask.issueId);
						UploadInnerSmsTaskBean uploadInnerBean = uploadTaskBean.new UploadInnerSmsTaskBean(
								tempTask.taskId, tempTask.issueId);
						uploadTaskBean.task.add(0, uploadInnerBean);

						sendSMS(context, tempTask.destMobile, tempTask.content, tempTask.taskId);
						sendSmsHandler.sendEmptyMessageDelayed(0, 1000 * 20);
					} else {
						L.i("sendSmsHandler  handleMessage else");
						if (!isUploadTask) {
							isUploadTask = true;
							upLoadSmsHandler.sendEmptyMessageDelayed(1, 1000 * 10);
						}
					}

					// if (resBeanLists != null && index < resBeanLists.size())
					// {
					// ResBean.Lists tempBean = resBeanLists.get(index++);
					// // 发送短信
					//
					// sendSmsHandler.sendEmptyMessageDelayed(1, 1000 * 20);
					// } else {
					// index = 0;
					// resBeanLists = null;
					// reportSmsHandler.sendEmptyMessageDelayed(1, 1000 * 10);
					// }
				};
			}.start();
		}
	};

	/**
	 * 访问网络
	 */
	public void accessNetwork() {
		String token = (String) SPUtils.get(context, Constants.token, "");
		if (StringUtils.isEmpty(token)) {
			return;
		}
		L.i("accessNetwork " + DateUtils.getNowTime());
		// Toast.makeText(getApplicationContext(), "发送网络请求", 0).show();
		if (CommonUtil.isNetworkAvailable(context) == 0) {
			saveLogInfo("没有网络");
			return;
		}

		String json = null;
		isUploadTask = false;
		// test();
		// 3 获取短信发送任务接口
		new MyAsyncTask(context, null) {

			@Override
			public String connectNetWorkSuccess(String... responseStr) {
				if (isLoadNetWorkSuccess()) {
					// 保存日志
					saveLogInfo("----获取任务日志-----: \n" + responseStr[0]);

					L.i("connectNetWorkSuccess if");
					uploadTaskBean = new UploadSmsTaskBean();
					if (resBean.task != null && resBean.task.size() > 0) {
						// 测试保存日志

						// 保存当前获取到服务端任务
						resTaskBean = resBean.task;
						L.i("resTaskBean:  " + resTaskBean.toString());
						task_length = resBean.task.size();
						// if (resTaskBean != null && resTaskBean.size() > 0) {
						// ResBean.Task tempTask = resTaskBean.remove(0);
						// UploadInnerSmsTaskBean uploadInnerBean = new
						// UploadInnerSmsTaskBean(tempTask.taskId,
						// tempTask.issueId);
						// uploadTaskBean.task.add(0, uploadInnerBean);
						//
						// }
						sendSmsHandler.sendEmptyMessageDelayed(0, 1000);
					}
					// SPUtils.put(context, key, object)
				} else {

				}
				return null;
			}
		}.execute(new String[] { Constants.FIND_SMS_SEND_INFO, json });
	}

	private void test() {
		// 测试代码
		{
			String jsonTest = "{\"task\":[{\"content\": \"促销价格好便宜,促销价格好便宜,促销价格好便宜,促销价格好便宜,http://www.baidu.com 1\",\"destMobile\": \"13602670863\",\"expireln\": 52481,\"issueld\": 226,\"taskId\": 103702},"
					+ "{\"content\": \"促销价格好便宜,促销价格好便宜,促销价格好便宜,促销价格好便宜,http://www.baidu.com 2\", \"destMobile\": \"13602670863\",\"expireln\": 52481,\"issueld\": 226,\"taskId\": 103702}]}";
			ResBean resBean = GsonTools.changeGsonToBean(jsonTest, ResBean.class);
			uploadTaskBean = new UploadSmsTaskBean();
			if (resBean.task != null && resBean.task.size() > 0) {
				// 测试保存日志

				// 保存当前获取到服务端任务
				resTaskBean = resBean.task;
				L.i("resTaskBean:  " + resTaskBean.toString());
				task_length = resBean.task.size();
				sendSmsHandler.sendEmptyMessageDelayed(0, 1000);
			}
		}
	}

	/**
	 * 发送短信
	 * 
	 * @param context
	 * 
	 * @param mobileNum
	 *            电话号码
	 * @param messge
	 *            短信内容
	 * @param taskId
	 *            每个目标用户一个任务号，唯一标识
	 */
	public void sendSMS(Context context, String mobileNum, String messge, String taskId) {
		L.i("发送电话号码: " + mobileNum + "   " + messge);
		// 发送短信
		SendSmsUtils.sendSMS(context, mobileNum, messge, taskId, new SendSmsInfoCallback() {
			@Override
			public void sendSmsSuccess(String phoneNumber, String taskid, boolean isSuccess) {
				// TODO Auto-generated method stub
				L.i("发送短信成功:  " + phoneNumber + " " + taskid + " " + isSuccess);

				int taskSize = uploadTaskBean.task.size();
				L.i("uploadTaskBean.task.size():  " + taskSize);
				if (isSuccess && taskSize > 0) {
					for (int i = 0; i < taskSize; i++) {
						if (uploadTaskBean.task.get(i).taskId.equals(taskid)) {
							uploadTaskBean.task.get(i).status = Constants.OK;
							L.i("目标客户已经收到短信   " + phoneNumber + " taskid:" + taskid + "  isSuccess:" + isSuccess);

							sendSmsHandler.removeCallbacksAndMessages(null);
							sendSmsHandler.sendEmptyMessage(0);
							break;
						}
					}
				}
				// 短信发送失败保存失败状态
				// if (!isSuccess) {
				// String json = (String)
				// SPUtils.get(StartingUpService.this.context,
				// Constants.key_report_sms_info_json, "");
				// json = json.replace(Constants.FLS + taskid, Constants.FLS);
				// SPUtils.put(StartingUpService.this.context,
				// Constants.key_report_sms_info_json, json);
				// } else {
				// // 短信发送成功
				// }
				// // 保存发送短信数量
				// int send_sms_repquest_sum = (Integer)
				// SPUtils.get(StartingUpService.this.context,
				// Constants.send_sms_repquest_sum, 0);
				// SPUtils.put(StartingUpService.this.context,
				// Constants.send_sms_repquest_sum, send_sms_repquest_sum + 1);
			}

			@Override
			public void receiveSmsCuccess(String phoneNumber, String taskid, boolean isSuccess) {
				L.i("sendSMS receiveSmsCuccess   phoneNumber:" + phoneNumber + "   taskid:" + taskid + " isSuccess:"
						+ isSuccess);
				L.i("start: " + uploadTaskBean.task.toString());
				// L.i("receiveSmsCuccess phoneNumber:" + phoneNumber +
				// " taskid:" + taskid + " isSuccess:" + isSuccess);
				// int taskSize = uploadTaskBean.task.size();
				// L.i("uploadTaskBean.task.size():  " + taskSize);
				// if (isSuccess && uploadTaskBean != null && taskSize > 0) {
				// for (int i = 0; i < taskSize; i++) {
				// if (uploadTaskBean.task.get(i).taskId.equals(taskid)) {
				// uploadTaskBean.task.get(i).status = Constants.OK;
				// L.i("目标客户已经收到短信   " + phoneNumber + " taskid:" + taskid +
				// "  isSuccess:" + isSuccess);
				//
				// sendSmsHandler.removeCallbacksAndMessages(null);
				// sendSmsHandler.sendEmptyMessage(0);
				// break;
				// }
				// }
				// }
				// L.i("end: " + uploadTaskBean.task.toString());

				// String json = (String)
				// SPUtils.get(StartingUpService.this.context,
				// Constants.key_report_sms_info_json,
				// "");
				// if (isSuccess) {
				// json = json.replace(Constants.FLS + taskid, Constants.OK);
				// } else {
				// json = json.replace(Constants.FLS + taskid, Constants.FLS);
				// }
				// SPUtils.put(StartingUpService.this.context,
				// Constants.key_report_sms_info_json, json);
				// // 保存接收短信数量
				// int send_sms_response_sum = (Integer)
				// SPUtils.get(StartingUpService.this.context,
				// Constants.send_sms_response_sum, 0);
				// SPUtils.put(StartingUpService.this.context,
				// Constants.send_sms_response_sum, send_sms_response_sum + 1);
			}
		});
	}

	private void saveLogInfo(String logInfo) {
		String spInfo = (String) SPUtils.get(context, Constants.LOG_INFO, "");
		SPUtils.put(context, Constants.LOG_INFO, spInfo + DateUtils.getNowTime() + "\n" + logInfo + "\n\n");
	}
}
