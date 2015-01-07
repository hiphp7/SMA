<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth {
    
    /**
     * 用户信息 session user
     *
     * @access private
     * @var object
     */
    private $_SU;
    
    /**
     * CI 句柄 codeigniter
     *
     * @access private
     * @var object
     */
    private $_CI;
    
    /**
     * 构造函数
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $this->_CI = & get_instance();
        $this->_SU = unserialize( $this->_CI->session->userdata('sessuser') );
    }
    
    /**
     * 获取 session 数据
     *
     * @access public
     * @return string
     */
    public function user($key = '') {
        
        if(empty($this->_SU)) {
            return FALSE;
        }
        
        if( empty($key) ) {
            return $this->_SU;
        } else {
            return isset( $this->_SU->$key ) ? $this->_SU->$key : FALSE;
        }
    }
    
    /**
     * 判断是否登录
     *
     * @access public
     * @return boolean
     */
    public function islogin() {

        
        if(empty($this->_SU) || $this->_SU->token === NULL){
            return FALSE;
        }
        
        $user = null;
        $options = array('token'=>$this->_SU->token);
        
        if($this->_SU->type == 'admin' && $this->_SU->uid != NULL) {
            
            $options['uid'] = $this->_SU->uid;
            $user = $this->_CI->user_model->getOne($options);
        }elseif($this->_SU->type == 'agent' && $this->_SU->agentid != NULL){

            $options['agentid'] = $this->_SU->agentid;
            $user = $this->_CI->agent_model->getOne($options);
        }elseif($this->_SU->type == 'bp' && $this->_SU->sellerid != NULL){

            $options['sellerid'] = $this->_SU->sellerid;
            $user = $this->_CI->seller_model->getOne($options);
        }  
        return $user ? TRUE : FALSE;
    }
    
    /**
     * 检测用户登录
     *
     * @access public
     * @params string $user_name 用户名
     * @params string $password 用户密码
     * @return void
     */
    public function checkuserlogin($user_name, $password,$type) {
        $user = null;
        $options = array();
        $pass = null;
        
        if($type == 'admin'){
            //运营门户登录
            $options['select'] = array('t_user.*','t_group.key','t_group.items','t_group.title','t_group.status as gstatus');
            $options['join'][] = array('t_group','t_user.gid=t_group.gid');
            $options['where'] = array('user_name'=>$user_name );
            
            $user = $this->_CI->user_model->getOne($options);
        }elseif($type == 'agent'){
            //代理门户登录
            $options['agentid'] = $user_name;
    
            $user = $this->_CI->agent_model->getOne($options);

        }elseif($type == 'bp'){
            //BP门户登录
            $options['sellerid'] = $user_name;
            $user = $this->_CI->seller_model->getOne($options);
        }
        
        if( ! $user ) 
            return FALSE;
        
        $pass = null;
        $pass = md5($password) == $user->password;
        /** 去除密码 */
        unset($user->password);
        
        $user->type = $type;
		
        return $pass ? $user : FALSE;
    }
	
    /**
     * 处理用户登录
     *
     * @access public
     * @params object $user 用户信息
     * @return void
     */
    public function process_login($user) {

        $data = array();

        $data['token']     = md5(random(10));
        
        if($user->type == 'admin'){
            
            $data['lastlogin'] = time();
    		
            $data['activity']  = time();
    		
            $data['lastip']    = $this->_CI->input->ip_address();
    		
            $data['uid']        = $user->uid;
            
            $this->_CI->user_model->update($data);
        }elseif($user->type == 'agent'){

            $data['agentid'] = $user->agentid;

            //$this->_CI->agent_model->update($data);
        }elseif($user->type == 'bp'){

            //$data['update_date'] = date('Y/m/d H:i:s');
            $data['sellerid'] = $user->sellerid;

          //  $this->_CI->seller_model->update($data);
        }


        /** 更新token */
        $user->token    = $data['token'];
        /** 写入session */
        $this->set_session($user);
    }
    
    /**
     * 处理用户登出
     * 
     * @access public
     * @return void
     */
    public function process_logout() {

        if($this->_SU){
            
            $this->_CI->load->model('userlog_model');
            $log = $this->_CI->userlog_model->getOne(array('lid'=>$this->user('logid')),TRUE);
            
            if(!$log['logouttime']){
                $data = array('logouttime'=>time(),'lid'=>$log['lid']);
                $this->_CI->userlog_model->update($data);
            }
        }
		
        $this->_CI->session->sess_destroy();
		
    }
    
    /**
     * 设置 session 数据
     *
     * @access public
     * @return void
     */
    public function set_session($data) {
		
        $session_data = array( 'sessuser'=>serialize($data) );
		
        $this->_CI->session->set_userdata($session_data);
		
    }

}

/* End of file Auth.php */
/* Location: ./application/libraries/Auth.php */
