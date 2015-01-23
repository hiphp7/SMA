/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

/**
 * @author huangjiej_2
 * 2014年10月18日 下午10:07:53
 */
public class DeviceInfo {
//mac":"3ere:eee:3434:34434","deviceId":"设备标识","imsi":"SIM卡设备号
	/**
	 * 地址
	 */
	private String mac;
	/**
	 * 设备id
	 */
	private String deviceId;
	/**
	 * 卡id
	 */
	private String imsi;
	/**
	 * 地址
	 */
	public String getMac() {
		return mac;
	}
	/**
	 * 地址
	 */
	public void setMac(String mac) {
		this.mac = mac;
	}
	/**
	 * 设备id
	 */
	public String getDeviceId() {
		return deviceId;
	}
	/**
	 * 设备id
	 */
	public void setDeviceId(String deviceId) {
		this.deviceId = deviceId;
	}
	/**
	 * 卡id
	 */
	public String getImsi() {
		return imsi;
	}
	/**
	 * 卡id
	 */
	public void setImsi(String imsi) {
		this.imsi = imsi;
	}
	@Override
	public String toString() {
		return "DeviceInfo [mac=" + mac + ", deviceId=" + deviceId + ", imsi="
				+ imsi + "]";
	}
	
	
	
	
}
