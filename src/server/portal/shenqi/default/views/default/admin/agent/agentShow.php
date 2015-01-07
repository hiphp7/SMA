<link rel="stylesheet" href="<?php echo static_url('theme/admin/css/password.css')?>" />
<?php 
echo ace_header('用户',$item->agentid);

echo ace_form_open('','',array('id'=>$item->agentid));

	$options = array(
			'label_text'=>'代理编号',
			'datatype'=>'*',
			'nullmsg'=>"请输入代理编号！",
			'errormsg'=>"请输入代理编号",
			'help'=>'代理登录后台的用户号'
	);
	echo ace_input_m($options ,'agentid',$item->agentid,'maxlength="45" readonly="readonly"');
	
	$options = array(
	        'label_text'=>'代理全称',
					'datatype'=>'*',
					'nullmsg'=>"请输入代理编号！",
					'errormsg'=>"请输入代理编号",
	        'help'=>'代理全称'
	);
	echo ace_input_m($options,'agentname',$item->agentname,'maxlength="45" readonly="readonly"');
	$options = array(
	        'label_text'=>'代理简称',
					'datatype'=>'*',
					'nullmsg'=>"请输入代理编号！",
					'errormsg'=>"请输入代理编号",
	        'help'=>'代理简称'
	);
	echo ace_input_m($options,'shortname',$item->shortname,'maxlength="45" readonly="readonly"');
	$options = array(
	        'label_text'=>'手机号',
					'datatype'=>'*11-11',
					'nullmsg'=>"请输入手机号！",
					'errormsg'=>"请输入手机号",
	        'help'=>'代理联系人手机号'
	);
	echo ace_input_m($options,'mobilenum',$item->mobilenum,'maxlength="11" readonly="readonly"');
	$options = array(
	        'label_text'=>'联系人',
					'datatype'=>'*1-15',
					'nullmsg'=>"请输入联系人！",
					'errormsg'=>"请输入联系人",
	        'help'=>'联系人'
	);
	echo ace_input_m($options,'contact',$item->contact,'maxlength="15" readonly="readonly"');

	$options = array(
	        'label_text'=>'QQ',
	        'help'=>'代理联系人的QQ'
	);
	echo ace_input($options,'qq',$item->qq,'maxlength="45" readonly="readonly"');
	
  echo ace_form_close()
?>
