/**
 * 
 */
package com.hummingbird.smsmarketing.rules;

import org.apache.commons.logging.Log;
import org.apache.commons.logging.LogFactory;

/**
 * 抽象的规则
 * @author huangjiej_2
 * 2014年12月8日 下午10:02:03
 */
public abstract class AbstractRule implements Rule {

	protected Log log = LogFactory.getLog(this.getClass());
	

	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return this.getClass().getName()+" [name=" + this.getRuleName() + ", id=" + getRuleId() + "]";
	}

	/**
	 * @return the enabled
	 */
	public abstract boolean isRuleEnabled();

	/**
	 * @return the priority
	 */
	public abstract Integer getPriority();
	
	
}
