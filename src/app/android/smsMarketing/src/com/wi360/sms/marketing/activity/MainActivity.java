package com.wi360.sms.marketing.activity;

import java.util.Date;

import android.content.Intent;
import android.view.KeyEvent;
import android.view.MotionEvent;
import android.view.View;
import android.view.View.OnTouchListener;
import android.view.animation.Animation;
import android.view.animation.Animation.AnimationListener;
import android.view.animation.ScaleAnimation;
import android.widget.ImageView;
import android.widget.LinearLayout;
import android.widget.RadioGroup;
import android.widget.TextView;
import android.widget.Toast;

import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.lidroid.xutils.view.annotation.event.OnClick;
import com.umeng.analytics.MobclickAgent;
import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.base.BaseActivity;
import com.wi360.sms.marketing.base.MyAsyncTask;
import com.wi360.sms.marketing.service.StartingUpService;
import com.wi360.sms.marketing.utils.ActivityAnimationUtils;
import com.wi360.sms.marketing.utils.AppUtils;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.DateUtils;
import com.wi360.sms.marketing.utils.L;

public class MainActivity extends BaseActivity {

	@ViewInject(R.id.main_radio)
	private RadioGroup radioGroup;

	@ViewInject(R.id.ib_refresh)
	private ImageView ib_refresh;

	@ViewInject(R.id.tv_date)
	private TextView tv_date;
	// 勤劳指数
	@ViewInject(R.id.tv_industryIndex)
	private TextView tv_industryIndex;
	// 勤劳指数
	@ViewInject(R.id.tv_beyondIndex)
	private TextView tv_beyondIndex;

	// 今天已发条数
	@ViewInject(R.id.tv_today_momentum)
	private TextView tv_today_momentum;
	// 昨天已发条数
	@ViewInject(R.id.tv_yestoday_momentum)
	private TextView tv_yestoday_momentum;
	// 待发条数
	@ViewInject(R.id.tv_remain_pool)
	private TextView tv_remainPool;

	// 潜在用户数量
	@ViewInject(R.id.tv_user_sum)
	private TextView tv_user_sum;

	@ViewInject(R.id.is_login_no)
	private LinearLayout ll_is_login_no;

	@ViewInject(R.id.is_login_yes)
	private LinearLayout ll_is_login_yes;

	private boolean isLoadWorkData;
	// 登陆后是否加载过网络数据
	private boolean isLoginLoadWorkData;

	@Override
	public void initView() {
		L.e("MainActivity onCreate initView");
		// 判断服务是否在运行状态
		if (!AppUtils.isServiceRunning(context, StartingUpService.class.getName())) {
			Intent myIntent = new Intent(context, StartingUpService.class);
			context.startService(myIntent);
		}
		view = View.inflate(context, R.layout.activity_main, null);
		ViewUtils.inject(this, view);
		tv_date.setText(DateUtils.TimeStamp2Date(new Date().getTime(), "MM月dd日"));

	}

	@Override
	public void initValue() {
		// 加载网络数据
		loadNetsWork();

	}

	/**
	 * 加载网络数据
	 */
	private void loadNetsWork() {
		// 如果没有登录,不加载网络数据
		if (!isLogin(false)) {
			// 如果没有登录
			return;
		}
		isLoginLoadWorkData = true;
		String json = null;

		new MyAsyncTask(context, "加载中") {
			@Override
			public String connectNetWorkSuccess(String... responseStr) {
				// 如果加载网络数据成功
				if (isLoadNetWorkSuccess()) {

				} else {

				}
				return null;
			}

			protected void onPostExecute(String msg) {
				super.onPostExecute(msg);
				if (isLoadNetWorkSuccess()) {
					isLoadWorkData = true;
					tv_industryIndex.setText("勤劳指数 " + resBean.industryIndex);
					tv_beyondIndex.setText("已经超越 " + resBean.beyondIndex + " 同行");
					ll_is_login_no.setVisibility(View.INVISIBLE);
					ll_is_login_yes.setVisibility(View.VISIBLE);
					tv_today_momentum.setText("今天已发 : " + resBean.taskInfo.today);
					tv_yestoday_momentum.setText("昨天已发 : " + resBean.taskInfo.yestoday);
					tv_remainPool.setText("待发条数 : " + resBean.taskInfo.remainPool);
					L.i(resBean.taskInfo.today);
					L.i(resBean.taskInfo.yestoday);
					L.i(resBean.taskInfo.remainPool);
				}

				// 调用获取潜在用户数量接口
				getPotentialUserNetsWorkData();
			}

		}.execute(Constants.FIND_INFO_URL, json);
	}

