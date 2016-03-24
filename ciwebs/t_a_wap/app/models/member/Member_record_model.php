<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Member_record_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->init_db();
	}
	
	//获取体育投注记录	
	function get_record_b($sql,$map){
		$nsql = 'select * from '.$sql.' where '.$map['where'].$map['order'].' limit '.$map['limit'].','.$map['limit2'];
		//echo $nsql;exit;
		$query = $this->private_db->query($nsql);
		return $query->result_array();
	}
	
	//获取体育投注记录总数
	function get_record_b_count($sql,$map){
		$nsql = $sql.' where '.$map['where'];
		return $this->private_db->count_all_results($nsql);
	}
	
	//获取体育串关记录
	function get_record_cg($map){
		$this->private_db->from('k_bet_cg');
		$this->private_db->where($map['where']);
		$this->private_db->order_by($map['order']);
		$query = $this->private_db->get();
		//echo $this->private_db->last_query();
		return $query->result_array();
	}
	
	//获取彩票投注记录
	function get_record_cp($array,$map){
		$this->private_db->from('c_bet');
		$this->private_db->select('c_bet.*,k_user.agent_id');
		$this->private_db->where($array);
		$this->private_db->order_by($map['order']);
		$this->private_db->limit($map['limit2'],$map['limit']);
		$this->private_db->join('k_user', 'c_bet.uid = k_user.uid', 'left');
		$query = $this->private_db->get();
		//echo $this->private_db->last_query();
		return $query->result_array();
	}
	
	//获取彩票投注记录
	function get_record_cp_count($array){
		$this->private_db->from('c_bet');
		$this->private_db->where($array);
		$this->private_db->join('k_user', 'c_bet.uid = k_user.uid', 'left');
		$query = $this->private_db->count_all_results();
		//echo $this->private_db->last_query();
		return $query;
	}
	
	//获取交易记录、往来记录总条数
	function get_correspondence_count($map){
		$this->private_db->from('k_user_cash_record');
		$this->private_db->where($map['where']);
		$this->private_db->join('k_user', 'k_user.uid = k_user_cash_record.uid');
		$this->private_db->order_by('k_user_cash_record.id desc');
		$query = $this->private_db->count_all_results();
		//echo $this->private_db->last_query();
		return $query;
	}
	
	//获取交易记录、往来记录
	function get_correspondence_record($map){
		$this->private_db->from('k_user_cash_record');
		$this->private_db->where($map['where']);
		$this->private_db->join('k_user', 'k_user.uid = k_user_cash_record.uid');
		$this->private_db->order_by('k_user_cash_record.id desc');
		$this->private_db->limit($map['limit2'],$map['limit']);
		$query = $this->private_db->get();
		//echo $this->private_db->last_query();
		return $query->result_array();
	}
	
	//获取交易记录、往来记录总计
	function get_correspondence_totl($map){
		$this->private_db->from('k_user_cash_record');
		$this->private_db->where($map['where']);
		$this->private_db->select_sum('discount_num');
		$this->private_db->select_sum('cash_num');
		$query = $this->private_db->get();
		//echo $this->private_db->last_query();
		return $query->result_array();
	}
	
	//获取报表统计
	function get_bb_count_sum($map){
		$this->private_db->from($map['table']);
		$this->private_db->where($map['where']);
		
		if(!empty($map['sum'])){
			foreach ($map['sum'] as $k=>$v){
				$this->private_db->select_sum($v);
			}
		}
		if(!empty($map['where_in'])) $this->private_db->where_in($map['where_in']['type'],$map['where_in']['val']);
		$query = $this->private_db->get();
		//echo $this->private_db->last_query();
		return $query->row_array();
	}
	
	//获取报表统计总条数
	public function get_bb_count_co($map){
		$this->private_db->from($map['table']);
		$this->private_db->where($map['where']);
		$query = $this->private_db->count_all_results();
		return $query;
	}

	public function get_all_one($vtype) {    //视讯电子一个种类的信息
		$this->db->from('k_video_games');     
		$this->db->order_by("id ASC");
    $this->db->where('vtype',$vtype);
    $this->db->where('gtype',0);   //视讯
	  return $this->db->get()->result_array();
	}
	
	
}