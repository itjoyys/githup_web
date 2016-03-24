<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cash_count_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//公司入款 线上入款
	public function income_line($map){
		$db_model['tab'] = 'k_user_bank_in_record';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;
		return $this->M($db_model)->field("/* parallel */ sum(deposit_num) as deposit_num,count(id) as countNum,count(distinct uid) as userNum")->where($map)->find();
	}
    //人工入款
	public function income_catm($map){
		$db_model['tab'] = 'k_user_catm';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;
		return $this->M($db_model)->field("/* parallel */ count(id) as catmNum,sum(catm_money) as catm_money,count(distinct uid) as userNum")->where($map)->find();
	}
    //会员出款被扣金额
	public function take_off($map){
		$db_model['tab'] = 'k_user_bank_out_record';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;
		return $this->M($db_model)->field("/* parallel */ count(id) AS outNum,sum(charge) AS charge,sum(favourable_num) AS favourable_num,sum(expenese_num) AS expenese_num,count(DISTINCT uid) AS userNum")->where($map)->find();
	}


    //会员出款
    public function user_out_money($map){
        $db_model['tab'] = 'k_user_bank_out_record';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;
		return $this->M($db_model)->field("count(id) as outNum,sum(outward_money) as outward_money,count(distinct uid) as userNum")->where($map)->find();
    }

	public function discount_count($map){
		$db_model['tab'] = 'k_user_discount_search';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;
		return $this->M($db_model)->field("sum(people_num - no_people_num) as people_num,sum(money) as money")->where($map)->find();
	}

	function discount_user($map){
		$db_model['tab'] = 'k_user_discount_search';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;
		return $this->M($db_model)->field("id")->where($map)->select();
	}

    //返水人数
	function discount_count_user($map){
		$db_model['tab'] = 'k_user_discount_count';
		$db_model['type'] = 1;
		return $this->M($db_model)->field("count(distinct uid) as userNum,username,sum(total_e_fd) as money,count(id) as people_num")->where($map)->find();
	}
    //给予优惠
	public function discount_fav($map){
        $db_model['tab'] = 'k_user_cash_record';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;
		return $this->M($db_model)->field("sum(discount_num) as fav_money,count(id) as fav_num,count(distinct uid) as userNum")->where($map)->find();
	}

	//给予优惠-详情
	public function discount_list($map){
        $db_model['tab'] = 'k_user_cash_record';
		$db_model['type'] = 1;
		//$db_model['is_port'] = 1;
		return $this->M($db_model)->field("id,uid,username,site_id,agent_id,sum(discount_num) as pm_money")->where($map)->group('uid')->select();
	}

}