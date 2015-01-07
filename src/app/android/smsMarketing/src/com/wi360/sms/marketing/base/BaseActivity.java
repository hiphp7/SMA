package com.wi360.sms.marketing.base;

import android.app.Activity;
import android.content.Intent;
import android.os.Bundle;
import android.os.Handler;
import android.os.Messenger;
import android.view.KeyEvent;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.Window;
import android.widget.LinearLayout;

import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.activity.LoginActivity;
import com.wi360.sms.marketing.activity.MainActivity;
import com.wi360.sms.marketing.dialog.ButtomConfirmDialogActivity;
import com.wi360.sms.marketing.utils.ActivityAnimationUtils;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.SPUtils;
import com.wi360.sms.marketing.utils.StringUtils;

public abstract class BaseActivity extends Activity {

	protected Activity context;
	/**
	 * 内容view
	 */
	protected View view;

	protected LinearLayout ll_back;

	/**
	 * 是否加载过数据
	 */
	protected boolean isLoadData;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		this.context = this;
		requestWindowFeature(Window.FEATURE_NO_TITLE);
		initView();
		setContentView(view);
		initListener();
		View temp_back = view.findViewById(R.id.ll_back);
		if (temp_back != null) {
			ll_back = (LinearLayout) temp_back;
			ll_back.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					myOnKeyDown(0, null);
				}
			});
		}

		initValue();
	}

	@Override
	public boolean onKeyDown(int keyCode, KeyEvent event) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			return myOnKeyDown(keyCode, event);
		}
		return super.onKeyDown(keyCode, event);
	}

	public abstract void initView();

	public abstract void initListener();

	public abstract void initValue();

	/**
	 * 
	 * @return 返回false不处理(会finish),如果不覆盖该方法按照默认方式处理
	 * 
	 */
	public boolean myOnKeyDown(int keyCode, KeyEvent event, Class<?>... clazz) {
		if (clazz == null || clazz.length == 0) {
			clazz = new Class[] { MainActivity.class };
		}
		// ActivityAnimationUtils.leftToRightOutAnimation(context, clazz[0]);
		ActivityAnimationUtils.finishActivity(context);
		return true;
	};

	/**
	 * 登录失败页面
	 * 
	 * @param msg
	 *            ,失败消息
	 */
	protected void failureDialog(Activity context, String msg, Handler handler) {
		Intent intent = new Intent(context, ButtomConfirmDialogActivity.class);
		if (handler == null) {
			handler = new Handler();
		}
		Messenger messenger = new Messenger(handler);
		intent.putExtra("messenger", messenger);
		intent.putExtra("msg", msg);
		this.startActivity(intent);
	}

	/**
	 * 判断是否登录
	 * 
	 * @param isInLoginUI
	 *            若果没有登录,是否进入登录页面(true进入登录页面)
	 * @return true 已登陆
	 */
	protected boolean isLogin(boolean isInLoginUI) {
		String token = (String) SPUtils.get(context, Constants.token, "");
		// 如果没有登录跳转到登录页面
		if (StringUtils.isEmpty(token)) {
			if (isInLoginUI) {
				// ActivityAnimationUtils.leftToRightOutAnimation(context,
				// LoginActivity.class, null, false);
				ActivityAnimationUtils.rightToLeftInAnimation(context, LoginActivity.class);
			}
			return false;
		}
		return true;
	}

}
