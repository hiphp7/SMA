<?php
class curl{
     protected $_useragent = "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:26.0) Gecko/20100101 Firefox/26.0";
     protected $_url;
     protected $_followlocation;
     protected $_timeout;
     protected $_maxRedirects;
     protected $_cookieFileLocation = './cookie.txt';
     protected $_post;
     protected $_postFields;
     //protected $_referer ="http://".$_SERVER['SERVER_NAME'];
     protected $_referer ="http://www.baidu.com";

     protected $_session;
     protected $_webpage;
     protected $_includeHeader;
     protected $_noBody;
     protected $_status;
     protected $_binaryTransfer;
     public    $authentication = 0;
     public    $auth_name      = '';
     public    $auth_pass      = ''; 
     /*
     *是否使用Auth登录验证
     */
     public function useAuth($use){
       $this->authentication = 0;
       if($use == true) $this->authentication = 1;
     }

     /*
     *设置验证使用的用户名
     */
     public function setName($name){
       $this->auth_name = $name;
     }
     /*
     *设置验证使用的密码
     */
     public function setPass($pass){
       $this->auth_pass = $pass;
     }

     public function __construct($url,$followlocation = true,$timeOut = 30,$maxRedirecs = 4,$binaryTransfer = false,$includeHeader = false,$noBody = false)
     {
         $this->_url = $url;
         $this->_followlocation = $followlocation;
         $this->_timeout = $timeOut;
         $this->_maxRedirects = $maxRedirecs;
         $this->_noBody = $noBody;
         $this->_includeHeader = $includeHeader;
         $this->_binaryTransfer = $binaryTransfer;

         $this->_cookieFileLocation = dirname(__FILE__).'/cookie.txt';

     }
     /*
     *设置请求来源，默认为百度
     */

     public function setReferer($referer){
       $this->_referer = $referer;
     }
     /*
     *设置coolieFile文件的存放位置，如使用cookie则必须设置为web服务器有读写权限的目录
     */
     public function setCookiFileLocation($path)
     {
         $this->_cookieFileLocation = $path;
     }

     /*
     *设置数据的提交方式为post,postFields是键值对数组
     */
     public function setPost ($postFields)
     {
        $this->_post = true;
        $this->_postFields = $postFields;
     }

     /*
     *设置userAgent,如无必要，请使用默认设置
     */
     public function setUserAgent($userAgent)
     {
         $this->_useragent = $userAgent;
     }

     /*
     *创建并发送请求
     */
     public function createCurl($url = 'nul',$cookie_str='')
     {
        if($url != 'nul'){
          $this->_url = $url;
        }

         $s = curl_init();

         curl_setopt($s,CURLOPT_URL,$this->_url);
         //curl_setopt($s,CURLOPT_HTTPHEADER,array("Expect: \r\ncharset=utf-8"));
         curl_setopt($s,CURLOPT_TIMEOUT,$this->_timeout);
         curl_setopt($s,CURLOPT_MAXREDIRS,$this->_maxRedirects);
         curl_setopt($s,CURLOPT_RETURNTRANSFER,true);
         curl_setopt($s,CURLOPT_FOLLOWLOCATION,$this->_followlocation);
         curl_setopt($s,CURLOPT_COOKIEJAR,$this->_cookieFileLocation);
         curl_setopt($s,CURLOPT_COOKIEFILE,$this->_cookieFileLocation);

         if($this->authentication == 1){
           curl_setopt($s, CURLOPT_USERPWD, $this->auth_name.':'.$this->auth_pass);
         }
         if(!empty($cookie_str)){
           curl_setopt($s, CURLOPT_COOKIE, $cookie_str);
         }
         if($this->_post)
         {
             curl_setopt($s,CURLOPT_POST,true);
             curl_setopt($s,CURLOPT_POSTFIELDS,$this->_postFields);
         }

         if($this->_includeHeader)
         {
               curl_setopt($s,CURLOPT_HEADER,true);
         }

         if($this->_noBody)
         {
             curl_setopt($s,CURLOPT_NOBODY,true);
         }
         /*
         if($this->_binary)
         {
             curl_setopt($s,CURLOPT_BINARYTRANSFER,true);
         }
         */
         curl_setopt($s,CURLOPT_USERAGENT,$this->_useragent);
         curl_setopt($s,CURLOPT_REFERER,$this->_referer);
         $this->_webpage = curl_exec($s);
         $this->_status = curl_getinfo($s,CURLINFO_HTTP_CODE);
         curl_close($s);

     }
/*取得http请求的状态,200为成功*/
   public function getHttpStatus()
   {
       return $this->_status;
   }

/*将请求的返回的内容以字符串返回*/
   public function __tostring(){
      return $this->_webpage;
   }
} 

