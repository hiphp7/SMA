<?php

if ( ! function_exists('jsonout'))
{
	function jsonout($errcode = 0, $errmsg = '成功', $data = null)
	{
		$rt = array('errcode'=>$errcode,'errmsg'=>$errmsg);
		if(!empty($data))
		{
			$rt = array_merge($rt,$data);
		}
		log_message("INFO",'json out put:'.json_encode($rt,JSON_UNESCAPED_UNICODE));
		echo json_encode($rt,JSON_UNESCAPED_UNICODE);
		exit;
	}

}
if ( ! function_exists('josnpost'))
{
	function josnpost()
	{
		$json = file_get_contents("php://input");
		if(empty($json))
		{
			 //$json = file_get_contents("php://stdin");
			return array();
		}
		$array = json_decode($json,true);
		log_message("INFO",'json in put:'.$json);
                log_message("INFO",'cookie in put:'.json_encode($_COOKIE));
		if($array === false)
		{
			jsonout(1000,'提交数据格式错误');
		}
		return $array;
	}
}
