<?php
/**
 *
 */
class MY_Model extends CI_Model {

	protected $public_db = null;
	protected $private_db = null;
	protected $video_db = null;

	public function __construct() {
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

		// if ($this->manage_db == null) {
		// 	$this->manage_db = $this->load->database("manage", TRUE, TRUE);
		// }
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


		//更新在线
	public function redis_update_user(){
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$redis_key = 'ulg'.CLUSTER_ID.'_'.SITEID.$_SESSION['uid'];
		//只有正式账号写入在线
		if (empty($_SESSION['shiwan'])) {
		    $redis->setex($redis_key,'1200','1');
		}

	}

	//退出
	public function redis_del_user(){
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$redis_key = 'ulg'.CLUSTER_ID.'_'.SITEID.$_SESSION['uid'];
	    $redis->del($redis_key);
	}


	//删除
	public function rdel($tab,$map){
		$obj_db = $this->tab_c($base_type);
        return $obj_db->delete($tab);
	}
	//更新
	public function rupdate($map,$arr=array(),$base_type = 1){
        $obj_db = $this->tab_c($base_type);
	    $obj_db = $this->w_condition($obj,$map);
        return $obj_db->update($map['table'],$arr);
	}
	//添加
	public function radd($tab,$arr=array()){
		$this->db->insert($tab,$arr);
    	return $this->db->insert_id();
	}
	//查询单条
	public function rfind($map,$base_type = 1){
		$obj_db = $this->tab_c($base_type);
	    $obj_db = $this->w_condition($obj,$map);
		$data=$obj_db->from($map['table'])->get()->result_array();
		if (!empty($data)) {
			return $data[0];
		}
	}
	//查询列表
	public function rget($map,$base_type = 1){
        $obj_db = $this->tab_c($base_type);
	    $obj_db = $this->w_condition($obj,$map);
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

	    if(!empty($map['where_in'])){
	        $obj->where_in($map['where_in']);
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
	    if(!empty($map['limit'])){
	        $obj->limit($map['limit']);
	    }
	    return $obj;
	}
	//统计总数
	public function rcount($tab,$map,$base_type){
		if (empty($tab)) {
			return 'system error 0000';
		}
		switch ($base_type) {
			case '1':
			    if (!empty($map)) {
			    	$this->db->where($map);
			    }
				return $this->db->count_all_results($tab);
				break;
			case '2':
			    if (!empty($map)) {
			    	$this->video_db->where($map);
			     }
				return $this->video_db->count_all_results($tab);
				break;
			case '3':
			     if (!empty($map)) {
			     	 $this->public_db->where($map);
			     }
				 return $this->public_db->count_all_results($tab);
				break;
		}

	}

    //操作日志
	// public function Syslog($log){
	// 	$arr = array();
	// 	$arr['log_info'] = $log['log_info'].'(系统：'.getOS().')';
	// 	$arr['log_ip'] = $log['log_ip'];
	// 	$arr['site_id'] = $_SESSION['site_id'];
 //    	$arr['login_name'] = $_SESSION['login_name'];
 //    	$arr['log_time'] = date('Y-m-d H:i:s');
 //    	$arr['uid'] = $_SESSION['adminid'];
	// 	return $this->radd('sys_log',$arr);
	// }

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
	        $this->db->where_in($map['where_in']);
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
	    if(!empty($map['group'])){
	        $this->db->group_by($map['group']);
	    }


	    if(!empty($map['pagecount'])){
	        $this->db->limit($map['pagecount'], $map['offset']);
	    }
	    if(!empty($map['limit_row'])){
	        $this->db->limit($map['limit_row'],$map['limit_start']);
	    }
	    if(!empty($map['limit'])){
	        $this->db->limit($map['limit']);
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

	//登录判断
	public function login_check($uid){
		if(!isset($uid))
		{
			echo "<script>alert(\"请先登录再进行操作\");top.location.href='/';</script>";
			exit();
		}else{
		    $this->db->from('k_user_login');
		    $this->db->where('uid',$uid);
		    $this->db->where('is_login',1);
		    $ulog = $this->db->get()->row_array();
		    //判断会员是否已经禁用
		    $this->db->from('k_user');
		    $this->db->where('uid',$uid);
		    $this->db->select('is_delete');
		    $isUse = $this->db->get()->row_array();
		    if ($isUse['is_delete'] == '1') {
		    	echo "<script>alert('对不起，您的账号异常已被停止，请与在线客服联系！');</script>";
				echo "<script>top.location.href='/';</script>";
				exit;
		    }elseif($isUse['is_delete'] == '2'){
		    	echo "<script>alert('对不起，您的账号异常已被暂停使用，请与在线客服联系！');</script>";
				echo "<script>top.location.href='/';</script>";
				exit;
		    }

		    //屏蔽试玩账号检测
		    if ($_SESSION['shiwan'] == '1') {
		    	$ulog['uid'] = $_SESSION['uid'];
		    }

			if($ulog['uid'] > 0){
				if($ulog['ssid'] != $_SESSION["ssid"])
				{
					//别处登陆
					echo "<script  charset=\"utf-8\" language=\"javascript\" type=\"text/javascript\">alert(\"请重新登陆账号\");</script>";
				    session_destroy();
				    echo "<script>top.location.href='/';</script>";
					exit();
				}else{
					//更新在线时间
					if (!$_SESSION['shiwan']) {
				    	$this->redis_update_user();
				    }
				}

			}else{
				session_destroy();
				echo "<script>top.location.href='/';</script>";
			}
		}

	}
}
