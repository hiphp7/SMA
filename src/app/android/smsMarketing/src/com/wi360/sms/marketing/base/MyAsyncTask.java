package com.wi360.sms.marketing.base;

import java.net.URLConnection;

import android.app.Activity;
import android.app.Dialog;
import android.content.Context;
import android.os.AsyncTask;
import android.widget.Toast;

import com.wi360.sms.marketing.bean.ResBean;
import com.wi360.sms.marketing.dialog.LoadDialog;
import com.wi360.sms.marketing.interfaces.MyResponseCallBack;
import com.wi360.sms.marketing.utils.CommonUtil;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.GsonTools;
import com.wi360.sms.marketing.utils.L;
import com.wi360.sms.marketing.utils.SPUtils;
import com.wi360.sms.marketing.utils.StringUtils;

public abstract class MyAsyncTask extends AsyncTask<String, String, String> {
	protected Activity context;

	private Context serviceContext;
	/**
	 * 显示加载数据dialog
	 */
	public Dialog loadDialog;
	private String loadMsg;
	/**
	 * 提示信息
	 */
	public String responseShowMsg;
	protected ResBean resBean;

	protected int statusCode;

	protected MyBaseAdapter adapter;

	/**
	 * @params:-1,onFailure
	 * 
	 * @params:0,onSuccess
	 * @params:1,onSuccess,拿到数据错误
	 */
	// 判断是否有网络,默认有网络
	protected boolean isNetsWork = true;

	public MyAsyncTask(Activity context, String loadMsg) {
		this.context = context;
		this.loadMsg = loadMsg;
	}

	public MyAsyncTask(Context serviceContext, String loadMsg) {
		this.serviceContext = serviceContext;
		this.loadMsg = loadMsg;
	}

	@Override
	protected void onPreExecute() {
		// 解决在service中调用 所以判断context是否等于null
		if (context != null) {
			isNetsWork = true;
			if (CommonUtil.isNetworkAvailable((Context) context) == 0) {// 没有网络
				isNetsWork = false;
			}
			// 有网络,有加载数据信息 就显示dialog
			if (isNetsWork && loadMsg != null) {
				loadDialog = LoadDialog.createLoadingDialog(context, loadMsg + "...");
				if (!loadDialog.isShowing()) {
					loadDialog.show();
				}
			}
		}
	}

	/**
	 * @params[0]:url
	 * @params[1]:json
	 * @return 发送成功返回null,发送失败返回失败信息
	 */
	@Override
	protected String doInBackground(String... params) {
		// 有网络
		if (isNetsWork) {
			HttpUtils.sendPost(params[0], params[1], new MyResponseCallBack() {
				@Override
				public void onSuccess(int statusCode, String responseInfo) {
					MyAsyncTask.this.statusCode = statusCode;
					resBean = GsonTools.changeGsonToBean(responseInfo, ResBean.class);
					responseShowMsg = connectNetWorkSuccess(responseInfo);
				}

				@Override
				public void onFailure(Exception error, String msg) {
					L.e(MyAsyncTask.class.getName() + " onFailure " + msg + " : " + error);
					responseShowMsg = connectNetWorkFailure(error, msg);
				}

				@Override
				public void addCookie(URLConnection conn) {
					String token = null;
					String mobileNum = null;
					if (context != null) {
						token = (String) SPUtils.get(context, Constants.token, "");
						mobileNum = (String) SPUtils.get(context, Constants.mobileNum, "");
					} else {
						token = (String) SPUtils.get(serviceContext, Constants.token, "");
						mobileNum = (String) SPUtils.get(serviceContext, Constants.mobileNum, "");
					}
					// String token = "7b76ad63bdd86325072b664cbea34aa4";
					// String mobileNum = "18922260815";
					L.i("addCookie token: " + token);
					L.i("addCookie mobileNum: " + mobileNum);
					if (conn != null) {
						if (!StringUtils.isEmpty(token) && !StringUtils.isEmpty(mobileNum)) {
							conn.setRequestProperty("Cookie", "token=" + token + "; mobileNum=" + mobileNum);
						}
					}
				}
			});
		} else {
			responseShowMsg = "请检查网络!";
		}
		return responseShowMsg;
	}

	/**
	 * 失败的时候调用,弹出失败信息,msg:失败信息,msg==null不弹出失败框
	 */
	@Override
	protected void onPostExecute(String msg) {
		if (context != null) {
			// dismiss加载数据dialog
			if (loadDialog != null && loadDialog.isShowing()) {
				loadDialog.dismiss();
			}
			if (msg != null) {
				Toast.makeText(context, msg, Toast.LENGTH_SHORT).show();
			}
		}

	}

	/**
	 * 判断网络数据加载是否成功
	 * 
	 * @return
	 */
	protected boolean isLoadNetWorkSuccess() {
		return resBean != null && resBean.errcode == 0;
	}

	/**
	 * 连接网络成功,调用该方法
	 * 
	 * @param responseInfo
	 */
	public abstract String connectNetWorkSuccess(String... responseStr);

	/**
	 * 连接网络失败,调用该方法 如果需要处理onFailure的失败信息,重写该方法即可
	 * 
	 * @param responseInfo
	 * @param msg
	 */
	public String connectNetWorkFailure(Exception error, String msg) {
		return msg;
	}

}
