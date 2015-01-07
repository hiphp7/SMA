<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('is_sae'))
{
    function is_sae()
    {
        return defined('SAE_ACCESSKEY') && (substr(SAE_ACCESSKEY, 0, 4 ) != 'kapp');
    }
}

function check_bp($bpid=null,$bpoid=null,$url=''){

    $_ci =& get_instance();
    $msg = null;

    if(empty($bpid)){
        $msg = lang('bpid_not_null');
    }else{
        if(!isset($_ci->bp_info)){
            $_ci->load->model('bp_info');
        }

        if(!$_ci->bp_info->getOne(array('bpid'=>$bpid,'bpoid'=>$bpoid))){
            $_ci->lang->load('bp');
            $msg = lang('bpid_is_not_valid');
        }
    }
    
    if($msg){
        if($_ci->input->is_ajax_request()){
            ajax_return($msg);
        }
        $_ci->session->set_flashdata('flash_danger',$msg);
        redirect($url);
    }
}
function js_count($count1,$count2){
    $count1 = intval($count1);
    $count2 = intval($count2);
    if ($count1 === $count2) {
        return '<span class="label label-info arrowed-right arrowed-in">（持平）<span>';
    }elseif(!$count2){
        return '<span class="label label-success arrowed-in arrowed-in-right">（上升'.$count1 * 100 .'%）';
    }elseif ($count1 > $count2){
        return '<span class="label label-success arrowed-in arrowed-in-right">（上升'.@round(($count1-$count2) / $count2,4) * 100 .'%）</span>';
    }else{
        return '<span class="label label-danger arrowed">（下降'.@round(($count2-$count1) / $count2,4) * 100 .'%）</span>';
    }
}
function hookForTree(&$array){

    $_ci =& get_instance();

    if(array_key_exists('params', $array)){
        $_ci->parameters->fromArray(json_to_array($array['params']));
        $array['params'] = clone $_ci->parameters;
    }
}

function show_flash_tips(){
    $ci = & get_instance();
    $string = $ci->session->flashdata('flash_tips');
    return show_alert($string);
}

function show_alert_danger(){
    $ci = & get_instance();
    $string = $ci->session->flashdata('flash_danger');
    return show_alert($string,'alert-danger');
}

function show_alert($string='',$alert = 'alert-info'){

    if(!empty($string)){
        return '<div class="alert '.$alert.'">
    					<button data-dismiss="alert" class="close" type="button">
    						<i class="icon-remove"></i>
    					</button>
                    '.$string.'
    				</div>';
    }
}

function show_form_error(){
    $string = validation_errors();
    return show_alert($string);
}

function json_to_array($json){
	if (!is_string($json)) {
		return array();
	}
    $value = json_decode($json,TRUE);
    return $value ? $value : array();
}

/**
 * 生成订单号算法
 *
 * 订单号13位长度（时间绰11+随机数3）
 *
 * @return string
 */
function genSn()
{
	return time().mt_rand(100,999);
}

/**
 * 删除数组的指定key，如果存在
 * @param array $array
 * @param string $key
 * @return array
 */
function array_unset_key($array = array(),$key=0){
	if(array_key_exists($key,$array)){
		unset($array[$key]);
	}
	return $array;
}

/**
 * php自带的array_merge方法，会将键名为整数的数组重新索引，所以自己写了一个
 * 合并数组,键名重复会覆盖
 */
function my_array_merge(){
	$arg_list = func_get_args();

	$returnList = array();
	foreach ($arg_list as $list){
		if(!is_array($list))continue;
		foreach ($list as $k=>$v){
			$returnList[$k] = $v;
		}
	}
	return $returnList;
}

//email地址,长度大于6位
function is_email($email) {
    return preg_match("/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/", $email);
}

//字母开头，允许5-32字节，允许字母数字下划线
function is_username($username) {
	
	if (preg_match('/^[a-zA-Z][a-zA-Z0-9_]{4,32}$/', $username)) {  
	
		return true;  
	} else { 
	 
		return false;  
	}  
}

//密码6到20个字符
function is_password($pwd) {
    $flag = true;
    if (strlen($pwd)>20 || strlen($pwd)<6){
        $flag = false;
    }
    
    if(preg_match("/^\d*$/",$pwd)){
        $flag = false;
    }
    
    if(preg_match("/^[a-z]*$/i",$pwd)){
        $flag = false;
    }
    
    if(!preg_match("/^[a-z\d]*$/i",$pwd)){
        $flag = false;
    }
    return $flag;
}

//json返回数据结构
function ajax_return($info='', $status=1, $data='',$back_url='') {
    $result = array('data' => $data, 'info' => $info, 'status' => $status,'back_url'=>$back_url);
    exit( json_encode($result) );
}

//随机数
function random($length, $chars = '2345689abcdefghjkmnpqrstuvwxyzABCDEFGHJKMNPQRSTUVWXYZ') {
    $hash = '';
    $max = strlen($chars) - 1;
    for($i = 0; $i < $length; $i++) {
        $hash .= $chars[mt_rand(0, $max)];
    }
    return $hash;
}

//时间date
function datetime($time=0){
	if(!$time){
		return '';
	}
	return @date('Y-m-d H:i:s', $time);	
}

//创建目录
function create_dir($path, $mode = 0777) {
    if( is_dir($path) ) return true;

    $dir_name = $path . DIRECTORY_SEPARATOR;
    @mkdir($dir_name, 0777, true);
    @chmod($dir_name, 0777);
    return $dir_name;
}

function formhash() {
    $hash = md5( random(12) );
    $_codeigniter =& get_instance();
    $_codeigniter->session->set_userdata( array('hash'=>$hash) );
    return $hash;
}

