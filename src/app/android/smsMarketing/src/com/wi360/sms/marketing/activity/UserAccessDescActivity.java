package com.wi360.sms.marketing.activity;

import java.util.ArrayList;
import java.util.List;

import android.R.color;
import android.app.Activity;
import android.view.KeyEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ListView;
import android.widget.TextView;

import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.base.BaseActivity;
import com.wi360.sms.marketing.base.MyAsyncTask;
import com.wi360.sms.marketing.base.MyBaseAdapter;
import com.wi360.sms.marketing.bean.GetPotentialUserDescBean;
import com.wi360.sms.marketing.bean.ResBean;
import com.wi360.sms.marketing.bean.ResBean.Lists;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.GsonTools;

/**
 * 客户访问详情
 * 
 * @author Administrator
 * 
 */
public class UserAccessDescActivity extends BaseActivity {

	@ViewInject(R.id.txt_title)
	protected TextView txt_title;

	@ViewInject(R.id.tv_title)
	protected TextView tv_title;

	@ViewInject(R.id.tv_content)
	private TextView tv_content;

	@ViewInject(R.id.lv_visit_date)
	private ListView lv_visit_date;

	private ResBean.Lists bean;

	@Override
	public void initView() {
		view = View.inflate(context, R.layout.layout_user_access_desc, null);
		ViewUtils.inject(this, view);
		lv_visit_date.setDivider(null);
		txt_title.setText("客户访问详情");

		bean = (Lists) getIntent().getSerializableExtra("bean");

	}

	@Override
	public void initListener() {

	}

	/**
	 * 8 获取潜在客户访问详情接口
	 */
	@Override
	public void initValue() {
		GetPotentialUserDescBean sendBean = new GetPotentialUserDescBean(bean.issueId, bean.customerMobile);
		String json = GsonTools.createGsonString(sendBean);
		new MyAsyncTask(context, "加载中") {
			@Override
			public String connectNetWorkSuccess(String...responseStr) {
				if (isLoadNetWorkSuccess()) {
					List<Lists> lists = new ArrayList<ResBean.Lists>();
					lists.addAll(resBean.list);
					adapter = new MyAdapter(context, lists);
				} else {

				}

				return null;
			}

			@Override
			protected void onPostExecute(String msg) {
				super.onPostExecute(msg);
				tv_title.setText(resBean.title);
				tv_content.setText(resBean.content);
				lv_visit_date.setAdapter(adapter);
			}
		}.execute(new String[] { Constants.FIND_POTENTIAL_USER_DESC_URL, json });
	}

	class MyAdapter extends MyBaseAdapter<Lists, View> {
		private TextView tv_data;

		public MyAdapter(Activity context, List<Lists> lists) {
			super(context, lists);
		}

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			convertView = View.inflate(context, R.layout.item_layout_date, null);
			tv_data = (TextView) convertView.findViewById(R.id.tv_access_date);
			tv_data.setText(lists.get(position).visitTime);
			return convertView;
		}

	}

	@Override
	public boolean myOnKeyDown(int keyCode, KeyEvent event, Class<?>... clazz) {
		return super.myOnKeyDown(keyCode, event, PotentialUserActivity.class);
	}

}
