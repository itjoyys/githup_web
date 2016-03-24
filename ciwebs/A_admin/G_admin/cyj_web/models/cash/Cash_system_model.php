<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cash_system_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//查询现金记录
	public function get_all_system($map,$limit){
		$db_model['tab'] = 'k_user_cash_record';
		$db_model['type'] = 1;
		return $this->M($db_model)->where($map)->order('id desc')->limit($limit)->select();
	}

	//总计
	public function get_all_count($map){
        $db_model['tab'] = 'k_user_cash_record';
		$db_model['type'] = 1;
		return $this->M($db_model)->field("count(k_user_cash_record.id) as allnum,sum(k_user_cash_record.cash_num) as Cnum,sum(k_user_cash_record.discount_num) as Dnum")->where($map)->find();
	}

	  function get_agents($index_id,$site_id,$agentsstr,$is_quanxian){
             // 查询代理
        $map = array();
         if (!empty($index_id)) {
            $map['index_id'] = $index_id;
        }else{
            $map['index_id'] = $_SESSION['index_id'];
        }
         if ($is_quanxian == 1){
           $map['id'] = array('in','('.$agentsstr.')');
        }elseif($is_quanxian == 0){
           $map['id'] = $_SESSION['agent_id'];
        }
        $map['site_id'] = $site_id;
        $map['is_demo'] = 0;
        $db_model = array();
        $db_model['tab'] = 'k_user_agent';
        $db_model['type'] = 1;
        return $this->M($db_model)->field("id,agent_user,agent_name,agent_login_user")->where($map)->select();
    }

}