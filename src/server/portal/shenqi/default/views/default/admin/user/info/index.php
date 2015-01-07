<?php 
    echo ace_header(array_values($this->breadcrumb),'user/info');

    echo ace_form_open();

    echo ace_input('昵称','nickname',$item['nickname']);
    
    echo ace_input('邮箱','email',$item['email']);
    
    echo ace_srbtn(null,false);
    
    echo ace_form_close();
?>