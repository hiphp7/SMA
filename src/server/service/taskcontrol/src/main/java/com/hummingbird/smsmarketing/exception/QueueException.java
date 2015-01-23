/**
 * 
 */
package com.hummingbird.smsmarketing.exception;

/**
 * 队列处理异常
 * @author huangjiej_2
 * 2014年12月7日 上午8:35:20
 */
public class QueueException extends SmsMarkingException {

	public QueueException() {
		super();
		// TODO Auto-generated constructor stub
	}

	public QueueException(int errcode, String message) {
		super(errcode, message);
		// TODO Auto-generated constructor stub
	}

	public QueueException(String message, Throwable cause) {
		super(message, cause);
		// TODO Auto-generated constructor stub
	}

	public QueueException(String message) {
		super(message);
		// TODO Auto-generated constructor stub
	}

	public QueueException(Throwable cause) {
		super(cause);
		// TODO Auto-generated constructor stub
	}

}
