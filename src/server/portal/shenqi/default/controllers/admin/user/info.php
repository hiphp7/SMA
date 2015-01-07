<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Info extends Admin_Controller{
	
	function __construct(){
		parent::__construct();
		$this->model = $this->user_model;
	}
	
	/**
	 * 
	 *修改个人资料
	 */
	function index(){

	    if($this->input->is_post()){
	        $this->updateUserInfo();
	    }
	    
		$user_id = $this->user_info->uid;
		
		$user = $this->model->getOne(intval($user_id),TRUE);
		
		$data = array(
			'item'	=> $user,
		);
		
		$this->_template('admin/user/info/index',$data);
	}
	
	/**
	 * 
	 * 修改密码
	 */
	function modify_pwd(){

	    if($this->input->is_post()){
	        $this->updatePassword();
	    }
		$this->_template('admin/user/info/pwd');
		
	}
	
	function updateUserInfo(){
	    //加载表单验证类
	    $this->load->library('form_validation');
	    //开始验证,验证规则在config/form_validation.php
	    $valid = $this->form_validation->run('admin/user/info/update');
	    if(!$valid){
	        //未通过验证
	        $msg = $this->form_validation->error_string();
	        ajax_return($msg,3);
	    }
	    
		$uid = $this->user_info->uid;
		
		$options = array();
		
		$options['where']['uid'] = $uid;
		
		$data = array();
		
		$data['nickname'] = trim($this->input->get_post('nickname'));
		
		$data['email'] = trim($this->input->get_post('email'));
		
		$tof = $this->model->update($data,$options);
		
		//信息返回操作
		if($tof){
	        ajax_return(lang('save_success'),0,'','/admin/main');
	    }else{
	        ajax_return(lang('save_failed'));
	    }
		
	}
	
	function updatePassword(){

	    //加载表单验证类
	    $this->load->library('form_validation');
	    //开始验证,验证规则在config/form_validation.php
	    $valid = $this->form_validation->run('index/_update_password');
	    if(!$valid){
	        //未通过验证
	        $msg = $this->form_validation->error_string();
	        ajax_return($msg,3);
	    }
	    
		$uid = $this->user_info->uid;
		
		$options = array();
		
		$options['where']['uid'] = $uid;
		
		$password             = $this->input->get_post('password');
		
		$data                 = array();
		
		$data['password']     = md5($password);
        $old_password         = md5($this->input->post('old_password'));
        if($data['password'] == $old_password){
            ajax_return(lang('new_pass_old_pass_same'));
        }
        
		$options['where']['password'] = $old_password;
		
		$tof = $this->model->update($data,$options);
		
		//信息返回操作
		if($tof){
		    ajax_return(lang('update_success'),0,'','/admin/main');
	    }else{
	        ajax_return(lang('update_pwd_failed'));
	    }
		    
	}
}
