<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
set_time_limit(60);

class ContentIssue extends Admin_Controller {
	 var $sellerid; 
	function __construct() {
		   
		parent::__construct();
		$this->load->model(array('issue_model','content_model','sharepage','rouji_group_model','issue_rouji_model','mass_sends_model','rouji_model','task_model'));
		$this->model = $this->issue_model;
		$this->sellerid = $this->user_info->sellerid;
	} 
		 
	function index(){
		$per_page	= (int)$this->input->get_post('per_page');
		
		$cupage	= config_item('site_page_num'); //每页显示个数
		
		$return_arr = array ('total_rows' => true );
		
		$where = array();
		$where['t_issue.status !=']="RVK";	
		$where['t_issue.sellerid']=$this->sellerid;	
		$title = htmlspecialchars($this->input->get_post("title"));
		$issueid = htmlspecialchars($this->input->get_post("issueid"));
		$like = array();
		$string = '';
		if($title=trim($title)){
			$like['title'] = $title;
			$string .="title=".$title;
		}
		$issueid=trim($issueid);
		if(!empty($issueid)){
			$where['issueid'] = $issueid;
			$string .="issueid=".$issueid;
		}
		$options	= array(
			'page'		=> $cupage,
			'per_page'	=> $per_page,
			'like'		=> $like,
			'select'		=> 't_issue.*,t.title',
			'where'		=> $where,
			'order'		=> 't_issue.createTime desc',
		);
		$options['join']= array(array('t_content t','t.contentid=t_issue.contentid'));
		
		$lc_list = $this->model->getAll($options, $return_arr); //查询所有信息
		foreach($lc_list as $k=>$v)
		{
			$row = $this->task_model->getOne(array('select'=>'count(*) as target','where'=>array('issueid'=>$v->issueid)));
			$lc_list[$k]->target = $row->target;
		}	
		$url = base_url('bp/contentManage/contentIssue?'.$string);
		
		$page = $this->sharepage->showPage ($url, $return_arr ['total_rows'], $cupage );

		$data = array(
			'lc_list'	=> $lc_list,
			'page'		=> $page,	
			'title'		=> $title,	
			'issueid'		=> $issueid,	
			'totals'	=> $return_arr ['total_rows'],	 //数据总数
		); 
		
		$this->_template('bp/contentManage/contentIssue/list',$data);  
	} 
	function edit()
	{
		$opt =array('select'=>'count(*) as c','where'=>array('sellerId'=>$this->sellerid));
		$opt['join'] = array(array('t_rouji r','r.groupid=t_rouji_group.groupid'));
		$having  = $this->rouji_group_model->getOne($opt); //查询所有信息
		if($having->c == 0)
		{
						$this->_template('message',array('msg'=>"请先添加任务机")); 
						return;
		}
		$id	 =	intval($this->input->get_post('id'));
		$options = array();
		if(!empty($id)){
			$options['where']['t_issue.sellerid']=$this->sellerid;	
			$options['where'] = array('issueid'=>$id);
		}
		$item = $this->model->getOne($options);
		$title  = $this->content_model->getAll(array('select'=>'contentid,title','where'=>array('status !='=>'RVK','sellerid'=>$this->sellerid),'order'=>'createtime desc')); //查询所有信息
		$titles = array();
		foreach($title as $v)
		{
			$titles[$v->contentid] = $v->title;
		}
		$groups  = $this->rouji_group_model->getAll(array('select'=>'groupid,groupname','where'=>array('sellerId'=>$this->sellerid))); //查询所有信息
		$group  = $this->issue_rouji_model->getAll(array('select'=>'groupid','group'=>'groupid','where'=>array('issueId'=>$id))); //查询所有信息
		$this->_template('bp/contentManage/contentIssue/edit',array('item'=>$item,'titles'=>$titles,'groups'=>$groups,'group'=>$group)); 
	} 

	public function info(){
		$opt =array('select'=>'count(*) as c','where'=>array('sellerId'=>$this->sellerid));
		$opt['join'] = array(array('t_rouji r','r.groupid=t_rouji_group.groupid'));
		$having  = $this->rouji_group_model->getOne($opt); //查询所有信息
		if($having->c == 0)
		{
						$this->_template('message',array('msg'=>"请先添加任务机")); 
						return;
		}

	    if($this->input->is_post()){
	        $this->save();
	    }
	    
		$id	 =	intval($this->input->get_post('id'));
		$options = array();
		if(!empty($id)){
			$options['where']['t_issue.sellerid']=$this->sellerid;	
			$options['where'] = array('issueid'=>$id);
		}
		$item = $this->model->getOne($options);
		$title  = $this->content_model->getAll(array('select'=>'contentid,title','where'=>array('status !='=>'RVK','sellerid'=>$this->sellerid),'order'=>'createtime desc')); //查询所有信息
		$titles = array();
		foreach($title as $v)
		{
			$titles[$v->contentid] = $v->title;
		}
		$groups  = $this->rouji_group_model->getAll(array('select'=>'groupid,groupname','where'=>array('sellerId'=>$this->sellerid))); //查询所有信息
		$group  = $this->issue_rouji_model->getAll(array('select'=>'groupid','group'=>'groupid','where'=>array('issueId'=>$id))); //查询所有信息
		if(empty($id)){
			$this->_template('bp/contentManage/contentIssue/info',array('item'=>$item,'titles'=>$titles,'groups'=>$groups,'group'=>$group)); 
		}else{
			$target = $this->task_model->getOne(array('select'=>'count(*) as c','where'=>array('issueId'=>$id)));
			$this->_template('bp/contentManage/contentIssue/show',array('item'=>$item,'titles'=>$titles,'groups'=>$groups,'group'=>$group,'target'=>$target->c)); 
		}
	}  
	
