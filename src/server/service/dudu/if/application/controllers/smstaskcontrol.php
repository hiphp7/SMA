<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Smstaskcontrol extends CI_Controller {

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
	public function index()
	{
	//	$this->load->view('welcome_message');
	}
	public function get_rouji_info()
	{
		$mobile=$this->input->cookie('mobileNum');
		$token=$this->input->cookie('token');
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
		$data = $this->model->getRoujiInfo($mobile);
		jsonout(0,'获取信息成功',$data);
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
