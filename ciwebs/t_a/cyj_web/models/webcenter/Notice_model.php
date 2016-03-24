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
        $v_c = $this->get_video_config();
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

		$map1 = "sid = '0' and notice_state = '1' and notice_cate != '6' and (notice_type in (".$v_c.") or notice_type is null)";
		$notice1 = $this->db->select('notice_cate,notice_date as add_time,notice_content as chn_simplified')->where($map1)->order_by('notice_date DESC')->get('site_notice',10,0)->result_array();
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
		$notice = $this->db->where($map)->order_by("add_time DESC")->get('k_message',8,0)->result_array();
		if ($type == '8') {
			if(!empty($sp)){
				$notice = array_merge($sp, $notice);
			}
			if(!empty($wh)){
				$notice = array_merge($wh, $notice);
			}
			$this->tab_c('3');
			$endtime = date('Y-m-d') . ' 23:59:59';
			$starttime = date('Y-m-d',time()-(7*24*60*60)) . ' 00:00:00';
			$notice3 = $this->public_db->where("(sid = '0' or sid = '".SITEID."') and notice_cate='s_p' and notice_date > '".$starttime."' and notice_date < '".$endtime."'")->order_by("notice_date DESC")->get('site_notice')->result_array();
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
			$connector='<br><br>';
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
        $v_c = $this->get_video_config();
	 	$list = '';
	 	$map_m = array();
	 	$map_m['site_id'] = SITEID;
	 	$map_m['is_delete'] = 0;
	 	$map_m['show_type'] = 2;
	 	$map_m['index_id'] = INDEX_ID;

	 	$list = $this->db->where($map_m)->order_by('add_time DESC')->limit(10)->get('k_message',10,0)->result_array();

	 	//$u = $this->private_db->from('site_notice');

		$map1 = "sid = '0' and notice_state = '1' and notice_cate != '6' and (notice_type in (".$v_c.") or notice_type is null)";

		$notice1 = $this->private_db->select('notice_cate,notice_date as add_time,notice_content as chn_simplified')->where($map1)->order_by('notice_date DESC')->get('site_notice',10,0)->result_array();
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

	//redis获取未读信息数量
    public function new_notice_count(){
    	if($_SESSION['shiwan'] == 1){
		    return 0;
		}
        $ncount = 0;
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
		$redis_key = $_SESSION['uid'].'_'.SITEID.'_'.INDEX_ID.'_newscount';
		$ncount = $redis->get($redis_key);
        $on_user = 0;

		if($redis->exists($redis_key)){
			$starttime = date('i',time());
			if ($starttime%5 == 0) {
				$count = $this->get_user_nnews();
				$redis->set($redis_key,$count);
			}else{
				$count = $ncount;
			}
		}else{
			$count = $this->get_user_nnews();
			$redis->set($redis_key,$count);
		}

        return $count;
    }


    //读取未读信息
    function get_user_nnews(){
        $list = 0;
		$list = $this->db->from('k_user_msg')->select('msg_id')->where("(uid='" . $_SESSION["uid"] . "' or (uid = '' and (level_id like '%" . $_SESSION['level_id'] . "%' or level_id = '-1'))) and site_id ='".SITEID."'  and is_delete = '0' and islook='0'")->get()->num_rows();
		$count = !empty($list)?$list:0;
        return $count;
    }

    //获取站点所有视讯,便于消息过滤
    public function get_video_config(){
        $map_m = array();
        $map_m['site_id'] = SITEID;
        $map_m['index_id'] = INDEX_ID;
        $video_module = $this->db->from('web_config')->where($map_m)->select('video_module')->get()->row_array();
        $video_config = explode(',',$video_module['video_module']);
        $v_c = '';
        foreach ($video_config as $k => $v) {
            $v_c .='"'.$v.'",';
        }
        $v_c = substr($v_c, 0,-1);

        return $v_c;
    }



}


?>