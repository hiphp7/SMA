<?php 
    echo ace_header(array_values($this->breadcrumb),'user/info');

    echo ace_form_open();

	$options = array(
	        'label_text'=>'昵称',
			'datatype'=>'*2-20',
			'nullmsg'=>"请输入昵称！",
			'errormsg'=>"用户昵称最少2字节",
	        'help'=>'昵称'
	);
    echo ace_input_m($options,'nickname',$item['nickname'],'maxlength="20"');
    
    echo ace_input('邮箱','email',$item['email']);
    
    echo ace_srbtn(null,false);
    
    echo ace_form_close();
?>
