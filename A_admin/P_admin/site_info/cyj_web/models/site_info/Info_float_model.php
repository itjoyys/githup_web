<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_float_model extends MY_Model {

	function __construct() {
		
	}
	public function get_float_list($id){
		$data=$this->db->where('fid',$id)->from('info_float_list_view')->order_by('sort desc')->get()->result_array();
		return $data;
	}
	public function get_state_float($state,$field='*') {
		$this->db->select($field);
		$this->db->from('info_float_edit_view');
		$this->db->where("state",$state);
		$this->db->where("site_id",$_SESSION['site_id']);
		$data=$this->db->get()->result_array();
		return $data;
	}
    public function upfloat($arr,$id){
    	$this->db->trans_strict(FALSE);
    	$this->db->trans_begin();
    	$this->db->insert('info_material',$arr);
    	$arr_e['img'] = $arr['img_url'];
    	$this->db->where('id',$id);
		$this->db->update('info_float_edit',$arr_e);
		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
	    }
    }
    public function float_case($arr,$fid){
		$arr_e['case_state'] = 1;
		$this->db->trans_strict(FALSE);
        $this->db->trans_begin();
		$this->db->where('id',$fid);
		$this->db->update('info_float_c_edit',$arr_e);
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