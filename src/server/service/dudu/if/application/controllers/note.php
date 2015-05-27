<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Note extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	var $json ;

	 function __construct()
	 {
		 parent::__construct();
		 $this->model = $this->Mymodel;
		 $this->json = josnpost();;
	 }
	 public function update()
	 {
		$mobile = $this->input->cookie('mobileNum');
		$token =  $this->input->cookie('token');
		$rt = $this->model->mobileExist($mobile);
		if(!$rt)
		{
			jsonout(2004,'手机号码不存在，非法用户访问');
		}
		$rt = $this->model->tokenCK($mobile,$token);
		if(!$rt)
		{
			jsonout(2005,'token 无效');
		}
		if(empty($this->json['issueId']))
		{
			jsonout(2015,'参数issueId缺失');
		}
		$rt = $this->model->issueidExist($this->json['issueId']);
		if(!$rt)
		{
			jsonout(2015,'非法issueId');
		}
		if(empty($this->json['customerMobile']))
		{
			jsonout(2015,'参数customerMobile缺失');
		}
		if(!isset($this->json['content']))
		{
			jsonout(2015,'参数content缺失');
		}
		$flag = $this->model->updateNote(array('roujiMobile'=>$mobile,'issueId'=>$this->json['issueId'],'customerMobile'=>$this->json['customerMobile'],'note'=>$this->json['content'],'customerCall'=>$this->json['call']));
		if($flag)
		{
			jsonout(0,'更新笔记信息成功');
		}else{
			jsonout(1090,'更新笔记信息失败，其它原因');
		}
	 }
	 public function get_issue_list()
	 {
		$mobile = $this->input->cookie('mobileNum');
		$token =  $this->input->cookie('token');
		$rt = $this->model->mobileExist($mobile);
		if(!$rt)
		{
			jsonout(2004,'手机号码不存在，非法用户访问');
		}
		$rt = $this->model->tokenCK($mobile,$token);
		if(!$rt)
		{
			jsonout(2005,'token 无效');
		}
		if(empty($this->json['pageIndex']))
		{
			jsonout(2015,'参数pageIndex缺失');
		}
		if(empty($this->json['pageSize']))
		{
			jsonout(2015,'参数pageSize缺失');
		}
		$data = $this->model->getIssueList($mobile,$this->json['pageIndex'],$this->json['pageSize']);
		if($data == false)
		{
			jsonout(1100,'获取笔记详细失败，其它原因');
		}
		jsonout(0,'获取成功',$data);
	 }
	 public function get_issue_note()
	 {
		$mobile = $this->input->cookie('mobileNum');
		$token =  $this->input->cookie('token');
		$rt = $this->model->mobileExist($mobile);
		if(!$rt)
		{
			jsonout(2004,'手机号码不存在，非法用户访问');
		}
		$rt = $this->model->tokenCK($mobile,$token);
		if(!$rt)
		{
			jsonout(2005,'token 无效');
		}
		if(empty($this->json['issueId']))
		{
			jsonout(2015,'参数issueId缺失');
		}
		$rt = $this->model->issueidExist($this->json['issueId']);
		if(!$rt)
		{
			jsonout(2015,'非法issueId');
		}
		if(empty($this->json['pageIndex']))
		{
			jsonout(2015,'参数pageIndex缺失');
		}
		if(empty($this->json['pageSize']))
		{
			jsonout(2015,'参数pageSize缺失');
		}
		$data = $this->model->getIssueNote($mobile,$this->json['issueId'],$this->json['pageIndex'],$this->json['pageSize']);
		if($data == false)
		{
			jsonout(1110,'获取笔记详细失败，其它原因');
		}
		jsonout(0,'获取成功',$data);
	 }
	function get_note_by_rouji()
	{
		$mobile = $this->input->cookie('mobileNum');
		$token =  $this->input->cookie('token');
		$rt = $this->model->mobileExist($mobile);
		if(!$rt)
		{
			jsonout(2004,'手机号码不存在，非法用户访问');
		}
		$rt = $this->model->tokenCK($mobile,$token);
		if(!$rt)
		{
			jsonout(2005,'token 无效');
		}
		if(empty($this->json['issueId']))
		{
			jsonout(2015,'参数issueId缺失');
		}
		if(empty($this->json['customerMobileNum']))
		{
			jsonout(2015,'参数customerMobileNum缺失');
		}
		$rt = $this->model->issueidExist($this->json['issueId']);
		if(!$rt)
		{
			jsonout(2015,'非法issueId');
		}
		$data = $this->model->getNoteByRouji($mobile,$this->json['issueId'],$this->json['customerMobileNum']);
		if($data == false)
		{
			jsonout(1120,'获取笔记详细失败，其它原因');
		}
		jsonout(0,'获取成功',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
