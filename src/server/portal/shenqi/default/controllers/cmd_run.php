<?php 

class Cmd_run extends CI_Controller { 

	function __construct()  {  
		parent::__construct(); 
		$this->load->database();
	}  
	function day(){
		$this->seller_day();
		$this->agent_day();
	}
	function month(){
		$this->seller_month();
		$this->agent_month();
	}
   
	function getAgents()
	{
		$ret = array();
		$sql= "select agentid,superiorid from t_agent ";
		$query = $this->db->query($sql);
		$rt = $query->result_array();
		foreach($rt as $v)
		{
			$ret[$v['agentid']] = $v['superiorid'];
		}
		$master = array();
		foreach ($ret as $k=>$v)
		{
			if($v=='')
			{
				continue;
			}
			if(array_key_exists($v,$master))
			{
				$master[$v][] = $k;
			}else{
				$master[$v] =array($k); 
			}
		}
		return $master;
	}
	function getSellers()
	{
		$ret = array();
		$sql= "select agentid,sellerid from t_seller ";
		$query = $this->db->query($sql);
		$sellers = $query->result_array();
		foreach($sellers as $v)
		{
			$ret[$v['sellerid']] = $v['agentid'];
		}
		$seller = array();
		foreach ($ret as $k=>$v)
		{
			if(array_key_exists($v,$seller))
			{
				$seller[$v][] = $k;
			}else{
				$seller[$v] =array($k); 
			}
		}
		return $seller;
	}
function fillAgent($master,$sellers,$key ='master')
{
	$data = array();
	$tmp = array();
	if(array_key_exists($key,$master)&&!is_array($master[$key]))
	{
			if(isset($sellers[$key])&&!empty($sellers[$key]))
			{
							return $sellers[$key];
			}
	}
	if(array_key_exists($key,$master)&&is_array($master[$key])){
		foreach($master[$key] as $v){
					$t = $this->fillAgent($master,$sellers,$v);
					if(!empty($t))
					{
									$tmp = array_merge($tmp,$t);
					}
		}
	}
	if(array_key_exists($key,$sellers)&&!empty($sellers[$key]))
	{
		$data = array_merge($tmp,$sellers[$key]);
  }
	if(empty($data))
	{
					return ;
  }
	//return array($key=>$data);
	return $data;
}

	function seller_month()
	{
		$firstday = date("Y-m-01",strtotime("-1 month"));
		$lastday = date("Y-m-t",strtotime("-1 month"));
		$rt = $this->sumMonthSendByRJ($firstday,$lastday);
		foreach($rt as $row)
		{
			$this->put2db('t_statistic_seller_m',$row);
		}
		$rt = $this->sumMonthSendByIssue($firstday,$lastday);
		foreach($rt as $row)
		{
			$this->put2db('t_statistic_seller_mi',$row);
		}
		$rt = $this->sumMonthRate($firstday,$lastday);
		foreach($rt as $row)
		{
			$this->put2db('t_statistic_seller_mr',$row);
		}
	}

