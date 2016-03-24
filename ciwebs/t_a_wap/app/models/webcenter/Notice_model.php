<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Notice_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//获取消息类别
	public function getNotice($type,$style=1){

		$map = "site_id = '".SITEID."' and index_id = '".INDEX_ID."' and is_delete = 0 and show_type = 2 ";

		if ($type == '8') {
			$map .= " and (game_type like '%1%' or state = 1)";
		}elseif($type == '3' || $type == '7'){
			$map .= " and (game_type like '%3%' or state = 1)";
		}elseif($type == '2'){
			$map .= " and (game_type like '%2%' or state = 1)";
		}else{
			$map .= " and state = 1";
		}

		$u = $this->db->from('site_notice');
		$map1 = "sid = '0' and notice_state = '1'";

		$notice1 = $u->select('notice_cate,notice_date as add_time,notice_content as chn_simplified')->where($map1)->order_by('notice_date DESC')->get()->result_array();
		//数据拼接
		foreach ($notice1 as $key => $val) {
		   switch ($val['notice_cate']) {
		   	case '3'://彩票
		   		$fc[] = $val;
		   		break;
		   	case '4'://体育
		   		$sp[] = $val;
		   		break;
		   	case '5'://视讯
		   		$vd[] = $val;
		   		break;
	   		case '2'://维护公告，全站显示
		   		$wh[] = $val;
		   		break;
		   }
		}

        $notice = '';
        $table = $this->db->from('k_message');
		$notice = $table->where($map)->order_by("add_time DESC")->limit(8)->get()->result_array();
		if ($type == '8') {
			if(!empty($sp)){
				$notice = array_merge($sp, $notice);
			}
			if(!empty($wh)){
				$notice = array_merge($wh, $notice);
			}
			$this->tab_c('3');
			$this->public_db->from('site_notice');
			$notice3 = $this->public_db->where("(sid = '0' or sid = '".SITEID."') and notice_cate='s_p'")->order_by("notice_date DESC")->limit("0,30")->get()->result_array();
			$notice = array_merge($notice3, $notice);

		}elseif($type == '3' || $type == '7'){
			if(!empty($vd)){
				$notice = array_merge($vd, $notice);
			}
			if(!empty($wh)){
				$notice = array_merge($wh, $notice);
			}
		}elseif($type == '2'){
			if(!empty($fc)){
				$notice = array_merge($fc, $notice);
			}
			if(!empty($wh)){
				$notice = array_merge($wh, $notice);
			}
		}else{
			if(!empty($wh)){
				$notice = array_merge($wh, $notice);
			}
		}
		foreach ($notice as $key => $row)
		{
		    $add_time[$key]  = $row['add_time'];
		}
		if (is_array($add_time)) {
			array_multisort($add_time,SORT_DESC,$notice);
		}

		if(!empty($notice)){
			return $this->return_notice($notice,$style);
		}
	}

	//区分向左还是向上
	function return_notice($notice,$style){
		$info = '';
		if($style==1){
			$connector='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
		}else{
			$connector='<br>';
		}
		foreach ($notice as $key => $value) {
			if(isset($value['chn_simplified'])){
				$info .= $value['chn_simplified'].$connector;
			}elseif (isset($value['notice_content'])) {
				$info .= $value['notice_content'].$connector;
			}

		}
		return $info;
	}

	//获取消息详情
	public function get_notice_data() {
	 	$list = '';
	 	$map_m = array();
	 	$map_m['site_id'] = SITEID;
	 	$map_m['is_delete'] = 0;
	 	$map_m['show_type'] = 2;
	 	$map_m['index_id'] = INDEX_ID;
	 	
	 	$list = $this->db->from('k_message')->where($map_m)->order_by('add_time DESC')->limit(10)->get()->result_array();
	 	
	 	$u = $this->db->from('site_notice');

		$map1 = "sid = '0' and notice_state = '1' and notice_cate != '6'";

		$notice1 = $u->select('notice_cate,notice_date as add_time,notice_content as chn_simplified')->where($map1)->order_by('notice_date DESC')->get()->result_array();
		if(!empty($list) && !empty($notice1)){
			$list = array_merge($list, $notice1);
		}
		if(empty($list)){
			$list = $notice1;
		}
		foreach ($list as $key => $row)
		{
		    $add_time[$key]  = $row['add_time'];
		}
		array_multisort($add_time,SORT_DESC,$list);
	 	return $list;
	}

	//获取未读消息条数
	public function get_notice_count($value=''){
		$list = '';
		$list = $this->db->from('k_user_msg')->select('msg_id')->where("(uid='" . $_SESSION["uid"] . "' or (uid = '' and (level_id like '%" . $_SESSION['level_id'] . "%' or level_id = '-1'))) and site_id ='".SITEID."'  and is_delete = '0' and islook='0'")->get()->num_rows();
	 	$count = !empty($list['count'])?$list['count']:0;
	 	$count = !empty($list)?$list:0;
	 	return $count;
	}



}


?>