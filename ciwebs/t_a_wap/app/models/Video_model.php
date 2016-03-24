<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Video_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->init_db();
	}
	
	public function get_limitval($userinfo,$g_type){
		if($g_type=='og'||$g_type=='ag'||$g_type=='ct'){
			$limit = ",".$g_type."_limit";
			$limitkey = $g_type."_limit";
			switch ($g_type) {
				case 'og':
					$limitd = '1,1';
					break;
				case 'ag':
					$limitd = "A";
					break;
				case 'ct':
					$limitd = "1702,1703,1700,3596`1706,36,37,10`11,35,9,3616`1707,1708,300,3604`693,3488,3612,691`1704,1705,3600,3156`1972,1971,3608,1973";
					break;        
				default:;
				break;
			}
			$this->db->from('k_user_agent');
			$this->db->where('id',$userinfo['agent_id']);
			$this->db->select("pid,agent_type".$limit);
			$agent = $this->db->get()->row_array();

			$this->db->from('k_user_agent');
			$this->db->where('id',$agent['agent_id']);
			$this->db->select("pid,agent_type".$limit);
			$upagent = $this->db->get()->row_array();

			$this->db->from('k_user_agent');
			$this->db->where('id',$upagent['agent_id']);
			$this->db->select("agent_type".$limit);
			$sh = $this->db->get()->row_array();
			
			$sh[$limitkey] = $sh[$limitkey] ? $sh[$limitkey] : $limitd;
			$upagent[$limitkey] = $upagent[$limitkey] ? $upagent[$limitkey] : $sh[$limitkey];
			$agent[$limitkey] = $agent[$limitkey] ? $agent[$limitkey] : $upagent[$limitkey];
			$limitval = $userinfo[$limitkey] ? $userinfo[$limitkey] : $agent[$limitkey];
			if($g_type=='og'){
				$val = str_replace(",", "|", $limitval);
				$limitval=$val."|";
			}
			return $limitval;
		}
	}
	
}