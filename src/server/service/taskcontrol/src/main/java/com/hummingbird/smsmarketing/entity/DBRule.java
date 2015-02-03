package com.hummingbird.smsmarketing.entity;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;
import java.util.Map;

import org.apache.commons.lang.StringUtils;

import com.hummingbird.common.util.json.JSONArray;
import com.hummingbird.common.util.json.JSONException;
import com.hummingbird.common.util.json.JSONObject;
import com.hummingbird.smsmarketing.rules.TaskTypeLimitRule;
import com.hummingbird.smsmarketing.vo.RuleRequest;

public class DBRule extends TaskTypeLimitRule{
    private String code;

    private String name;

    private String description;

    private String statCondition;

    private String targetType;

    private Integer maxRequest;

    private String implClass;

    private Integer priority;

    private Integer enabled;

    public String getCode() {
        return code;
    }

    public void setCode(String code) {
        this.code = code == null ? null : code.trim();
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name == null ? null : name.trim();
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description == null ? null : description.trim();
    }

    public String getStatCondition() {
        return statCondition;
    }

    public void setStatCondition(String statCondition) {
        this.statCondition = statCondition == null ? null : statCondition.trim();
    }

    public String getTargetType() {
        return targetType;
    }

    public void setTargetType(String targetType) {
        this.targetType = targetType == null ? null : targetType.trim();
    }

    public Integer getMaxRequest() {
        return maxRequest;
    }

    public void setMaxRequest(Integer maxRequest) {
        this.maxRequest = maxRequest;
    }

    public String getImplClass() {
        return implClass;
    }

    public void setImplClass(String implClass) {
        this.implClass = implClass == null ? null : implClass.trim();
    }

    public Integer getPriority() {
        return priority;
    }

    public void setPriority(Integer priority) {
        this.priority = priority;
    }

    public Integer getEnabled() {
        return enabled;
    }

    public void setEnabled(Integer enabled) {
        this.enabled = enabled;
    }

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.rules.Rule#getRuleId()
	 */
	@Override
	public String getRuleId() {
		return code;
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.rules.Rule#getRuleName()
	 */
	@Override
	public String getRuleName() {
		return name;
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.rules.TaskTypeLimitRule#getStatParams()
	 */
	@Override
	public Map<String,Object> getStatParams(RuleRequest ruleReq) {
		//把json转成map
		Map map = new HashMap();
		if(StringUtils.isBlank(statCondition)){
			return map;
		}
		JSONObject jo;
		try {
			String tempstatCondition=statCondition.replaceAll("\\$\\{mobile\\}", ruleReq.getIssueSendRequestSet().getIssueSender().getMobileNum());
			jo = new JSONObject(tempstatCondition);
		} catch (JSONException e) {
			e.printStackTrace();
			return map;
		}
		for (Iterator iterator = jo.keys(); iterator.hasNext();) {
			String key = (String) iterator.next();
			Object value = jo.opt(key);
			if(value!=null&&value instanceof JSONArray){
				JSONArray valuearr = (JSONArray) value;
				List valuelist = new ArrayList();
				for (int i = 0; i < valuearr.length(); i++) {
					Object obj = valuearr.opt(i);
					valuelist.add(obj);
				}
				map.put(key,valuelist);
			}
			else{
				map.put(key, value);
			}
		}
		
		return map;
	}

	/* (non-Javadoc)
	 * @see com.hummingbird.smsmarketing.rules.AbstractRule#isEnabled()
	 */
	@Override
	public boolean isRuleEnabled() {
		return enabled!=null&&1==enabled;
	}
}