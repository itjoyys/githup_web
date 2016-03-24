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

}

?>