<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Account_level_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//获取层级
	public function get_level($map,$limit){
	    $db_model['tab'] = 'k_user_level';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;//读取从库
		if (empty($limit)) {
            return $this->M($db_model)->where($map)->select('id');
		}else{
		    return $this->M($db_model)->where($map)->limit($limit)->select();
		}

	}

	//获取所有层级会员数量
	public function level_count(){
        $db_model['tab'] = 'k_user';
		$db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
		$map['site_id'] = $_SESSION['site_id'];
        $map['shiwan'] = 0;

		return $this->M($db_model)->field("count('uid') as num,level_id")->where($map)->group('level_id')->select('level_id');
	}

	//获取层级旗下会员
	public function get_user($map,$limit){
        $db_model['tab'] = 'k_user';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;//读取从库
		if (!empty($limit)) {
		    return $this->M($db_model)->where($map)->limit($limit)->order("uid desc")->select('uid');
		}else{
			return $this->M($db_model)->field("uid,reg_date")->where($map)->order("uid desc")->select('uid');
		}

	}

		//公司入款批量
	public function user_cash_All($uids,$date =array()){
		$map = array();
		$map['uid'] = array('in',$uids);
		$map['make_sure'] = 1;
		$map['site_id'] = $_SESSION['site_id'];

		$db_model['tab'] = 'k_user_bank_in_record';
		$db_model['type'] = 1;
		return $this->M($db_model)->field("sum(deposit_num) as money,count(id) as num,max(deposit_num) as max_money,uid")
		         ->where($map)->group('uid')->select('uid');
	}

	//人工入款
	public function user_in_rg($uids,$date = array()){
		$map = array();
		$map['uid'] = array('in',$uids);
		$map['type'] = 1;
		$map['site_id'] = $_SESSION['site_id'];
		$map['catm_type'] = array('in','(1,6,4)');
		if (!empty($date)) {
		   $map['updatetime'] = array(
		   	                      array('>=',$date[0].' 00:00:00'),
		   	                      array('<=',$date[1].' 23:59:59')
		   	                    );
		}
		$db_model['tab'] = 'k_user_catm';
		$db_model['type'] = 1;
		return $this->M($db_model)->field("count(id) as num,sum(catm_money) as money,max(catm_money) as max_money,uid")
		           ->where($map)->group('uid')
		           ->select('uid');
	}

	//出款
	public function user_out_rg($uids,$date = array()){
		//人工
        $map = array();
		$map['uid'] = array('in',$uids);
		$map['type'] = 2;
		$map['site_id'] = $_SESSION['site_id'];
		$map['catm_type'] = array('in','(1,2,4,8)');
		if (!empty($date)) {
		   $map['updatetime'] = array(
		   	                      array('>=',$date[0].' 00:00:00'),
		   	                      array('<=',$date[1].' 23:59:59')
		   	                    );
		}
		$db_model['tab'] = 'k_user_catm';
		$db_model['type'] = 1;
		return $this->M($db_model)->field("count(id) as num,sum(catm_money) as money,max(catm_money) as max_money,uid")
		        ->where($map)->group('uid')
		        ->select('uid');
	}
    //线上出款
	public function user_out_xs($uids,$date = array()){
        $map = array();
		$map['uid'] = array('in',$uids);
		$map['out_status'] = 1;
		$map['site_id'] = $_SESSION['site_id'];
		if (!empty($date)) {
		   $map['out_time'] = array(
		   	                      array('>=',$date[0].' 00:00:00'),
		   	                      array('<=',$date[1].' 23:59:59')
		   	                    );
		}
		$db_model['tab'] = 'k_user_bank_out_record';
		$db_model['type'] = 1;
		return $this->M($db_model)->field("sum(outward_num) as money,count(id) as num,max(outward_num) as max_money,uid")
		        ->where($map)->group('uid')
		        ->select('uid');
	}



	//返回会员所有人工入款
	public function get_all_user_RG($uids,$type = 1){
        $map_catm = array();
        if ($type == 1) {
            $map_catm['uid'] = array('in',$uids);
        }
        $map_catm['site_id'] = $_SESSION['site_id'];
        $map_catm['type'] = 1;

        $db_model['tab'] = 'k_user_catm';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        return $this->M($db_model)->field("count(id) as catmNum,sum(catm_money) as catm_money,uid")->where($map_catm)->group("uid")->select('uid');
	}
    //获取所有会员公司线上入款
	public function get_all_user_GS($uids,$type = 1){
        $map_cash = array();
        if ($type == 1) {
            $map_cash['uid'] = array('in',$uids);
        }
        $map_cash['site_id'] = $_SESSION['site_id'];
        $map_cash['make_sure'] = 1;
        $db_model['tab'] = 'k_user_bank_in_record';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
        return $this->M($db_model)->field("sum(deposit_num) as cash_money,count(id) as cashNum,uid")->where($map_cash)->group('uid')->select('uid');
	}

	//获取所有符合条件会员
	public function get_yes_user($level_id,$map = array()){
		//获取层级下所有会员
		$map_u = array();
		$map_u['is_delete'] = 0;
	    $map_u['site_id'] = $_SESSION['site_id'];
	    $map_u['level_id'] = $level_id;
	    $map_u['is_locked'] = 0;

	    $db_model['tab'] = 'k_user';
	    $db_model['type'] = 1;
        $db_model['is_port'] = 1;//读取从库
		$level_user = $this->M($db_model)->field("uid,reg_date")->where($map_u)->order("uid desc")->select('uid');
		if (empty($level_user)) { return false;}
		$uids = '('.implode(',',array_keys($level_user)).')';
        //人工入款
        $catm_data = array();
		$catm_data = $this->get_all_user_RG($uids);
        //公司线上入款
        $map_cash = array();
		$cash_data = $this->get_all_user_GS($uids);
        //判断是否符合条件
		foreach ($level_user as $kk => $vv) {
			$inum = $imoney = 0;
	        $inum = $cash_data[$vv['uid']]['cashNum']+$catm_data[$vv['uid']]['catmNum'];
	        $imoney = $cash_data[$vv['uid']]['cash_money']+$catm_data[$vv['uid']]['catm_money'];
	        if ($inum >= $map['deposit_num'] && $imoney >= $map['deposit_count']) {
	            //是否开启注册时间筛选
	            if ($map['start_date'] && $map['end_date']) {
	                if ($vv['reg_date'] >= $map['start_date'] && $vv['reg_date'] <= $map['end_date']) {
	                }else{
	                    unset($level_user[$kk]);
	                }
	            }
	        }else{
	           unset($level_user[$kk]);
	        }
		}
		return $level_user;
	}


}