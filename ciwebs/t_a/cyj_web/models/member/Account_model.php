<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Account_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->load->model('Common_model');
		$this->init_db();
	}
	//获取最近十笔交易记录
	public function get_cash_limit(){
		$this->db->from('k_user_cash_record');
		$this->db->where('site_id',SITEID);
		$this->db->where('uid',$_SESSION['uid']);
		$this->db->order_by('id','desc');
		$this->db->limit('10');
		$cash_data = $this->db->get()->result_array();
		if($cash_data){
			foreach ($cash_data as $key => $value) {
				$cash_data[$key]['cash_type'] =  $this->Common_model->cash_type_r($value['cash_type']);
				$cash_data[$key]['cash_do_type'] =  $this->Common_model->cash_do_type_r($value['cash_do_type']);
				$cash_data[$key]['remark'] =  $this->Common_model->str_cut($value['remark']);
				$cash_data[$key]['cash_num'] = number_format($value['discount_num']+$value['cash_num'],2);
				$cash_data[$key]['cash_balance'] = number_format($value['cash_balance'],2);

			}
		}
		return $cash_data;
	}


	//体育退水限额
	public function get_sp_advisory($spArr){
		$spType = array();
		foreach ($spArr as $key => $value) {
			$tmpA = $tmpB = array();
			$this->db->from('sp_games_view');
			$this->db->where('type',$value);
			$tmp1 = $this->db->get()->result_array();

			$this->db->from('k_user_agent_sport_set');
			$this->db->where('aid',$_SESSION['agent_id']);
			$tmp2 = $this->db->get()->result_array();

			foreach($tmp1 as $k1=>$v1){
				foreach($tmp2 as $k2=>$v2){
					if($v1['id'] == $v2['type_id']){
						$tmpA[$v1['t_type']] = array_merge($v1,$v2);
					}
				}
			}

			$this->db->from('k_user_sport_set');
			$this->db->where('uid',$_SESSION['uid']);
			$tmp3 = $this->db->get()->result_array();

			foreach($tmp1 as $k1=>$v1){
				foreach($tmp3 as $k3=>$v3){
					if($v1['id'] == $v3['type_id']){
						$tmpB[$v1['t_type']] = array_merge($v1,$v3);
					}
				}
			}
			$spType[$value] = array_merge($tmpA,$tmpB);
		}
		return $spType;
	}

	//彩票退水限额
	public function get_lottery_advisory($fcArr){
		$fcType = array();
		foreach ($fcArr as $key => $value) {
			$tmpA = $tmpB = array();
			$this->db->from('fc_games_view');
			$this->db->where('fc_type',$value);
			$tmp1 = $this->db->get()->result_array();

			$this->db->from('k_user_agent_fc_set');
			$this->db->where('aid',$_SESSION['agent_id']);
			$tmp2 = $this->db->get()->result_array();

			foreach($tmp1 as $k1=>$v1){
				foreach($tmp2 as $k2=>$v2){
					if($v1['id'] == $v2['type_id']){
						$tmpA[$v1['type']] = array_merge($v1,$v2);
					}
				}
			}

			$this->db->from('k_user_fc_set');
			$this->db->where('uid',$_SESSION['uid']);
			$tmp3 = $this->db->get()->result_array();

			foreach($tmp1 as $k1=>$v1){
				foreach($tmp3 as $k3=>$v3){
					if($v1['id'] == $v3['type_id']){
						$tmpB[$v1['type']] = array_merge($v1,$v3);
					}
				}
			}
			$fcType[$value] = array_merge($tmpA,$tmpB);
		}
		return $fcType;
	}

	//修改密码
	public function edit_password($set){
		$map = array();
		$map['table'] = 'k_user';
		$map['where']['uid'] = $_SESSION['uid'];
		$map['where']['site_id'] = SITEID;
		$map['where']['index_id'] = INDEX_ID;
		$result = $this->Account_model->rupdate($map,$set);
		return $result;
	}

	//是否开启返水
	public function is_self_user(){
		$this->db->from('k_user_level');
		$this->db->where('site_id',SITEID);
		$this->db->where('index_id',INDEX_ID);
		$this->db->where('id',$_SESSION['level_id']);
		$this->db->select('is_self_fd');
		return $this->db->get()->row_array();
	}

	public function get_video_username($pwdtype){
		$map = array();
		$this->video_db->from($pwdtype.'_user');
		$this->video_db->where('site_id',SITEID);
		$this->video_db->where('index_id',INDEX_ID);
		$this->video_db->where('username',$_SESSION['username']);
		$map = $this->video_db->get()->row_array();
		return $map;
	}
}