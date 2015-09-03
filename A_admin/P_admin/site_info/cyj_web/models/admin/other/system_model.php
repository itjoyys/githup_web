<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class System_model extends MY_Model {

	function __construct() {
		//$this->init_db();
	}
	public function get_fc_limit($type) {
		$this->db->from('fc_games_view as fc');
		$this->db->join('k_user_agent_fc_set as set', 'set.type_id = fc.id');
		$this->db->where("set.site_id",$_SESSION['site_id']);
		$this->db->where("fc.fc_type",$type);
		$this->db->where("set.is_default",1);
		$data=$this->db->get()->result_array();
		return $data;
	}

	public function get_sp_limit($type) {
		$this->db->from('sp_games_view as sp');
		$this->db->join('k_user_agent_sport_set as set', 'set.type_id = sp.id');
		$this->db->where("set.site_id",$_SESSION['site_id']);
		$this->db->where("sp.type",$type);
		$this->db->where("set.is_default",1);
		$data=$this->db->get()->result_array();
		return $data;
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
        if ($tab == 'info_flash_edit' || $tab == 'info_activity_edit' || $tab == 'info_cateimg_edit') {
           $this->db->where('site_id','t');
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