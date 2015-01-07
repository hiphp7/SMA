package com.wi360.sms.marketing.activity;

import android.view.View;
import android.view.View.OnClickListener;
import android.widget.RelativeLayout;
import android.widget.TextView;

import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.lidroid.xutils.view.annotation.event.OnClick;
import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.base.BaseActivity;
import com.wi360.sms.marketing.utils.ActivityAnimationUtils;
import com.wi360.sms.marketing.utils.AppUtils;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.SPUtils;
import com.wi360.sms.marketing.utils.StringUtils;

/**
 * 回拨记录页面
 * 
 * @author Administrator
 * 
 */
public class MyActivity extends BaseActivity {

	@ViewInject(R.id.txt_title)
	private TextView txt_title;

	@ViewInject(R.id.tv_login)
	private TextView tv_login;

	@ViewInject(R.id.tv_name)
	private TextView tv_name;

	@ViewInject(R.id.tv_phone)
	private TextView tv_phone;

	@ViewInject(R.id.tv_version)
	private TextView tv_version;

	@ViewInject(R.id.rl_version_update)
	private RelativeLayout rl_version_update;

	@Override
	public void initView() {
		view = View.inflate(context, R.layout.layout_my, null);
		ViewUtils.inject(this, view);
		txt_title.setText("我的");

	}

	@Override
	public void initListener() {
		String token = (String) SPUtils.get(context, Constants.token, "");
		// if (!StringUtils.isEmpty(token)) {
		// tv_login.setVisibility(View.VISIBLE);
		// } else {
		// tv_login.setVisibility(View.INVISIBLE);
		// }
		tv_login.setVisibility(View.INVISIBLE);
		// // 点击退出登陆button
		// tv_login.setOnClickListener(new OnClickListener() {
		// @Override
		// public void onClick(View v) {
		//
		// }
		// });
	}

	@OnClick({ R.id.parent_login, R.id.rl_version_update, R.id.tv_login,R.id.ll_log })
	public void myOnClick(View v) {
		switch (v.getId()) {
		case R.id.parent_login:
			String token = (String) SPUtils.get(context, Constants.token, "");
			if (StringUtils.isEmpty(token)) {
				ActivityAnimationUtils.rightToLeftInAnimation(context, LoginActivity.class);
			}
			break;
		case R.id.rl_version_update:
			signOutLogin();
			break;
		case R.id.tv_login:
			signOutLogin();
			break;
		case R.id.ll_log:
			ActivityAnimationUtils.rightToLeftInAnimation(context, LogActivity.class);
			break;
		}
	}

	@Override
	public void initValue() {
		tv_version.setText(AppUtils.getVersionName(context));
		String name = (String) SPUtils.get(context, Constants.name, "");
		String phone = (String) SPUtils.get(context, Constants.mobileNum, "");
		String token = (String) SPUtils.get(context, Constants.token, "");
		if (!StringUtils.isEmpty(token)) {
			tv_name.setText(name);
			tv_phone.setText(phone);
		}
	}

	private void signOutLogin() {
		SPUtils.remove(context, Constants.token);
		SPUtils.remove(context, Constants.mobileNum);
		SPUtils.remove(context, Constants.name);

		tv_name.setText("用户名(点击登录)");
		tv_phone.setText("");
		tv_login.setVisibility(View.INVISIBLE);

//		finish();
		ActivityAnimationUtils.finishActivity(context);
	}
}
