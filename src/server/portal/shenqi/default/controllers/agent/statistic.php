<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Statistic extends Admin_Controller { 
 
	var $agentid;

    function __construct()  {  
        parent::__construct(); 
				$this->load->model(array('statistic_agent_d_model','statistic_agent_m_model','sharepage'));
				$this->agentid = $this->user_info->agentid;
    }  
	function day(){
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
 		$sdate=$this->input->get_post('sdate');
		$str = base_url('agent/statistic/day?');;
		$date_range = $this->input->get_post('date_range');
		if($date_range){
						$tmp_date = explode('-', $date_range);
						$sdate = date('Y-m-d',strtotime(trim($tmp_date[0])));
						$edate = date('Y-m-d',strtotime(trim($tmp_date[1])));
		}else{
 						$sdate = date('Y-m-01');
 						$edate = date('Y-m-d');
						$date_range = date('Y/m/01').' - '.date('Y-m-d');
		}
		$str = '&date_range='.$date_range;
		$child =$this->getAllChild();
		$sellerids = join(array_keys($child['seller']),"','");
		$agentids = join(array_keys($child['agent']),"','");
 		$sql = "select s_date,sentcount,agentid,'代理' as type from t_statistic_agent_d where s_date>='$sdate' and s_date <='$edate' and agentid in('{$agentids}') union all select * from (select s_date,sentcount ,sellerid,'商户' from t_statistic_seller_d where s_date>='$sdate' and s_date<='$edate' and sellerid in ('{$sellerids}') ) m order by s_date desc,type,agentid";
		$count = "select count(*) as c from ($sql) as m";
		$query = $this->db->query($count);
		$total_rows  = $query->first_row()->c;
		$limit = " limit $per_page ,$cupage";
		$query = $this->db->query($sql.$limit);
		$rt = $query->result();
		foreach($rt as $k=>$v)
		{
			if(empty($v->agentid))
			{
				unset($rt[$k]);
				continue;
			}
			if($v->type=='代理')
			{
				$rt[$k]->agentname = $child['agent'][$v->agentid];
			}else
			{
				$rt[$k]->agentname = $child['seller'][$v->agentid];
			}
		}
		$url = base_url('agent/statistic/day?'.$str);
		$page = $this->sharepage->showPage ($url, $total_rows, $cupage );
		$this->_template('agent/day',array('lc_list'=>$rt,'page'=>$page,'totals'  => $total_rows,'date_range'=>$date_range));
	}	
	function month(){
		$per_page	= (int)$this->input->get_post('per_page');
		$cupage	= config_item('site_page_num'); //每页显示个数
		$day = date('Y-m',strtotime('-1 month'));
		$child =$this->getAllChild();
		$sellerids = join(array_keys($child['seller']),"','");
		$agentids = join(array_keys($child['agent']),"','");
		$sql = "select s_month,sentcount,agentid,'代理' as type from t_statistic_agent_m where s_month='$day' and agentid  in('{$agentids}') union all select * from (select s_month,sum(sentcount) ,sellerid,'商户' from t_statistic_seller_m where s_month='$day' and sellerid in ('{$sellerids}') ) m order by s_month desc,type,agentid";
		$count = "select count(*) as c from ($sql) as m";
		$query = $this->db->query($count);
		$total_rows  = $query->first_row()->c;
		$limit = " limit $per_page ,$cupage";
		$query = $this->db->query($sql.$limit);
		$rt = $query->result();
		foreach($rt as $k=>$v)
		{
			if(empty($v->agentid))
			{
				unset($rt[$k]);
				continue;
			}
			if($v->type=='代理')
			{
				$rt[$k]->agentname = $child['agent'][$v->agentid];
			}else
			{
				$rt[$k]->agentname = $child['seller'][$v->agentid];
			}
		}
		$url = base_url('agent/statistic/month?');
		$page = $this->sharepage->showPage ($url, $total_rows, $cupage );
		$this->_template('agent/month',array('lc_list'=>$rt,'page'=>$page,'totals'  => $total_rows));
	}	
	function getAllChild()
	{
		$sql = "select agentname,agentid from t_agent where superiorid='{$this->agentid}'";
		$query = $this->db->query($sql);
		$agents =  $query->result_array();
		$a = array();
		foreach($agents as $v)
		{
			$a[$v['agentid']] = $v['agentname'];
		}
		$sql = "select sellername,sellerid from t_seller where agentid='{$this->agentid}'";
		$query = $this->db->query($sql);
		$sellers = $query->result_array();
		$s = array();
		foreach($sellers as $v)
		{
			$s[$v['sellerid']] = $v['sellername'];
		}
		return array('seller'=>$s,'agent'=>$a);
	}
} 
