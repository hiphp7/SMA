<?php if ( ! defined('BASEPATH') ) exit('No direct script access allowed');

class Login extends MY_Controller {
    
    /**
     * 拒绝登录等待时间
     * 
     * @access private
     * @var int
     */
    private $_access_login_time;
    
    /**
     * 限制登录总共次数
     * 
     * @access private
     * @var int
     */
    private $_access_login_count;
    
    /**
     * 登录错误剩余次数
     * 
     * @access private
     * @return int
     */
    private $_access_login_error;
    
    /**
     * 拒绝登录提示文字
     * 
     * @access private
     * @return int
     */
    private $_access_login_title;
    
    /**
     * 构造函数
     * 
     * @access public
     * @return void
     */
    public function __construct() {
		
        parent::__construct(); 

        $this->load->library('Auth');
        
        $this->load->model('userlog_model');
    }
    
    /**
     * 用户退出
     *
     * @access public
     * @return void
     */
    public function logout($url='') {
        
        $this->load->helper('cookie');
        delete_cookie('P');
        
        $this->auth->process_logout();
        
        redirect( site_url(str_replace('-', '/', $url)) );
    }
    
    private function before_login($type='admin'){

        $this->_access_login_time   = (int) $this->config->item('site_accesslogin_time');
        
        $this->_access_login_count  = (int) $this->config->item('site_accesslogin_count');
        
        $ip = $this->input->ip_address();
        
        $in = time() - ($this->_access_login_time * 60);

        $user_name = htmlspecialchars(trim($this->input->get_post('user_name')));
        
        $options = array('loginname'=>$user_name, 'status'=>1,'order_by'=>'logintime desc','type'=>$type);
        $last_sc = $this->userlog_model->getOne($options);
        
        //根据设置时间,查出最近的登录错误的次数
        $options['logintime >'] = $in; 
        $options['status'] = 0; 
        if($last_sc){
            $options['lid >'] = $last_sc->lid;
        }
        
        $err = $this->userlog_model->getTotal($options);
        
        //
        $this->_access_login_error  = $this->_access_login_count - $err - 1;
        
        
        if($this->_access_login_error <= 0){
            
            $optons['select_max'] = 'lid';
            $last_error = $this->userlog_model->getOne($options);
            if($last_error){
                $wait_time = ($this->_access_login_time * 60) - (time() - $last_error->logintime);
                $str = $wait_time.'秒';
                
                if($wait_time > 60){
                    $mi = round($wait_time / 60,0);
                    if($mi > 60){
                        $str = round($mi / 60,1).'小时';
                    }else{
                        $str = intval($wait_time / 60).'分钟'.($wait_time % 60).'秒';
                    }
                }
                $this->_access_login_title  = '您的帐号因密码错误次数太多已被锁定，请' . $str . '后再登录！';
            }
        }
            
    }
    
    /**
     * AJAX 用户登录
     * 
     * @access public
     * @return json
     */
    public function ajax_login_user($type='admin') {

        if( ! $this->input->is_ajax_request() ) 
            show_404();
        
        checkformhash();

        $auth_code = htmlspecialchars(trim($this->input->get_post('auth_code')));
        session_start();
        $check_code = isset($_SESSION['pass_auth_code']) ? $_SESSION['pass_auth_code'] : '';
        if($check_code != strtolower($auth_code)){
            ajax_return('验证码不正确，请重新输入！',99998);//如果屏蔽 应当是应客户要求
        }
        
        $this->before_login($type);
        
        $user_name = htmlspecialchars(trim($this->input->get_post('user_name')));
		
        $password = htmlspecialchars(trim($this->input->get_post('password')));
        
        /** 检测是否拒用户登录 */
        if( $this->_access_login_count ) {
			
            if( $this->_access_login_error < 0 ) {
				
                ajax_return($this->_access_login_title);
            }
        }

        $logs = array('loginname'=>$user_name, 'logintime'=>time(), 'loginip'=>$this->input->ip_address(), 'status'=>0,'type'=>$type);
        /** 检测用户登录状态 */
        $user = $this->auth->checkuserlogin($user_name, $password, $type);
        if( FALSE === $user ) {
			
            $this->userlog_model->add($logs);
			
            $tips = ($this->_access_login_error == 0) ? $this->_access_login_title : '用户名或密码错误，您还可以尝试 ' . $this->_access_login_error . ' 次！';
// 			$tips = lang('login_failed');
            ajax_return($tips);
			 
        }
        //运营门户状态不正确提示被禁止登录
        if( $type == 'admin' && (!$user->status || !$user->gstatus)) {

            $this->userlog_model->add($logs);
			
			      ajax_return(lang('login_prohibit'));
        }
        
        if( $type == 'agent' && $user->status == 'OFF') {
        
            $this->userlog_model->add($logs);
            	
            ajax_return(lang('user_prohibit'));
        }
        
        //用户门户登录,状态不正确提示被注销
        if( $type == 'bp' && $user->status == "OFF") {
        
            $this->userlog_model->add($logs);
            	
            ajax_return(lang('user_prohibit'));
        }
        
        /** 写入用户登录日志 */
        $logs['status'] = 1;

        $this->userlog_model->add($logs);
        
        $user->logid = $this->userlog_model->last_insert_id();
        /** 处理用户登录 */
        $this->auth->process_login($user);
        
        $referer = $this->input->get_post('referer');
        
        //清空验证码
        unset($_SESSION['pass_auth_code']);
        ajax_return($referer,0);
    }
    
    /**
     * 获取登录页面背景图片
     */
    function get_bg_img(){
        $this->load->helper('file');
        echo json_encode(get_filenames(FCPATH.config_item('site_attachments_dir').'login'));
    }
    
}

/* End of file accounts.php */
/* Location: ./application/controllers/accounts.php */
