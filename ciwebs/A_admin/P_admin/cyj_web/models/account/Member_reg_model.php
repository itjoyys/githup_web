<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Member_reg_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//会员注册信息
	public function get_member_reg($map){
		$this->db->where($map);
		$rows = $this->db->get('k_user_reg_config')->result_array();
		return $rows[0];
	}



}