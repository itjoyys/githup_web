<?php
if (!defined('BASEPATH')) {
	exit('No direct access allowed.');
}

class MY_Model extends CI_Model {

	protected $public_db = null;
	protected $private_db = null;
	protected $video_db = null;

	public function __construct() {
        $this->load->library('DBModel');
        $this->init_db();

	}

	//初始私有化数据库
	protected function init_db() {
		if ($this->public_db == null) {
			$this->public_db = $this->load->database("public", TRUE, TRUE);
		}
		//连接私有数据库
		if ($this->private_db == null) {
			$this->private_db = $this->load->database("private", TRUE, TRUE);
		}

		if ($this->video_db == null) {
			$this->video_db = $this->load->database("video", TRUE, TRUE);
		}
	}

	//PHP stdClass Object转array
	protected function object_array($array) {
	    if(is_object($array)) {
	        $array = (array)$array;
	    } if(is_array($array)) {
	        foreach($array as $key=>$value) {
	            $array[$key] = $this->object_array($value);
	        }
	    }
	    return $array;
	}

	public function M($mtab){
		if ($mtab['type'] == 1) {
            $data_con = $this->load->database('private', TRUE, TRUE);
        }elseif ($mtab['type'] == 2) {
        	$data_con = $this->load->database('public', TRUE, TRUE);
        }elseif($mtab['type'] == 3){
        	$data_con = $this->load->database('video', TRUE, TRUE);
        	$v_port = $data_con->port;
        }elseif($mtab['type'] == 4){
        	$data_con = $this->load->database('manage', TRUE, TRUE);
        	$v_port = $data_con->port;
        }else{
        	$data_con = $this->load->database('private', TRUE, TRUE);
        }

		$db_config = array();
		//端口
		if (!empty($v_port)) {
			$db_config['host'] = $data_con->hostname.':'.$v_port;
		}else{
			$db_config['host'] = $data_con->hostname;
		}
        //$db_config['host'] = $data_con->hostname;
		$db_config['user'] = $data_con->username;
		$db_config['pass'] = $data_con->password;
		$db_config['dbname'] = $data_con->database;
        return M($mtab['tab'],$db_config);
	}

	//获取页数
	public function get_page($tab,$totalPage,$page){
		$db_model['tab'] = $tab;
		$db_model['type'] = 1;
		return $this->M($db_model)->showPage($totalPage,$page);
	}

		//返回批量插入SQL
	public function madd_sql($data,$tab){
        foreach ($data as $k=> $v) {
            $keys = $vals = array();
            foreach ($v as $key => $val) {

                $keys[] = $this->parseKey($key);// "`".$key."`";
                $vals[] = "'" . $val . "'";
            }

            $keystr = join(",", $keys);
            $valstr = join(",", $vals);
            $tmp_valstr .= "($valstr),";
        }

        $tmp_valstr = rtrim($tmp_valstr,',');
        return "insert into $tab($keystr) values".$tmp_valstr;
	}

	//返回批量更新sql
	public function mupdate_sql($data,$tab){
        foreach ($data as $key => $val) {
	        $key = $this->parseKey($key);
	        if (is_array($val)) {
	           //为数组
	           $sets[] = "{$key}".'='."{$key}{$val[0]}{$val[1]}";
	        }else{
	           $sets[] = "{$key}='{$val}'";
	        }
	    }
        $setstr = join(",", $sets);
        $sql = 'update '.$tab.' set '.$setstr.' where '.$map['where'];
	}


	//mfind 走dbmodel
	public function mfind($map){
        $db_model = array();
        $db_model['tab'] = $map['tab'];
        $db_model['type'] = $map['type'];
        if (!empty($map['is_port'])) {
            $db_model['is_port'] = $map['is_port'];
        }
        $obj = $this->M($db_model);
        if (!empty($map['where'])) {
            $obj->where($map['where']);
        }
        if (!empty($map['field'])) {
            $obj->field($map['field']);
        }
        if (!empty($map['limit'])) {
            $obj->limit($map['limit']);
        }
        return $obj->find();
	}

		//更新在线
	public function redis_update_user(){
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$redis_akey = 'alg'.$_SESSION['site_id'].$_SESSION['adminid'];
		$redis->setex($redis_akey,'1200','1');
	}


	//退出
	public function redis_del_user(){
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$redis_akey = 'alg'.$_SESSION['site_id'].$_SESSION['adminid'];
        $redis->del($redis_akey);
	}

