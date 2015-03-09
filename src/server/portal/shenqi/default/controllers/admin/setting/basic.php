<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class basic extends Admin_Controller {
	  
	function __construct() {
		   
		parent::__construct();
	} 
		 
	function index(){
	    redirect(admin_base_url('setting/basic/info'));
	}
	
	function info(){

	    parent::info();
	    
		$setting = (object) $this->config->config;
		
		$this->_template('admin/setting/basic',array('setting'=>$setting));
	} 
	 
    
	
	public function save(){
		
	    $this->load->library('form_validation');
	    $valid = $this->form_validation->run('admin/setting/basic/info');
	    if(!$valid){
	        $msg = $this->form_validation->error_string();
	        ajax_return($msg,3);
	    }
		$file='./default/config/setting.php';
		$array = array();
		foreach ($this->input->post(null,true) as $k=>$v){
			if(in_array($k, array('sms_pwd'))){
				$array[$k] = htmlspecialchars($v);
			}elseif(intval($v)){
				$array[$k] = intval($v);
			}else{
				$array[$k] = htmlspecialchars($v);
			}
		}		
		//缓存
		require $file;
		foreach($config as $k=>$v)
		{
						if(isset($array[$k]))
						{
										$config[$k] = $array[$k];
						}
		}
		$text='<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");';
		
		$text.=' $config='.var_export($config,true).';';
		
		if(false!==@fopen($file,'w+')){
			
			file_put_contents($file,$text);
			
			ajax_return(lang('save_success'),0);
		}else{
			ajax_return(lang('save_failed'));
		}
		die;
	}
	
}
 
?>
