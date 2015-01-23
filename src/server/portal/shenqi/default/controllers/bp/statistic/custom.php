<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Custom extends Admin_Controller { 
 
	var $sellerid;

    function __construct()  {  
        parent::__construct(); 
	$this->load->model(array('sharepage','issue_model'));
	$this->sellerid = $this->user_info->sellerid;
    }  
    
	function index(){
		$per_page = (int)$this->input->get_post('per_page');
		$sdate = $this->input->get_post('sdate');
		$edate = $this->input->get_post('edate');
                $cupage = config_item('site_page_num'); //每页显示个数
                $return_arr = array ('total_rows' => true );
                $option =array('where'=>array('t_issue.sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		if(!empty($sdate))
		{
			$option['where']['visitTime >='] = $sdate.' 00:00:00';
		}else
		{
			$option['where']['visitTime >='] = date('Y-m-d').' 00:00:00';
			$sdate = date('Y-m-d');
		}
		if(!empty($edate))
		{
			$option['where']['visitTime <='] = $edate.' 23:59:59';
		}
		$option['select'] ='c.title,v.*,starttime,endtime';
		$option['join'] = array(array('t_customer_visit v','v.issueId=t_issue.issueId'),array('t_content c','c.contentid=t_issue.contentid'));
                $option['order'] = 'visitTime desc,v.issueId';
                $rt =$this->issue_model->getAll($option,$return_arr);
		$url = '?sdate='.$sdate.'&edate='.$edate;
                $page = $this->sharepage->showPage ($url, $return_arr ['total_rows'], $cupage );
                $this->_template('bp/statistic/custom',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'sdate'=>$sdate,'edate'=>$edate));
	}	
} 
