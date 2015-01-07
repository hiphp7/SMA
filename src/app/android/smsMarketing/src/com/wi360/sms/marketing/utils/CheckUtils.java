package com.wi360.sms.marketing.utils;

import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import android.app.Activity;
import android.content.Context;
import android.text.TextUtils;
import android.widget.Toast;

public class CheckUtils {
	/**
	 * 验证是否是有效电话号码
	 * 
	 * @param mobiles
	 * @return true验证通过(有效)
	 */
	public static boolean checkMobileNO(Activity context, String mobiles) {
		String msg = null;
		boolean state = true;
		if (TextUtils.isEmpty(mobiles)) {
			msg = "请填写手机号码";
			state = false;
		}
		// Pattern p =
		// Pattern.compile("^((13[0-9])|(15[^4,\\D])|(18[0,5-9]))\\d{8}$");
		Pattern p = Pattern
				.compile("^(((13[0-9])|(15([0-3]|[5-9]))|(18[0-1,5-9]))\\d{8})|(0\\d{2}-\\d{8})|(0\\d{3}-\\d{7})$");
		Matcher m = p.matcher(mobiles);
		//
		if (!m.matches() && state) {
			msg = "电话号码格式不正确 !";
			state = false;
		}

		if (!state) {
			UIUtils.showToast(context, msg);
		}

		return state;
	}

	public static boolean checkMobileNumber(String mobileNumber) {
		boolean flag = false;
		try {
			Pattern regex = Pattern
					.compile("^(((13[0-9])|(15([0-3]|[5-9]))|(18[0,5-9]))\\d{8})|(0\\d{2}-\\d{8})|(0\\d{3}-\\d{7})$");
			Matcher matcher = regex.matcher(mobileNumber);
			flag = matcher.matches();
		} catch (Exception e) {
			flag = false;
		}
		return flag;
	}

	/**
	 * 移动号码判断,
	 * 
	 * @param mobile
	 * @return :boolean：是移动号码返回真，否则返回假
	 */
	public static boolean IsMobileNum(String mobile) {
		String reg = "/^(((13)[5-9]{1})|((15)[0,1,2,7,8,9]{1})|(188))[0-9]{8}$|(^((134)[0-8]{1})[0-9]{7}$)/";
		return Pattern.matches(reg, mobile);
	}

	/**
	 * 是否发送网络请求
	 * 
	 * 只在某一个时间段发送网络请求,否则在半晚上会给客户发送消息,让用户感到烦
	 * 
	 * @return true发送,
	 */
	public static boolean isSendNetWorkRequest() {
		return isSendNetWorkRequest(null);
	}

	public static boolean isSendNetWorkRequest(Context context) {

		// boolean isSend = false;
		boolean isSend = true;
		String hoursStr = new SimpleDateFormat("HH").format(new Date().getTime());
		int hours = Integer.valueOf(hoursStr);
		L.i("----------时间:  " + hours);
		if (hours >= 8 && hours < 22) {
			isSend = true;
		}
		// 清空日志
		if (hours == 8 && context != null) {
			SPUtils.remove(context, Constants.LOG_INFO);
		}
		return isSend;
	}

}
