package com.wi360.sms.marketing.activity;

import java.util.List;

import zrc.widget.ZrcListView;
import android.app.Activity;
import android.view.KeyEvent;
import android.view.View;
import android.view.ViewGroup;
import android.widget.TextView;

import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.activity.BackRecordActivity.MyAdapter;
import com.wi360.sms.marketing.base.BaseActivity;
import com.wi360.sms.marketing.base.MyAsyncTask;
import com.wi360.sms.marketing.base.MyBaseAdapter;
import com.wi360.sms.marketing.bean.GetNotesDescBean;
import com.wi360.sms.marketing.bean.ResBean;
import com.wi360.sms.marketing.bean.ResBean.IssueList;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.GsonTools;

/**
 * 回拨记录详情
 * 
 * @author Administrator
 * 
 */
public class BackRecordDescActivity extends BaseActivity {

	@ViewInject(R.id.txt_title)
	protected TextView txt_title;

	// @ViewInject(R.id.bt_submit)
	// private Button bt_delete;

	private ZrcListView listView;

	// 页码
	private int pageIndex = 1;
	// 每一页显示条数
	private int pageSize = 20;
	// 总记录
	private int listTotal;
	// 数据一共有多少页
	private int totalPage;

	private ResBean.IssueList bean;

	@Override
	public void initView() {
		view = View.inflate(context, R.layout.layout_back_record_desc, null);
		listView = (ZrcListView) view.findViewById(R.id.lv_content);
		ViewUtils.inject(this, view);
		txt_title.setText("回拨记录详情");
		bean = (IssueList) getIntent().getSerializableExtra("bean");
	}

	@Override
	public void initListener() {

	}

	@Override
	public void initValue() {
		loadNetWorkData(pageIndex, pageSize, bean.issueId, "加载中", true);
	}

	public void loadNetWorkData(int pageIndex, int pageSize, String issueId, String showLoadMsg, final boolean isRefresh) {
		GetNotesDescBean sendBean = new GetNotesDescBean(pageIndex, pageSize, issueId);
		String json = GsonTools.createGsonString(sendBean);
		new MyAsyncTask(context, "加载中") {

			@Override
			public String connectNetWorkSuccess(String...responseStr) {
				if (isLoadNetWorkSuccess()) {

				} else {

				}
				// 处理listview加载跟多,下拉刷新
				if (isLoadNetWorkSuccess()) {
					// 总记录
					listTotal = resBean.listTotal;
					// 总页数
					totalPage = (listTotal % BackRecordDescActivity.this.pageSize == 0) ? (listTotal / BackRecordDescActivity.this.pageSize)
							: (listTotal / BackRecordDescActivity.this.pageSize + 1);
					if (isRefresh) {
						listView.setRefreshSuccess("加载成功"); // 通知加载成功
						if (BackRecordDescActivity.this.listTotal > BackRecordDescActivity.this.pageSize) {
							listView.startLoadMore(); // 开启LoadingMore功能
						} else {
							listView.setRefreshFail("");
						}
					} else {// 加载更多
						listView.setLoadMoreSuccess();
					}

				} else {// 加载失败
					if (isRefresh) {// 下拉刷新
						listView.setRefreshFail("加载失败");
					} else {// 加载更多
						listView.stopLoadMore();
					}
				}
				return null;
			}

			@Override
			protected void onPostExecute(String msg) {
				if (isLoadNetWorkSuccess()) {
					if (adapter == null) {
						adapter = new MyAdapter(context, resBean.noteList);
						listView.setAdapter(adapter);
					} else {
						adapter.notifyDataSetChanged();
					}
				}
				super.onPostExecute(msg);
			}
		}.execute(new String[] { Constants.FIND_BACK_RESCORD_DESC_URL, json });
	}

	class MyAdapter extends MyBaseAdapter<ResBean.NoteList, View> {

		public MyAdapter(Activity context, List<ResBean.NoteList> lists) {
			super(context, lists);
		}

		class HolderView {
			public TextView tv_phone;
			public TextView tv_date;
			public TextView tv_desc;
		}

		private ResBean.NoteList tempBean;

		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			HolderView holder = null;
			if (convertView == null) {
				holder = new HolderView();
				convertView = View.inflate(context, R.layout.item_layout_back_record_desc, null);
				holder.tv_phone = (TextView) convertView.findViewById(R.id.tv_phone);
				holder.tv_date = (TextView) convertView.findViewById(R.id.tv_date);
				holder.tv_desc = (TextView) convertView.findViewById(R.id.tv_desc);
				convertView.setTag(holder);
			} else {
				holder = (HolderView) convertView.getTag();
			}
			tempBean = lists.get(position);
			holder.tv_phone.setText(tempBean.customerMobile);
			holder.tv_date.setText(tempBean.lastDate);
			holder.tv_desc.setText(tempBean.noteContent);

			return convertView;
		}

	}

	@Override
	public boolean myOnKeyDown(int keyCode, KeyEvent event, Class<?>... clazz) {
		return super.myOnKeyDown(keyCode, event, BackRecordActivity.class);
	}

}
