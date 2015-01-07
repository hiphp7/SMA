<?php 
$str = file_get_contents("php://input");
include "curl.php";
$url='http://'.$_GET['host'].'/'.urldecode($_GET['api']);
$mobile = $_GET['mobileNum'];
$token = $_GET['token'];
$cookie_str = "mobileNum={$mobile}; token={$token}";
$curl = new curl("");
$curl->setPost($str);
$curl->createCurl($url,$cookie_str);
echo $curl->__tostring();
