<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mymodel extends CI_Model{
	function __construct(){
		parent::__construct();
		$this->load->database();
	}

	function getUnread($mobile)
	{
		$sql ="select count(*) as c from t_customer_visit where status='URD' and roujiMobile='{$mobile}'";
		$query = $this->db->query($sql);
		$c= (int)$query->row(0)->c;
		return array('unreadCustomer'=>$c);
	}
	function issueidExist($issueid)
	{
		$sql ="select count(*) as c from t_issue where issueid='{$issueid}'";
		$query = $this->db->query($sql);
		if($query->row(0)->c >0)
		{
			return true;
		}
		return false;
	}

	function mobileExist($mobile)
	{
		$sql = "select count(*) as c from t_rouji where mobileNum='$mobile' ";
		log_message('INFO','------sql:'.$sql);
		$query = $this->db->query($sql);
		if($query->row(0)->c >0)
		{
			return true;
		}
		return false;
	}

	function tokenCK($mobile,$token)
	{
		$time = time();
		$sql = "select count(*) as c from t_rouji_token where mobileNum='$mobile' and token='$token'"
;//." and (expireIn >='{$time}' or expireIn is null)";
		$query = $this->db->query($sql);
		if($query->row(0)->c >0)
		{
			return true;
		}
		return false;
	}
	function getRoujiInfo($mobile)
	{
		$today = date('Y-m-d');
		$yestoday = date('Y-m-d',strtotime('-1 day'));
		$sql = "select (select count(*) from  t_task where status='CRT' and now() between startTime and endtime) as remainPool,(select count(*) from t_task where roujiMobileNum ='{$mobile}' and senttime between '{$yestoday} 00:00:00' and '{$yestoday} 23:59:59' ) as yestoday,(select count(*) from t_task where roujiMobileNum ='{$mobile}' and senttime >= '{$today} 00:00:00' and status in ('RPF','RPS')) as today";
log_message('INFO',$sql);
		$query = $this->db->query($sql);
		if($query->num_rows <= 0)
		{
			return array();
		}
		$rt['taskInfo'] = $query->row_array();
		$sql = "select (select if(count(*)=0,1,count(*)) from (select count(*) as c from t_task where (status='RPF' or status='RPS') and senttime >= '{$today} 00:00:00' group by roujiMobileNum ) as rj) as rouji,(select ifnull(max(c),1)  from (select count(*) as c from t_task where (status='RPF' or status='RPS') and senttime >= '{$today} 00:00:00' group by roujiMobileNum ) as m) maxman,(select count(*) as beyondIndex from (select count(*) as c from t_task where (status='RPF' or status='RPS') and senttime >= '{$today} 00:00:00' group by roujiMobileNum having c<{$rt['taskInfo']['today']}) b) as beyondIndex";
		$query = $this->db->query($sql);
		$row= $query->row_array();
		$rt['industryIndex'] = floor((($rt['taskInfo']['today'])/($row['maxman']+1))*100).'%';
		$rt['beyondIndex'] = floor(($row['beyondIndex']/$row['rouji'])*100).'%';
		//$rt['beyondIndex'] = (round($rt['taskInfo']['today']/$row['maxman']*$row['rouji'],0)).'%';
log_message('INFO',$sql);
		return $rt;
	}
	function getIssueContent($id)
	{
		$sql = "select title,smscontent as content from t_issue i  left join t_content c on(c.contentId=i.contentId) where  i.status in ('CRT','ASS') and issueid=$id";
log_message('INFO','------sql:'.$sql);
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->first_row('array');
		}
		return array();
	} 
	function getVisitList($issueId,$mobile,$customerMobile)
	{
		$sql = "select visitTime from t_customer_visit where issueId='{$issueId}' and roujiMobile='{$mobile}' and  customerMobile='{$customerMobile}' order by visitTime desc";
log_message('INFO','------sql:'.$sql);
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			$this->db->update('t_customer_visit',array('status'=>'RD'),array('status'=>'URD','roujiMobile'=>$mobile,'customerMobile'=>$customerMobile,'issueid'=>$issueId));
			return $query->result_array();
		}
		return array();
	}

	function getCustomerList($mobile,$pageIndex,$pageSize,$sort)
	{
		$rt =array();
		$sql = "select issueId,customerMobile,count(*) as counter,max(visitTime) as lastDate,max(status) as unread from t_customer_visit where roujiMobile='{$mobile}' group by issueId,customerMobile";
		$order = " order by lastDate desc,counter desc,customerMobile";
		if($sort =='02')
		{
			$order = " order by counter desc,customerMobile";
		}
		$limit = " limit ".($pageIndex-1)*$pageSize." ,{$pageSize}";
		$query = $this->db->query('select count(*) as c from ('.$sql.') a');
		$total = $query->first_row()->c;
		$query = $this->db->query($sql.$order.$limit);
		$rt['list'] = array();
		$rt['listTotal'] = (int)$total;
		$rt['pageIndex'] = $pageIndex;
		$rt['pageSize'] = $pageSize;
		if ($query->num_rows() <= 0)
		{
			return $rt;
		}
		$list = $query->result_array();
		foreach($list as $k=>$v)
		{
			$tmp =array();
			$tmp = $this->getNote($v['issueId'],$mobile,$v['customerMobile']);
			$v['counter'] = (int)$v['counter'];
			$tmp = array_merge($v,$tmp);
			array_push($rt['list'],$tmp);
		}
		return $rt;
	}
	function getNote($issueId,$mobile,$customerMobile)
	{
		$sql = "select note as record,customerCall from t_note where issueId='{$issueId}' and roujiMobile='{$mobile}' and customerMobile='{$customerMobile}' limit 1";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		return array();
	}
	function updateNote ($data)
	{
		if($this->noteExist($data))
		{
			$note = $data['note'];
			$time = date('Y-m-d H:i:s');
			$sql ="update t_note set note='{$note}',updatetime='{$time}',customerCall='{$data['customerCall']}' where roujiMobile='{$data['roujiMobile']}' and issueid='{$data['issueId']}' and  customerMobile='{$data['customerMobile']}'";
			return $this->db->query($sql);
		}else{
			$data['createTime'] = date('Y-m-d H:i:s');
			$data['updateTime'] = date('Y-m-d H:i:s');
			return $this->db->insert('t_note',$data);
		}
	}
	function noteExist($data)
	{
		$sql = "select count(*) as c from t_note where roujiMobile='{$data['roujiMobile']}' and issueid='{$data['issueId']}' and  customerMobile='{$data['customerMobile']}'";
		$query = $this->db->query($sql);
		if($query->row(0)->c >0)
		{
			return true;
		}
		return false;
	}
	function getIssueList($mobile,$pageIndex,$pageSize)
	{
		$sql = "select n.issueId,c.title, count(idt_note) as noteCounter from t_issue i left join t_note n on(n.issueid=i.issueid) left join t_content c on(c.contentId=i.contentId) where roujiMobile='{$mobile}' group by n.issueId";
		$limit = " limit ".($pageIndex-1)*$pageSize." ,{$pageSize}";
		$query = $this->db->query('select count(*) as c from ('.$sql.') a');
		$total = $query->first_row()->c;
		$query = $this->db->query($sql.$limit);
		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
			return array('pageIndex'=>$pageIndex,'pageSize'=>$pageSize,'listTotal'=>(int)$total,'issueList'=>$data);
		}
		return array('pageIndex'=>$pageIndex,'pageSize'=>$pageSize,'listTotal'=>(int)$total,'issueList'=>array());
	}
	function getIssueNote($mobile,$issueId,$pageIndex,$pageSize)
	{
		$sql = " select customerMobile,note as noteContent,updatetime as lastDate from t_note where roujiMobile='{$mobile}' and issueId ='{$issueId}' order by lastDate desc";
log_message('INFO','------sql:'.$sql);
		$limit = " limit ".($pageIndex-1)*$pageSize." ,{$pageSize}";
		$query = $this->db->query('select count(*) as c from ('.$sql.') a');
		$total = (int)$query->first_row()->c;
		$query = $this->db->query($sql.$limit);
		if ($query->num_rows() > 0)
		{
			$data = $query->result_array();
			return array('pageIndex'=>$pageIndex,'pageSize'=>$pageSize,'listTotal'=>(int)$total,'noteList'=>$data);
		}
		//return array('pageIndex'=>$pageIndex,'pageSize'=>$pageSize,'listTotal'=>(int)$total,'noteList'=>array());
		return false;
	}
	function getNoteByRouji($mobile,$issueId,$customerMobile)
	{
		$sql = "select note as noteContent,customerCall as 'call',updateTime as lastDate,customerMobile from t_note where issueId='{$issueId}' and roujiMobile='{$mobile}' and customerMobile='{$customerMobile}' limit 1";
		$query = $this->db->query($sql);
		if ($query->num_rows() > 0)
		{
			return $query->row_array();
		}
		return array("noteContent"=>'','call'=>'','lastDate'=>'','customerMobile'=>$customerMobile);

	}
}
