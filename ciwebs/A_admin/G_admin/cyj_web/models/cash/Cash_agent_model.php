<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cash_agent_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

		//退佣查询
  	public function agent_serch_list($where){
      $db_model = array();
      $db_model['tab'] = 'k_user_agent_record';
      $db_model['type'] = 1;

      return $this->M($db_model)->where($where)->order('id desc')->select();
  	}


    //期数查询
    public function agent_serch_qishu($where){
      $db_model = array();
      $db_model['tab'] = 'k_qishu';
      $db_model['type'] = 1;
      return $this->M($db_model)->where($where)->order('id desc')->select();
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