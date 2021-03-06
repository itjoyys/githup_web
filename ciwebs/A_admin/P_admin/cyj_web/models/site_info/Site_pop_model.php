<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Site_pop_model extends MY_Model {

	function __construct() {
        parent::__construct();
	}

    public function get_pop($per,$offset,$index_id) {
	    $this->db->where("site_id",$_SESSION['site_id']);
	    $this->db->where("index_id",$index_id);
	    $this->db->where("is_delete >",'0');
		$this->db->order_by('id desc');
		return $this->db->get('site_ad',$per,$offset)->result_array();
	}

	public function get_pop_count($index_id){
        $this->db->where("site_id",$_SESSION['site_id']);
        $this->db->where("index_id",$index_id);
	    $this->db->where("is_delete >",'0');
		return $this->db->count_all_results('site_ad');
	}
}