	//获取在线子账号
    public function redis_online_account(){
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$redis_ckey = 'alg'.$_SESSION['site_id'].'*';
        $on_user = $redis->keys($redis_ckey);
        return $on_user;
	}

		//多站点样式
	public function select_sites(){
		$map_r = array();
		$map_r['table'] = 'web_config';
		$map_r['where']['site_id'] = $_SESSION['site_id'];
		$sites = $this->rget($map_r);
		$sites_str = '<select name="index_id" id="index_id"><option value="0" >全部</option>';
		foreach ($sites as $k => $v) {
		    $sites_str .= '<option value="'.$v['index_id'].'" >'.$v['web_name'].'</option>';
		}
		$sites_str .= '</select>';
		return $sites_str;
	}


	//删除
	public function rdel($tab,$map){
        return $this->db->delete($tab,$map);
	}
	//更新
	public function rupdate($map,$arr=array(),$base_type = 1){
        $obj_db = $this->tab_c($base_type);
        if ($map['sql'] == 1) {//array('-',money);专用
            foreach ($arr as $key => $val) {
	            $key = $this->parseKey($key);
	            if (is_array($val)) {
	               //为数组
	               $sets[] = "{$key}".'='."{$key}{$val[0]}{$val[1]}";
	            }else{
	               $sets[] = "{$key}='{$val}'";
	            }
	        }
	        $setstr = join(",", $sets);
	        $sql = 'update '.$map['table'].' set '.$setstr.' where '.$map['where'];
	        $obj_db->query($sql);
        }else{
            $obj_db = $this->w_condition($obj,$map);
            return $obj_db->update($map['table'],$arr);
        }

	}

	protected function parseKey(&$key) {
        $key   =  trim($key);
        if(!is_numeric($key) && !preg_match('/[,\'\"\*\(\)`.\s]/',$key)) {
            $key = '`'.$key.'`';
        }
        return $key;
    }

	//批量更新 专用
	public function mupdate($arr,$map,$base_type = 1){
		$obj_db = $this->tab_c($base_type);
	    $strs = implode(',', array_keys($arr));
		$sql = "UPDATE ".$map['table']." SET ".$map['key']." = CASE ".$map['map']." ";
		foreach ($arr as $key => $val) {
		    $sql .= sprintf("WHEN %d THEN %d ", $key, $val);
		}
		$sql .= "END WHERE ".$map['map']." IN ($strs)";
		$obj_db->query($sql);
		return $obj_db->affected_rows();
	}
	//添加
	public function radd($tab,$arr=array()){
		$this->db->insert($tab,$arr);
    	return $this->db->insert_id();
	}
	//mode统计总数
	public function mcount($map,$db_model,$join){
		if (!empty($join)) {
		    return $this->M($db_model)->join($join)->where($map)->count();
		}else{
			return $this->M($db_model)->where($map)->count();
		}

	}
		//查询单条
	public function rfind($map,$base_type = 1){
		$obj_db = $this->tab_c($base_type);
	    $obj_db = $this->w_condition($obj_db,$map);
		$data=$obj_db->from($map['table'])->get()->result_array();
		if (!empty($data)) {
			return $data[0];
		}
	}
	//查询列表
	public function rget($map,$base_type = 1){
        $obj_db = $this->tab_c($base_type);
	    $obj_db = $this->w_condition($obj_db,$map);
	    return $obj_db->from($map['table'])->get()->result_array();
	}

    //数据库切换
	public function tab_c($type){
		switch ($type) {
			case '1':
			    $obj_db = $this->db;
				break;
			case '2':
			    $obj_db = $this->video_db;
				break;
			case '3':
			    $obj_db = $this->public_db;
				break;
		}
		return $obj_db;
	}

