/**
 * 
 */
package com.hummingbird.smsmarketing.exception;

import com.hummingbird.common.exception.BusinessException;

/**
 * 营销神器异常
 * @author huangjiej_2
 * 2014年12月6日 下午2:34:02
 */
public class SmsMarkingException extends BusinessException{

	public SmsMarkingException() {
		super();
		// TODO Auto-generated constructor stub
	}

	public SmsMarkingException(int errcode, String message) {
		super(errcode, message);
		// TODO Auto-generated constructor stub
	}

	public SmsMarkingException(String message, Throwable cause) {
		super(message, cause);
		// TODO Auto-generated constructor stub
	}

	public SmsMarkingException(String message) {
		super(message);
		// TODO Auto-generated constructor stub
	}

	public SmsMarkingException(Throwable cause) {
		super(cause);
		// TODO Auto-generated constructor stub
	}

	
	
}
