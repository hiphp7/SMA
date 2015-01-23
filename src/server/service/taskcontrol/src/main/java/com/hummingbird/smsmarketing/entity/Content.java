package com.hummingbird.smsmarketing.entity;

import java.util.Date;

public class Content {
    private Integer contentid;

    private String title;

    private String smscontent;

    private String mobilepage;

    private String pcpage;

    private String padpage;

    private Date createtime;

    private String status;

    private String type;
    
    private String sellerid;
    
    /**
     * 是否显示短链，Y显示
     */
    private String shorturl; 

    public Integer getContentid() {
        return contentid;
    }

    public void setContentid(Integer contentid) {
        this.contentid = contentid;
    }

    public String getTitle() {
        return title;
    }

    public void setTitle(String title) {
        this.title = title == null ? null : title.trim();
    }

    public String getSmscontent() {
        return smscontent;
    }

    public void setSmscontent(String smscontent) {
        this.smscontent = smscontent == null ? null : smscontent.trim();
    }

    public String getMobilepage() {
        return mobilepage;
    }

    public void setMobilepage(String mobilepage) {
        this.mobilepage = mobilepage == null ? null : mobilepage.trim();
    }

    public String getPcpage() {
        return pcpage;
    }

    public void setPcpage(String pcpage) {
        this.pcpage = pcpage == null ? null : pcpage.trim();
    }

    public String getPadpage() {
        return padpage;
    }

    public void setPadpage(String padpage) {
        this.padpage = padpage == null ? null : padpage.trim();
    }

    public Date getCreatetime() {
        return createtime;
    }

    public void setCreatetime(Date createtime) {
        this.createtime = createtime;
    }

    public String getStatus() {
        return status;
    }

    public void setStatus(String status) {
        this.status = status == null ? null : status.trim();
    }

    public String getType() {
        return type;
    }

    public void setType(String type) {
        this.type = type == null ? null : type.trim();
    }
    
    public String getSellerid() {
        return sellerid;
    }

    public void setSellerid(String sellerid) {
        this.sellerid = sellerid == null ? null : sellerid.trim();
    }

	/**
	 * @return the showurl
	 */
	public String getShorturl() {
		return shorturl;
	}

	/**
	 * @param showurl the showurl to set
	 */
	public void setShorturl(String showurl) {
		this.shorturl = showurl;
	}
	
	/**
	 * 是否显示短链
	 * @return
	 */
	public boolean isShowUrl(){
		return "Y".equalsIgnoreCase(shorturl);
	}
}