package com.wi360.sms.marketing.activity;

import java.util.ArrayList;
import java.util.List;

import zrc.widget.SimpleFooter;
import zrc.widget.SimpleHeader;
import zrc.widget.ZrcListView;
import zrc.widget.ZrcListView.OnStartListener;
import android.app.Activity;
import android.content.Intent;
import android.net.Uri;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.FrameLayout;
import android.widget.ImageView;
import android.widget.TextView;

import com.wi360.sms.marketing.R;
import com.wi360.sms.marketing.base.BaseActivity;
import com.wi360.sms.marketing.base.MyAsyncTask;
import com.wi360.sms.marketing.base.MyBaseAdapter;
import com.wi360.sms.marketing.bean.GetPotentialUserBean;
import com.wi360.sms.marketing.bean.ResBean;
import com.wi360.sms.marketing.utils.ActivityAnimationUtils;
import com.wi360.sms.marketing.utils.Constants;
import com.wi360.sms.marketing.utils.GsonTools;

/**
 * 潜在客户页面
 * 
 * @author Administrator
 * 
 */
public class PotentialUserActivity extends BaseActivity {

	protected TextView txt_title;

	private ZrcListView lvContent;

	List<ResBean.Lists> datas = null;
	// 每页记录数
	private int pageSize = 20;
	// 页码
	private int pageIndex = 1;
	// 记录总数
	private int listTotal = 0;
	// 总页数
	private int totalPage = 0;

	private Button txt_error;

	private FrameLayout frame_error;

	@Override
	public void initView() {
		view = View.inflate(context, R.layout.layout_potential_user, null);
		// ViewUtils.inject(this, view);
		txt_title = (TextView) view.findViewById(R.id.txt_title);
		txt_error = (Button) view.findViewById(R.id.txt_error);
		frame_error = (FrameLayout) view.findViewById(R.id.error_view);
		lvContent = (ZrcListView) view.findViewById(R.id.lv_content);
		// 设置默认偏移量，主要用于实现透明标题栏功能。（可选）
		// float density = getResources().getDisplayMetrics().density;
		// lvContent.setFirstTopOffset((int) (50 * density));

		txt_title.setText("潜在客户");

	}

	@Override
	public void initListener() {

		// 设置下拉刷新的样式
		SimpleHeader header = new SimpleHeader(this);
		header.setTextColor(0xff0066aa);
		header.setCircleColor(0xff33bbee);
		lvContent.setHeadable(header);

		// 设置加载更多的样式
		SimpleFooter footer = new SimpleFooter(this);
		footer.setCircleColor(0xff33bbee);
		lvContent.setFootable(footer);

		// // 设置列表项出现动画（可选）
		// lvContent.setItemAnimForTopIn(R.anim.topitem_in);
		// lvContent.setItemAnimForBottomIn(R.anim.bottomitem_in);

		// 下拉刷新事件回调
		lvContent.setOnRefreshStartListener(new OnStartListener() {
			@Override
			public void onStart() {
				pageIndex = 1;
				loadNetsWorkData(pageIndex, pageSize, "01", true, false);
			}
		});

		// 加载更多事件回调
		lvContent.setOnLoadMoreStartListener(new OnStartListener() {
			@Override
			public void onStart() {
				if (pageIndex <= totalPage) {
					++pageIndex;
					loadNetsWorkData(pageIndex, pageSize, "01", false, true);
				}
			}
		});
		// 主动下拉刷新
		lvContent.refresh();
	}

	@Override
	public void initValue() {
		// loadNetsWorkData(1, 20, "01", true, false);
	}

