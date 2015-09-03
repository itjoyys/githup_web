<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_activity_model extends MY_Model {

	function __construct() {
		//$this->init_db();
	}
	public function get_activity($pid) {
		$data=$this->db->from('info_activity_edit_view')->where("state > 0 and pid = '".$pid."'")->order_by('sort desc')->get()->result_array();
		return $data;
	}
	public function get_state_activity($state,$field='*') {
		$this->db->select($field);
		$this->db->from('info_activity_edit_view');
		$this->db->where("state",$state);
		$this->db->where("site_id",$_SESSION['site_id']);
		$data=$this->db->get()->result_array();
		return $data;
	}

    public function upactivity($arr,$id){
    	$this->db->trans_strict(FALSE);
    	$this->db->trans_begin();
    	$this->db->insert('info_material',$arr);
    	$arr_e['img'] = $arr['img_url'];
    	$this->db->where('id',$id);
		$this->db->update('info_activity_edit',$arr_e);
		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
	    }
    }
    public function activity_case($arr){
		$arr_e['case_state'] = 1;
		$this->db->trans_strict(FALSE);
        $this->db->trans_begin();
		$this->db->where('site_id',$_SESSION['site_id']);
		$this->db->update('info_activity_edit',$arr_e);
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
