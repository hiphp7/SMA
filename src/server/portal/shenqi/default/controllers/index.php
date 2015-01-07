<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Index extends MY_Controller { 
 
    function __construct()  {  
        parent::__construct(); 
    }  
    
    function user_clause(){
        echo 'coming song!';   
    }
    
    function index()   {  
/*
        $server_name = $_SERVER['SERVER_NAME'];
        if($server_name == 'admin.pass.com'){
            
            $this->admin();
        }elseif($server_name == 'bp.pass.com'){
            
            $this->bp();
        }else{
            $data = array('action'=>site_url('login/ajax_login_user/agent'));
            $this->load->view('agent/login',$data);
        }
        */
    }  
    
    function admin()   {  
        $data = array('action'=>site_url('login/ajax_login_user'));
        $this->load->view('admin/login',$data);
    }  
    
    function bp()   {
        $data = array('action'=>site_url('login/ajax_login_user/bp'));
        $this->load->view('bp/login',$data);
    }
    function agent()   {
				$data = array('action'=>site_url('login/ajax_login_user/agent'));
				$this->load->view('agent/login',$data);
    }
    
    function forgot_pwd($type='bp')   {
        
        $this->load->library('form_validation');
        //开始验证,验证规则在config/form_validation.php
        $valid = $this->form_validation->run('index/reg');
        if(!$valid){
            //未通过验证
            $msg = $this->form_validation->error_string();
            ajax_return($msg,3);
        }

        $pwd = trim($this->input->post('pwd'));
        if(!is_password($pwd)){
            ajax_return(lang('pwd_format_is_not_valid'));
        }
        
        $model = $this->bp_users_model;
        if($type == 'user_info'){
            $model = $this->user_info_model;
        }
        
        $user = null;
        $mobile = $this->input->post('mobile');
        if($mobile){
            $username = htmlspecialchars(trim($this->input->post('username')));
            if($type == 'bp' && $username == ''){
                
                ajax_return('BP帐号不能为空！');
            }elseif($type == 'bp'){
                
                $user = $model->getOne(array('user_id'=>$username,'mobile'=>$mobile));
            }else{
                
                $user = $model->getOne(array('mobile'=>$mobile));
            }
        }
        
        //判断用户是否存在
        if(!$user){
            if($type == 'bp'){
                ajax_return(lang('user_not_exist_or_valid'));
            }
            ajax_return(lang('user_not_exist'),3);
        }
        
        $sms_code       = $this->input->post('sms_code');
        
        $this->load->model('sms_code_model');
        $one          = $this->sms_code_model->get_u_sms_code($mobile);
        
        //没有发送验证码
        if(!$one){
            ajax_return(lang('not_send_sms'),3);
        }
        
        //验证码不正确
        if($sms_code != $one->smscode){
            ajax_return(lang('sms_incorrect'),3);
        }
        //验证码过期
        if((time()-strtotime($one->send_date)) > $one->expire_in){
            ajax_return(lang('sms_code_time_out'),3);
        }
        
        $password = md5($pwd);
        $flag = false;
        
        if($type == 'bp'){
            $flag = $model->update(array('password'=>$password),array('id'=>$user->id));
        }else{
            $flag = $model->update(array('pwd'=>$password),array('uuid'=>$user->uuid));
        }
        
        if($flag){
            ajax_return('重置密码成功，请重新登录',0);
        }
        
        ajax_return('重置密码失败，请使用新密码登录！');
    }
    
    private function _reset_user_info_pwd($mobile){
        $user = $this->user_info_model->getOne(array('mobile'=>$mobile));
    }
} 