	/**
	 * 加载网络数据
	 * 
	 * @desc 7 获取潜在客户列表接口
	 * @param isShowDialog
	 *            : 是否弹出load dialog需要(true)
	 */
	private void loadNetsWorkData(int pageIndex, int pageSize, String sort, boolean isShowDialog,
			final boolean isLoadMore) {
		GetPotentialUserBean potentialBean = new GetPotentialUserBean(pageIndex, pageSize, sort);
		String json = GsonTools.createGsonString(potentialBean);
		String showMsg = null;
		if (isShowDialog) {
			showMsg = "加载中";
		}
		new MyAsyncTask(context, showMsg) {

			@Override
			public String connectNetWorkSuccess(String... responseStr) {
				if (isLoadNetWorkSuccess()) {
					if (datas == null || !isLoadMore) {
						datas = new ArrayList<ResBean.Lists>();
					}
					datas.addAll(resBean.list);
					// 总记录
					listTotal = resBean.listTotal;
					// 总页数
					totalPage = (listTotal % PotentialUserActivity.this.pageSize == 0) ? (listTotal / PotentialUserActivity.this.pageSize)
							: (listTotal / PotentialUserActivity.this.pageSize + 1);
				} else {

				}

				return null;
			}

			protected void onPostExecute(String msg) {
				super.onPostExecute(msg);

				if (isLoadNetWorkSuccess()) {
					if (adapter == null || !isLoadMore) {
						adapter = new MyAdapter(context, datas);
						lvContent.setAdapter(adapter);
					}
					// 是否是加载更多
					if (isLoadMore) {
						lvContent.setLoadMoreSuccess();
					} else {
						lvContent.setRefreshSuccess("加载成功"); // 通知加载成功
						if (listTotal > PotentialUserActivity.this.pageSize) {
							lvContent.startLoadMore(); // 开启LoadingMore功能
						}
					}
					adapter.notifyDataSetChanged();
				} else {
					// 是否是加载更多
					if (isLoadMore) {
						lvContent.stopLoadMore();
					} else {
						lvContent.setRefreshFail("加载失败");
					}
				}
				// 判断是否显示错误页面
				if (isLoadNetWorkSuccess()) {
					if (datas == null || datas.size() < 1) {
						frame_error.setVisibility(View.VISIBLE);
						txt_error.setText("当前没有潜在客户");
						lvContent.setVisibility(View.INVISIBLE);
					}
				} else {
					frame_error.setVisibility(View.VISIBLE);
					lvContent.setVisibility(View.INVISIBLE);
				}

			};
		}.execute(new String[] { Constants.FIND_POTENTIAL_USER_LIST_URL, json });

	}

	class MyAdapter extends MyBaseAdapter<ResBean.Lists, View> {

		public MyAdapter(Activity context, List<ResBean.Lists> datas) {
			super(context, datas);
		}

		class HolderView {
			public TextView tv_phone;
			public ImageView iv_item_record;
			public TextView tv_num;
			public TextView tv_date;
			public Button bt_dial_phone;
			public Button bt_record_phone_desc;
		}


		@Override
		public View getView(int position, View convertView, ViewGroup parent) {
			HolderView holder = null;
			if (convertView == null) {
				holder = new HolderView();
				convertView = View.inflate(context, R.layout.item_layout_potential_user_list, null);
				holder.tv_phone = (TextView) convertView.findViewById(R.id.tv_phone);
				holder.iv_item_record = (ImageView) convertView.findViewById(R.id.iv_item_record);
				holder.tv_num = (TextView) convertView.findViewById(R.id.tv_num);
				holder.tv_date = (TextView) convertView.findViewById(R.id.tv_date);
				holder.bt_dial_phone = (Button) convertView.findViewById(R.id.bt_dial_phone);
				holder.bt_record_phone_desc = (Button) convertView.findViewById(R.id.bt_record_phone_desc);
				convertView.setTag(holder);
			} else {
				holder = (HolderView) convertView.getTag();
			}

			final ResBean.Lists bean_temp = datas.get(position);

			holder.tv_phone.setText(bean_temp.customerMobile);
			holder.tv_num.setText(bean_temp.counter + "次");
			holder.tv_date.setText(bean_temp.lastDate);
			// if (bean_temp.record != null && bean_temp.record.length() > 0) {
			// holder.iv_item_record.setImageResource(R.drawable.icon_record);
			// }
			if ("RD".equals(bean_temp.unread)) {
				holder.iv_item_record.setImageResource(R.drawable.icon_record);
			}

			// 拨打电话
			holder.bt_dial_phone.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					Intent intent = new Intent();
					intent.setAction(Intent.ACTION_CALL);
					intent.setData(Uri.parse("tel:" + bean_temp.customerMobile));
					// 根据意图过滤器参数激活电话拨号功能
					startActivity(intent);
				}
			});
			// 记录通话情况button
			holder.bt_record_phone_desc.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					ActivityAnimationUtils.rightToLeftInAnimation(context, RecordSpeakDescActivity.class, bean_temp);
				}
			});
			// item添加监听事件,客户访问详情
			convertView.setOnClickListener(new OnClickListener() {
				@Override
				public void onClick(View v) {
					ActivityAnimationUtils.rightToLeftInAnimation(context, UserAccessDescActivity.class, bean_temp);
				}
			});
			return convertView;
		}

	}

}
