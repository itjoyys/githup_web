<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Info_iword_model extends MY_Model {

	function __construct() {
		//$this->init_db();
	}
	public function iword_case($arr,$id){
		$arr_e['case_state'] = 1;
		$this->db->trans_strict(FALSE);
        $this->db->trans_begin();
		$this->db->where('id',$id);
		$this->db->update('info_iword_edit',$arr_e);
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