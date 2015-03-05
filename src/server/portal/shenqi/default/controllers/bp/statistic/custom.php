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
		$cupage = config_item('site_page_num'); //每页显示个数
		$return_arr = array ('total_rows' => true );
		$option =array('where'=>array('t_issue.sellerId'=>$this->sellerid),'page'    => $cupage,'per_page'  => $per_page);
		$date_range = $this->input->get_post('date_range');
		$string=base_url('bp/statistic/custom?');
		if($date_range){
						$tmp_date = explode('-', $date_range);
						$option['where']['visitTime >= '] = trim($tmp_date[0]).' 00:00:00';
						$option['where']['visitTime <= '] = trim($tmp_date[1]).' 23:59:59';
						$string .= '&date_range='.$date_range;
		}else{
						$option['where']['visitTime >= '] = date('Y/m/d').' 00:00:00';
						$option['where']['visitTime <= '] = date('Y/m/d').' 23:59:59';
						$date_range = date('Y/m/d').' - '.date('Y/m/d');
						$string .= '&date_range='.$date_range;
		}
		$option['select'] ='c.title,v.*,starttime,endtime';
		$option['join'] = array(array('t_customer_visit v','v.issueId=t_issue.issueId'),array('t_content c','c.contentid=t_issue.contentid'));
		$option['order'] = 'visitTime desc,v.issueId';
		$rt =$this->issue_model->getAll($option,$return_arr);
		$page = $this->sharepage->showPage ($string, $return_arr ['total_rows'], $cupage );
		$this->_template('bp/statistic/custom',array('lc_list'=>$rt,'page'=>$page,'totals'  => $return_arr ['total_rows'],'date_range'=>$date_range));
	}	
} 
