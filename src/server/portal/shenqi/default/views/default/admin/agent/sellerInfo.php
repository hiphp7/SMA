<link rel="stylesheet" href="<?php echo static_url('theme/admin/css/password.css')?>" />
<?php 
echo ace_header('用户',$item->sellerid);

echo ace_form_open('','',array('id'=>$item->sellerid));

	$options = array(
			'label_text'=>'商户编号',
			'datatype'=>'u',
			'nullmsg'=>"请输入商户编号！",
			'errormsg'=>"商户编号只允许字母开头，允许5-16字节，允许字母数字下划线",
			'help'=>'商户登录后台的用户号'
	);
	echo ace_input_m($options ,'sellerid',$item->sellerid,'maxlength="45" id="sellerid"');
	
	$options = array(
	        'label_text'=>'商户全称',
					'datatype'=>'*',
					'nullmsg'=>"请输入商户全称！",
					'errormsg'=>"请输入商户全称",
	        'help'=>'商户全称'
	);
	echo ace_input_m($options,'sellername',$item->sellername,'maxlength="45" id="sellername"');
	$options = array(
	        'label_text'=>'商户简称',
					'datatype'=>'*',
					'nullmsg'=>"请输入商户简称！",
					'errormsg'=>"请输入商户简称",
	        'help'=>'商户简称'
	);
	echo ace_input_m($options,'shortname',$item->shortname,'maxlength="45" id="shortname"');
	
	if(!$item->sellerid){
		$options = array('label_text'=>'密码','datatype'=>'pwd','help'=>lang('pwd_help_msg'),'errormsg'=>lang('pwd_help_msg'));
		echo ace_password($options, array('id'=>'q_password1','name'=>'password'));
	}
	$options = array(
	        'label_text'=>'手机号',
					'datatype'=>'n11-11',
					'nullmsg'=>"请输入手机号！",
					'errormsg'=>"请输入手机号",
	        'help'=>'商户联系人手机号'
	);
	echo ace_input_m($options,'mobilenum',$item->mobilenum,'maxlength="11"');
	$options = array(
	        'label_text'=>'联系人',
					'datatype'=>'*1-15',
					'nullmsg'=>"请输入联系人！",
					'errormsg'=>"请输入联系人",
	        'help'=>'联系人'
	);
	echo ace_input_m($options,'contact',$item->contact,'maxlength="15"');

	$options = array(
	        'label_text'=>'QQ',
					'datatype'=>'n5-15',
					'nullmsg'=>"请输入QQ！",
					'errormsg'=>"QQ号不能大于15位数字,不能小于5位",
	        'help'=>'商户联系人的QQ'
	);
//	echo ace_input_m($options,'qq',$item->qq,'maxlength="15"');
	
  	echo ace_srbtn('agent/seller',false);
  echo ace_form_close()
?>
<script type="text/javascript">
    $(function(){

        $("input[type=password]").closest(".col-sm-5").addClass('col-sm-4').removeClass('col-sm-5');
        var qd_html = '<div class="passwordStrength"><b>密码强度：</b> <span class="">弱</span><span class="">中</span><span class="last">强</span></div>';

        $("#q_password1").closest('span').after(qd_html);
        
        $("#sellername").change(function(){
            
            sellername = $("#sellername").val();
            
            if(sellername !=""){
                $.post("<?php echo admin_base_url('agent/seller/ajaxName')?>",
                   { sellername: sellername }	,
                   function(data){
                       result = eval(data);
                       if(result.status == 1){
                           $("#sellername").val("");
                           $("#sellername").focus();
                            layer.alert(result.info);
                       }
                    },"json"	
                );	
            } 	
        });
        $("#shortname").change(function(){
            
            agentshort = $("#shortname").val();
            
            if(agentshort !=""){
                $.post("<?php echo admin_base_url('agent/seller/ajaxShort')?>",
                   { sellershort: agentshort }	,
                   function(data){
                       result = eval(data);
                       if(result.status == 1){
                           $("#shortname").val("");
                           $("#shortname").focus();
                            layer.alert(result.info);
                       }
                    },"json"	
                );	
            } 	
        });
        $("#sellerid").change(function(){
            
            agentid = $("#sellerid").val();
            
            if(agentid !=""){
                $.post("<?php echo admin_base_url('agent/seller/ajaxId')?>",
                   { sellerid: agentid }	,
                   function(data){
                       result = eval(data);
                       if(result.status == 1){
                           $("#sellerid").val("");
                           $("#sellerid").focus();
                            layer.alert(result.info);
                       }
                    },"json"	
                );	
            } 	
        });
    });
</script>