function checkformhash($ajax = FALSE) {
    /*
    $_codeigniter =& get_instance();
    $hash = $_codeigniter->input->get('hash');
    $sess = $_codeigniter->session->userdata('hash');
    if( ! $ajax ) {
        if($hash !== $sess) ajax_return('', 'Access Denied !', 0);
    } else {
        return ($hash === $sess) ? TRUE : FALSE;
    }
    */
    return TRUE;
}

//后台动态目录
function admin_base_url($uri = '',$directory='') {
	$directory = $directory ? $directory : config_item('site_admin_dir').'/';
	
    return base_url($directory.$uri);
}

function site_url($uri = '') {
    return base_url($uri);
}

function static_url($uri = '') {
    return base_url(config_item('site_static_dir').'/'. $uri);
}

function showmessage($content = 'NULL', $continue = 'back', $icon = 'success', $time = 3) {

    $time = ((int) $time * 1000);
    $continue_html = '';
    if ($continue == 'back') {
        $continue = 'history.back()';
    } elseif($continue) {
        $continue = strpos($continue, 'http://') ? 'window.location="' . $continue . '"' : 'window.location="' . site_url($continue) . '"';
    }

//     $list = array('success', 'error', 'warning', 'question');

//     $icon = in_array($icon, $list) ? $icon : $list[0];

    $html  = '<!DOCTYPE HTML><html><head><title>提示信息</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    $html .= '<style type="text/css">body{color:#000;font:12px verdana, arial, tahoma;background:#F5F7F6;}';
    $html .= '#box{width:520px;border:1px solid #CCC;background:#FFF;margin:180px ';
    $html .= 'auto;padding:20px 35px 20px 100px;border-radius:5px;box-shadow:0 0 10px #C0C0C0}#box h1{font-size:20px;font-weight:normal}#box a{';
    $html .= 'color:#1A7613;text-decoration:none}</style><script type="text/javascript">function url() { ' . $continue . ' }setTimeout("url()", ' . $time . ')';
    $html .= '</script></head><body><div id="box"><h1>' . $content . '</h1>';
    
    if($continue != ''){
        $html .= '<p>如果浏览器没有自动跳转，请 <a href="javascript:;" onClick="url()">点击这里</a>';
    }
    
    $html .= '</p></div></body></html>';

    exit($html);
}

function authcode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
    $ckey_length = 4;	
    $key = md5($key ? $key : config_item('site_auth_key'));
    $keya = md5(substr($key, 0, 16));
    $keyb = md5(substr($key, 16, 16));
    $keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';

    $cryptkey = $keya.md5($keya.$keyc);
    $key_length = strlen($cryptkey);

    $string = $operation == 'DECODE' ? base64_decode(substr($string, $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
    $string_length = strlen($string);

    $result = '';
    $box = range(0, 255);

    $rndkey = array();
    for($i = 0; $i <= 255; $i++) {
        $rndkey[$i] = ord($cryptkey[$i % $key_length]);
    }

    for($j = $i = 0; $i < 256; $i++) {
        $j = ($j + $box[$i] + $rndkey[$i]) % 256;
        $tmp = $box[$i];
        $box[$i] = $box[$j];
        $box[$j] = $tmp;
    }

    for($a = $j = $i = 0; $i < $string_length; $i++) {
        $a = ($a + 1) % 256;
        $j = ($j + $box[$a]) % 256;
        $tmp = $box[$a];
        $box[$a] = $box[$j];
        $box[$j] = $tmp;
        $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
    }

    if($operation == 'DECODE') {
        if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
            return substr($result, 26);
        } else {
            return '';
        }
    } else {
        $string = $keyc . str_replace('=', '', base64_encode($result));
        return str_replace('=', '', base64_encode($string));
    }
}

function build_verify ( $string = '', $width = 60, $height = 24, $type = 'PNG' ) {

    $length = strlen ( $string );

    $width = ($length * 10 + 10) > $width ? ($length * 10 + 10) : $width;

    if ( $type != 'GIF' && function_exists('imagecreatetruecolor') ) {
        $im = imagecreatetruecolor ( $width, $height );
    } else {
        $im = imagecreate ( $width, $height );
    }

    $backColor = imagecolorallocate ( $im, rand(200, 255), rand(200, 255), rand(200, 255) );

    imagefilledrectangle ( $im, 0, 0, $width - 1, $height - 1, $backColor );

    $stringColor = imagecolorallocate ( $im, mt_rand(0, 200), mt_rand(0, 120), mt_rand(0, 120) );

    for ($i = 0; $i < 25; $i++) {
        imagesetpixel($im, mt_rand(0, $width), mt_rand(0, $height), $stringColor);
    }

    for ($i = 0; $i < $length; $i++) {
        imagestring($im, 5, $i * 10 + 10, mt_rand(1, 5), $string{$i}, $stringColor);
    }

    // ob_clean();
    header('Content-type: image/' . strtolower($type) );
    $function = 'image' . strtolower($type);
    $function( $im );
    imagedestroy( $im );
}

//内容过滤
function filter($str){
	 $farr = array(
	   "/\s+/", //过滤多余的空白
	   "/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU", //过滤 script等恶意代码,还可以加入object的过滤flash
	   "/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU", //过滤javascript的on事件
	 );
	 $tarr = array(
	   " ",
	   " ", //如果要直接清除不安全的标签，这里可以留空
	   " ",
	 );
	 $str = preg_replace( $farr,$tarr,$str);
	 return $str;
}

/* End of file common_helper.php */
/* Location: ./application/helpers/common_helper.php */