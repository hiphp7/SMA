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
 		$option =array('where'=>array('sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		$date_range = $this->input->get_post('date_range');
		$string=base_url('bp/statistic/day/dayByRJ?');
		if($date_range){
						$tmp_date = explode('-', $date_range);
						$option['where']['s_date >= '] = date('Y-m-d',strtotime(trim($tmp_date[0])));
						$option['where']['s_date <= '] = date('Y-m-d',strtotime(trim($tmp_date[1])));
						$string .= '&date_range='.$date_range;
		}else{
						$option['where']['s_date >= '] = date('Y-m-01');
						$option['where']['s_date <= '] = date('Y-m-d');
						$date_range = date('Y/m/01').' - '.date('Y/m/d');
						$string .= '&date_range='.$date_range;
		}
		$option['order'] = 's_date desc,sentCount desc ,mobile';
		$rt =$this->statistic_seller_d_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ($string, $return_arr ['total_rows'], $cupage );
 		$this->_template('bp/statistic/dayByRj',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'date_range'=>$date_range));
	}	
	function dayByIssue()
	{
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
		$return_arr = array ('total_rows' => true );

		$date_range = $this->input->get_post('date_range');
		$string=base_url('bp/statistic/day/dayByIssue?');
 		$option =array('where'=>array('sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		if($date_range){
						$tmp_date = explode('-', $date_range);
						$option['where']['s_date >= '] = date('Y-m-d',strtotime(trim($tmp_date[0])));
						$option['where']['s_date <= '] = date('Y-m-d',strtotime(trim($tmp_date[1])));
						$string .= '&date_range='.$date_range;
		}else{
						$option['where']['s_date >= '] = date('Y-m-01');
						$option['where']['s_date <= '] = date('Y-m-d');
						$date_range = date('Y/m/01').' - '.date('Y/m/d');
						$string .= '&date_range='.$date_range;
		}
		$option['order'] = 's_date desc,issueid';
		$rt =$this->statistic_seller_di_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ($string, $return_arr ['total_rows'], $cupage );
 		$this->_template('bp/statistic/dayByIssue',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'date_range'=>$date_range));
	}
	function dayByRate(){
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
		$return_arr = array ('total_rows' => true );
		$date_range = $this->input->get_post('date_range');
		$string=base_url('bp/statistic/day/dayByRate?');
 		$option =array('where'=>array('sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		if($date_range){
						$tmp_date = explode('-', $date_range);
						$option['where']['s_date >= '] = date('Y-m-d',strtotime(trim($tmp_date[0])));
						$option['where']['s_date <= '] = date('Y-m-d',strtotime(trim($tmp_date[1])));
						$string .= '&date_range='.$date_range;
		}else{
						$option['where']['s_date >= '] = date('Y-m-01');
						$option['where']['s_date <= '] = date('Y-m-d');
						$date_range = date('Y/m/01').' - '.date('Y/m/d');
						$string .= '&date_range='.$date_range;
		}
		$option['order'] = 's_date desc';
		$rt =$this->statistic_seller_dr_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ('', $return_arr ['total_rows'], $cupage );
 		$this->_template('bp/statistic/dayByRate',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'date_range'=>$date_range));
	}	
} 
