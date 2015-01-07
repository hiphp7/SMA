<?php
/**
 * 自定义模型类,继承CI_Model
 * 
 * @author xuebingwang
 *
 */

class MY_Model extends CI_Model{
	
	public $_table;
	protected  $_pk;
	protected  $_fields;
	protected  $_new;
	
	function __construct($table=''){
		if(!$this->_table){
			$this->_table = strtolower('t_'.str_replace('_model', '', get_class($this)));
		}
		if(!empty($table))
		{
	     $this->_table = 't_'.$table;
		}
		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
	}
	
	/**
	 * 设置数据
	 */
	private function _setData(Array $data=array())
	{
		if(empty($this->_fields)){
			$this->getFields();
		}
		
		if(is_array($data))
		foreach($data as $key=>&$val){
			if(!array_key_exists(strtolower($key), $this->_fields['_type'])){
				unset($data[$key]);
			}else{
				if(!is_scalar($val)){
					continue;
				}
				$type = strtolower($this->_fields['_type'][strtolower($key)]);
				if($type == 'date'){
				    $this->db->set($key,"to_date('$val','YYYY/MM/DD HH24:MI:SS')",FALSE);
				    unset($data[$key]);
				}
// 				if(strpos($type, 'int') !== false){
// 					$val = (int)$val;
// 				}elseif(in_array($type, array('float', 'double'))){
// 					$val = (float)$val;
// 				}elseif(is_bool($val)){
// 					$val = (string)$val;
// 				}
			}
		}
	
		return $data;
	}

	/**
	 * 获取字段信息，如果信息不存在，则查询数据库自动缓存
	 */
	public function getFields()
	{
		if(!$this->_fields){

		    $this->cache->set_dir($this->_table);
		    $this->_fields = $this->cache->get($this->_table.'_fields');
		    if(!$this->_fields){
        		$this->_getFields();
		    }
			
		}
		return $this->_fields;
		
	}
	
	public function getPK()
	{
	    if(!$this->_pk){
	        if(empty($this->_fields)){
	            $this->getFields();
	        }
    		$this->_pk = isset($this->_fields['_pk']) ? $this->_fields['_pk'] : null;
	    }
		return $this->_pk;
	}
	
	/**
	 * 获取表中字段信息
	 *
	 */
	private function _getFields()
	{
		$fields = array();
		if($list = $this->db->query("select column_name,data_type from information_schema.COLUMNS  WHERE table_name = '$this->_table'")->result_array())
		{
			foreach($list as $field)
			{
				$field = array_flip(array_change_key_case(array_flip(array_change_key_case($field))));
				$type[$field['column_name']] = $field['data_type'];
			}
		}
		$this->_fields['_type'] = $type;

		$sql = "show  INDEX from $this->_table";
		$key = array_change_key_case($this->db->query($sql)->row_array());
		
		if(isset($key['key_name'])&&$key['key_name']=='PRIMARY'){
			$this->_fields['_pk'] = strtolower($key['column_name']);
		}
//exit();
		
		$this->cache->set_dir($this->_table);
		$this->cache->save($this->_table.'_fields', $this->_fields,config_item('site_cache_time'));
		
		return $this->_fields;
	}
	
	/**
	 * 获取一条新的数据库表对象或数组
	 * @return Ambigous <multitype:, multitype:multitype:boolean mixed unknown  >
	 */
	public function getNew($is_array=TRUE){
	    if(!$this->_new){
	        
    		$fileds = $this->getFields();
    		foreach ($fileds['_type'] as &$val){
    			$val = '';
    		}
    		$this->_new = $fileds['_type'];
	    }
		return $is_array ? $this->_new : (object)$this->_new;
	}
	
	/**
	 * 获取一条数据
	 * @param array|string|int $options
	 * @param boolean $is_array
	 */
	public function getOne($options=null,$is_array=false){
	    
		if (!$options){
	        return $this->getNew($is_array);
	    } 
	    
	    $this->cache->set_dir($this->_table);
	    $cache_name = md5(serialize(array($options,$is_array)).'one');
	    $data = $this->cache->get($cache_name);
	    
	    if($data !== FALSE){
	        return $data;
	    }
	    
		$this->createSql($options);
		$query = $this->db->limit(2)->get($this->_table);
		
		$n = is_array($options) && array_key_exists('n', $options) ? $options['n'] : 0;
		$item = $is_array ? $query->row_array(strtoupper($n)) : $query->row(strtoupper($n));
		
		//将所有key转为小写
		if($is_array && is_array($item)){
			$item = array_change_key_case($item);
		}elseif(is_object($item)){
			$item = (object)array_change_key_case((array)$item);
		}
		
		$this->cache->save($cache_name,$item,config_item('site_cache_time'));
		
		return $item;
	}
	


