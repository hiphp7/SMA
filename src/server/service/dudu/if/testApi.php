<!DOCTYPE html>
<head>
<meta charset="UTF-8" />
<meta http-equiv="title" content="" />
<meta name="description" content="" />
<meta name="keywords" content="" />
<meta name="language" content="chinese" />
<style>
li {margin:2px 0 2px 0;}
</style>
<title>API调试</title>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
<div style="width: 500px; margin: auto;">
<ul>
<li><label>服务器</label><select name="host" id="host">
<option value="127.0.0.1">127.0.0.1</option>
</select></li>
<li><label>肉鸡号码</label><input type="text" name="mobileNum" id="mobile" value="">
<li><label>肉鸡token</label><input type="text" name="token" id="token" value="">
<li><label>端口</label><input type="text" name="port" id="port" value="80">
</li>
<li><label>接口名</label><input type="text" name="api" id="api" style="width: 492px;">
</li>
<li><label>参数</label><textarea id="param" style="margin: 2px; width: 492px; height: 87px;"></textarea></li>
<li><input type="button" value="提交" id="bt"></li>
<li><label>响应</label><div id="resp" style="width: 500px;word-break: break-all;"></div></li>
</ul>
</div>
<div>
<ul>
</ul>
</div>
<script>
$("#bt").click(function (){
var host = $("#host").val();
var m = $("#mobile").val();
var t = $("#token").val();
if($("#port").val()!=80){
	host = host+':'+ $("#port").val();
}
var url = "f.php?api="+encodeURIComponent($("#api").val())+'&host='+host+'&mobileNum='+m+'&token='+t;
var data = $("#param").val();
try{
var x = jQuery.parseJSON(data);
}
catch (e) {
if(data!=='')
{
alert('输入的格式不是json');
return;
}
}
$.ajax({type:'POST',url:url,data:data,success:function(resp){
 $("#resp").html(JSON.stringify(resp));
//var msg = JSON.stringify(resp);
//msg = msg.replace(",",",\n");
//alert(msg);
},dataType:"json","error":function (a,b){
$("#resp").html(a.responseText);
}});

});


</script>
</body>
</html>
