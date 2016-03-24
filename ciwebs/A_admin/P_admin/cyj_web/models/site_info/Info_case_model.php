<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_case_model extends MY_Model {

	function __construct() {
	    parent::__construct();
	}
	public function get_case($per,$offset,$map) {
		if (!empty($map)) {
			$this->db->where($map);
		}
		$this->db->order_by('id desc');
		$data=$this->db->get('info_case',$per,$offset)->result_array();
		return $data;
	}

	public function get_case_count($map){
       if (!empty($map)) {
			$this->db->where($map);
		}
		return $this->db->count_all_results('info_case');
	}

	public function update_case($arr,$id){
		$this->db->where('id', $id);
        return $this->db->update('info_case', $arr);
	}
	public function case_del($eid,$id,$tab){
		$arr_e['case_state'] = 0;
		$arr['cstate'] = 4;
		$this->db->trans_strict(FALSE);
                $this->db->trans_begin();
        if ($tab == 'info_activity_edit') {
            $this->db->from('info_case');
            $this->db->where('id',$id);
            $info = $this->db->get()->row_array();
            //$this->db->last_query();exit;
            $this->db->where('index_id',$info['index_id']);
            $this->db->where('site_id',$_SESSION['site_id']);
        }else{
           $this->db->where('id',$eid);
        }
		$this->db->update($tab,$arr_e);
		$this->db->where('id',$id);
		$this->db->update('info_case',$arr);
		if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return FALSE;
        }else{
            $this->db->trans_commit();
            return TRUE;
	    }
	}

}