	//条件拼接
	public function w_condition($obj,$map){
		$obj = empty($obj)?$this->db:$obj;
	    if(!empty($map['select'])){
	        $obj->select($map['select']);
	    }else{
	        $obj->select($map['table'].'.*');
	    }
	    if(!empty($map['join'])){
	        $obj->join($map['join']['table'],$map['join']['action']);
	    }
	    if(!empty($map['where'])){
	        $obj->where($map['where']);
	    }
	    if(!empty($map['limit'])){
	        $obj->limit($map['limit']);
	    }

	    if(!empty($map['where_in'])){
	        $this->db->where_in($map['where_in']['item'],$map['where_in']['data']);
	    }
	    if(!empty($map['where_not_in'])){
	        $obj->or_where_in($map['where_not_in']);
	    }
	    if(!empty($map['or_where_in'])){
	        $obj->or_where_in($map['or_where_in']);
	    }
	    if(!empty($map['or_where_not_in'])){
	        $obj->or_where_not_in($map['or_where_not_in']);
	    }
	     if(!empty($map['or_where'])){
	        $obj->or_where($map['or_where']);
	    }
	    if(!empty($map['like'])){
	        $obj->like($map['like']['title'],$map['like']['match'],$map['like']['after']);
	    }

	    if(!empty($map['not_like'])){
	        $obj->not_like($map['not_like']['title'],$map['not_like']['match'],$map['not_like']['after']);
	    }

	    if(!empty($map['or_like'])){
	        $obj->or_like($map['or_like']['title'],$map['or_like']['match'],$map['or_like']['after']);
	    }

	    if(!empty($map['or_not_like'])){
	        $obj->or_not_like($map['or_not_like']['title'],$map['or_not_like']['match'],$map['or_not_like']['after']);
	    }

	    if(!empty($map['order'])){
	        $obj->order_by($map['order']);
	    }

	    if(!empty($map['pagecount'])){
	        $obj->limit($map['pagecount'], $map['offset']);
	    }
	    if(!empty($map['limit_row'])){
	        $obj->limit($map['limit_row'],$map['limit_start']);
	    }
	    return $obj;
	}
	//统计总数
	public function rcount($map,$base_type = 1){
		$obj_db = $this->tab_c($base_type);
	    $obj_db = $this->w_condition($obj_db,$map);
	    return $obj_db->count_all_results($map['table']);
	}

    //操作日志
	public function Syslog($log){
		$arr = array();
		$arr['log_info'] = $log['log_info'].'(系统：'.getOS().')';
		$arr['log_ip'] = $this->get_ip();
		$arr['uname'] = $log['uname'];
		$arr['type'] = $log['type'];
		$arr['site_id'] = $_SESSION['site_id'];
    	$arr['login_name'] = $_SESSION['login_name'];
    	$arr['log_time'] = date('Y-m-d H:i:s');
    	$arr['uid'] = $_SESSION['adminid'];
		return $this->radd('sys_log',$arr);
	}

	/**
	 * 查询单个表
	 * @param  [array] $map 所有参数
	 * @param  [array] $map['select'] 查询字段名
	 * @param  [array] $map['where'] 查询where语句
	 * @param  [int] $map['pagecount'] 分页的每页总条数
	 * @param  [int] $map['offset'] limit的后参数
	 * @return [array]
	 */
	public function get_table_one($map){
	    $one = $this->get_table($map);
	    return $one[0];
	}

	/**
	 * 查询所有表
	 * @param  [array] $map 所有参数
	 * @param  [array] $map['select'] 查询字段名
	 * @param  [array] $map['where'] 查询where语句
	 * @param  [int] $map['pagecount'] 分页的每页总条数
	 * @param  [int] $map['offset'] limit的后参数
	 * @return [array]
	 */
	public function get_table($map=array()) {
	    $this->db->from($map['table']);

	    if(!empty($map['select'])){
	        $this->db->select($map['select']);
	    }else{
	        $this->db->select($map['table'].'.*');
	    }
	    if(!empty($map['join'])){
	        $this->db->join($map['join']['table'],$map['join']['action']);
	    }
	    if(!empty($map['where'])){
	        $this->db->where($map['where']);
	    }

	     if(!empty($map['where_in'])){
	        $this->db->where_in($map['where_in']['item'],$map['where_in']['data']);
	    }
	    if(!empty($map['where_not_in'])){
	        $this->db->or_where_in($map['where_not_in']);
	    }
	    if(!empty($map['or_where_in'])){
	        $this->db->or_where_in($map['or_where_in']);
	    }
	    if(!empty($map['or_where_not_in'])){
	        $this->db->or_where_not_in($map['or_where_not_in']);
	    }
	    if(!empty($map['like'])){
	        $this->db->like($map['like']['title'],$map['like']['match'],$map['like']['after']);
	    }

	    if(!empty($map['not_like'])){
	        $this->db->not_like($map['not_like']['title'],$map['not_like']['match'],$map['not_like']['after']);
	    }

	    if(!empty($map['or_like'])){
	        $this->db->or_like($map['or_like']['title'],$map['or_like']['match'],$map['or_like']['after']);
	    }

	    if(!empty($map['or_not_like'])){
	        $this->db->or_not_like($map['or_not_like']['title'],$map['or_not_like']['match'],$map['or_not_like']['after']);
	    }
	    if(!empty($map['order'])){
	        $this->db->order_by($map['order']);
	    }


	    if(!empty($map['pagecount'])){
	        $this->db->limit($map['pagecount'], $map['offset']);
	    }
	    if(!empty($map['limit_row'])){
	        $this->db->limit($map['limit_row'],$map['limit_start']);
	    }
	    $query = $this->db->get();

	    $rows = $query->result_array();
	    return $rows;
	}



