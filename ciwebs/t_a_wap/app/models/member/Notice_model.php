<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Notice_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->init_db();
	}

	//获取最新的公告信息
	function GetLatestNews($map){
		$this->private_db->from('k_message');
		$this->private_db->select('add_time,chn_simplified');
		$this->private_db->where($map['where']);
		$this->private_db->order_by($map['order']);
		$query = $this->private_db->get();
		return $query->result_array();
	}

	//获取历史公告信息
	function GetHistiryNews($site_id,$index_id,$start_id){
		$offset = 0;
		$data['ErrorCode'] = 0;
		
		$count = intval($get['PageSize']);

		$this->private_db->select('id,add_time,chn_simplified');
		$this->private_db->where("is_delete='0' and site_id='".$site_id."' and index_id = '".$index_id."' and id > '".$start_id."' and show_type=2");
		$this->private_db->order_by("add_time desc");
		if($count > 0){
			$query = $this->private_db->get('k_message', $count, $offset)->result_array();
		}else{
			$query = $this->private_db->get('k_message')->result_array();
		}
		$data['Data']['List'] = array();
		foreach ($query as $key => $val) {
			$data['Data']['List'][$key]['ID'] = '--' . $val['id'] . '--';
			$data['Data']['List'][$key]['StartDateTime'] = $val['add_time'];
			$data['Data']['List'][$key]['Content'] = $val['chn_simplified'];
			$data['Data']['List'][$key]['Read'] = 1;

		}
		$data['Data']['SearchData'] = $query[0]['id']."|".$query[0]['add_time'];
		return $data;
	}

	//获取历史公告总数
	function GetHistiryNewsCount(){
		$map['where'] = "is_delete='0' and site_id='".SITEID."' and site_id='".INDEX_ID."'  and show_type='2'";
		$map['order'] = "add_time desc";
		$this->private_db->from('k_message');
		$this->private_db->select('id');
		$this->private_db->where($map['where']);
		$query = $this->private_db->count_all_results();
		return $query;
	}

	//获取未读消息条数
	public function GetMessageCount($uid){
		$list = '';
                $lid = $_SESSION['level_id'];
                $where_lid = "level_id REGEXP ',$lid,|^$lid$|,$lid$|^$lid,'";
		$list = $this->db->from('k_user_msg')->select('msg_id')->where("(uid='" . $uid . "' or (uid = '' and ( " . $where_lid . " or level_id = '-1'))) and site_id ='".SITEID."' and index_id = '".INDEX_ID."' and is_delete = '0' and islook='0'")->get()->num_rows();
	 	$count = !empty($list)?$list:0;
	 	return $count;
	}

	//获取用户等级
	function GetUserLevelId($uid){
		$this->private_db->from('k_user');
		$this->private_db->select('level_id');
		$this->private_db->where("uid = '$uid'");
		$query = $this->private_db->get();
		return $query->result_array();
	}

	//获取信息公告，个人信息 id
	function GetSmsId($level_id){
		$level_id = '';
		$uid = $_SESSION["uid"];
		$map="(uid='". $uid ."' or (uid = '' and (level_id = '".$level_id."' or level_id = '-1')))   and is_delete = '0' and site_id = '".SITEID."' and index_id = '".INDEX_ID."'";
		$this->private_db->from('k_user_msg');
		$this->private_db->select('msg_id');
		$this->private_db->where($map);
		$this->private_db->order_by('islook asc,msg_id desc');
		$query = $this->private_db->get();
		return $query->result_array();
	}

	//获取信息公告，个人信息
	function GetSms($get){
		$uid = $_SESSION['uid'];
		$row = $this->GetUserLevelId($uid);
		$id = $this->GetSmsId($row[0]['level_id']);
		$limit = array($get['PageSize'],$get['PageSize'] * ($get['CurrentPage'] - 1));

		if($id){
			foreach ($id as $v) {
				$bid .=	$v['msg_id'].',';
			}
			$id		=	rtrim($bid,',');
		}
		$data['ErrorCode'] = 0;
		$data['Data']['SearchTime'] = time();
		$this->private_db->select("islook,msg_title,msg_time,msg_info,msg_id,type");
		$this->private_db->where("msg_id in($id)");
		$this->private_db->order_by('islook asc,msg_id desc');
		$mes = $this->private_db->get('k_user_msg', $limit[0], $limit[1])->result_array();
		foreach ($mes as $key => $val) {
			$data['Data']['List'][$key]['MessID'] = '--' . $val['msg_id'] . '--';
			$data['Data']['List'][$key]['SendType'] = '--' . $val['type'] . '--';
			$data['Data']['List'][$key]['SendContent'] = $val['msg_title'];
			$data['Data']['List'][$key]['SendDateTime'] = $val['msg_time'];
			$data['Data']['List'][$key]['IsReply'] = '--' . $val['islook'] . '--';
			$data['Data']['List'][$key]['ReadState'] = '--' . $val['islook'] . '--';
		}
		return $data;
	}

	//获取信息公告，个人信息总数
	function GetSmsCount(){
		$data['ErrorCode'] = 0;
		$uid = $_SESSION['uid'];
		//$site_id = $_SESSION['site_id'];
		//$index_id = $_SESSION['index_id'];
		//var_dump($_SESSION);exit();
		/*$row = $this->GetUserLevelId($uid);
		$id = $this->GetSmsId($row[0]['level_id']);
		if($id){
			foreach ($id as $v) {
				$bid .=	$v['msg_id'].',';
			}
			$id		=	rtrim($bid,',');
		}
		$this->private_db->where("msg_id in ($id)");*/

		$time=time()-60*60*24*100;
        $time=date('Y-m-d H:i:s',$time);
		//$this->private_db->from('k_message');
		//$this->private_db->where("is_delete ='0' and site_id='".SITEID."' and index_id='".INDEX_ID."' and show_type='2' and add_time >'".$time."'");
		$data['Data']['NoticeCount'] = '--0--';
		$data['Data']['MessageCount'] = '--'.$this->GetMessageCount($uid).'--';
		return $data;
	}

	//获取信息公告，个人信息，状态改变
	function UpSmsChange($map){
		$this->private_db->where($map['key'], $map['val']);
		$this->private_db->set($map['set'],1);
		$this->private_db->update($map['tab']);
		return $this->private_db->affected_rows();
	}

	public function GetOneNews($msg_id,$table='k_user_msg',$type=1) {
		$data['ErrorCode'] = 0;
		$this->private_db->from($table);
		if($type == 1){
			$this->private_db->where("msg_id", $msg_id);
			$datas = $this->private_db->get()->row_array();
			$data['SendContent'] = $datas['msg_info'];
			$data['SendDateTime'] = $datas['msg_time'];
		}else{
			//跟新
			$this->private_db->where("id", $msg_id);
			$datas = $this->private_db->get()->row_array();
			$data['SendContent'] = $datas['chn_simplified'];
			$data['SendDateTime'] = $datas['add_time'];

		}
		return $data;
	}

	//获取信息公告，游戏公告
	function get_sports_news($type){
		$ndate = date('Y-m-d H:i:s',(time()-30*24*60*60));
		$ntype = array('sp'=>4,'tv'=>5,'fc'=>3);

		$map = " sid = '0' and notice_state = '1' and (notice_cate='".$ntype[$type]."' or notice_cate='2') and notice_date > '".$ndate."' ";
		$this->private_db->from('site_notice');
		$this->private_db->where($map);
		$this->private_db->limit(8,0);
		$this->private_db->order_by('notice_date DESC');
		return $this->private_db->get()->result_array();
	}

}