<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Day extends Admin_Controller { 
 
	var $sellerid;

    function __construct()  {  
        parent::__construct(); 
	$this->load->model(array('statistic_seller_d_model','statistic_seller_di_model','statistic_seller_dr_model','sharepage'));
	$this->sellerid = $this->user_info->sellerid;
    }  
    
	function dayByRJ(){
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
		$return_arr = array ('total_rows' => true );
 		$sdate=$this->input->get_post('sdate');
 		if(empty($sdate))
 		{
 						$sdate = date('Y-m-01');
 		}
 		$edate=$this->input->get_post('edate');
 		if(empty($edate))
 		{
 						$edate = date('Y-m-d');
 		}
 		$option =array('where'=>array('s_date >='=>$sdate,'s_date <='=>$edate,'sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		$option['order'] = 's_date desc,mobile';
		$rt =$this->statistic_seller_d_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ('', $return_arr ['total_rows'], $cupage );
 		$this->_template('bp/statistic/dayByRj',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'sdate'=>$sdate,'edate'=>$edate));
	}	
	function dayByIssue()
	{
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
		$return_arr = array ('total_rows' => true );
 		$sdate=$this->input->get_post('sdate');
 		if(empty($sdate))
 		{
 						$sdate = date('Y-m-01');
 		}
 		$edate=$this->input->get_post('edate');
 		if(empty($edate))
 		{
 						$edate = date('Y-m-d');
 		}
 		$option =array('where'=>array('s_date >='=>$sdate,'s_date <='=>$edate,'sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		$option['order'] = 's_date desc,issueid';
		$rt =$this->statistic_seller_di_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ('', $return_arr ['total_rows'], $cupage );
 		$this->_template('bp/statistic/dayByIssue',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'sdate'=>$sdate,'edate'=>$edate));
	}
	function dayByRate(){
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
		$return_arr = array ('total_rows' => true );
 		$sdate=$this->input->get_post('sdate');
 		if(empty($sdate))
 		{
 						$sdate = date('Y-m-01');
 		}
 		$edate=$this->input->get_post('edate');
 		if(empty($edate))
 		{
 						$edate = date('Y-m-d');
 		}
 		$option =array('where'=>array('s_date >='=>$sdate,'s_date <='=>$edate,'sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		$option['order'] = 's_date desc';
		$rt =$this->statistic_seller_dr_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ('', $return_arr ['total_rows'], $cupage );
 		$this->_template('bp/statistic/dayByRate',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'sdate'=>$sdate,'edate'=>$edate));
	}	
} 
