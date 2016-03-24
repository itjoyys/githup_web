<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blance extends MY_Controller {
    public function __construct() {
		parent::__construct();
		$this->login_check();
		$this->load->model('cash/Blance_model');
	}

	public function index(){
		$agent_id = $this->input->get('agent_id');//代理ID
        $map = array();
        $site_id = $_SESSION['site_id'];
        $is_quanxian = $_SESSION['guanliyuan'];
        $agentsstr = $_SESSION['agent_ids'];
        $agent_up = $this->Blance_model->get_agents($index_id,$site_id,$agentsstr,$is_quanxian);
        //p($agent_up);die;
        $this->add('is_guanliyuan', $is_quanxian);
        $this->add('agent_up', $agent_up);
        $map['site_id'] = $_SESSION['site_id'];
        //代理
		if ($is_quanxian == 1){
			if (!empty($agent_id)) {
			    $map['agent_id'] = $agent_id;
			}else{
				$agentstr = $_SESSION['agent_ids'];
				$map['agent_id'] = array('in','('.$agentsstr.')');
			}
		}else{
			$map['agent_id'] = $_SESSION['agent_id'];
		}
        $map['index_id'] = $_SESSION["index_id"];

        $map['is_delete'] = 0;
        $map['shiwan'] = 0;

        $blance_on = $this->Blance_model->get_user_blance_on($map);

        $map['is_delete'] = 1;
        $blance_off = $this->Blance_model->get_user_blance_on($map);

		$sum = $blance_on['money'] + $blance_on['ag'] + $blance_on['og']
		      +$blance_on['mg']+$blance_on['bbin']+$blance_on['ct']
		      +$blance_on['lebo'];

		$sums = $blance_off['money'] + $blance_off['ag'] + $blance_off['og']
		        +$blance_off['mg'] + $blance_off['bbin'] + $blance_off['ct']
		        +$blance_off['lebo'];

        $map_b = array();
        $map_b['table'] = 'k_user_agent';
        $map_b['where']['site_id'] = $_SESSION['site_id'];
        $map_b['where']['agent_type'] = 'a_t';
        $map_b['where']['is_delete'] = 0;
        $map_b['where']['is_demo'] = 0;

        $this->add('agent',$this->Blance_model->rget($map_b));
		$this->add('blance_on',$blance_on);
		$this->add('index_id',$index_id);
		$this->add('agent_id',$agent_id);
		$this->add('blance_off',$blance_off);
		$this->add('u_date',date('Y-m-d H:i:s'));
		$this->add('sum',$sum);
		$this->add('sums',$sums);
		$this->display('cash/blance.html');
	}


}
