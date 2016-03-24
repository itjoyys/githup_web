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

	//获取最近十笔交易记录
	public function get_cash_limit($start,limit){
		$this->db->from('k_user_cash_record');
		$this->db->where('site_id',SITEID);
		$this->db->where('uid',$_SESSION['uid']);
		$this->db->where('cash_type',1);
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

	
}