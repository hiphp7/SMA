package com.hummingbird.smsmarketing.entity;


/**
 * 肉机的app秘钥，用于生成签名
 * @author huangjiej_2
 * 2014年11月11日 下午11:01:24
 */
public class AppInfo {

    private String appId;

    private String appKey;

    public String getAppId() {
        return appId;
    }

    public void setAppId(String appId) {
        this.appId = appId == null ? null : appId.trim();
    }

    public String getAppKey() {
        return appKey;
    }

    public void setAppKey(String appKey) {
        this.appKey = appKey == null ? null : appKey.trim();
    }

	@Override
	public String toString() {
		return "AppInfo [appId=" + appId + ", appKey=" + appKey + "]";
	}



    
    
}