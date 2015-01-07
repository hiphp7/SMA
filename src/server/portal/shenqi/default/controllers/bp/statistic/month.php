<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Month extends Admin_Controller { 

	var $sellerid;

	function __construct()  {  
		parent::__construct(); 
		$this->load->model(array('statistic_seller_m_model','statistic_seller_mi_model','statistic_seller_mr_model','sharepage'));
		$this->sellerid = $this->user_info->sellerid;
	}  

	function monthByRJ(){
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
		$return_arr = array ('total_rows' => true );
 		$day=$this->input->get_post('s_month');
 		if(empty($day))
 		{
 						$day = date('Y-m',strtotime('-1 month'));
 		}
		$option =array('where'=>array('s_month'=>$day,'sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		$option['order'] = 's_month desc,mobile';
		$rt =$this->statistic_seller_m_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ('', $return_arr ['total_rows'], $cupage );
 		$this->_template('bp/statistic/monthByRj',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'s_month'=>$day));
	}	
	function monthByIssue()
	{
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
		$return_arr = array ('total_rows' => true );
 		$day=$this->input->get_post('s_month');
 		if(empty($day))
 		{
 						$day = date('Y-m',strtotime('-1 month'));
 		}
		$option =array('where'=>array('s_month'=>$day,'sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		$option['order'] = 's_month desc';
		$rt =$this->statistic_seller_mi_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ('', $return_arr ['total_rows'], $cupage );
 		$this->_template('bp/statistic/monthByIssue',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'s_month'=>$day));
	}
	function monthByRate(){
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
		$return_arr = array ('total_rows' => true );
 		$day=$this->input->get_post('s_month');
 		if(empty($day))
 		{
 						$day = date('Y-m',strtotime('-1 month'));
 		}
		$option =array('where'=>array('s_month'=>$day,'sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		$option['order'] = 's_month desc';
		$rt =$this->statistic_seller_mr_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ('', $return_arr ['total_rows'], $cupage );
 		$this->_template('bp/statistic/monthByRate',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'s_month'=>$day));
	}	
} 
