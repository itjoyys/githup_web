<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
//我要推广
class Spread_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->init_db();
	}
	//获取推广数据
	public function get_spread_data(){
      $this->db->from('k_user');
      $this->db->where('uid',$_SESSION['uid']);
      $this->db->where('site_id',SITEID);
      $this->db->where('index_id',INDEX_ID);
      $this->db->select("username,spread_num,spread_money");
      return $this->db->get()->row_array();
	}

    //获取视讯配置
  public function video_config(){
      $this->db->from('web_config');
    	$this->db->where('site_id',SITEID);
    	$this->db->where('index_id',INDEX_ID);
    	$this->db->select('video_module');
    	$result = $this->db->get()->row_array();
    	if ($result['video_module']) {
    	    return explode(',',$result['video_module']);
    	}
  }

    //前期累计返水数据
  public function get_spread_dis(){
      $this->db->from('k_user_discount_spread');
      $this->db->where('index_id',INDEX_ID);
      $this->db->where('site_id',SITEID);
      $this->db->where('state',1);
      $this->db->order_by('count_bet asc');
      $this->db->select();
      return $this->db->get()->result_array();
  }

  //获取排行数据
  public function get_spread_top($type){
      $this->db->from('k_user');
      $this->db->where('index_id',INDEX_ID);
      $this->db->where('site_id',SITEID);
      $this->db->where('shiwan',0);
      if ($type == 1) {
          $this->db->order_by('spread_num desc');
      }else{
          $this->db->order_by('spread_money desc');
      }

      $this->db->limit(10);
      $this->db->select('username,spread_num,spread_money');
      return $this->db->get()->result_array();
  }
}