	function sumMonthSendByIssue($start,$end)
	{
		$month = substr($start,0,7);
		$sql= "select '$month' as s_month,issueid,title,cycle,sellerid,sum(planCount) as plancount,sum(realCount) as realcount from t_statistic_seller_di where s_date >='$start' and s_date<='$end' group by sellerId,issueid order by sellerid,issueid";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function sumMonthSendByRJ($start,$end)
	{
		$month = substr($start,0,7);
		$sql= "select '$month' as s_month,sellerid,mobile,sum(sentCount) as sentcount from t_statistic_seller_d where s_date >='$start' and s_date<='$end' group by sellerId,mobile order by sellerid,mobile";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function sumMonthRate($start,$end)
	{
		$month = substr($start,0,7);
		$sql= "select '$month' as s_month,sellerid,sum(sentCount) as sentcount,sum(visitCount) as visitcount,round(sum(sentCount)/sum(visitCount)*100,2) as rate from t_statistic_seller_dr where s_date >='$start' and s_date<='$end' group by sellerId order by sellerid";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	function seller_day($d=1){
		$day = date("Y-m-d",strtotime("-$d day"));
		$sql = 'select sellerid from t_seller';
		//echo $sql;
		$query = $this->db->query($sql);
		$rt = $query->result_array();
		foreach($rt as $v)
		{
			$rows = $this->getDaySendByRJ($day,$v['sellerid']);
			foreach($rows as $row){
				$this->put2db('t_statistic_seller_d',$row);
			}
			$rows = $this->getDaySendByIssue($day,$v['sellerid']);
			foreach($rows as $row){
				$row['realcount'] =$this->getIssueRealCount($day,$row['issueid']);
				$this->put2db('t_statistic_seller_di',$row);
			}
			$rows = $this->getDayRate($day,$v['sellerid']);
			foreach($rows as $row){
				if($row['sentcount']==0&&$row['visitcount']==0){
					continue;
				}
				$this->put2db('t_statistic_seller_dr',$row);
			}
		}
	}

	function getDayRate($day,$sellerid){
		$sql ="select '$sellerid' as sellerid ,'$day' as s_date,count(*) as sentcount,count(visitTime) as visitcount,round( count(visitTime)/count(*)*100,2) as rate from t_task t left join t_issue i on(i.issueid=t.issueid) left join t_customer_visit v on(v.issueId=t.issueId and visitTime between '$day 00:00:00' and '$day 23:59:59') where sentTime between '$day 00:00:00' and '$day 23:59:59' and sellerid='$sellerid' ";
		$query = $this->db->query($sql);
		return $query->result_array();
	}

	function getDaySendByIssue($day,$sellerid)
	{
		$sql="select '$sellerid' as sellerid ,'$day' as s_date,i.issueid ,c.title,CONCAT(i.starttime,'至',i.endtime,'  ') as cycle,count(*) as plancount  from t_task t left join t_issue i on (i.issueid=t.issueid) left join t_content c on(c.contentid=i.contentid) where sentTime between '$day 00:00:00' and '$day 23:59:59' and i.sellerid='$sellerid' group by i.issueid";
		//echo $sql."\n";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	function getIssueRealCount($day ,$issueid)
	{
 		$sql="select count(*) as c from t_task where  sentTime between '$day 00:00:00' and '$day 23:59:59' and issueid='$issueid'";
		$query = $this->db->query($sql);
		return $query->row(0)->c;
	}
	function getDaySendByRJ($day,$sellerid)
	{
		$sql="select '$sellerid' as sellerid ,'$day' as s_date,roujimobilenum as mobile,count(*) as sentcount from t_task t left join t_issue i on (i.issueid=t.issueid) where sentTime between '$day 00:00:00' and '$day 23:59:59' and sellerid='$sellerid' group by roujiMobileNum";
		//echo $sql."\n";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	function agent_month(){
		$day = date("Y-m",strtotime("-1 month"));
		$a = $this->getAgents();
		$b = $this->getSellers();
		$c=array();
		foreach($b as $k=>$v ){
			$sellers = $this->fillAgent($a,$b,$k);
			$data = $this->sumMonthAgent($day,$sellers,$k);
			foreach($data as $row){
				if(empty($row['s_month']))
				{
					continue;
				}
				$this->put2db('t_statistic_agent_m',$row);
			}
		}
	}
	function agent_day($d=1){
		$day = date("Y-m-d",strtotime("-$d day"));
		$a = $this->getAgents();
		$b = $this->getSellers();
		$c=array();
		foreach($b as $k=>$v ){
						$sellers = $this->fillAgent($a,$b,$k);
						$data = $this->sumDayAgent($day,$sellers,$k);
						foreach($data as $row){
										if(empty($row['s_date']))
										{
														continue;
										}
										$this->put2db('t_statistic_agent_d',$row);
						}
		}
	}
	function sumDayAgent($day,$sellers,$agentid){
     $sql = "select sum(sentCount) as sentcount,s_date,'$agentid' as agentid from t_statistic_seller_d  where sellerid in ('".join($sellers,"','")."') and s_date='$day'";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	function sumMonthAgent($month,$sellers,$agentid){
     $sql = "select sum(sentCount) as sentcount,s_month,'$agentid' as agentid from t_statistic_seller_m  where sellerid in ('".join($sellers,"','")."') and s_month='$month'";
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	function put2db($t,$data)
	{
		$row = array();
		$fields = $this->db->list_fields($t);
		foreach($fields as $v){
			$row[$v] = $data[strtolower($v)];
		}
		$sql="insert into $t (".join($fields,',').")values('";
		$sql.= join($row,"','")."')";
		//echo $sql;
		return $this->db->query($sql);
	}
}
