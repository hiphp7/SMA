<?php
include 'curl.php';

class SMS 
{
 protected $_corpID;
 protected $_host;
 protected $_port;
 protected $_pwd;
 protected $_url;
 protected $_errMSG =array('-1'=>'帐号未注册','-2'=>'其他错误','-3'=>'密码错误',
			'-4'=>'手机号格式错误','-5'=>'余额不足','-6'=>'定时时间格式错误','-7'=>'10H内禁止发送','-8'=>'接口调用错误');
 protected $_curl;
 function __construct($param)
 {
   $this->_corpID = $param['user']; 
   //$this->_host = $param['host']; 
   $this->_pwd = $param['pwd']; 
   $this->_host = '211.152.9.213'; 
   //$this->_port = $param['port']; 
   $this->_port = 6600; 
   $this->_url = $this->_getUrl(); 
   $this->_curl = new curl($this->_url);
   $this->_curl->setReferer('www.baidu.com');
 }
 protected function _getURL($host='',$port='')
 {
   if(empty($host))
   {
     $host = $this->_host;
   }
   if(empty($port))
   {
     $port = $this->_port;
   }
   return 'http://'.$host.':'.$port.'/';
 }
 protected function _getParam(array $arr,$isEnCode = true)
 {
   $ret = '?1=1';
   foreach($arr as $k=>$v)
   {
     if($isEnCode)
     {
       $ret .= "&$k=".urlEnCode($v);
     }else
     {
       $ret .= "&$k=".$v;
     }
   }
   return $ret;
 }
 public function selSum($user='',$pwd='')
 {
   $cmd = 'SelSum.aspx';
   $param = array('CorpID'=>$user,'Pwd'=>$pwd);
   if(empty($user))
   {
     $param['CorpID']= $this->_corpID;
   }
   if(empty($pwd))
   {
     $param['Pwd']= $this->_pwd;
   }
   $msg = $this->_getParam($param);

   $this->_curl->createCurl($this->_url.$cmd.$msg);
   $status = $this->_curl->getHttpStatus();
   if($status != 200)
   {
       return  $this->jsonReturn($status,'http err url='.$this->_url.$cmd.$msg);
   }
   $count = $this->_curl->__tostring();
   if($count < 0)
   {
     return  $this->jsonReturn($count,$this->_errMSG[$count]);
   }
   $ret = $this->_retSplit($count,'|');
   return  $this->jsonReturn(0,'ok',array('SMS'=>$ret[1],'MMS'=>$ret[3]));
 }
 protected function _retSplit($ret,$split)
 {
   if($split == '|')
   {
     $tmp = explode('|',$ret);
     $tmp[0] = explode(':',$tmp[0]);
     $tmp[1] = explode(':',$tmp[1]);
     return array_merge($tmp[0],$tmp[1]);
   }
   return explode($split,$ret);
 }
 public function send(array $mobile,$content,$cell='',$sendTime='')
 {
   $cmd = 'Send.aspx';
   $mobiles = join($mobile,',');
   $content = html_entity_decode($content);
   $param = array('CorpID'=>$this->_corpID,'Pwd'=>$this->_pwd,'Mobile'=>$mobiles,
		'Content'=>$content,'Cell'=>$cell,'SendTime'=>$sendTime);
   $msg = $this->_getParam($param,false);
   log_message('info',$this->_url.'-----'.$msg);
   $this->_curl->setPost($msg);
  
   $this->_curl->createCurl($this->_url.$cmd);
   $status = $this->_curl->getHttpStatus();
   if($status != 200)
   {
     return $this->jsonReturn(1,'http err'.$status.'url='.$this->_url.$cmd.$msg);
   }
   $ret = $this->_curl->__tostring();
   if($ret < 0)
   {
     return  @$this->jsonReturn($ret,$this->_errMSG[$ret]);
   }
   return  $this->jsonReturn(0,'ok',array('MSGID'=>$ret));
 }
 public function get($user='',$pwd='')
 {
   $cmd = 'Get.aspx';
   $param = array('CorpID'=>$user,'Pwd'=>$pwd);
   if(empty($user))
   {
     $param['CorpID']= $this->_corpID;
   }
   if(empty($pwd))
   {
     $param['Pwd']= $this->_pwd;
   }
   $msg = $this->_getParam($param);
   $this->_curl->createCurl($this->_url.$cmd.$msg);
   $status = $this->_curl->getHttpStatus();
   if($status != 200)
   {
     return 'http err '.$status.' url='.$this->_url.$cmd.$msg;
   }
   $ret = $this->_curl->__tostring();
   if($ret < 0)
   {
     return $this->_errMSG[$ret];
   }
   $ret = $this->_retSplit($ret,'#');
   return $ret;
 }
 public function getRpt($user='',$pwd='')
 {
   $cmd = 'GetSMSRpt.aspx';
   $param = array('CorpID'=>$user,'Pwd'=>$pwd);
   if(empty($user))
   {
     $param['CorpID']= $this->_corpID;
   }
   if(empty($pwd))
   {
     $param['Pwd']= $this->_pwd;
   }
   $msg = $this->_getParam($param);
   $this->_curl->createCurl($this->_url.$cmd.$msg);
   $status = $this->_curl->getHttpStatus();
   if($status != 200)
   {
     return $this->jsonReturn(1,'http err'.$status.'url='.$this->_url.$cmd.$msg);
     //return 'http err '.$status.' url='.$this->_url.$cmd.$msg;
   }
   $ret = $this->_curl->__tostring();
//echo $ret.' ret '.$this->_url.$cmd.$msg;
   $tmp = $this->_retSplit($ret,'#');
   if(empty($tmp[1]))
   {
     //return $ret;
     $this->jsonReturn(1,$status.'url='.$this->_url.$cmd.$msg);
   }
   return  $this->jsonReturn(0,'ok',$tmp);
   //return $tmp;
 }
 protected function  jsonReturn($ret,$msg,$data=array())
 {
    $json = array('ret'=>$ret,'msg'=>$msg,'data'=>array($data));
    return json_encode($json);
 }

}
// $sms =new SMS('wi360','wi360123');
// echo $sms->send(array('13692228034'),'test 【wi360】');
 //echo $sms->getRpt();
