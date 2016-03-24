<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Site_info_model extends MY_Model {

	function __construct() {
        parent::__construct();
		//$this->init_db();
	}
    public function get_website(){
        return $this->db->where("site_id = '".$_SESSION['site_id']."'")->from('web_config')->get()->result_array();
    }
}