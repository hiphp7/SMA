<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends Admin_Controller{
	
	function __construct(){
		
		parent::__construct();
	}
	
	public function index(){
	    $count = array();
	    
	    $this->_template('admin/main',$data);
	}
	
	public function test(){
	    $this->_template('admin/test');
	}
	
	
}

/* End of file index.php */
/* Location: ./defaute/controllers/index.php */


?>
