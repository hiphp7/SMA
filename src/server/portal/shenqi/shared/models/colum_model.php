<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * 用户组管理模型类
 */
class Colum_model extends MY_Model {
	
    public $type = array('admin'=>'运营门户','bp'=>'商户门户','agent'=>'代理门户');
    /**
     * 构造函数
     * 
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
        
    }
}

/* End of file colum_model.php */
/* Location: ./application/models/colum_model.php */
