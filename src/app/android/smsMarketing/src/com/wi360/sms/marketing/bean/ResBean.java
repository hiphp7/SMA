package com.wi360.sms.marketing.bean;

import java.io.Serializable;
import java.util.List;

public class ResBean implements Serializable {

	public int errcode;
	public String errmsg;
	public String token;
	/**
	 * 2 手机号码登录接口
	 */
	public String name;
	public String headImage;
	public String mobileNum;

	/**
	 * 3 获取短信发送任务接口
	 */
	public List<Task> task;

	public class Task implements Serializable {
		public String taskId;
		public String issueId;
		public String destMobile;
		public String content;
		public int expireIn;

	}

	/**
	 * 5 获取肉鸡执行信息接口
	 */
	// 勤劳指数，当天已发送数量/最大个人当天发送数量*100
	public String industryIndex;
	// beyondIndex
	public String beyondIndex;

	public TaskInfo taskInfo;

	// 任务执行信息json对象
	public class TaskInfo implements Serializable {
		// 待发池数量
		public String remainPool;
		// 今天已发条数
		public String today;
		// 昨天已发条数
		public String yestoday;
	}

	/**
	 * 6 获取未读潜在客户数接口
	 */
	// 未读客户数
	public String unreadCustomer;

	/**
	 * 7 获取潜在客户列表接口
	 */
	public int pageIndex;
	public int pageSize;
	public int listTotal;
	// 列表json数组对象
	public List<Lists> list;

	public class Lists implements Serializable {
		// 潜在客户内容访问次数
		public int counter;
		// 潜在客户手机号码
		public String customerMobile;
		// 发布号
		public String issueId;
		// 潜在客户最后访问时间
		public String lastDate;
		// public String lastName;
		// public String callName;
		 public String customerCall;
		// 客户回访记录，可以为空
		public String record;
		public String sex;
		// 未读标志，YES-未读，NO-已读
		public String unread;
		/**
		 * 3 获取短信发送任务接口
		 * 
		 * @author Administrator
		 * 
		 */
		public String content;
		// 目标手机号码，即接收短信内容的手机号码
		public String destMobile;
		// 有效时间，精确到秒，接收到任务，必须在有效时间内执行，超过有效时间，将app不执行
		public int expireIn;
		// 发布号
		// public String issueId;
		// 每个目标用户一个任务号，唯一标识
		public String taskId;

		/**
		 * 8 获取潜在客户访问详情接口
		 */
		public String visitTime;

	}

	/**
	 * 8 获取潜在客户访问详情接口
	 */
	public String title;
	// public Lists list;
	// 内容
	public String content;

	/**
	 * 10 查询有笔记的发布号列表
	 */
	public List<IssueList> issueList;

	public class IssueList implements Serializable {
		public String issueId;
		public String title;
		public int noteCounter;
	}

	/**
	 * 11 获取笔记详细
	 */
	public List<NoteList> noteList;

	public class NoteList implements Serializable {
		public String customerMobile;
		public String noteContent;
		public String lastDate;
	}
	/**
	 * 12 获取笔记详细(肉鸡)
	 */
	public String customerMobile;
	public String noteContent;
	public String call;
	public String lastDate;
	
}
