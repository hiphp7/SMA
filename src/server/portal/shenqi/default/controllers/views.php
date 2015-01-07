<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Views extends MY_Controller { 
 
    function __construct()  {  
        parent::__construct(); 
				$this->load->model(array('address_model','customer_visit_model','issue_model'));
				$this->model = $this->address_model;
    }  
    
    function index($suffix=''){
			if(empty($suffix))
			{
							show_404();
			}
			$item = $this->model->getOne(array('where'=>array('suffix'=>$suffix)));
			if(empty($item))
			{
							show_404();
			}
			$data=array();
			$data['customerMobile'] = $item->sendsmobilenum;
			$data['roujiMobile'] = $item->roujimobile;
			$data['issueId'] = $item->issueid;
			$data['visitTime'] = date('Y-m-d H:i:s');
			$data['status'] = 'URD';
			$this->customer_visit_model->add($data);
			$option = array('select'=>'mobilepage','where'=>array('issueid'=>$item->issueid));
			$option['join'] = array(array('t_content t','t.contentid=t_issue.contentid'));
			$obj = $this->issue_model->getOne($option);
			$file = $obj->mobilepage;
			if(!is_file($file)|| !is_readable($file))
			{
							show_404();
			}
			$view = file_get_contents($file);
			if(empty($view)||$view===false)
			{
							show_404();
			}
			echo $view;
    }
} 
