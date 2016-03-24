<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Agent_reg_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//代理申请数量
	public function get_count($map){
		$db_model['tab'] = 'k_user_agent';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;//读取从库
		return $this->M($db_model)->field("*")->where($map)->count();
	}

	public function get_agent($map,$limit){
		$db_model['tab'] = 'k_user_agent';
		$db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
		return $this->M($db_model)->where($map)->limit($limit)->order("id DESC,is_delete DESC")->select();
	}

	public function get_page($totalPage,$page){
		$db_model['tab'] = 'k_user_agent';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;//读取从库
		return $this->M($db_model)->showPage($totalPage,$page);
	}



}