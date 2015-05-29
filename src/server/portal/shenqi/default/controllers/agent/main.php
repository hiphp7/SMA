<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Main extends Admin_Controller { 
 
    function __construct()  {  
        parent::__construct(); 
				$this->load->model(array('agent_model'));
				$this->model = $this->agent_model;
    }  
    
    function index()   {  
        
        $this->_template('agent/main');
    }  
	function info(){
	    if($this->input->is_post()){
	        $this->update();
	    }
		$row =$this->model->getOne(array('agentid'=>$this->user_info->agentid));
		$this->_template('agent/info',array('item'=>$row));
	}	
	function update()
	{
		$data['mobileNum'] = htmlspecialchars($this->input->get_post('mobilenum'));
		$data['qq'] = htmlspecialchars($this->input->get_post('qq'));
		$data['contact'] = htmlspecialchars($this->input->get_post('contact'));
		$data['updatetime'] = date('Y-m-d H:i:s');
		$data['agentid'] = $this->user_info->agentid;
		$result=$this->model->update($data);
		if($result){
			ajax_return(lang('save_success'),0,'',base_url('agent/main'));
		}else{
			ajax_return(lang('save_failed'),1);
		}
	}
	function modify_pwd(){
	    if($this->input->is_post()){
	        $this->updatePassword();
	    }
		$this->_template('agent/pwd');
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
	    
		$uid = $this->user_info->agentid;
		
		$options = array();
		
		$options['where']['agentid'] = $uid;
		
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
		    ajax_return(lang('update_success'),0,'',base_url('agent/main'));
	    }else{
	        ajax_return(lang('update_pwd_failed'));
	    }
		    
	}
} 
