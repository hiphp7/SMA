package com.hummingbird.smsmarketing.entity;

public class MassSends {
    private Integer idtMassSends;

    private String masssendspath;

    private Integer issueid;

    public Integer getIdtMassSends() {
        return idtMassSends;
    }

    public void setIdtMassSends(Integer idtMassSends) {
        this.idtMassSends = idtMassSends;
    }

    public String getMasssendspath() {
        return masssendspath;
    }

    public void setMasssendspath(String masssendspath) {
        this.masssendspath = masssendspath == null ? null : masssendspath.trim();
    }

    public Integer getIssueid() {
        return issueid;
    }

    public void setIssueid(Integer issueid) {
        this.issueid = issueid;
    }
}