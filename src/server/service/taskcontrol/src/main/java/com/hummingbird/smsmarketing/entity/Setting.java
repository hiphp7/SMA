package com.hummingbird.smsmarketing.entity;

public class Setting {
    private String settingname;

    private String value;

    public String getSettingname() {
        return settingname;
    }

    public void setSettingname(String settingname) {
        this.settingname = settingname == null ? null : settingname.trim();
    }

    public String getValue() {
        return value;
    }

    public void setValue(String value) {
        this.value = value == null ? null : value.trim();
    }
}