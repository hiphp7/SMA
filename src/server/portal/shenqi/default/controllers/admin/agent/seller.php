<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Seller extends Admin_Controller {
	function __construct() {
		   
		parent::__construct();
		$this->load->model(array('seller_model','sharepage'));
		$this->model = $this->seller_model;
		$this->agentId= 'master';
	} 
		 
	function index(){
		$per_page	= (int)$this->input->get_post('per_page');
		
		$cupage	= config_item('site_page_num'); //每页显示个数
		
		$return_arr = array ('total_rows' => true );
		
		$like = array();
		$name  = htmlspecialchars($this->input->get_post("sellername"));
		$string = '';
		if(!empty($name)){
			$like['sellername'] = $name;
			$string .="&sellername=".$name;
		}
		$where = array('agentId'=>$this->agentId);
		$options	= array(
			'page'		=> $cupage,
			'per_page'	=> $per_page,
			'like'		=> $like,
			'select'		=> '*',
			'where'		=> $where,
			'order'		=> 'createtime desc'
		);
		
		$lc_list = $this->model->getAll($options, $return_arr); //查询所有信息
		
		$url = admin_base_url('agent/seller?'.$string);
		
		$page = $this->sharepage->showPage ($url, $return_arr ['total_rows'], $cupage );

		$data = array(
			'lc_list'	=> $lc_list,
			'page'		=> $page,	
			'name'		=> $name,	
			'totals'	=> $return_arr ['total_rows'],	 //数据总数
		); 
		$this->_template('admin/agent/sellerList',$data);  
	} 
	 
	public function show(){
		$id	 =	$this->input->get_post('id');
		$options = array();
		if(!empty($id)){
			$options['where'] = array('sellerId'=>$id);
		}
		$item = $this->model->getOne($options);
		if(empty($item))
		{
			show_404();
		}
	  $this->_template('admin/agent/sellerShow',array('item'=>$item)); 
	}  

	public function info(){

	    if($this->input->is_post()){
	        $this->save();
	    }
	    
		$id	 =	$this->input->get_post('id');
		$options = array();
		if(!empty($id)){
			$options['where'] = array('agentId'=>$id);
		}
		$item = $this->model->getOne($options);
	  $this->_template('admin/agent/sellerInfo',array('item'=>$item)); 
	}  
	
	public function save(){

		$data['sellerName'] = htmlspecialchars($this->input->get_post('sellername'));
		$data['sellerId'] = htmlspecialchars($this->input->get_post('sellerid'));
		$data['shortName'] = htmlspecialchars($this->input->get_post('shortname'));
		$data['mobileNum'] = htmlspecialchars($this->input->get_post('mobilenum'));
		$data['password'] = md5(trim($this->input->get_post('password')));
		$data['qq'] = htmlspecialchars($this->input->get_post('qq'));
		$data['agentId'] =$this->agentId;
		$data['contact'] = htmlspecialchars($this->input->get_post('contact'));
		$data['status'] = 'ON';
		$data['createtime'] = date('Y-m-d H:i:s');
		$data['updatetime'] = date('Y-m-d H:i:s');
		
		$result=$this->model->add($data);
		if($result){
			ajax_return(lang('save_success'),0,'',admin_base_url('agent/seller'));
		}else{
			ajax_return(lang('save_failed'),1);
		}
		die;
	}
	function delete()
  {
/*
					$id = $this->input->get_post('id');
					$this->seller_model->delete(array('where'=>array('agentId'=>$id)));
					$this->model->delete(array('where'=>array('agentId'=>$id)));
					if($result){
									ajax_return('删除成功',1,'',base_url('agent/agent'));
					}else{
									ajax_return("删除失败",0);
					}
 */ 
  }
    public function clicktik(){
    
    	$val = $this->input->get_post('val'); //字段值
			$val = $val==1?'ON':'OFF';
    
    	$field = htmlspecialchars($this->input->get_post('field'));//所要操作的字段
    	$field = $field ? $field : 'status';
    	
    	$result = null;
    	if($this->model){
	    	$data[$field] = $val;
	    	$data[$this->model->getPK()] = $this->input->get_post('id'); //所在的字段ID,主键
	    	//修改信息
	    	$result = $this->model->update($data);
    	}
    
    	//信息返回操作
    	if($result){
    		echo json_encode(array('status'=> '1','msg'=>'修改成功'));exit();
    	}else{
    		echo json_encode(array('status'=> '2','msg'=>'修改失败'));exit();
    	}
   } 
	
	function ajaxId()
	{
		$id = $this->input->get_post('sellerid'); //字段值
		$options = array('select'=>'*');
		if(!empty($id)){
			$options['where'] = array('sellerId'=>$id);
		}
		$item = $this->model->getOne($options);
    if(isset($item)&&!empty($item->sellerid)){
    	echo json_encode(array('status'=> '1','info'=>'商户编号已经存在'));exit();
    }else{
    	echo json_encode(array('status'=> '0','info'=>'可用'));exit();
    }
	}
	function ajaxName()
	{
		$id = $this->input->get_post('sellername'); //字段值
		$options = array('select'=>'*');
		if(!empty($id)){
			$options['where'] = array('sellerName'=>$id);
		}
		$item = $this->model->getOne($options);
    if(isset($item)&&!empty($item->sellerid)){
    	echo json_encode(array('status'=> '1','info'=>'商户名称已经存在'));exit();
    }else{
    	echo json_encode(array('status'=> '0','info'=>'可用'));exit();
    }
	}
	function ajaxShort()
	{
		$short = $this->input->get_post('sellershort'); //字段值
		$id = $this->input->get_post('sellershort'); //字段值
		$options = array('select'=>'*');
		if(!empty($short)){
			$options['where'] = array('shortname'=>$short);
		}
		$item = $this->model->getOne($options);
    //if(isset($item)&&!empty($item->agentid)&&$item->agentid!=$id){
    if(isset($item)&&!empty($item->sellerid)){
    	echo json_encode(array('status'=> '1','info'=>'商户简称已经存在'));exit();
    }else{
    	echo json_encode(array('status'=> '0','info'=>'可用'));exit();
    }
	}
	
	function modify_pwd(){
	    if($this->input->is_post()){
	        $this->updatePassword();
	    }
		$id=$this->input->get_post('id');
		$row = $this->model->getOne(array('where'=>array('sellerid'=>$id,'agentId'=>$this->agentId)));
		if(empty($row))
		{
		  $this->_template('message',array('msg'=>'修改的商户不存在'));
		  return;
		}
		$this->_template('admin/agent/reset',array('id'=>$id));
	}	
	function updatePassword(){

	    //加载表单验证类
	    $this->load->library('form_validation');
	    //开始验证,验证规则在config/form_validation.php
	    $valid = $this->form_validation->run('index/_reset_password');
	    if(!$valid){
	        //未通过验证
	        $msg = $this->form_validation->error_string();
	        ajax_return($msg,3);
	    }
	    
		$uid = $this->agentId;
		
		$options = array();
		
		$options['where']['sellerid'] = $this->input->get_post('sellerid');
		$options['where']['agentId'] = $uid;
		
		$password             = $this->input->get_post('password');
		
		$data                 = array();
		
		$data['password']     = md5($password);
		
		$tof = $this->model->update($data,$options);
		
		//信息返回操作
		if($tof){
		    ajax_return(lang('update_success'),0,'',admin_base_url('agent/seller'));
	    }else{
	        ajax_return(lang('update_pwd_failed'));
	    }
	}
}
 
?>
