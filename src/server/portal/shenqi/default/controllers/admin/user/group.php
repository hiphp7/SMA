<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Group extends Admin_Controller {
	  
	function __construct() {
		   
		parent::__construct();
		
		$this->load->model(array('group_model','sharepage'));
		$this->model = $this->group_model;
		$this->lang->load('admin');
	} 
		 
	function index(){
		$per_page	= (int)$this->input->get_post('per_page');
		
		$cupage	= config_item('site_page_num'); //每页显示个数
		
		$return_arr = array ('total_rows' => true );
		
		$where = array();
		if($this->user_info->key != 'root'){
			$where['status >='] = 0;
			$where['gid !='] = 1;
		}
		
		$title = htmlspecialchars($this->input->get_post("title"));
		
		$like = array();
		$string = '';
		if($title=trim($title)){
			
			$like['title'] = $title;
			$string .="title=".$title;
		}

		$options	= array(
			'page'		=> $cupage,
			'per_page'	=> $per_page,
			'like'		=> $like,
			'where'		=> $where,
			'order'		=> 'gid desc',
		);
		
		$lc_list = $this->model->getAll($options, $return_arr); //查询所有信息
		
		$url = admin_base_url('user/group?');
		
		$page = $this->sharepage->showPage ($url, $return_arr ['total_rows'], $cupage );

		$data = array(
			'lc_list'	=> $lc_list,
			'page'		=> $page,	
			'title'		=> $title,	
			'totals'	=> $return_arr ['total_rows'],	 //数据总数
		); 
		
		$this->_template('admin/user/grouplist',$data);  
	} 
	 

	public function info(){

	    if($this->input->is_post()){
	        $this->save();
	    }
	    
		$gid	 =	intval($this->input->get_post('gid'));
		$options = array();
		if($gid>0){
			$options['where'] = array('gid'=>$gid);
		}
		$item = $this->model->getOne($options);
		if($item){
			$item->array_group = explode(",",$item->items);
		}else{
			$item->array_group = array();
		}
		
		$data = array(
		
			'item'	=> $item,	
			'colum_list' => $this->tree->getValueOptions()
		); 

		$this->_template('admin/user/groupinfo',$data); 
	}  
	
	public function save(){

	    
		$gid = (int)$this->input->get_post('gid');
		
		$data['title'] = htmlspecialchars($this->input->get_post('title'));
		
		//判断名称是否有重复
		$item = $this->model->getOne(array('title'=>$data['title']));
		if($item && intval($item->gid) != intval($gid)){
		    ajax_return(lang('group_name_exist'));
		}
		
		$items = $this->input->get_post('items');
		if(!$items){
		    ajax_return('请至少选择一个权限栏目！');
		}
		$data['items'] = @implode(",",$items);
		
		//保存信息
		if($gid>0){
			$data['gid'] = $gid;
			$result=$this->model->update($data);
			$this->user_model->update(array('token'=>''),array('gid'=>$gid));
		}else{
			
			$result=$this->model->add($data);
		}
		
		//信息返回操作
		if($result){
			ajax_return(lang('save_success'),0,'','/admin/user/group');
		}else{
			ajax_return(lang('save_failed'));
		}
		
		die;
	}
	
	function delete(){

	    $id = $this->input->get_post('id');
	    
	    if($this->user_model->getOne(array('gid'=>$id))){

	        echo json_encode(array('status'=> 3,'msg'=>'该角色下还有用户，请先删除其下所有用户，再删除该角色！'));
	        exit();
	    }
	    parent::delete();
	}
	
	/**
	 * 状态修改
	 */
	public function clicktik(){
	
	    $val = $this->input->get_post('val'); //字段值
	    if(!$val){
	        $id = intval($this->input->get_post('id'));
	        $this->user_model->update(array('token'=>''),array('gid'=>$id));
	    }
	
	    parent::clicktik();
	}
}
 
?>
