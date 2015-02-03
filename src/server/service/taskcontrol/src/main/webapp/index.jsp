<%@ page language="java" import="java.util.*" pageEncoding="utf-8"%>
<%
String path = request.getContextPath();
String basePath = request.getScheme()+"://"+request.getServerName()+":"+request.getServerPort()+path+"/";
%>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head>
    <base href="<%=basePath%>">
    
    <title>My JSP 'index.jsp' starting page</title>
	<meta http-equiv="pragma" content="no-cache">
	<meta http-equiv="cache-control" content="no-cache">
	<meta http-equiv="expires" content="0">    
	<meta http-equiv="keywords" content="keyword1,keyword2,keyword3">
	<meta http-equiv="description" content="This is my page">
	<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
  </head>
  <body>
  	<div>
	<ul>
	<li><label>服务器</label>
	<select name="host" id="host">
	<option value="http://<%=request.getServerName()%>:<%=request.getServerPort()%><%=request.getContextPath() %>" selected>当前服务器地址</option>
	</select><input type='txt' id='realhost' >
	<li><label>接口名</label>
	<textarea id='api' cols="60"></textarea>

	</li>
	<li><label>参数</label><textarea id="param" cols="100" rows="15"></textarea></li>
	<li><label>cookie</label><textarea id="cookie" cols="100" rows="2"></textarea></li>
	<li><input type="button" value="提交" id="bt"></li>
	<li><label>响应</label><div id="resp"></div></li>
	</ul>
	</div>
	<ul>
<li onclick='setbinding("/roujiauth/user/get_smscode","{\"appId\":\"smsmarketing\",\"timeStamp\":\"TIMESTAMP\", \"nonce\":\"NONCE\",\"signature\":\"8176f5be1ad9e977009c29e4c68218fe\",\"mobileNum\":\"18922260815\"}  ")'>发送验证码</li> 
<li onclick='setbinding("/roujiauth/user/login","{\"appId\":\"smsmarketing\",\"timeStamp\":\"TIMESTAMP\", \"nonce\":\"NONCE\",\"signature\":\"8176f5be1ad9e977009c29e4c68218fe\", \"loginInfo\":{\"mobileNum\":\"18922260815\",\"smsCode\":\"223344\"},\"deviceInfo\":{\"mac\":\"3ere:eee:3434:34434\",\"deviceId\":\"设备标识\",\"imsi\":\"SIM卡设备号\"}}")'>验证验证码</li>
<li onclick='setbinding("/smstaskcontrol/get_task","","{\"mobileNum\":\"18922260815\"}")'>获取短信发送任务</li>
<li onclick='setbinding("/smstaskcontrol/update_task_status","{\"task\":[    {\"taskId\":\"1\",\"issueId\":\"14\",\"status\":\"OK\"}]}","{\"mobileNum\":\"18922260815\"}")'>上报短信发送任务执行</li>
<li onclick='setbinding("/smstaskcontrol/queuestatus","")'>队列状态</li>
<li onclick='setbinding("/smstaskcontrol/resetnomoredata","")'>重置可从数据库中读数据</li>
<!-- <li onclick='setbinding("/order/fromPayGateWayNotify","{\"errorcode\":\"0\",\"errormsg\":\"支付成功\",\"dealId\":\"UD002014111223555900000050918795\"}")'>支付通知</li>
<li onclick='setbinding("/credit_billing/order/monitor","")'  style="display: none">状态监控</li> -->
</ul> 
  </body>
    <script>
    var type='payload'
    
		$("#bt").click(function (){
			$("#resp").html("");
			var hostval = $("#realhost").val()
			if(hostval==''){
				hostval=$("#host").val()
			}
			var url =hostval+$("#api").val();
			var data = $("#param").val();
			if(type=='payload')
			{
				var vcookie = $('#cookie').val();
				if(vcookie!=''){
					try{
						vcookie = eval('('+vcookie+')');
						for(item in vcookie){
							$.cookie(item, vcookie[item]);
						}
					}
					catch(e){console.print(e)}
					
				}
				
				$.ajax({type:'POST',contentType:'application/json',url:url,data:data,
				success:function(resp){ $("#resp").html(resp);},dataType:"html"}
				);
			}
			else if(type=='cookie'){
				data = eval('('+data+')');
				for(item in data){
					$.cookie(item, data[item]);
				}
				$.ajax({type:'POST',contentType:'application/json',url:url,
					success:function(resp){ $("#resp").html(resp);},dataType:"html"}
					);
			}
			
		});
		
		function setbinding(api,json,cookiejson){
			$("#api").val(api)
			$("#param").val(json)
			$("#cookie").val(cookiejson)
			type='payload'
		}
		
		jQuery.cookie = function(name, value, options) {
			if (typeof value != 'undefined') {
			   options = options || {};
			   if (value === null) {
			    value = '';
			    options = $.extend({}, options);
			    options.expires = -1;
			   }
			   var expires = '';
			   if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
			    var date;
			    if (typeof options.expires == 'number') {
			     date = new Date();
			     date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
			    } else {
			     date = options.expires;
			    }
			    expires = '; expires=' + date.toUTCString();
			   }
			   var path = options.path ? '; path=' + (options.path) : '';
			   var domain = options.domain ? '; domain=' + (options.domain) : '';
			   var secure = options.secure ? '; secure' : '';
			   document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
			} else {
			   var cookieValue = null;
			   if (document.cookie && document.cookie != '') {
			    var cookies = document.cookie.split(';');
			    for (var i = 0; i < cookies.length; i++) {
			     var cookie = jQuery.trim(cookies[i]);
			     if (cookie.substring(0, name.length + 1) == (name + '=')) {
			      cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
			      break;
			     }
			    }
			   }
			   return cookieValue;
			}
			};
			
			function setbinding_cookie(api,json){
				$("#api").val(api)
				$("#param").val(json)
				type = 'cookie';
				
			}
		
		
	</script>
  
</html>
