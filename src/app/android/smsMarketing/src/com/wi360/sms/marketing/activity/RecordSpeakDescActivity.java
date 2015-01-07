package com.wi360.sms.marketing.activity;

import android.app.Activity;
import android.os.Handler;
import android.os.SystemClock;
import android.view.KeyEvent;
import android.view.View;
import android.widget.EditText;
import android.widget.RadioGroup;
import android.widget.RadioGroup.OnCheckedChangeListener;
import android.widget.TextView;

import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.lidroid.xutils.view.annotation.event.OnClick;
import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.base.BaseActivity;
import com.wi360.sms.marketing.base.MyAsyncTask;
import com.wi360.sms.marketing.bean.GetNotesDescBean12;
import com.wi360.sms.marketing.bean.ResBean;
import com.wi360.sms.marketing.bean.ResBean.Lists;
import com.wi360.sms.marketing.bean.UpdateVisitUserBean;
import com.wi360.sms.marketing.utils.ActivityAnimationUtils;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.GsonTools;
import com.wi360.sms.marketing.utils.L;
import com.wi360.sms.marketing.utils.StringUtils;
import com.wi360.sms.marketing.utils.UIUtils;

/**
 * 记录通话详情
 * 
 * @author Administrator
 * 
 */
public class RecordSpeakDescActivity extends BaseActivity {

	@ViewInject(R.id.txt_title)
	protected TextView txt_title;

	@ViewInject(R.id.et_nickname)
	protected EditText et_nickname;

	@ViewInject(R.id.et_content)
	protected EditText et_content;

	@ViewInject(R.id.tv_phone)
	private TextView tv_phone;

	// @ViewInject(R.id.sex)
	// protected RadioGroup rg_sex;

	// private String sex;

	private ResBean.Lists bean;

	@Override
	public void initView() {
		view = View.inflate(context, R.layout.layout_record_speak_desc, null);
		ViewUtils.inject(this, view);
		txt_title.setText("记录通话详情");
		bean = (Lists) getIntent().getSerializableExtra("bean");
		// tv_phone.setText(bean.customerMobile);
		// if (!StringUtils.isEmpty(bean.record)) {
		// et_content.setText(bean.record);
		// }
		// if (!StringUtils.isEmpty(bean.customerCall)) {
		// et_nickname.setText(bean.customerCall);
		// }
	}

	@Override
	public void initValue() {
		// et_nickname.setText(bean.)
		GetNotesDescBean12 sendBean = new GetNotesDescBean12(bean.customerMobile, bean.issueId);
		String sendJson = GsonTools.createGsonString(sendBean);
		new MyAsyncTask(context, "加载中") {
			@Override
			public String connectNetWorkSuccess(String... responseStr) {
				return null;
			}

			@Override
			protected void onPostExecute(String msg) {
				if (isLoadNetWorkSuccess()) {
					tv_phone.setText(resBean.customerMobile);
					et_content.setText(resBean.noteContent);
					et_nickname.setText(resBean.call);
				}
				super.onPostExecute(msg);
			}
		}.execute(new String[] { Constants.FIND_BACK_RESCORD_DESC_URL12, sendJson });

	}

	@Override
	public void initListener() {

	}

	private void loadNetsWorkData() {
		String phone = tv_phone.getText().toString().trim();
		String name = et_nickname.getText().toString().trim();
		String content = et_content.getText().toString().trim();
		if (StringUtils.isEmpty(name)) {
			UIUtils.showToast(context, "请填充称呼");
			return;
		}
		// if (StringUtils.isEmpty(sex)) {
		// UIUtils.showToast(context, "请选择性别");
		// return;
		// }
		if (StringUtils.isEmpty(content)) {
			UIUtils.showToast(context, "请填写内容");
			return;
		}

		UpdateVisitUserBean sendBean = new UpdateVisitUserBean(bean.issueId, phone, content, name);
		String json = GsonTools.createGsonString(sendBean);

		new MyAsyncTask(context, "保存中") {

			@Override
			public String connectNetWorkSuccess(String... responseStr) {
				if (isLoadNetWorkSuccess()) {
					// UIUtils.showToast(context, resBean.errmsg);

				} else {
					UIUtils.showToast(context, resBean.errmsg);
				}
				return null;
			}

			@Override
			protected void onPostExecute(String msg) {
				super.onPostExecute(msg);
				if (isLoadNetWorkSuccess()) {
					// failureDialog(context, resBean.errmsg, null);
					// et_nickname.setText("");
					// et_content.setText("");
					UIUtils.showToast(context, resBean.errmsg);
					// RecordSpeakDescActivity.this.finish();
					ActivityAnimationUtils.finishActivity(context);

					Thread thread = new Thread(new Runnable() {

						@Override
						public void run() {
							// TODO Auto-generated method stub

						}
					});
					try {
						thread.join();
					} catch (InterruptedException e) {
						e.printStackTrace();
					}
				}
			}
		}.execute(new String[] { Constants.SAVE_RECORD_DESC_URL, json });

	}

	@OnClick({ R.id.bt_submit })
	public void myOnCheck(View v) {
		switch (v.getId()) {
		case R.id.bt_submit:// 保存记录详情信息
			loadNetsWorkData();
			break;

		default:
			break;
		}
	}

	@Override
	public boolean myOnKeyDown(int keyCode, KeyEvent event, Class<?>... clazz) {
		// return super.myOnKeyDown(keyCode, event,
		// PotentialUserActivity.class);
		ActivityAnimationUtils.finishActivity(RecordSpeakDescActivity.this);
		return true;
	}

}
