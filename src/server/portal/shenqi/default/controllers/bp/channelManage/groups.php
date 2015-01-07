<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Groups extends Admin_Controller {
	 var $sellerid; 
	function __construct() {
		   
		parent::__construct();
		$this->load->model(array('rouji_group_model','rouji_model','sharepage'));
		$this->model = $this->rouji_group_model;
		$this->sellerid = $this->user_info->sellerid;
	} 
		 
	function index(){
		$per_page	= (int)$this->input->get_post('per_page');
		
		$cupage	= config_item('site_page_num'); //每页显示个数
		
		$return_arr = array ('total_rows' => true );
		
		$like = array();
		$name  = htmlspecialchars($this->input->get_post("groupName"));
		$string = '';
		if(!empty($name)){
			$like['groupname'] = $name;
			$string .="&groupname=".$name;
		}
		$where = array('sellerid'=>$this->sellerid);
		$options	= array(
			'page'		=> $cupage,
			'per_page'	=> $per_page,
			'like'		=> $like,
			'select'		=> '*',
			'where'		=> $where
		);
		
		$lc_list = $this->model->getAll($options, $return_arr); //查询所有信息
		
		$url = base_url('bp/channelManage/groups?'.$string);
		
		$page = $this->sharepage->showPage ($url, $return_arr ['total_rows'], $cupage );

		$data = array(
			'lc_list'	=> $lc_list,
			'page'		=> $page,	
			'name'		=> $name,	
			'totals'	=> $return_arr ['total_rows'],	 //数据总数
		); 
		
		$this->_template('bp/channelManage/groupList',$data);  
	} 
	 

	public function info(){

	    if($this->input->is_post()){
	        $this->save();
	    }
	    
		$id	 =	intval($this->input->get_post('id'));
		$options = array();
		if($id>0){
	     $options['sellerid']=$this->sellerid;	
			$options['where'] = array('groupid'=>$id);
		}
		$item = $this->model->getOne($options);
	  $this->_template('bp/channelManage/groupInfo',array('item'=>$item)); 
	}  
	
	public function save(){

		$id = (int)$this->input->get_post('id');
		$data['groupName'] = htmlspecialchars($this->input->get_post('groupname'));
		if($id>0){
			$data['groupId'] = $id;
			$result=$this->model->update($data);
		}else{
			$data['sellerid'] = $this->sellerid;
			$result=$this->model->add($data);
		}
		//信息返回操作
		if($result){
			ajax_return(lang('save_success'),0,'',base_url('bp/channelManage/groups'));
		}else{
			ajax_return(lang('save_failed'),1);
		}
		die;
	}
	function delete()
  {
					$id = $this->input->get_post('id');
					$result = $this->model->delete(array('where'=>array('groupid'=>$id,'sellerid'=>$this->sellerid)));
					if($result){
									ajax_return('删除成功',1,'',base_url('bp/channelManage/groups'));
					}else{
									ajax_return("删除失败",0);
					}
  }
  function ajaxName()
	{
		$name = $this->input->get_post('groupname'); //字段值
		$options = array('select'=>'*');
		if(!empty($name)){
			$options['where'] = array('groupname'=>$name,'sellerId'=>$this->sellerid);
		}
		$item = $this->rouji_group_model->getOne($options);
    //if(isset($item)&&!empty($item->agentid)&&$item->agentid!=$id){
    if(isset($item)&&!empty($item->groupname)){
    	echo json_encode(array('status'=> '1','info'=>'组名称已经存在'));exit();
    }else{
    	echo json_encode(array('status'=> '0','info'=>'可用'));exit();
    }

  }	
}
 
?>
