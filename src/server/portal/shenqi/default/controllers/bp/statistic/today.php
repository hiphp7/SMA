<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class Today extends Admin_Controller { 
 
	var $sellerid;

    function __construct()  {  
        parent::__construct(); 
	$this->load->model(array('sharepage'));
	$this->sellerid = $this->user_info->sellerid;
    }  
    
	function dayByRJ(){
		$sellerid=$this->sellerid ;
		$day=date('Y-m-d');
		$sql="select '$sellerid' as sellerid ,'$day' as s_date,roujimobilenum as mobile,count(*) as sentcount from t_task t left join t_issue i on (i.issueid=t.issueid) where sentTime >= '$day 00:00:00' and t.status !='RVK' and sellerid='$sellerid' group by roujiMobileNum";      
                $query = $this->db->query($sql);
		$data = $query->result();
 		$this->_template('bp/statistic/todayByRj',array('lc_list'=>$data,'page'=>'','totals'  => 0,'sdate'=>0,'edate'=>0));
	}	
	function dayByIssue()
	{
		$sellerid=$this->sellerid ;
		$day=date('Y-m-d');
		$sql="select '$sellerid' as sellerid ,'$day' as s_date,i.issueid ,c.title,CONCAT(i.starttime,'至',i.endtime,'  ') as cycle,count(*) as plancount  from t_task t left join t_issue i on (i.issueid=t.issueid) left join t_content c on(c.contentid=i.contentid) where sentTime >= '$day 00:00:00' and t.status !='RVK' and i.sellerid='$sellerid' group by i.issueid";
                $query = $this->db->query($sql);
		$data = $query->result();
		foreach($data as $k=>$row){
			$data[$k]->realcount =$this->getIssueRealCount($day,$row->issueid);
		}
 		$this->_template('bp/statistic/todayByIssue',array('lc_list'=>$data,'page'=>'','totals'  =>0,'sdate'=>0,'edate'=>0));
	}
	function dayByRate(){
		$sellerid=$this->sellerid ;
		$day=date('Y-m-d');
		$sql ="select '$sellerid' as sellerid ,'$day' as s_date,count(*) as sentcount,count(visitTime) as visitcount,round( count(visitTime)/count(*)*100,2) as rate from t_task t left join t_issue i on(i.issueid=t.issueid) left join t_customer_visit v on(v.issueId=t.issueId and visitTime >= '$day 00:00:00') where sentTime >= '$day 00:00:00' and t.status !='RVK' and sellerid='$sellerid' ";
                $query = $this->db->query($sql);
		$data = $query->result();
 		$this->_template('bp/statistic/todayByRate',array('lc_list'=>$data,'page'=>'','totals'  =>0,'sdate'=>0,'edate'=>0));
	}	
	function getIssueRealCount($day ,$issueid)
	{
		$sql="select count(*) as c from t_task where sentTime between '$day 00:00:00' and '$day 23:59:59' and issueid='$issueid'";
		$query = $this->db->query($sql);
		return $query->row(0)->c;
	}
} 
