package com.wi360.sms.marketing.activity;

import android.view.View;
import android.widget.LinearLayout;
import android.widget.ScrollView;
import android.widget.TextView;

import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.base.BaseActivity;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.SPUtils;

public class LogActivity extends BaseActivity {
	String log_info;
	@Override
	public void initView() {
		log_info = "";
		ScrollView sv = (ScrollView) View.inflate(context, R.layout.log_layout, null);
		TextView tv = (TextView) sv.findViewById(R.id.tv_log);
		log_info = (String) SPUtils.get(context, Constants.LOG_INFO, "没有日志");
		tv.setText(log_info);
		view = sv;
	}

	@Override
	public void initListener() {

	}

	@Override
	public void initValue() {

	}

}
