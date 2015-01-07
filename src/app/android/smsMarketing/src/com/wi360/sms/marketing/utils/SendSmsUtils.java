package com.wi360.sms.marketing.utils;

import java.util.ArrayList;
import java.util.List;

import android.app.Activity;
import android.app.PendingIntent;
import android.content.BroadcastReceiver;
import android.content.Context;
import android.content.Intent;
import android.content.IntentFilter;
import android.telephony.SmsManager;

public class SendSmsUtils {
	private static final String SENT_SMS_ACTION = "MY_SENT_SMS_ACTION";
	private static final String DELIVERED_SMS_ACTION = "MY_DELIVERED_SMS_ACTION";

	/**
	 * 发送短信后回调接口
	 * 
	 * @author Administrator
	 * 
	 */
	public interface SendSmsInfoCallback {
		/**
		 * 发送短信是否成功
		 * 
		 * @param phoneNumber
		 *            电话号码
		 * @param taskId
		 *            每个目标用户一个任务号，唯一标识
		 * @param isSuccess
		 *            true发送成功
		 */
		public void sendSmsSuccess(String phoneNumber, String taskId, boolean isSuccess);

		/**
		 * 对方已经成功接收到短信
		 * 
		 * @param phoneNumber
		 *            电话号码
		 * @param taskId
		 *            每个目标用户一个任务号，唯一标识
		 * @param isSuccess
		 *            true 接收短信成功
		 */
		public void receiveSmsCuccess(String phoneNumber, String taskId, boolean isSuccess);
	}

	// <uses-permission android:name="android.permission.SEND_SMS" />
	/**
	 * 发送短信
	 * 
	 * @param phoneNumber
	 * @param message
	 * @param smsCallBack
	 */
	public static void sendSMS(Context context, String phoneNumber, String message, String taskId,
			SendSmsInfoCallback smsCallBack) {
		// 获取短信管理器
		android.telephony.SmsManager smsManager = android.telephony.SmsManager.getDefault();
		// 拆分短信内容（手机短信长度限制）
		// List<String> divideContents = smsManager.divideMessage(message);
		// for (String text : divideContents) {
		// smsManager.sendTextMessage(phoneNumber, null, text, sentPI(context,
		// phoneNumber, taskId, smsCallBack),
		// deliverPI(context, phoneNumber, taskId, smsCallBack));
		// }
		// --------------------------------------------------------------
		// if (message.length() > 70) {
		ArrayList<String> msgs = smsManager.divideMessage(message);

		ArrayList<PendingIntent> sentIntents = new ArrayList<PendingIntent>();
		ArrayList<PendingIntent> deliveryIntents = new ArrayList<PendingIntent>();
		for (int i = 0; i < msgs.size(); i++) {
			sentIntents.add(sentPI(context, phoneNumber, taskId, smsCallBack));
			deliveryIntents.add(deliverPI(context, phoneNumber, taskId, smsCallBack));
		}
		// for (int i = 0; i < msgs.size(); i++) {
		// }
		smsManager.sendMultipartTextMessage(phoneNumber, null, msgs, sentIntents, deliveryIntents);
		// }
		// else {
		// smsManager.sendTextMessage(phoneNumber, null, message,
		// sentPI(context, phoneNumber, taskId, smsCallBack),
		// deliverPI(context, phoneNumber, taskId, smsCallBack));
		// }
	}

	/**
	 * 短信发送成功
	 * 
	 * @param context
	 * @return sentPI
	 */

	private static PendingIntent sentPI(final Context context, final String phoneNumber, final String taskId,
			final SendSmsInfoCallback smsCallback) {
		Intent sentIntent = new Intent(SENT_SMS_ACTION);
		PendingIntent sentPI = PendingIntent.getBroadcast(context, 0, sentIntent, 0);
		// register the Broadcast Receivers
		context.registerReceiver(new BroadcastReceiver() {
			@Override
			public void onReceive(Context _context, Intent _intent) {
				if (_intent.getAction().equals(SENT_SMS_ACTION)) {
					switch (getResultCode()) {
					case Activity.RESULT_OK:
						L.i("短信发送成功");
						smsCallback.sendSmsSuccess(phoneNumber, taskId, true);
						// Toast.makeText(context, "短信发送成功",
						// Toast.LENGTH_SHORT).show();
						break;
					case SmsManager.RESULT_ERROR_GENERIC_FAILURE:
						L.e("SmsManager.RESULT_ERROR_GENERIC_FAILURE");
						smsCallback.sendSmsSuccess(phoneNumber, taskId, false);
						break;
					case SmsManager.RESULT_ERROR_RADIO_OFF:
						L.e("SmsManager.RESULT_ERROR_RADIO_OFF");
						smsCallback.sendSmsSuccess(phoneNumber, taskId, false);
						break;
					case SmsManager.RESULT_ERROR_NULL_PDU:
						L.e("SmsManager.RESULT_ERROR_NULL_PDU");
						smsCallback.sendSmsSuccess(phoneNumber, taskId, false);
						break;
					default:
						L.e("SmsManager   default");
						smsCallback.sendSmsSuccess(phoneNumber, taskId, false);
						break;
					}
				} else {
					L.e("SmsManager   default");
					// smsCallback.sendSmsSuccess(false);
				}

			}
		}, new IntentFilter(SENT_SMS_ACTION));
		return sentPI;
	}

	/**
	 * 收信人已经成功接收
	 * 
	 * @param context
	 * @return deliverPI
	 */
	private static PendingIntent deliverPI(final Context context, final String phoneNumber, final String taskId,
			final SendSmsInfoCallback smsCallback) {
		// create the deilverIntent parameter
		Intent deliverIntent = new Intent(DELIVERED_SMS_ACTION);
		PendingIntent deliverPI = PendingIntent.getBroadcast(context, 0, deliverIntent, 0);
		context.registerReceiver(new BroadcastReceiver() {
			@Override
			public void onReceive(Context _context, Intent _intent) {
				if (_intent.getAction().equals(DELIVERED_SMS_ACTION)) {
					switch (getResultCode()) {
					case Activity.RESULT_OK:
						L.i("onReceive  收信人已经成功接收");
						// Toast.makeText(context, "收信人已经成功接收",
						// Toast.LENGTH_SHORT).show();
						smsCallback.receiveSmsCuccess(phoneNumber, taskId, true);
						break;
					case Activity.RESULT_CANCELED:
						smsCallback.receiveSmsCuccess(phoneNumber, taskId, false);
						break;
					default:
						smsCallback.receiveSmsCuccess(phoneNumber, taskId, false);
						break;
					}

				} else {
					L.i("onReceive  发送的短信不匹配");
					// smsCallback.receiveSmsCuccess(phoneNumber, taskId,
					// false);
				}
			}
		}, new IntentFilter(DELIVERED_SMS_ACTION));

		return deliverPI;
	}

}
