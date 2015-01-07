package com.wi360.sms.marketing.activity;

import java.util.Date;

import android.app.Dialog;
import android.content.Intent;
import android.os.Handler;
import android.os.SystemClock;
import android.view.KeyEvent;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.HorizontalScrollView;
import android.widget.TextView;

import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.lidroid.xutils.view.annotation.event.OnClick;
import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.base.BaseActivity;
import com.wi360.sms.marketing.base.MyAsyncTask;
import com.wi360.sms.marketing.bean.LoginBean;
import com.wi360.sms.marketing.bean.ResBean;
import com.wi360.sms.marketing.bean.SMSLoginBean;
import com.wi360.sms.marketing.receiver.Alarmreceiver;
import com.wi360.sms.marketing.utils.ActivityAnimationUtils;
import com.wi360.sms.marketing.utils.CheckUtils;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.GsonTools;
import com.wi360.sms.marketing.utils.L;
import com.wi360.sms.marketing.utils.SPUtils;
import com.wi360.sms.marketing.utils.SharedPreferencesUtils;
import com.wi360.sms.marketing.utils.StringUtils;

/**
 * 登录
 * 
 * @author Administrator
 * 
 */
public class LoginActivity extends BaseActivity {
	@ViewInject(R.id.et_phone_number)
	private EditText et_phone_number;

	@ViewInject(R.id.et_sms_code)
	private EditText et_sms_code;

	@ViewInject(R.id.bt_get_code)
	private Button bt_get_code;

	@ViewInject(R.id.bt_submit)
	private Button bt_submit;

	@ViewInject(R.id.txt_title)
	private TextView txt_title;

	private Long currentTime;
	private boolean isSendSmsCode;

	private Dialog loadDialog;

	private Handler handler = new Handler() {
		public void handleMessage(android.os.Message msg) {
			// 验证码发送时间
			Long smsCodeTime = (Long) SPUtils.get(context, Constants.key_end_send_login_coed_time, new Long(0));
			currentTime = new Date().getTime();
			if ((currentTime - smsCodeTime) < 60 * 1000) {
				bt_get_code.setText((60 - (currentTime - smsCodeTime) / 1000) + "");
				if (bt_get_code.isEnabled()) {
					bt_get_code.setEnabled(false);
				}
				if (!bt_submit.isEnabled()) {
					bt_submit.setEnabled(true);
				}
				this.sendEmptyMessageDelayed(1, 1000);
				return;
			}

			bt_get_code.setEnabled(true);
			bt_get_code.setText("获取验证码");
			handler.removeCallbacksAndMessages(null);
		};
	};

	@Override
	public void initView() {
		view = View.inflate(context, R.layout.layout_login, null);
		ViewUtils.inject(this, view);
		bt_submit.setEnabled(false);
		txt_title.setText("手机号码登录");

	}

	@Override
	public void initValue() {
		handler.removeCallbacksAndMessages(null);
		handler.sendEmptyMessageDelayed(1, 1000);
	}

	/**
	 * 点击清除手机号码
	 * 
	 * @param v
	 */
	@OnClick({ R.id.bt_get_code, R.id.bt_submit })
	public void myOnClick(View v) {
		switch (v.getId()) {
		case R.id.bt_get_code:
			getCodeCheck();
			break;
		case R.id.bt_submit:
			loginCheck();
			break;

		}

	}

	private void loginCheck() {
		// UIUtils.showToast(context, "登录");

		String phone = et_phone_number.getText().toString().trim();
		String smsCode = et_sms_code.getText().toString().trim();
		if (!CheckUtils.checkMobileNO(this, phone)) {
			return;
		}
		if (StringUtils.isEmpty(smsCode)) {
			failureDialog(context, "请输入验证码", null);
			return;
		}
		// 是否发送验证码
		if (!isSendSmsCode) {
			failureDialog(context, "请先发送验证码", null);
			return;
		}
		// {
		// long smsCodeTime = (Long) SPUtils.get(context,
		// Constants.key_end_send_login_coed_time, new Long(0));
		// long currentTime = new Date().getTime();
		// if ((currentTime - smsCodeTime) < 90 * 1000) {
		// failureDialog(context, "发送验证码后,90秒内不能重复发送", null);
		// return;
		// }
		// }
		LoginBean loginBean = new LoginBean(context, phone, smsCode);
		String json = GsonTools.createGsonString(loginBean);
		loginSendNetsWork(json);

	}

