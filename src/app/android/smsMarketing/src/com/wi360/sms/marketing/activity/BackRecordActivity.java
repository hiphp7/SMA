package com.wi360.sms.marketing.activity;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.List;

import zrc.widget.SimpleFooter;
import zrc.widget.SimpleHeader;
import zrc.widget.ZrcListView;
import zrc.widget.ZrcListView.OnStartListener;
import android.app.Activity;
import android.os.Handler;
import android.os.Looper;
import android.os.Message;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.TextView;

import com.lidroid.xutils.ViewUtils;
import com.lidroid.xutils.view.annotation.ViewInject;
import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.base.BaseActivity;
import com.wi360.sms.marketing.base.MyAsyncTask;
import com.wi360.sms.marketing.base.MyBaseAdapter;
import com.wi360.sms.marketing.bean.FindNotesReleaseNumberListBean;
import com.wi360.sms.marketing.bean.ResBean;
import com.wi360.sms.marketing.utils.ActivityAnimationUtils;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.GsonTools;
import com.wi360.sms.marketing.utils.StringUtils;

/**
 * 10 查询有笔记的发布号列表
 * 
 * @author Administrator
 * 
 */
public class BackRecordActivity extends BaseActivity {

	@ViewInject(R.id.txt_title)
	private TextView txt_title;

	@ViewInject(R.id.lv_content)
	private ZrcListView listView;

	// 页码
	private int pageIndex = 1;
	// 每一页显示条数
	private int pageSize = 20;
	// 总记录
	private int listTotal;
	// 数据一共有多少页
	private int totalPage;

	private Button txt_error;

	private FrameLayout frame_error;

	@Override
	public void initView() {
		view = View.inflate(context, R.layout.layout_back_record, null);
		ViewUtils.inject(this, view);
		txt_title.setText("回拨记录");
		txt_error = (Button) view.findViewById(R.id.txt_error);
		frame_error = (FrameLayout) view.findViewById(R.id.error_view);

		// 设置下拉刷新的样式（可选，但如果没有Header则无法下拉刷新）
		SimpleHeader header = new SimpleHeader(this);
		header.setTextColor(0xff0066aa);
		header.setCircleColor(0xff33bbee);
		listView.setHeadable(header);

		// 设置加载更多的样式（可选）
		SimpleFooter footer = new SimpleFooter(this);
		footer.setCircleColor(0xff33bbee);
		listView.setFootable(footer);

		// 设置列表项出现动画（可选）
		listView.setItemAnimForTopIn(R.anim.topitem_in);
		listView.setItemAnimForBottomIn(R.anim.bottomitem_in);

		// 下拉刷新事件回调（可选）
		listView.setOnRefreshStartListener(new OnStartListener() {
			@Override
			public void onStart() {
				// refresh();
				pageIndex = 1;
				loadNetWorkData(pageIndex, pageSize, null, true);
			}
		});

		// 加载更多事件回调（可选）
		listView.setOnLoadMoreStartListener(new OnStartListener() {
			@Override
			public void onStart() {
				// loadMore();

			}
		});
	}

	@Override
	public void initListener() {

	}

	/**
	 * 10 查询有笔记的发布号列表
	 */
	@Override
	public void initValue() {
		loadNetWorkData(pageIndex, pageSize, "加载中", true);
	}

	private void loadNetWorkData(int pageIndex, int pageSize, String showLoadMsg, final boolean isRefresh) {
		FindNotesReleaseNumberListBean sendBean = new FindNotesReleaseNumberListBean(pageIndex, pageSize);
		String json = GsonTools.createGsonString(sendBean);
		new MyAsyncTask(context, showLoadMsg) {

			@Override
			public String connectNetWorkSuccess(String...responseStr) {
				// 处理listview加载跟多,下拉刷新
				if (isLoadNetWorkSuccess()) {
					// 总记录
					listTotal = resBean.listTotal;
					// 总页数
					totalPage = (listTotal % BackRecordActivity.this.pageSize == 0) ? (listTotal / BackRecordActivity.this.pageSize)
							: (listTotal / BackRecordActivity.this.pageSize + 1);
					if (isRefresh) {
						listView.setRefreshSuccess("加载成功"); // 通知加载成功
						if (BackRecordActivity.this.listTotal > BackRecordActivity.this.pageSize) {
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
						adapter = new MyAdapter(context, resBean.issueList);
						listView.setAdapter(adapter);
					} else {
						adapter.notifyDataSetChanged();
					}

					// 判断是否显示错误页面
					if (isLoadNetWorkSuccess()) {
						if (resBean.issueList == null || resBean.issueList.size() < 1) {
							frame_error.setVisibility(View.VISIBLE);
							txt_error.setText("当前没有回报记录");
							listView.setVisibility(View.INVISIBLE);
						}else{
							frame_error.setVisibility(View.INVISIBLE);
							listView.setVisibility(View.VISIBLE);
						}
					} else {
						frame_error.setVisibility(View.VISIBLE);
						listView.setVisibility(View.INVISIBLE);
					}
				}
				super.onPostExecute(msg);
			}
		}.execute(new String[] { Constants.FIND_NOTES_LIST_URL, json });

	}

	class MyAdapter extends MyBaseAdapter<ResBean.IssueList, View> {

		public MyAdapter(Activity context, List<ResBean.IssueList> lists) {
			super(context, lists);
		}

		class HolderView {
			public TextView tv_title;
			public TextView tv_noteCounter;
		}

		@Override
		public View getView(final int position, View convertView, ViewGroup parent) {
			HolderView holder = null;
			if (convertView == null) {
				holder = new HolderView();
				convertView = View.inflate(context, R.layout.item_layout_back_record_list, null);
				holder.tv_title = (TextView) convertView.findViewById(R.id.tv_title);
				holder.tv_noteCounter = (TextView) convertView.findViewById(R.id.tv_noteCounter);
				convertView.setTag(holder);
			} else {
				holder = (HolderView) convertView.getTag();
			}
			holder.tv_title.setText(lists.get(position).title);
			holder.tv_noteCounter.setText(lists.get(position).noteCounter + "");

			convertView.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					ActivityAnimationUtils.rightToLeftInAnimation(context, BackRecordDescActivity.class,
							lists.get(position));
				}
			});
			return convertView;
		}

	}

}
