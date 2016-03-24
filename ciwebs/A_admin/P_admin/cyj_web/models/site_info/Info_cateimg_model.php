<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_cateimg_model extends MY_Model {

	function __construct() {

	}

	public function get_state_cateimg($state,$field='*') {
		$this->db->select($field);
		$this->db->from('info_cateimg_edit_view');
		$this->db->where("state",$state);
		$this->db->where("site_id",$_SESSION['site_id']);
		$data=$this->db->get()->result_array();
		return $data;
	}
	public function find_cateimg($id,$field='*'){
		$data=$this->db->select($field)->from("info_cateimg_edit")->where("id",$id)->get()->result_array();
		if (!empty($data)) {
			return $data[0];
		}
	}
    public function  update_cateimg($arr,$id){
        $this->db->where('id', $id);
        return $this->db->update('info_cateimg_edit', $arr);
    }
    public function upcateimg($arr,$id,$type){
    	$this->db->trans_strict(FALSE);
    	$this->db->trans_begin();
    	$this->db->insert('info_material',$arr);
    	$img_field = 'img_'.$type;
    	$arr_e[$img_field] = $arr['img_url'];
    	$this->db->where('id',$id);
		$this->db->update('info_cateimg_edit',$arr_e);
		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
	    }
    }
    public function cateimg_case($arr,$eid){
		$arr_e['case_state'] = 1;
		$this->db->trans_strict(FALSE);
        $this->db->trans_begin();
		$this->db->where('id',$eid);
		$this->db->update('info_cateimg_edit',$arr_e);
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