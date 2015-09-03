<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_flash_model extends MY_Model {

	function __construct() {
		//$this->init_db();
	}
	public function get_state_flash($state,$field='*') {
		$this->db->select($field);
		$this->db->from('info_flash_edit_view');
		$this->db->where("state",$state);
		$this->db->where("site_id",$_SESSION['site_id']);
		$data=$this->db->get()->result_array();
		return $data;
	}

    public function upflash($arr,$id){
    	$this->db->where('id',$id);
		return $this->db->update('info_flash_edit',$arr);

    }
    public function flash_case($arr,$field,$id){
		$arr_e['case_state'] = 1;
		$this->db->trans_strict(FALSE);
        $this->db->trans_begin();
		$this->db->where($field,$id);
		$this->db->update('info_flash_edit',$arr_e);
		$this->db->insert('info_case',$arr);
		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
	    }
	}
}