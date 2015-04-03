<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class ContentMake extends Admin_Controller {
	  
	function __construct() {
		   
		parent::__construct();
		
		$this->load->model(array('content_model','sharepage','rouji_model'));
		$this->model = $this->content_model;
		$this->sellerid = $this->user_info->sellerid;
		#$this->lang->load('admin');
	} 
		 
	function index(){
		$per_page	= (int)$this->input->get_post('per_page');
		
		$cupage	= config_item('site_page_num'); //每页显示个数
		
		$return_arr = array ('total_rows' => true );
		
		$where = array();
	  $where['sellerId']=$this->sellerid;	
	  $where['status']="OK ";	
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
			'order'		=> 'createTime desc',
		);
		
		$lc_list = $this->model->getAll($options, $return_arr); //查询所有信息
		
		$url = base_url('bp/contentManage/contentMake?'.$string);
		
		$page = $this->sharepage->showPage ($url, $return_arr ['total_rows'], $cupage );

		$data = array(
			'lc_list'	=> $lc_list,
			'page'		=> $page,	
			'title'		=> $title,	
			'totals'	=> $return_arr ['total_rows'],	 //数据总数
		); 
		
		$this->_template('bp/contentManage/contentMake/list',$data);  
	} 
	 
	public function show(){
		$contentId	 =	intval($this->input->get_post('id'));
		$options = array();
		if($contentId>0){
			$options['where'] = array('contentId'=>$contentId
			,'sellerId' => $this->sellerid
			);
		}
		$item = $this->model->getOne($options);
		$this->_template('bp/contentManage/contentMake/show',array('item'=>$item)); 
	}
	public function info(){
		$opt =array('select'=>'count(*) as c','where'=>array('sellerId'=>$this->sellerid));
		$opt['join'] = array(array('t_rouji_group r','r.groupid=t_rouji.groupid'));
		$having  = $this->rouji_model->getOne($opt); //查询所有信息
		if($having->c == 0)
		{
						$this->_template('message',array('msg'=>"请先添加任务机")); 
						return;
		}

	    if($this->input->is_post()){
	        $this->save();
	    }
	    
		$contentId	 =	intval($this->input->get_post('contentid'));
		$options = array();
		if($contentId>0){
			$options['where'] = array('contentId'=>$contentId
			,'sellerId' => $this->sellerid
			);
		}
		$item = $this->model->getOne($options);
		$this->_template('bp/contentManage/contentMake/info',array('item'=>$item)); 
	}  
	
	public function save(){

		$tpl ='attachments/tpl/pageTpl.html'	;    
		$contentId = (int)$this->input->get_post('contentid');
		
		$data['title'] = htmlspecialchars($this->input->get_post('title'));
		$data['smscontent'] = htmlspecialchars($this->input->get_post('smscontent'));
		$mobilepage = $this->input->get_post('mobilepage');
		//$data['mobilePage'] = htmlspecialchars($this->input->get_post('mobilepage'));
		//$data['pcPage'] = htmlspecialchars($this->input->get_post('pcpage'));
		//$data['padPage'] = htmlspecialchars($this->input->get_post('padpage'));
		$data['type'] = htmlspecialchars($this->input->get_post('type'));
		$data['shorturl'] = htmlspecialchars($this->input->get_post('shorturl'));
		//保存信息
		if($contentId>0){
			$data['contentId'] = $contentId;
			$result=$this->model->update($data);
		}else{
			$data['status'] = 'OK';
			$data['sellerid'] =	$this->sellerid;
			$data['createTime'] = date('Y-m-d H:i:s');
			$name = $this->sellerid.time();
			$filename = 'attachments/page/'.$name.'.html';
			$data['mobilepage'] = $filename;
		  $tpls = file_get_contents($tpl);	
			$str = str_replace('{body}',$mobilepage,$tpls);
			$rt = file_put_contents($filename,$str);
			if($rt === false){
							ajax_return(lang('save_failed').' file not save');
			}
			$result=$this->model->add($data);
		}
		
		//信息返回操作
		if($result){
			ajax_return(lang('save_success'),0,'',base_url('bp/contentManage/contentMake/'));
		}else{
			ajax_return(lang('save_failed').$result);
		}
		die;
	}
	
	function delete(){
	    $id = $this->input->get_post('contentid');
	    parent::delete($id,'RVK');
	}
	
}
 
?>
