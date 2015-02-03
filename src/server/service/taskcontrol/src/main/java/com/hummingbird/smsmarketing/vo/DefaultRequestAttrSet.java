/**
 * 
 */
package com.hummingbird.smsmarketing.vo;

import java.util.HashMap;
import java.util.Map;
import java.util.TreeMap;

import org.apache.commons.beanutils.BeanUtils;
import org.codehaus.jackson.map.util.BeanUtil;

import com.hummingbird.smsmarketing.constant.TaskTypeConst;

/**
 * @author huangjiej_2
 * 2014年12月8日 下午11:43:09
 */
public class DefaultRequestAttrSet implements RequestAttrSet {

	
	
	
	public DefaultRequestAttrSet() {
		super();
	}

	
	/**
	 * 其它属性
	 */
	private Map<String, Object> attrs=new TreeMap<String, Object>();

	

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.vo.RequestAttrSet#get(java.lang.String)
	 */
	@Override
	public Object get(String key) {
		return attrs.get(key);
	}
	
	/**
	 * 设置值
	 * @param key
	 * @param obj
	 */
	public void put(String key,Object obj){
		attrs.put(key, obj);
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.vo.RequestAttrSet#getAllAttr()
	 */
	@Override
	public Map<String, Object> getAllAttr() {
		return attrs;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#hashCode()
	 */
	@Override
	public int hashCode() {
		final int prime = 31;
		int result = 1;
		result = prime * result + ((attrs == null) ? 0 : attrs.hashCode());
		return result;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#equals(java.lang.Object)
	 */
	@Override
	public boolean equals(Object obj) {
		if (this == obj)
			return true;
		if (obj == null)
			return false;
		if (!(obj instanceof DefaultRequestAttrSet))
			return false;
		DefaultRequestAttrSet other = (DefaultRequestAttrSet) obj;
		if (attrs == null) {
			if (other.attrs != null)
				return false;
		} else if (!attrs.equals(other.attrs))
			return false;
		return true;
	}

	/* (non-Javadoc)
	 * @see java.lang.Object#toString()
	 */
	@Override
	public String toString() {
		return "DefaultRequestAttrSet [attrs=" + attrs + "]";
	}

	/**
	 * 判断是否支持
	 * @param reqattrs
	 * @return
	 */
	public boolean supports(RequestAttrSet reqattrs) {
		if(this.attrs.equals(reqattrs)){
			return true;
		}
//		for (Map.Entry en : attrs.entrySet()) {
//			Object key = en.getKey();
//		}	
		
		if(supportsbytasktype(reqattrs)){
			//看除去任务类型后，还是不是相等的
			TreeMap<String, Object> testmap = new TreeMap<String,Object>(reqattrs.getAllAttr());
			testmap.put(RequestAttrSet.REQUEST_TASK_TYPE, attrs.get(RequestAttrSet.REQUEST_TASK_TYPE));
			return attrs.equals(testmap);
		}
		
		return false;
	}
	
	/**
	 * 任务类型的专门判断
	 * @param reqattrs
	 * @return
	 */
	public boolean supportsbytasktype(RequestAttrSet reqattrs){
		Object type = this.attrs.get(RequestAttrSet.REQUEST_TASK_TYPE);
		if(type!=null){
			Object targettype = reqattrs.get(RequestAttrSet.REQUEST_TASK_TYPE);
			if(type.equals(targettype)){
				return true;
			}
			//如果当前队列不是广告类的，而请求的内容也是非广告类的，就会通过
			if(targettype!=null){
				if(!TaskTypeConst.TASK_TYPE_ADVERTISEMENT.equals(type)&&
						TaskTypeConst.TASK_TYPE_NOT_ADV.equals(targettype)		
						){
					return true;
				}
				
			}
		}
		
		return false;
	}

}