	/**
	 *查询表获取条数
	 * @param  [array] $map 所有参数
	 * @param  [array] $map['where'] 查询where语句
	 * @return [array]
	 */
	public function get_table_count($map=array()){
	    if(!empty($map['where'])){
	        $this->db->where($map['where']);
	    }
	    if(!empty($map['where_in'])){
	        $this->db->where_in($map['where_in']['item'],$map['where_in']['data']);
	    }
	    // if (!empty($map['or_where'])) {
	    //     $this->db->or_where($map['or_where']);
	    // }
	    if(!empty($map['where_not_in'])){
	        $this->db->or_where_in($map['where_not_in']['item'],$map['where_not_in']['data']);
	    }
	    if(!empty($map['or_where_in'])){
	        $this->db->or_where_in($map['or_where_in']['item'],$map['or_where_in']['data']);
	    }
	    if(!empty($map['or_where_not_in'])){
	        $this->db->or_where_not_in($map['or_where_not_in']['item'],$map['or_where_not_in']['data']);
	    }
	    if(!empty($map['like'])){
	        $this->db->like($map['like']['title'],$map['like']['match'],$map['like']['after']);
	    }

	    if(!empty($map['not_like'])){
	        $this->db->not_like($map['not_like']['title'],$map['not_like']['match'],$map['not_like']['after']);
	    }

	    if(!empty($map['or_like'])){
	        $this->db->or_like($map['or_like']['title'],$map['or_like']['match'],$map['or_like']['after']);
	    }

	    if(!empty($map['or_not_like'])){
	        $this->db->or_not_like($map['or_not_like']['title'],$map['or_not_like']['match'],$map['or_not_like']['after']);
	    }

	    return  $this->db->count_all_results($map['table']);
	}

	/**
	 * 表添加
	 * @param  [array] $map 所有参数
	 * @param  [array] $map['data'] 添加的数据数组
	 * @return [array]
	 */
	public function create_table($map=array()){
	    $return = $this->db->insert($map['table'],$map['data']);
	    return $return;
	}

	/**
	 * 表修改
	 * @param  [array] $map 所有参数
	 * @param  [array] $map['where'] 查询where语句
	 * @return [array]
	 */
	public function update_table($map=array()){
	    if(!empty($map['where'])){
	        $this->db->where($map['where']);
	    }
	    if(!empty($map['where_in'])){
	        $this->db->where_in($map['where_in']['item'],$map['where_in']['data']);
	    }
	    $return = $this->db->update($map['table'],$map['data']);
	    return $return;
	}




	/**
	 * 表删除
	 * @param  [array] $map 所有参数
	 * @param  [array] $map['where'] 查询where语句
	 * @return [array]
	 */
	public function del_table($map=array()){
	    $this->db->where($map['where']);
	    $return = $this->db->del($map['table'],$map['data']);
	    return $return;
	}

		//获取IP
	public function get_ip(){
	    $realip = '';
	    $unknown = 'unknown';
	    if (isset($_SERVER)){
	        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)){
	            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	            foreach($arr as $ip){
	                $ip = trim($ip);
	                if ($ip != 'unknown'){
	                    $realip = $ip;
	                    break;
	                }
	            }
	        }else if(isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)){
	            $realip = $_SERVER['HTTP_CLIENT_IP'];
	        }else if(isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)){
	            $realip = $_SERVER['REMOTE_ADDR'];
	        }else{
	            $realip = $unknown;
	        }
	    }else{
	        if(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)){
	            $realip = getenv("HTTP_X_FORWARDED_FOR");
	        }else if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)){
	            $realip = getenv("HTTP_CLIENT_IP");
	        }else if(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)){
	            $realip = getenv("REMOTE_ADDR");
	        }else{
	            $realip = $unknown;
	        }
	    }
	    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
	    return $realip;
	}

	//获取地址
	public function get_address($loginIp){
		include_once(dirname(__FILE__)."/ip.php");
        return convertip($loginIp);
	}
}
