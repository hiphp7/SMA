<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Rouji extends Admin_Controller {
	 var $sellerid; 
	function __construct() {
		   
		parent::__construct();
		$this->load->model(array('rouji_model','sharepage','rouji_group_model'));
		$this->model = $this->rouji_model;
		$this->sellerid = $this->user_info->sellerid;
	} 
		 
	function index(){
		$per_page	= (int)$this->input->get_post('per_page');
		
		$cupage	= config_item('site_page_num'); //每页显示个数
		
		$return_arr = array ('total_rows' => true );
		
		$where = array();
		$mobileNum = htmlspecialchars($this->input->get_post("mobileNum"));
		$userName = htmlspecialchars($this->input->get_post("userName"));
		$groupId = htmlspecialchars($this->input->get_post("groupId"));
		$like = array();
		$string = '';
		if(!empty($userName)){
			$like['userName'] = $userName;
			$string .="&userName=".$userName;
		}
		if(!empty($mobileNum)){
			$like['mobileNum'] = $mobileNum;
			$string .="&mobileNum=".$mobileNum;
		}
		if(!empty($groupId)){
			$like['t.groupId'] = $groupId;
			$string .="&groupId=".$groupId;
		}
		$where['sellerid'] = $this->sellerid;
		$options	= array(
			'page'		=> $cupage,
			'per_page'	=> $per_page,
			'like'		=> $like,
			'select'		=> 't_rouji.*,groupname',
			'where'		=> $where,
			'order'		=> 't_rouji.createTime desc',
		);
		$options['join']= array(array('t_rouji_group t','t.groupId=t_rouji.groupId'));
		
		$lc_list = $this->model->getAll($options, $return_arr); //查询所有信息
		
		$url = base_url('bp/channelManage/rouji?'.$string);
		
		$page = $this->sharepage->showPage ($url, $return_arr ['total_rows'], $cupage );
		$groups  = $this->rouji_group_model->getAll(array('select'=>'groupid,groupname','where'=>array('sellerId'=>$this->sellerid))); //查询所有信息

		$data = array(
			'lc_list'	=> $lc_list,
			'page'		=> $page,	
			'mobileNum'		=> $mobileNum,	
			'groupId'		=> $groupId,	
			'groups'		=> $groups,	
			'userName'		=> $userName,	
			'totals'	=> $return_arr ['total_rows'],	 //数据总数
		); 
		
		$this->_template('bp/channelManage/roujiList',$data);  
	} 
	 

	public function info(){

	    if($this->input->is_post()){
	        $this->save();
	    }
	    
		$id	 =	$this->input->get_post('id');
		$options = array();
		if(!empty($id)){
			$options['where'] = array('mobileNum'=>$id);
		}
		$item = $this->model->getOne($options);
		$groups  = $this->rouji_group_model->getAll(array('select'=>'groupid,groupname','where'=>array('sellerid'=>$this->sellerid))); //查询所有信息
	  $this->_template('bp/channelManage/roujiInfo',array('item'=>$item,'groups'=>$groups)); 
	}  
	
	public function save(){

		$id = $this->input->get_post('id');
		$data['mobileNum'] = $id;
		$data['userName'] = htmlspecialchars($this->input->get_post('userName'));
		$data['groupId'] = htmlspecialchars($this->input->get_post('groupId'));
		if(!empty($id)){
			$result=$this->model->update($data);
		}else{
		  $data['mobileNum'] = $this->input->get_post('mobileNum');
			$data['createTime'] = date('Y-m-d H:i:s');
			$result=$this->model->add($data);
		}
		//信息返回操作
		if($result){
			ajax_return(lang('save_success'),0,'',base_url('bp/channelManage/rouji'));
		}else{
			ajax_return(lang('save_failed'),1);
		}
		die;
	}
	function upload()
	{
	    if(!$this->input->is_post()){
							$groups  = $this->rouji_group_model->getAll(array('select'=>'groupid,groupname','where'=>array('sellerid'=>$this->sellerid))); //查询所有信息
							$this->_template('bp/channelManage/roujiBatch',array('groups'=>$groups));  
							return ;
	    }
		if(isset($_FILES['files']['tmp_name'])&&!is_uploaded_file($_FILES['files']['tmp_name'])){
            ajax_return(lang('has_no_upload_file'));
		}

	  $path = "attachments/data/";  
		//$rempath = $path.md5_file($_FILES['files']['tmp_name']).'.csv';
		//$upload_flag = @move_uploaded_file($_FILES['files']['tmp_name'], $rempath);
		/*if(!$upload_flag)
		{
						ajax_return("文件上传失败！");
						exit;
		}*/
		$id = (int)$this->input->get_post('groupId');
		$data = array();
		try{
						include 'shared/libraries/Excel/Excel_Reader.php';
						$reader = new Excel_Reader();
						$reader->setOutputEncoding('UTF-8');
						$reader->read($_FILES['files']['tmp_name']);
						$tmp = $reader->sheets[0]['cells'];
						$time = date('Y-m-d H:i:s');
						foreach( $tmp as $k=> $v)
						{
										if($k==1)
										{
														continue;
										}
										if($this->roujiExist($v[1]))
										{
														echo '{"status":1,"info":"上传失败,号码'.$v[1].'已经存在"}';
														return;
										}
										if(empty($v[1])||empty($v[2])){
														continue;
										}
										$data[] = array('mobileNum'=>$v[1],'username'=>$v[2],'groupid'=>$id,'createtime'=>$time);
						}
						$this->rouji_model->addBatch($data);
		}
		catch(Exception $e) 
		{
						echo '{"status":1,"info":"上传失败"}';
		}
		echo '{"status":0,"info":"上传成功"}';
		return;
	}	
	function moveGroup()
	{
		if($this->input->is_post()){
			$mobile = $this->input->get_post('mobile');
			$groupid = $this->input->get_post('groupId');
			$rt = $this->model->update(array('mobilenum'=>$mobile,'groupid'=>$groupid));
			if($rt>0){
				echo '{"info":"移动成功","status":"1"}';
			}else{
				echo '{"info":"移动分组失败","status":"0"}';
			}
			return;
		}
		$groups = $this->rouji_group_model->getAll(array('sellerid'=>$this->sellerid));
		$mobile = $this->input->get_post('mobile');
		$item = $this->model->getOne(array('where'=>array('mobileNum'=>$mobile)));
		$this->load->view('bp/channelManage/roujiGroup',array('item'=>$item,'groups'=>$groups)); 
	}
	function  roujiExist($num)
	{
		$row = $this->rouji_model->getOne(array('where'=>array('mobileNum'=>$num)));
		if(empty($row))
		{
			return false;
		}
		return true;
	}
	function delete()
  {
					$id = $this->input->get_post('id');
					$result = $this->model->delete(array('where'=>array('mobileNum'=>$id)));
					if($result){
									//ajax_return('删除成功',1);
									echo '{"status":1,"msg":"删除成功"}';
					}else{
									echo '{"status":0,"msg":"删除失败"}';
					}
  }
	function ajaxMobile()
	{
		$mobile = $this->input->get_post('mobile'); //字段值
    $options = array('select'=>'*');
    if(!empty($mobile)){
      $options['where'] = array('mobilenum'=>$mobile);
    }   
    $item = $this->rouji_model->getOne($options);
    if(isset($item)&&!empty($item->mobilenum)){
      echo json_encode(array('status'=> '1','info'=>'电话号码已经添加'));exit();
    }else{
      echo json_encode(array('status'=> '0','info'=>'可用'));exit();
    }
	}	
}
 
?>
