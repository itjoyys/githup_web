<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Log_manage_model extends MY_Model {

	function __construct() {
	    parent::__construct();
	}
    //获取会员登录
	public function get_member_login($map,$limit){
		 $db_model['tab'] = 'history_login';
		 $db_model['type'] = 1;
         $db_model['is_port'] = 1;//读取从库
         return $this->M($db_model)->where($map)->order("id DESC")->limit($limit)-> select();
	}
	//获取会员登录
	public function get_admin_login($map,$limit){
		 $db_model['tab'] = 'sys_admin_login';
		 $db_model['type'] = 1;
		 $db_model['is_port'] = 1;//读取从库
         return $this->M($db_model)->where($map)->order("id DESC")->limit($limit)-> select();
	}

	//获取操作日志
	public function get_admin_do($map,$limit){
	     $db_model['tab'] = 'sys_log';
		 $db_model['type'] = 1;
		 $db_model['is_port'] = 1;//读取从库
         return $this->M($db_model)->where($map)->limit($limit)->order('log_id DESC')->select();
	}

}