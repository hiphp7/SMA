<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Index extends Admin_Controller {
	  
	function __construct() {
		   
		parent::__construct();
		
		$this->load->model(array('group_model','sharepage'));
		$this->model = $this->user_model;
		$this->lang->load('admin');

	} 

	function index(){
		
		$per_page	= (int)$this->input->get_post('per_page');
		
		$cupage	= intval($this->config->item('site_page_num')); //每页显示个数
		
		$return_arr = array ('total_rows' => true );
		$where = array();
		$like  = array();
		if($this->user_info->key != 'root'){
			$where[$this->model->_table.'.status >='] = 0;
			$where['pid']        = $this->user_info->uid;
		}
		
		$user_name = htmlspecialchars($this->input->get_post("user_name"));
		$string    = '';
		if($user_name=trim($user_name)){
			
			$like['user_name']  = $user_name;
			$string .="user_name=".$user_name;
		}

		$nickname     = htmlspecialchars($this->input->get_post("nickname"));
		
		if($nickname  = trim($nickname)){
			
			$like['nickname']  = $nickname;
			$string .="nickname=".$nickname;
		}

		$options	= array(
			'page'		=> $cupage,
			'per_page'	=> $per_page,
			'where'		=> $where,
			'like'		=> $like,
			'order'		=> "uid desc",
		);
		
		$lc_list = $this->model->getAll($options, $return_arr); //查询所有信息
// 		print_r($lc_list);die;
		
		$url = admin_base_url('user/index?').$string;
		
		$page = $this->sharepage->showPage ($url, $return_arr ['total_rows'], $cupage );
		
		$data = array(
			'lc_list'	=> $lc_list,
			'page'		=> $page,	
			'totals'	=> $return_arr ['total_rows'],	 //数据总数
		); 
		
		$this->_template('admin/user/userlist',$data);  
	} 
	 

	public function info(){

	    if($this->input->is_post()){
	        $this->save();
	    }
	    
		$id	  =	intval($this->input->get_post('uid'));
		$options = array();
		if($id>0){
			$options['where'] = array('uid'=>$id);
		}
		$item = $this->model->getOne($options);
		
		$where['status'] = 1;
		$options['order'] = 'gid';
		if($this->user_info->gid == 0){
			
		}else if ($this->user_info->gid == 1){
			$where['gid >'] = 1;
		}else{
			$where['gid >'] = 2;
		}
		
		$options['where'] = $where;
		$group_list = array();
		foreach ($this->group_model->getAll($options) as $tmp){
			$group_list[$tmp->gid] = $tmp->title;
		}
		$data = array(
			'item'	=> $item,	
			'group_list'	=> $group_list,
		); 
		$this->_template('admin/user/userinfo',$data); 
	}  
	public function edit(){

	    if($this->input->is_post()){
	        $this->save();
	    }
	    
		$id	  =	intval($this->input->get_post('uid'));
		$options = array();
		if($id>0){
			$options['where'] = array('uid'=>$id);
		}
		$item = $this->model->getOne($options);
		
		$where['status'] = 1;
		$options['order'] = 'gid';
		if($this->user_info->gid == 0){
			
		}else if ($this->user_info->gid == 1){
			$where['gid >'] = 1;
		}else{
			$where['gid >'] = 2;
		}
		
		$options['where'] = $where;
		$group_list = array();
		foreach ($this->group_model->getAll($options) as $tmp){
			$group_list[$tmp->gid] = $tmp->title;
		}
		$data = array(
			'item'	=> $item,	
			'group_list'	=> $group_list,
		); 
		$this->_template('admin/user/userinfo',$data); 
	}
	public function save(){
		
		$id = (int)$this->input->get_post('id');
		$data['user_name'] = trim($this->input->get_post('user_name'));

		//判断名称是否有重复
		$item = $this->model->getOne(array('user_name'=>$data['user_name']));
		if($item && intval($item->uid) != intval($id)){
		    ajax_return(lang('service_user_name_exist'));
		}
		
		$data['pid'] = $this->user_info->uid;
		
		//地区
		$data['district'] = $this->input->get_post('district');
		
		if(!$id){
			
			if(!is_username($data['user_name'])){
				
				ajax_return('账号只允许字母开头，允许5-16字节，允许字母数字下划线');		
			}
			
			$password = $this->input->get_post('password');
			
			if(!is_password($password)){

			    ajax_return('密码只允许6到20个字母、数字字符');
			}
			
			$data['password'] = md5($password);
						
		}
		
		$data['gid'] = (int)$this->input->get_post('gid');
		
		$data['email'] = $this->input->get_post('email');
		
		if(!is_email($data['email']) and trim($data['email'])){
			
			ajax_return('E-mail不是有效的邮箱格式！');
		}
		
		$data['nickname'] = htmlspecialchars($this->input->get_post('nickname'));
		
		//保存信息
		if($id>0){
			$data['token'] = '';
			$result=$this->model->update($data,array('uid'=>$id));
		}else{
			
			$data['regip'] = $this->egetip();
			
			$data['regtime'] = time();
			
			$result=$this->model->add($data);
		}
		
		//信息返回操作
		if($result){
		
			ajax_return(lang('save_success'),0,'','/admin/user/index');
		}else{
			ajax_return(lang('save_failed'));
		}
	}
	
	//取得IP
	public function egetip(){
		if(getenv('HTTP_CLIENT_IP')&&strcasecmp(getenv('HTTP_CLIENT_IP'),'unknown')) 
		{
			$ip=getenv('HTTP_CLIENT_IP');
		} 
		elseif(getenv('HTTP_X_FORWARDED_FOR')&&strcasecmp(getenv('HTTP_X_FORWARDED_FOR'),'unknown'))
		{
			$ip=getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif(getenv('REMOTE_ADDR')&&strcasecmp(getenv('REMOTE_ADDR'),'unknown'))
		{
			$ip=getenv('REMOTE_ADDR');
		}
		elseif(isset($_SERVER['REMOTE_ADDR'])&&$_SERVER['REMOTE_ADDR']&&strcasecmp($_SERVER['REMOTE_ADDR'],'unknown'))
		{
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		$ip=trim(preg_replace("/^([\d\.]+).*/","\\1",$ip));
		return $ip;
	}
	
	
	/**
	 * 
	 * ajax判断用户名是否存在
	 * 
	 */
	function ajaxUserName(){
		
		$status = 'y';
		
		$user_name = trim($this->input->get_post('param'));
		
		$user_info = $this->model->getOne(array('user_name'=>$user_name),TRUE);
		if($user_info){
			$status = '账号已经存在，请重新输入!';
		}
		
		echo $status;
	}
	
	/**
	 * 状态修改
	 */
	public function clicktik(){
	
	    $val = $this->input->get_post('val'); //字段值
	    if(!$val){
	        $id = intval($this->input->get_post('id'));
	        $this->model->update(array('token'=>''),array('uid'=>$id));
	    }
	   
	    parent::clicktik();
	}
	
}
 
?>
