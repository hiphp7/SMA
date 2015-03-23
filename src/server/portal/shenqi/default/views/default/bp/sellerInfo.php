<?php 
echo ace_form_open();
	$options = array(
			'label_text'=>'<span class="red">*</span>商户编号',
			'help'=>'商户登录后台的用户号'
	);
	echo ace_input($options ,'sellerid',$item->sellerid,'maxlength="45" id="sellerid" disabled="disabled"');
	
	$options = array(
	        'label_text'=>'<span class="red">*</span>商户全称',
	        'help'=>'商户全称'
	);
	echo ace_input($options,'sellername',$item->sellername,'maxlength="5" id="sellername" disabled="disabled"');
	$options = array(
	        'label_text'=>'<span class="red">*</span>商户简称',
	        'help'=>'商户简称'
	);
	echo ace_input($options,'shortname',$item->shortname,'maxlength="45" id="shortname" disabled="disabled"');
	
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
	
  	echo ace_srbtn('bp/main',false);
  echo ace_form_close()
?>
<script type="text/javascript">
    $(function(){
        $("input[type=password]").closest(".col-sm-5").addClass('col-sm-4').removeClass('col-sm-5');
        var qd_html = '<div class="passwordStrength"><b>密码强度：</b> <span class="">弱</span><span class="">中</span><span class="last">强</span></div>';
        $("#q_password1").closest('span').after(qd_html);
    });
</script>
