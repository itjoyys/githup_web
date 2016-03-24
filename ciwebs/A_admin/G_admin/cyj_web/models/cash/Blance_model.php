<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Blance_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//会员启用余额
	public function get_user_blance_on($map){
      $db_model['tab'] = 'k_user';
		  $db_model['type'] = 1;

	    return $this->M($db_model)->field("sum(money) as money,sum(og_money) as og,sum(mg_money) as mg,sum(ag_money) as ag,sum(bbin_money) as bbin,sum(ct_money) as ct,sum(lebo_money) as lebo")->where($map)->find();
	}

  //会员停用金额
  public function get_user_blance($map){
      $db_model['tab'] = 'k_user';
      $db_model['type'] = 1;

      return $this->M($db_model)->field("sum(money) as money,sum(og_money) as og,sum(mg_money) as mg,sum(ag_money) as ag,sum(bbin_money) as bbin,sum(ct_money) as ct,sum(lebo_money) as lebo")->where($map)->find();
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