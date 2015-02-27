<link rel="stylesheet" href="<?php echo static_url('theme/admin/css/password.css')?>" />
<?php 
echo ace_header('用户',$item->uid);

echo ace_form_open('','',array('id'=>$item->uid));

	$options = array(
			'label_text'=>'账号',
			'datatype'=>'u',
			'nullmsg'=>"请输入账号！",
			'errormsg'=>"账号只允许字母开头，允许5-16字节，允许字母数字下划线",
			'help'=>'用户名,登录后台的账号'
	);
	echo ace_input_m($options ,'user_name',$item->user_name,'maxlength="32"');
	
	$options = array(
	        'label_text'=>'昵称',
			'datatype'=>'*2-20',
			'nullmsg'=>"请输入昵称！",
			'errormsg'=>"用户昵称最少2字节",
	        'help'=>'昵称'
	);
	echo ace_input_m($options,'nickname',$item->nickname,'maxlength="20"');
	
	if(!$item->uid){
		$options = array('label_text'=>'密码','datatype'=>'pwd','help'=>lang('pwd_help_msg'),'errormsg'=>lang('pwd_help_msg'));
		echo ace_password($options, array('id'=>'q_password1','name'=>'password'));
	}
	
	$data = array('label_text'=>'所属用户组','help'=>'');
	echo ace_dropdown($data,'gid',$group_list,$item->gid);
	
	echo ace_group(ace_label('地区'),'<div id="city"></div>');
	

	$options = array(
	        'label_text'=>'E-mail',
	        'help'=>'用户的邮箱,格式：xx@xx.com'
	);
	echo ace_input($options,'email',$item->email,'maxlength="30"');
	
	if($item->uid){
		$options = array('disabled'=>'disabled','name'=>'','class'=>'width-100');
		echo ace_input('注册时间',$options,datetime($item->regtime));
		echo ace_input('注册IP',$options,$item->regip);
		echo ace_input('最后登入时间',$options,datetime($item->lastlogin));
		echo ace_input('最后登入IP',$options,$item->lastip);
	}
  	echo ace_srbtn('admin/user/index');
  echo ace_form_close()
?>
<script type="text/javascript">
    $(function(){

        $("input[type=password]").closest(".col-sm-5").addClass('col-sm-4').removeClass('col-sm-5');
        var qd_html = '<div class="passwordStrength"><b>密码强度：</b> <span class="">弱</span><span class="">中</span><span class="last">强</span></div>';

        $("#q_password1").closest('span').after(qd_html);
        
        $("#city").linkagesel({selected:'<?php echo $item->district?>'});
        
        $("#user_name").change(function(){
            
            user_name = $("#user_name").val();
            
            if(user_name !=""){
                $.post("<?php echo admin_base_url('user/index/ajaxUserName')?>",
                   { user_name: user_name }	,
                   function(data){
                       result = eval(data);
                       if(result.status == 1){
                           $("#user_name").val("");
                           $("#user_name").focus();
                            alert(result.info);
                       }
                    },"json"	
                );	
            } 	
        });
    });
</script>
