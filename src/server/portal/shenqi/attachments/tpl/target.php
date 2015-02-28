<?php 
$str = "18929509393\r\n15959111013";
Header("Content-type: application/octet-stream"); 
Header("Accept-Ranges: bytes"); 
Header("Accept-Length:".strlen($str)); 
Header("Content-Disposition: attachment; filename=target.txt"); 
echo $str;
