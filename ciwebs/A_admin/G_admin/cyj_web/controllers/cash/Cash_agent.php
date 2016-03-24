<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Cash_agent extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Cash_agent_model');
	}

	public function index(){
		$this->display('cash/cash_agent/index.html');

	}

	//退佣查询
	public function agent_serch_list(){
		//查询条件
		$agent_id = $this->input->get('agent_id');//代理ID
        $map = array();
        $site_id = $_SESSION['site_id'];
        $is_quanxian = $_SESSION['guanliyuan'];
        $agentsstr = $_SESSION['agent_ids'];
        $agent_up = $this->Cash_agent_model->get_agents($index_id,$site_id,$agentsstr,$is_quanxian);
        $this->add('is_guanliyuan', $is_quanxian);
        $this->add('agent_up', $agent_up);
		$qs = $this->input->get('qs');//期数id选择
		$where = "1=1 ";
		$where .= " and qishu_id =".$qs;
		 //代理
		if ($is_quanxian == 1){
			if (!empty($agent_id)) {
				$where .= " and agent_id =".$agent_id;
			}else{
				$agentstr = $_SESSION['agent_ids'];
			  	 $where .= " and agent_id in".($agentstr);
			}
		}else{
			$where .= " and agent_id =".$_SESSION['agent_id'];
		}
		if ($qs){
   			$agentRecord = $this->Cash_agent_model->agent_serch_list($where);
   			foreach($agentRecord as $key=>$val){
   					$agentRecord[$key]['status']=$this->status_type($val['status']);
   			}
		}
		$map = array();
    	$map['table'] = 'k_qishu';
    	$map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['is_delete'] = 0;
		$map['order'] = "id DESC";
		$qishu=$this->Cash_agent_model->rget($map);
		$this->add("qishu",$qishu);
		$this->add("qs",$qs);
		$this->add("agentRecord",$agentRecord);
		$this->display('cash/cash_agent/agent_serch_list.html');
	}

	//代理状态
	function status_type($type){
		switch ($type) {
			case 0:
				return '未處理';
				break;
			case 1:
				return '取消';
				break;
			case 2:
				return '退傭';
				break;
			case 3:
				return '掛賬';
				break;
			case 4:
				return '未達門檻';
				break;
			case 5:
				return '未達佣金資格';
				break;
			case 6:
				return '已達門檻';
				break;
		}
	}

}
