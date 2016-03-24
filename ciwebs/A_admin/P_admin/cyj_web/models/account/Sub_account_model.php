<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Sub_account_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}


	public function get_sub_count($map,$limit){
		$db_model['tab'] = 'sys_admin';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;//读取从库
		return $this->M($db_model)->where($map)->order("uid desc")->limit($limit)->select();;
	}
	public function getaccountinfo($uid){
		$query=$this->private_db->query('select * from sys_admin where uid = '.$uid);
		$d=$query->row_array();
		if($d['ssid']){
			session_id($d['ssid']);
     		session_regenerate_id(true);
     		session_write_close();
			$redis = new Redis();
			$redis->connect(REDIS_HOST,REDIS_PORT);
			$redis_akey = 'alg'.$d['site_id'].$d['ssid'];
	        $redis->del($redis_akey);
		}
	}
}