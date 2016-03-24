<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
class Red_bag_model extends MY_Model {
	function __construct() {
	}

	//批量插入
	public function add_bag_info($data){
		$this->db->trans_start();
		$this->db->query($this->madd_sql($data,'redenvelopes_log'));
		return $this->db->trans_complete();
	}
	//批量修改状态之后在插入
	public function update_add_info($data,$id,$index_id){
		$arr= array();
		$arr['table'] = "redenvelopes_log";
		$arr['where']['rid']= $id;
		$arr['where']['index_id']= $index_id;
		$arr['where']['site_id']= $_SESSION['site_id'];
		$arr['where']['make_sure']= 1;
		$map1['make_sure'] = "0";
		$result = $this->rupdate($arr,$map1);
		 return $this->add_bag_info($data);
	}
	//批量更新
	public function update_bag_info($data,$rid){
		$this->db->trans_start();
        $update_sql = "update redenvelopes_log set money=(case uuid ";
        foreach ($data as $key => $val) {
            $update_sql .= " when '".$val['uuid']."'  THEN ".$val['money'];
        }
        $update_sql .=  " else money end) WHERE rid =".$rid ." and site_id = '".$_SESSION['site_id']."'";
        $this->db->query($update_sql);
		return $this->db->trans_complete();
	}

	//红包分组查询
	public function get_bag_group(){
		//红包分组
        $bag = array();
        $bag['table'] = "redenvelopes";
        $bag['select'] = "title,id";
        $bag['order'] = "id desc";
        $bag['where']['make_sure'] = "1";
        return $this->rget($bag);
	}
		//层级信息
	function get_level_info($level,$site_id,$index_id){
		$map = array();
		$map['table'] = "k_user_level";
		$map['where']['site_id'] = $site_id;
		$map['where']['index_id'] = $index_id;
		$map['where_in']['id'] = $level;
		$map['select']= "level_des,id";
		$result = $this->rget($map);
		$str = "";
		$groupid = explode(",",$level);
		foreach ($result as $key => $value) {
			if(in_array($value['id'],$groupid)){
				if($key!=count($groupid)-1){
					$str.=$value['level_des'].",";
				}else{
					$str.=$value['level_des'];
				}
			}
		}
		return $str;
	}

	//红包状态
	function get_bag_status($status){
		switch ($status) {
			case '0':
				$msg = "未开始";
				break;
			case '1':
				$msg = "活动正在进行中";
				break;
			case '2':
				$msg = "活动已结束！";
				break;
			case '3':
				$msg = "红包已终止";
				break;
		}
		return $msg;
	}
		//会员抢得红包来源
	function get_bag_type($ptype){
		switch ($ptype) {
			case '0':
				$msg = "电脑";
				break;
			case '1':
				$msg = "WAP";
				break;
			case '2':
				$msg = "APP";
				break;
		}
		return $msg;
	}
	//属于哪个站点
	function get_web_config($index_id){
		$map = array();
		$map['table'] = "web_config";
		$map['where']['site_id'] = $_SESSION['site_id'];
		$map['where']['index_id'] = $index_id;
		$map['select'] = "copy_right";
		return $this->rfind($map);
	}



}