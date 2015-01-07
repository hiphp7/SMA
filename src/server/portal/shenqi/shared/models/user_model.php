<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * 用户管理模型类
 *
 * @author  <xuebingwang2010@gmail.com>
 * @links 
 *
 */
class User_model extends MY_Model {
	
    private $_table_field = array('*');
    /**
     * 构造函数
     * 
     * @access public
     * @return void
     */
    public function __construct() {
		
        parent::__construct();
    }
    
    /**
     * (non-PHPdoc)
     * @see MY_Model::getAll()
     */
    public function getAll($options=array(),&$total_rows=array()){
    	 
    	$options['select'] = array("$this->_table.*",'g.title as group_name',);
    
    	$join[] 		   = array("t_group g" ,"g.gid=$this->_table.gid");
    
    	$options['join'] = $join;
    
    	return parent::getAll($options,$total_rows);
    }
    
    /**
     * 根据条件查找用户，如果找不到，直接根据查找条件插入一个，并返回
     * @param string $options
     * @return array
     */
    public function findsert(Array $options=array()){
    	if(!$options){
    		return array();
    	}
    	$user = $this->getOne($options,TRUE);
    	if(!$user){
    		$options['create_time'] = time(); 
    		$options['regtime'] = time(); 
    		$options['regip'] = $this->session->userdata['ip_address'];
    		$options['uid'] = $this->add($options);
    		$user = $options;
    	}else {
    	    unset($user['password']);
    	}
    	return $user;
    }
}

/* End of file users_model.php */
/* Location: ./application/models/users_model.php */
