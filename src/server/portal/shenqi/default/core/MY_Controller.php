<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');  

class MY_Controller extends CI_Controller {
    
    public function __construct() {
		
        parent::__construct();
        $this->load->library('Session');
        $this->load->model(array('user_model'));
        $this->load->switch_theme();

        if(!$this->input->is_ajax_request() && !$this->input->get('is_ajax')){
            $this->output->enable_profiler((boolean)config_item('site_debug'));
        }
        
    }
	
}


class Admin_Controller extends MY_Controller {
   
    public $user_info;
    
    public $model;
    
    /**
     * menu_tree
     * 菜单集合
     *
     * @var Tree
     * @access  private
     **/
    public $menu_tree;
    
    /**
     * _current_menu
     * 当前所在的菜单的下标
     *
     * @var int
     * @access  private
     **/
    public $current_menu = -1;
    
	/**
     * breadcrumb
     * 面包屑
     *
     * @var array
     * @access  public
     **/
	public $breadcrumb = array();
    
	/**
	 * 构造函数
	 */
    public function __construct() {
		
        parent::__construct();

        $this->load->library('Auth');
        
         //var_dump($this->auth->user());
         //var_dump($this->auth->islogin());die;
        // 检测是否有权限登录管理后台
		if (!$this->auth->islogin()){
		    //如果是ajax请求,直接返回
            if ($this->input->is_ajax_request()) {
                ajax_return(lang('you_are_logout'),-999,'','/login/logout');
            }
			redirect( site_url('login/logout') );
		}
		
		$this->user_info = $this->auth->user();
		unset($this->user_info->password);
    
    	$this->load->model("colum_model");

    	$options     = array('type'=>$this->user_info->type);
    	
		//管理菜单列表
		if ($this->user_info->type == 'admin' && $this->user_info->key != 'root') {
			$list        = explode(",",$this->user_info->items);
			
			if(is_array($list)){
				$options['where_in'] = array('cid'=>$list);
			}
		}
		
		$options['where'] = array('status'=> 1);
		$options['order'] = 'order_id desc,cid desc';

		//获取当前真实地址
		$current_arr['directory'] = $this->router->directory;
		$current_arr['con_name']  = $this->router->class;
		$current_arr['con_name'] .= $this->router->method == 'index' ? '' : '/'.$this->router->method;
		
		$list = $this->colum_model->getAll2Array($options);
		
 		//var_dump($list);die;
		//判断是否有权限访问当前页面,仅精确到控制器级别
		$has_no_rule = TRUE;
		foreach ($list as $item){
		    $con_name = explode('/', $item['con_name']);
		    $con_name = $con_name[0];
		    //如果所拥有的菜单的目录和控制器与当前访问的目录控制器一致,则认为有访问权限
		    if($item['directory'].$con_name == $this->router->directory.$this->router->class){
// 		        var_dump($item);die;
		        $has_no_rule = FALSE;
		        break;
		    }
		}
		//如果没有访问权限,直接跳转至
		if($has_no_rule){
// 		   reset($list);
// 		   $colum = current($list);
// 	       $this->session->set_flashdata('flash_tips','权限不足！');
// 		   redirect('/'.$colum['directory'].$colum['con_name']);

		    showmessage('权限不足！','');
		}
		
		$this->load->library('Tree');
		//构造树型对象
		$this->tree->init($list,array('cid', 'parents','title'),0,$current_arr,'hookForTree');
		$this->breadcrumb[] = array('title'=>'<a href="'.base_url('/'.$this->user_info->type.'/main').'">首页</a>','link'=>admin_base_url('index'));
		//找出当前地址的所有祖先
		foreach ($this->tree->navi($this->tree->current_id) as $item){
			$this->breadcrumb[$item['cid']] = array('title'=>$item['title'],'link'=>admin_base_url($item['con_name'],$item['directory']));
		}
		$this->menu_tree      = $this->tree->leaf();
		$this->current_menu   = $this->tree->current_id;
		
    }
    
    /**
     * 列表页
     */
    public function index(){
        $this->load->model('sharepage');
    }
    
    /**
     * 单页，详情页
     * @param string $id
     */
    public function info($id=null){

        if($this->input->is_post()){
            $this->save($id);
        }
    }
    
    /**
     * 保存方法
     */
    protected function save($id=null){
    	die;
    }
    
    /**
     * 状态修改
     */
    public function clicktik(){
    
    	$val = $this->input->get_post('val'); //字段值
    
    	$field = htmlspecialchars($this->input->get_post('field'));//所要操作的字段
    	$field = $field ? $field : 'status';
    	
    	$result = null;
    	if($this->model){
	    	$data[$field] = $val;
	    	$data[$this->model->getPK()] = intval($this->input->get_post('id')); //所在的字段ID,主键
	    	//修改信息
	    	$result = $this->model->update($data);
    	}
    
    	//信息返回操作
    	if($result){
    			
    		echo json_encode(array('status'=> '1','msg'=>'修改成功'));exit();
    	}else{
    			
    		echo json_encode(array('status'=> '2','msg'=>'修改失败'));exit();
    	}
    
    }
    
    /**
     * 删除
     */
    public function delete($id = '',$status='0'){
			if(empty($id))
			{
    	    $id = $this->input->get_post('id');
			}
    	//删除信息
    	$result = null;
    	if($this->model){
    	    $fields = $this->model->getFields();
    	    //如果含有status字段,为假删
    	    //if(in_array('status',$fields['_type'])){
    	    if(array_key_exists('status',$fields['_type'])){
    	        
    	    	$data['status'] = $status;
        		$data[$fields['_pk']] = $id; //所在的字段ID,主键
        		//修改信息
        		$result = $this->model->update($data);
    	    }else{
    	        //否则为真删
    	        $result = $this->model->delete(array($fields['_pk']=>$id));
    	    }
    	}
    
    	//信息返回操作
    	if($result){
    			
    		echo json_encode(array('status'=> '1','msg'=>'删除成功'));exit();
    	}else{
    			
    		echo json_encode(array('status'=> '2','msg'=>'删除失败'));exit();
    	}
    
    }
    
    /**
     * 批量操作
     */
    public function batch(){
    
    	$idArr = $this->input->post("id");
    
    	$type	= intval($this->input->post("type"));
    
    	$field	= htmlspecialchars($this->input->post("field"));
    
    	//选择要处理的ID
    	if( count($idArr) < 0){
    		echo json_encode(array('status'=> '2'));exit();
    	}
    
    	//处理的字段
    	if(!$field){
    		echo json_encode(array('status'=> '2'));exit();
    	}
    	
    	if($this->model){
	    	//101为删除恢复
    		if($type==101){
    			$data[$field] = '1';
    		}else{
    			$data[$field] = $type;
    		}
    		
    		$where_in[$this->model->getPK()] = $idArr;
    		
    		$options['where_in'] = $where_in;
    		
    		if($type == 100){
    			$this->model->delete($options);
    		}else{
	    		$this->model->update($data,$options);
    		}
    	}
    
    	echo json_encode(array('status'=> '1'));exit();
    }
    

    /**
     * 加载视图
     *
     * @access  protected
     * @param   string
     * @param   array
     * @return  void
     */
    protected function _template($template, $data = array())
    {
        $page_title = array('admin'=>'运营门户','bp'=>'商户门户','agent'=>'代理门户');
        $data['tpl']        = $template;
        $data['page_title'] = $page_title[$this->uri->segments[1]];
        $this->load->view($this->user_info->type.'/sys_entry', $data);
    }
}