	private void loginSendNetsWork(String json) {
		new MyAsyncTask(context, "登录中") {

			@Override
			public String connectNetWorkSuccess(String... responseStr) {
				if (resBean != null && resBean.errcode == 0) {

					SPUtils.put(context, Constants.token, resBean.token);
					SPUtils.put(context, Constants.mobileNum, resBean.mobileNum);
					SPUtils.put(context, Constants.name, resBean.name);

					// 还原发送验证码状态
					isSendSmsCode = false;
				} else {
					responseShowMsg = resBean.errmsg;
				}
				return responseShowMsg;
			}

			protected void onPostExecute(String msg) {
				super.onPostExecute(msg);
				if (resBean != null && resBean.errcode == 0) {
					Intent intent = new Intent(context, Alarmreceiver.class);
					intent.setAction("repeating");
					context.sendBroadcast(intent);
					// 登陆成功
					ActivityAnimationUtils.leftToRightOutAnimation(context, MainActivity.class);
					// LoginActivity.this.finish();

				}
			};
		}.execute(new String[] { Constants.LOGIN_URL, json });
	}

	/**
	 * 获取验证码
	 */
	public void getCodeCheck() {
		String phone = et_phone_number.getText().toString().trim();
		// 如果没有登录,跳转到登录页面
		// if (StringUtils.isEmpty(phone)) {
		// ActivityAnimationUtils.rightToLeftInAnimation(this,
		// LoginActivity.class);
		// }
		if (!CheckUtils.checkMobileNO(this, phone)) {
			return;
		}

		// 获取上一次发送验证码时间,60秒内不能再重复发送验证码
		{
			Long smsCodeTime = (Long) SPUtils.get(context, Constants.key_end_send_login_coed_time, new Long(0));
			currentTime = new Date().getTime();
			if ((currentTime - smsCodeTime) < 60 * 1000) {
				failureDialog(context, "60秒类不能重复发送验证码", null);
				return;
			}

		}
		SMSLoginBean smsBean = new SMSLoginBean(context, phone);
		String json = GsonTools.createGsonString(smsBean);
		sendSmsCodeNotswork(json);

	}

	private void sendSmsCodeNotswork(String json) {
		new MyAsyncTask(context, "发送中") {
			@Override
			public String connectNetWorkSuccess(String... responseStr) {
				if (resBean.errcode == 0) {
					SPUtils.put(context, Constants.key_end_send_login_coed_time, new Long(new Date().getTime()));
					// 保存发送短信验证码状态
					isSendSmsCode = true;
					responseShowMsg = resBean.errmsg;
				} else {
					responseShowMsg = resBean.errmsg;
				}
				return responseShowMsg;
			}

			protected void onPostExecute(String msg) {
				if (resBean != null && resBean.errcode == 0) {
					bt_submit.setEnabled(true);
					handler.sendEmptyMessageDelayed(0, 1000);
				}
				super.onPostExecute(msg);
			};
		}.execute(Constants.SEND_LOGIN_SMS_CODE_URL, json);
		// }.execute(Constants.FIND_INFO_URL, json);
	}

	@Override
	public void initListener() {

	}

	@Override
	public boolean myOnKeyDown(int keyCode, KeyEvent event, Class<?>... clazz) {
		// return super.myOnKeyDown(keyCode, event, MyActivity.class);
		ActivityAnimationUtils.finishActivity(LoginActivity.this);
		return true;
	}

	@Override
	protected void onDestroy() {
		handler.removeCallbacksAndMessages(null);
		L.e(LoginActivity.class.getName() + "___" + "onDestroy");
		super.onDestroy();
	}
}
