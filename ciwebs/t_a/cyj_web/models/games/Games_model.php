<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Games_model extends MY_Model {
	
	/**
	 * [GetUserName] 获取会员信息
	 */
	public function GetUserName(){
		$uid = @$_SESSION['uid'];
		$this->private_db->from('k_user');
		$this->private_db->select('username,agent_id,shiwan');
		$this->private_db->where("site_id = '" . SITEID . "' and uid = '" . $uid . "'");
		return $this->private_db->get()->row_array();
	}

	public function GetPTUser($loginname = ''){
		$this->video_db->from('pt_user');
		$this->video_db->select("g_username,password");
		$this->video_db->where("site_id = '" . SITEID . "' and username = '" . $loginname . "'");
		return $this->video_db->get()->row_array();
	}

}

?>