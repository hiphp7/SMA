<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Channel extends Admin_Controller {
	 var $sellerid; 
	function __construct() {
		   
		parent::__construct();
		$this->load->model(array('rouji_group_model'));
	} 
		 
	function index(){
		$this->_template('bp/channelManage/index');  
	} 
}
 
?>