	public function getTotal($options=array()){
	    
	    $this->cache->set_dir($this->_table);
	    $cache_name = md5(serialize($options).'total');
	    $cache_data = $this->cache->get($cache_name);
	    if($cache_data !== FALSE){
	        
	        return $cache_data;
	    }
	    
        $this->createSql($options);
        $this->db->from($this->_table);
        
	    $total = 0;
	    if (array_key_exists('group_by', $options)) {
	        
	        $this->db->ar_select = array();
	        $this->db->select($this->_table.'.'.$this->getPK());
	        
	        $sql = $this->db->_count_string.$this->db->_protect_identifiers('numrows').' FROM ('.$this->db->compile_select().') COUNTTABLE';
	        
	        $query = $this->query($sql);
	        $this->db->reset_select();
	        
	        if ($query->num_rows() > 0)
	        {
	            $total = (int)$query->row()->numrows;
	        }
	        
	    }else{
    	    $total = $this->db->count_all_results();
	    }
	    

	    $this->cache->save($cache_name,$total,config_item('site_cache_time'));
	    
	    return $total;
	}
	
	/**
	 * 获取多条数据
	 * @param array|int|string $options
	 * @param array $total_rows
	 * @param boolean $is_array
	 */
	public function getAll($options=null,&$total_rows=array(),$is_array=false){

	    $this->cache->set_dir($this->_table);
	    
	    $cache_name = md5(serialize(array($options,$is_array)).'all');
	    $cache_data = $this->cache->get($cache_name);
	    if($cache_data !== FALSE){
	        list($data,$total_rows) = $cache_data;
	        return $data;
	    }
	    
		//判断是否需要获取总条数
		if($total_rows){
		    $total_rows['total_rows'] = $this->getTotal($options);
		}
		
		$this->createSql($options);
		
		if(isset($options['per_page']) && isset($options['page'])){
			$this->db->limit($options['page'],$options['per_page']);
		}
		
		if(isset($options['order'])){
			$this->db->order_by($options['order']);
		}
		
		$query = $this->db->get($this->_table);
		
		$data = $is_array ? $query->result_array() : $query->result();
		
		//将所有key转为小写
		
		$data = array_map(array($this,'change_key_to_lower'),$data);
		$this->cache->save($cache_name,array($data,$total_rows),config_item('site_cache_time'));
		
		return $data;
	}
	
	private function change_key_to_lower($data){
		if(!$data || is_string($data)){
			return $data;
		}
		return is_array($data) ? array_change_key_case($data) : (object)array_change_key_case((array)$data);
	}
	
	
	/**
	 * 获取多条数据,以数组形式返回
	 * @param array|int|string $options
	 */
	public function getAll2Array($options=null){
		$null = null;
		return $this->getAll($options,$null,true);
	}
	
	/**
	 * 
	 * @param array|int|string $options
	 */
	protected function createSql($options=null){
	    
		switch (true){
			case is_int($options):
			    
			    $this->db->where($this->_table.'.'.$this->getPK(),$options);
			    break;
			    
			case is_string($options):
			    
			    $this->db->where($options);
			    break;
			    
			case is_array($options):
			    
			    $this->_createSql($options);
			    break;
		}
		
	}
	
	/**
	 * 
	 * @param array $options
	 * @return boolean
	 */
	private function _createSql($options=array()){
	    if(!is_array($options)){
	        return false;
	    }
	    
	    $where = array();
	    $where_in = array();
	    $like = array();
	    
	    foreach ($options as $func=>$list){
	        switch (true){
	        	    
	        	case $func == 'select': 					//要查询的字段名
	        	    if (is_array($list)) {
	        	        $list	= implode(',', $list);
	        	    }
	        	    $this->db->$func($list);
	        	    break;
	        	    
	        	case $func == 'like':
	        	    $like = $list;
	        	    break;
	        	    
	        	case $func == 'str_where':
	        	    $this->db->where($list);
	        	    break;    
	        	    
        	    case $func == 'where':
        	        $where = array_merge($list,$where);
        	        break;
	        	     
	        	case $func == 'join':
	        	     
	        	    if(!is_array($list)){
	        	        break;
	        	    }
	        	     
	        	    foreach ($list as $params){
	        	        $table = $cond = '';
	        	        $type = 'left';
	        	         
	        	        $size = count($params);
	        	        if($size == 2 || $size == 3){
	        	            $size == 2 ? list($table,$cond) = $params : list($table,$cond,$type) = $params;
	        	             
	        	            $this->db->join($table,$cond,$type);
	        	        }
	        	    }
	        	    break;
	        	    
        	    case $this->_in_fileds($func)://判断key是否是当前数据库的某个字段
        	        if(is_array($list)){
        	            $where_in[$func] = $list;
        	        }else {
            	        $where[$func] = $list;
        	        }
        	        break;
        	        
	        	case is_array($list):
	        	    foreach ($list as $k=>$v){
	        	        if(method_exists($this->db,$func)){
	        	            $this->db->$func($k,$v);
	        	        }
	        	    }
	        	    break;

	        	default:
	        	    if(method_exists($this->db, $func)){
	        	        $this->db->$func($list);
	        	    }
	        	    //TODO
	        }
	    }
	    
	    $this->getFields();
        foreach ($where as $key=>$val){
            $k = trim(str_replace(array('!=','>=','=<','=','>','<'), '', $key));
            $k = explode('.', $k);
            $k = count($k) == 2 ? $k[1] : $k[0];
            
			$type = strtolower(isset($this->_fields['_type'][$k]) ? $this->_fields['_type'][$k] : '');
			if($type == 'date'){
			    $this->db->where($key,$val,FALSE);
			    unset($where[$key]);
			}
        }
	    if($where){
    	    $this->db->where($where);
	    }
	    if($like){
    	    $this->db->like($like);
	    }
	    if($where_in){
	        foreach ($where_in as $func=>$list){
    	        $this->db->where_in($func,$list);
	        }
	    }
	}
	