	/**
	 * 获取潜在客户数据
	 */
	private void getPotentialUserNetsWorkData() {
		String json = null;
		new MyAsyncTask(context, null) {

			@Override
			public String connectNetWorkSuccess(String... responseStr) {
				if (isLoadNetWorkSuccess()) {

				} else {

				}
				return null;
			}

			protected void onPostExecute(String msg) {
				super.onPostExecute(msg);
				if (isLoadNetWorkSuccess()) {
					if (Integer.valueOf(resBean.unreadCustomer) > 0) {
						tv_user_sum.setText(resBean.unreadCustomer);
						tv_user_sum.setVisibility(View.VISIBLE);
					} else {
						tv_user_sum.setVisibility(View.INVISIBLE);
					}
				} else {

				}

				// tv_user_sum.setText(100 + "");
				// tv_user_sum.setVisibility(View.VISIBLE);
			};
		}.execute(new String[] { Constants.FIND_POTENTIAL_USER_URL, json });

	};

	@Override
	public void initListener() {
		ib_refresh.setOnTouchListener(new OnTouchListener() {
			@Override
			public boolean onTouch(View v, MotionEvent event) {
				ScaleAnimation scaleAnimation = null;
				switch (event.getAction()) {
				case MotionEvent.ACTION_DOWN:
					scaleAnimation = new ScaleAnimation(1.0f, 1.06f, 1.0f, 1.06f, Animation.RELATIVE_TO_SELF, 0.5f,
							Animation.RELATIVE_TO_SELF, 0.5f);
					scaleAnimation.setDuration(100);
					scaleAnimation.setFillAfter(true);
					ib_refresh.startAnimation(scaleAnimation);
					break;
				case MotionEvent.ACTION_UP:
					scaleAnimation = new ScaleAnimation(1.0f, 0.96f, 1.0f, 0.96f, Animation.RELATIVE_TO_SELF, 0.5f,
							Animation.RELATIVE_TO_SELF, 0.5f);
					scaleAnimation.setDuration(100);
					scaleAnimation.setFillAfter(true);
					scaleAnimation.setAnimationListener(new AnimationListener() {
						@Override
						public void onAnimationStart(Animation animation) {
						}

						@Override
						public void onAnimationRepeat(Animation animation) {
						}

						@Override
						public void onAnimationEnd(Animation animation) {
							if (isLogin(true)) {
								// 若果已登录,加载网络数据
								loadNetsWork();
							}
						}
					});
					ib_refresh.startAnimation(scaleAnimation);
					break;
				}

				return true;
			}
		});
	}

	@OnClick({ R.id.rb_potential_user, R.id.rb_back_record, R.id.rb_my })
	public void myOnclick(View v) {
		// 如果没有登录,不加载网络数据
		if (!isLogin(true)) {
			// 如果没有登录
			return;
		}
		if (v != null) {
			switch (v.getId()) {
			case R.id.rb_potential_user:// 潜在用户
				ActivityAnimationUtils.rightToLeftInAnimation(context, PotentialUserActivity.class);
				break;
			case R.id.rb_back_record:// 回拨记录
				ActivityAnimationUtils.rightToLeftInAnimation(context, BackRecordActivity.class);
				break;
			case R.id.rb_my:// 我的
				ActivityAnimationUtils.rightToLeftInAnimation(context, MyActivity.class);
				break;
			// case R.id.is_login_yes:// 推广
			// ActivityAnimationUtils.rightToLeftInAnimation(context,
			// WaitExtensionActivity.class);
			// break;
			}
		}
	}

	private long currentTime = 0;

	@Override
	public boolean myOnKeyDown(int keyCode, KeyEvent event, Class<?>... clazz) {
		if (keyCode == KeyEvent.KEYCODE_BACK) {
			if (currentTime > 0) {
				// finish();
				ActivityAnimationUtils.finishActivity(context);
				return true;
			}
			currentTime = System.currentTimeMillis();
			new Thread() {
				@Override
				public void run() {
					try {
						Thread.sleep(3000);
						currentTime = 0;
					} catch (InterruptedException e) {
						e.printStackTrace();
					}
				}
			}.start();
			Toast.makeText(this, "再点一次退出", 0).show();
		}
		return true;
	}

	@Override
	protected void onStart() {
		super.onStart();
		if (!isLogin(false)) {// 没有登录
			ll_is_login_no.setVisibility(View.VISIBLE);
			ll_is_login_yes.setVisibility(View.INVISIBLE);
			tv_industryIndex.setText("");
			tv_beyondIndex.setText("");
		} else {// 已经登陆
			ll_is_login_no.setVisibility(View.INVISIBLE);
			ll_is_login_yes.setVisibility(View.VISIBLE);
			if (!isLoginLoadWorkData) {
				isLoginLoadWorkData = true;
				loadNetsWork();
			} else {
				getPotentialUserNetsWorkData();
			}

		}
	}

	public void onResume() {
		super.onResume();
		MobclickAgent.onResume(this);
	}

	public void onPause() {
		super.onPause();
		MobclickAgent.onPause(this);
	}
}
