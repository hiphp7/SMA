<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Colum extends Admin_Controller {
	  
	function __construct() {
		   
		parent::__construct();
		
		$this->load->model('sharepage');
		$this->model = $this->colum_model;
	} 
		 
	function index(){
		$where = array();
		
		if($this->user_info->key != 'root'){
			$where = array('status >='=>0);
		}
		
		$title = htmlspecialchars(trim($this->input->get_post("title")));
		$type = htmlspecialchars(trim($this->input->get_post("type")));

		$options	= array(
	        'type'      =>$type ? $type : 'admin',   
			'where'		=> $where,
			'order'		=> " order_id desc,cid desc",
		);
		
		$lc_list = $this->model->getAll2Array($options); //查询所有信息
		$tree = new Tree();
		$tree->init($lc_list);
		if($title){
			$newTree = $tree->findTreeByName($title);
			if($newTree){
				$tree->setTree(array($newTree['cid']=>$newTree));
			}
		}
		
		$data = array(
        			'lc_tree'    => $tree,
        			'type'	     => $type,
        			'title'	     => $title,
        	        'fileter_options' => array('title'=>$title),
        	        'page'       => ''
        		);
		$this->_template('admin/setting/columlist',$data);
	} 
	 

	public function info($type='admin'){
	    parent::info();
	    
		$cid	=	intval($this->input->get_post('cid'));

		$options = array();
		if($cid>0){
			$options['where'] = array('cid'=>$cid);
		}
		
		$item = $this->model->getOne($options,true);
		$this->parameters->fromArray(json_to_array($item['params']));
		$item['params'] = $this->parameters;
		
		$parents = array('顶级栏目');
		

		$where = array();
		
		if($this->user_info->key != 'root'){
		    $where = array('status >='=>0);
		}
		$item['type'] = $item['type'] ? $item['type'] : $type;
		$options	= array(
		        'type'      =>$item['type'],
		        'where'		=> $where,
		        'order'		=> " order_id desc,cid desc",
		);
		
		$tree = new Tree();
		$tree->init($this->model->getAll2Array($options));
		
		foreach ($tree->getValueOptions() as $k=>$tmp){
		    $parents[$k] = $tmp['title'];
		}
		
		$data = array(
			'parents' => $parents,
			'item'	=> $item,	
		); 

		$this->_template('admin/setting/columinfo',$data);
	}  
	
	public function save(){
		
		$cid = (int)$this->input->get_post('cid');
		
		$data['title'] = htmlspecialchars($this->input->get_post('title'));
		
		$data['directory'] = htmlspecialchars($this->input->get_post('directory'));

		$data['con_name'] = htmlspecialchars($this->input->get_post('con_name'));
		
		$data['parents'] = intval($this->input->get_post('parents'));
		
		$data['type'] = htmlspecialchars($this->input->get_post('type'));
		
		$data['params'] = json_encode($this->input->get_post('params'));
		//保存信息
		if($cid>0){
			$data['cid'] = $cid;
			$result=$this->model->update($data);
		}else{
			
			$result=$this->model->add($data);
		}
		
		//信息返回操作
		if($result){

		    ajax_return(lang('save_success'),0,'','/admin/setting/colum');
	    }else{
	        ajax_return(lang('save_failed'));
	    }
		
		die;  
	}
	
	//ajax排序
	public function order_insert(){
		
		$val = intval($this->input->get_post('val'));
		
		$id = intval($this->input->get_post('id'));
		
		$field = trim($this->input->get_post('field'));
		
		if($id>0){
			
			$data[$field] = $val;
			$data['cid'] = $id;
			$this->model->update($data);
			
			echo json_encode(array('status'=> '1','msg'=>'修改成功'));exit();
		}else{
			
			echo json_encode(array('status'=> '2','msg'=>"出错啦"));exit();
		}
	}
	
	
}
 
?>
