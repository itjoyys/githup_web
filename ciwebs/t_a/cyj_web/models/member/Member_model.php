<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Member_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->init_db();
	}

	function get_egames($per,$offset,$map) {
		if (!empty($map)) {
			$this->public_db->where($map);
		}
		$this->public_db->order_by('id ASC');
		return $this->public_db->get('mg_game',$per,$offset)->result_array();
	}

	//获取用户等级
	function get_user_level_id($uid){
		$this->private_db->from('k_user');
		$this->private_db->select('level_id');
		$this->private_db->where("uid = '$uid'");
		$query = $this->private_db->get();
		return $query->result_array();
	}

	//会员中心连接
	public function get_member_url($url){
		switch ($url) {
			case '1':
				$str = '../../member/cash/setmoney';
				break;
			case '2':
				$str = '../../member/cash/getmoney';
				break;
			case '3':
				$str = '../../member/cash/zr_money';
				break;
			case '4':
				$str = '../../member/account/userinfo';
				break;
			case '5':
				$str = "../../member/record/tc_record";
				break;
			case '6':
				$str = "../../member/record/bb_count";
				break;
			case '7':
				$str = "../../member/news/latest_news";
				break;
			case '8':
				$str = "../../member/news/sms";
				break;
			case '9':
				$str = "../../member/news/sports_news";
				break;
		}
		return $str;
	}
	
}