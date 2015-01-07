<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customer extends CI_Controller {

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
	 public function how_many_unread()
	 {

		$mobile = $this->input->cookie('mobileNum');
		$token =  $this->input->cookie('token');
		$rt = $this->model->mobileExist($mobile);
		if(!$rt)
		{
			jsonout(2004,'手机号码不存在');
		}
		$rt = $this->model->tokenCK($mobile,$token);
		if(!$rt)
		{
			jsonout(2005,'token 无效');
		}
		$data = $this->model->getUnread($mobile);
		jsonout(0,'获取信息成功',$data);
	 }
	 public function get_datail()
	 {
		$mobile = $this->input->cookie('mobileNum');
		$token =  $this->input->cookie('token');
		$rt = $this->model->mobileExist($mobile);
		if(!$rt)
		{
			jsonout(2004,'手机号码不存在');
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
		$issue = $this->model->getIssueContent($this->json['issueId']);
		if(empty($issue))
		{
			jsonout(1061,'访获详情取失败');
		}
		$list = $this->model->getVisitList($this->json['issueId'],$mobile,$this->json['customerMobile']);
		if($list == false)
		{
			jsonout(1061,'访获详情取失败');
		}
		$issue['list'] = $list;
		jsonout(0,'获取成功',$issue);
	 }
	 public function get_customer_list()
	 {
		$mobile = $this->input->cookie('mobileNum');
		$token =  $this->input->cookie('token');
		$rt = $this->model->mobileExist($mobile);
		if(!$rt)
		{
			jsonout(2004,'手机号码不存在');
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
			jsonout(2015,'参数pageSize 不正确');
		}
		if(empty($this->json['sort']))
		{
			jsonout(2015,'参数sort缺失');
		}
		if($this->json['sort']!='01'&& $this->json['sort']!='02')
		{
			jsonout(2015,'参数sort不正确');
		}
		$data = $this->model->getCustomerList($mobile,$this->json['pageIndex'],$this->json['pageSize'],$this->json['sort']);
		jsonout(0,'获取成功',$data);
	 }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