	private function _in_fileds($key=''){
	    
        $array = explode('.', $key);
        $key = trim(str_replace(array('!=','>=','=<','=','>','<'), '', $key));
        return count($array) == 2 ? TRUE : array_key_exists($key, $this->getNew());
    }
	
	/**
	 * 检查sql中是否有where条件
	 * @return boolean
	 */
	private function hasWhere(){
		return $this->db->ar_where || $this->db->ar_wherein || $this->db->ar_like;
	}
	
	/**
	 * 增加记录
	 * @param array $data
	 * @return int|boolean
	 */
	public function add(Array $data=array()){
		$data = $this->_setData($data);
		if(array_key_exists($this->getPK(),$data)){
		    //unset($data[$this->getPK()]);
		}
		
		$this->cache->clean($this->_table);
		
		return $this->db->insert($this->_table, $data);
	}
	
	/**
	 * 指量增加记录
	 * @param array $data
	 * @return boolean
	 */
	public function addBatch(Array $data=array()){

	    $this->cache->clean($this->_table);
	    
		return $this->db->insert_batch($this->_table, $data);
	}
	
	/**
	 * 批量修改记录
	 * @param array $set
	 * @return boolean
	 */
	public function updateBatch(Array $set=array(),$index){

	    $this->cache->clean($this->_table);
	    
		$this->db->update_batch($this->_table, $set,$index);
		return $this->db->affected_rows();
	}
	
	/**
	 * 更新记录
	 * @param array $data
	 * @param array $where
	 * @return object|boolean
	 */
	public function update(Array $data=array(),$options=array()){
		//整理数据
		$data = $this->_setData($data);
		if(!$options){
			$pk = $this->getPK();
			if(array_key_exists($pk, $data)){
    			$pkValue = $data[$pk];
				$options['where'][$pk] = $pkValue;
				unset($data[$pk]);
			}
		}
		
		$this->createSql($options);
		
		//如果没有修改内容或者where条件，直接返回
		if(!$data || !$this->hasWhere()){
		    log_message('ERROR','没有where条件!');
			return false;
		}
		
		$this->cache->clean($this->_table);
		
		$this->db->update($this->_table,$data);
		return $this->db->affected_rows();
	}
	
	/**
	 * 
	 * @param array $data
	 * @param array $options
	 * @return Ambigous <object, boolean>|Ambigous <number, boolean>
	 */
	public function save(Array &$data=array(),$options=array()){
	    
	    if(array_key_exists($this->getPK(), $data) && $data[$this->getPK()]){
	        
	        return $this->update($data,$options);
	    }else {
	        
	        $data[$this->getPK()] = $this->add($data);
	        
	        return $data[$this->getPK()];
	    }
	}
	
	/**
	 * 删除记录
	 * @param array $where
	 */
	public function delete($options=null){
		$this->createSql($options);
		if(!$this->hasWhere()){
			return false;
		}
		$this->cache->clean($this->_table);
		
		return $this->db->delete($this->_table);
	}
	
	/**
	 * 
	 * @param string $query
	 */
	public function query($query,$func='row'){
	    if(empty($func)){
			$this->db->query($query);
	        return $this->db->affected_rows();
	    }
		return $this->db->query($query)->$func();
	}
	public function last_insert_id()
  {
		return $this->db->insert_id();
  }
}

?>