	public function save(){

		$id = (int)$this->input->get_post('id');
		$data['contentid'] = htmlspecialchars($this->input->get_post('contentid'));
		$data['starttime'] = htmlspecialchars($this->input->get_post('starttime'));
		$data['endtime'] = htmlspecialchars($this->input->get_post('endtime'));
		if($id>0){
			$data['issueid'] = $id;
			$result=$this->model->update($data);
		}else{
			$data['status'] = 'RVK';
			$data['createTime'] = date('Y-m-d H:i:s');
			$data['sellerid'] = $this->sellerid;
			$result=$this->model->add($data);
		}
		//信息返回操作
		if($result){
			//ajax_return(lang('save_success'),0,'',base_url('bp/contentManage/contentMake/'));
			$id = $id=='0'?$this->model->last_insert_id():$id;
			echo '{"status":"0","info":"成功","id":'.$id.'}';
		}else{
			echo '{"status":"1","info":"","id":""}';
		}
		die;
	}
	function addgroup()
	{
		$groupids = $this->input->get_post('groupid');
		$id = (int)$this->input->get_post('id');
	  $in = join(',',$groupids) ;
		$options = array();
		$options['select'] = "groupid,mobileNum";
		$options['where_in'] = array('groupid'=>$in);
		$data  = $this->rouji_model->getAll($options);
		if(empty($data))
		{
			echo '{"status":"1","info":"选择的组的任务机为空"}';
			exit;
		}
		$tmp = array();
		foreach($data  as $k=>$v)
		{
			$tmp[$k]['roujiMobileNum'] = $v->mobilenum;
			$tmp[$k]['groupid'] = $v->groupid;
			$tmp[$k]['issueid'] = $id;
			$tmp[$k]['status'] = 'ABL';
		}
		$this->issue_rouji_model->query("delete from t_issue_rouji where issueid ='$id'",'');
		$rt = $this->issue_rouji_model->addBatch($tmp);
		if($rt)
		{
						echo '{"status":"0","info":""}';
		}else{
						echo '{"status":"1","info":"保存任务机失败"}';
		}
	}
	function upload()
	{
		if(isset($_FILES['files']['tmp_name'])&&!is_uploaded_file($_FILES['files']['tmp_name'])){
            ajax_return(lang('has_no_upload_file'));
		}

	  $path = "attachments/data/";  
		$rempath = $path.md5_file($_FILES['files']['tmp_name']).'.txt';
		$upload_flag = @move_uploaded_file($_FILES['files']['tmp_name'], $rempath);
		if(!$upload_flag)
		{
						ajax_return("文件上传失败！");
						exit;
		}
		$id = (int)$this->input->get_post('id');
		$this->mass_sends_model->add(array('massSendsPath'=>$rempath,'issueId'=>$id));
		echo '{"status":0,"info":"上传成功"}';
		ignore_user_abort(true);
		$size=ob_get_length();
		header("Content-Length: $size");
		header("Connection: Close");
		ob_flush();
		$tmp = $this->getIssue($id);
		$this->addTarget($rempath,$tmp);
		$this->model->update(array('issueid'=>$id,'status'=>'CRT'));
	}	
	private function getIssue($id){
		$option = array('select'=>'issueid,startTime,endTime,type,e.contentId,title','where'=>array('issueid'=>$id));
		$option['join'] =array(array('t_content e','e.contentid=t_issue.contentid'));
		$item = $this->model->getOne($option);
		return get_object_vars($item);
  }
	private  function addTarget($file,$data)
	{
		$data['status'] = 'CRT';
		$handle = @fopen($file, "r");
		$i=0;
		$tmp = array();
		while (($buffer = fgets($handle, 4096)) !== false) {
			$str = trim($buffer);	
			$data['sendsMobileNum'] = $str;
			$tmp[] = $data;
			if($i==1000)
			{	
				$this->task_model->addBatch($tmp)	;
				unset($tmp);
				$i=0;
				$tmp = array();
			}
			$i++;
		} 
		if(sizeof($tmp)>0)
		{
			$this->task_model->addBatch($tmp) ;
			//	print_r($tmp);
		}
		fclose($handle);
	}
	function revoke(){
	    $id = $this->input->get_post('id');
			$data['status']='RVK';
			$data['issueid']=$id;;
			$result = $this->model->update($data);
    	if($result){
    		echo json_encode(array('status'=> '1','msg'=>'撤销成功'));exit();
    	}else{
    		echo json_encode(array('status'=> '2','msg'=>'撤销失败'));exit();
    	}
	}
	
}
 
?>
