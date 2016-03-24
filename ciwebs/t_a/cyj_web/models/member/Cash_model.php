<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Cash_model extends MY_Model {

	function __construct() {
		parent::__construct();
		$this->init_db();
	}

	public function get_bank_arr(){
		$redis = new Redis();
		$redis->connect(REDIS_HOST,REDIS_PORT);
		$vdata = $redis->hgetall('bank_type');
		if (empty($vdata)) {
			//redis中数据为空 从数据库读取
			$this->db->from('k_bank_cate');
			$this->db->where('state',1);
			$this->db->order_by('id','asc');
			$mdata = $this->db->get()->result_array();
			foreach ($mdata as $key => $val) {
				$bank_json = json_encode($val, JSON_UNESCAPED_UNICODE);
				$bank_json = str_replace('"', '-', $bank_json);
				$hset[$val['id']] = $bank_json;
			}
			$redis->hmset('bank_type',$hset);
			$bank_arr = $mdata;
			}else{
				$vdata = str_replace('-', '"', $vdata);
				foreach($vdata as $key=>$value){
				$vdata[$key] = json_decode($value,true);//转数组
			}
			$bank_arr = $vdata;
			ksort($bank_arr);//按照键名从小到大排序
		}
		return $bank_arr;
	}

	public function get_bank_in_arr($level_id){
		$this->db->from('k_bank');
		$this->db->where('is_delete',0);
		$this->db->where('site_id',SITEID);
		$banksa = $this->db->get()->result_array();
		
	    if($banksa){
			$banks = $arr = $data = '';
			foreach ($banksa as $key => $value) {
				$arr = explode(',',$value['level_id']);
				if(in_array($level_id, $arr)){
					$banks[] = $value;
				}
			}
		}
	    foreach ($banks as $key => $value) {
	    	$this->db->from('k_user_bank_in_record');
	    	$this->db->select('sum(deposit_money) as c,bid');
	    	$this->db->where('bid',$value['id']);
	    	$this->db->where('log_time >',date("Y-m-d").' 00.00.00');
	    	$money = $this->db->get()->row_array();
	        if($money['c']>$value['stop_amount']){
	            unset($banks[$key]);
	        }
	    }
	    shuffle($banks);
	    return $banks;
	}


	public function get_setmoney(){   //线上存款，获取存款文案和银行信息
		  if($_SESSION['ty'] == 1 || $_SESSION['ty'] == 2){        //判断是否为预览
				$this->db->from('info_deposit_edit');
				$this->db->where('type',$_SESSION['ty']);
			}else{
				$this->db->from('info_deposit_use');
			}
			$this->db->where('site_id',SITEID);
			$this->db->where('index_id',INDEX_ID);
			$this->db->where('state',1);
			$this->db->order_by('sort','DESC');
			$deposit = $this->db->get()->result_array();
			return $deposit;
	}


	public function get_video_config(){      //获取视讯名单
		$map['where']['site_id'] = SITEID;
		$map['where']['index_id'] = INDEX_ID;
		$map['table'] = 'web_config';
		$video_config = $this->rfind($map);
		$video_config = explode(',',$video_config['video_module']);
    return $video_config;
	}


	public function get_cash_record($uid){      //获取现金交易记录
		$this->db->from('k_user_cash_record');
		$this->db->where('index_id',INDEX_ID);
		$this->db->where('site_id',SITEID);
		$this->db->where('uid',$uid);
		$this->db->where('cash_type',1);
		$this->db->where('cash_do_type',1);
		$this->db->order_by('cash_date desc');
		$cash_record = $this->db->get()->row_array();
		return $cash_record;
	}


	public function add_cash_record($uid,$username,$credit,$money,$remark){ //插入现金交易记录
			$data_c = array();
			$data_c['uid'] = $uid;
			$data_c['agent_id'] = $_SESSION['agent_id'];
			$data_c['username'] = $username;
			$data_c['site_id'] = SITEID;
			$data_c['index_id'] = INDEX_ID;
			$data_c['cash_type'] = 1;
			$data_c['cash_do_type'] = 1; //表示存入
			$data_c['cash_num'] = $credit;
			$data_c['cash_balance'] = $money;
			$data_c['cash_date'] = date('Y-m-d H:i:s');
			$data_c['remark'] = $remark;
			$result = $this->db->insert('k_user_cash_record',$data_c);
			return $result;
	}

	public function out_cash_record(){      //获取出款信息
			$this->db->from('k_user_bank_out_record');
			$this->db->where('uid',$_SESSION['uid']);
			$this->db->where_in('out_status',array(0,4));
			$this->db->where('site_id',SITEID);
			$this->db->select('order_num');
			$result = $this->db->get()->row_array();
			return $result;
	}


	public function edit_pass($newpw){     //修改密码
			$this->db->from('k_user');
			$this->db->where('uid',$_SESSION['uid']);
			$this->db->where('site_id',SITEID);
			$this->db->set('qk_pwd',$newpw);
			$this->db->update();
			$result = $this->db->affected_rows();
			return $result;
	}

	public function is_first(){     //判断是否是首次出款
		$this->db->from('k_user_bank_out_record');
    $this->db->where('uid',$_SESSION['uid']);
    $this->db->where('out_status',1);
    $result = $this->db->get()->row_array();
    return $result; 
	}

	public function get_agent_user(){   //获取代理商帐号
		$this->db->from('k_user_agent');
    $this->db->where('id',$_SESSION['agent_id']);
    $this->db->select('agent_user');
    $agent_user = $this->db->get()->row_array();
    return $agent_user;
	}

	public function now_money(){    //查询用户当前余额
			$this->db->from('k_user');
      $this->db->select('money');
      $this->db->where('uid',$_SESSION['uid']);
      $umban = $this->db->get()->row_array();
      return $umban;
	}

	public function get_bank(){      // 获取银行卡官网
			$this->db->from('k_bank');
			$this->db->select('card_address');
			$this->db->where('id',$bid);
			$this->db->where('site_id',SITEID);
			$bank = $this->db->get()->row_array();
			return $bank;
	}

	public function is_first_in(){    //查询是否首次入款
			$this->db->from('k_user_bank_in_record');
			$this->db->select('id');
			$this->db->where('uid',$_SESSION['uid']);
			$this->db->where('make_sure',1);
			$this->db->where('site_id',SITEID);
			$user_record = $this->db->get()->row_array();
			return $user_record;
	}

	public function get_order_num($order_num){    //查询提交次数
			$this->db->from('k_user_bank_in_record');
			$this->db->select('order_num');
			$this->db->where('order_num',$order_num);
			$this->db->where('site_id',SITEID);
			$result = $this->db->get()->row_array();
			return $result;
	}

	public function get_level_des(){       //获取层级位置
			$this->db->from('k_user_level');
			$this->db->select('level_des');
			$this->db->where('id',$_SESSION['level_id']);
			$this->db->where('site_id',SITEID);
			$level_des = $this->db->get()->row_array();
			return $level_des;
	}


	//额度转入
	public function conversionIn($credit, $g_type){
		$uid = $_SESSION['uid'];
		$username = $_SESSION['username'];

		//更新会员金额
		$this->db->trans_begin();
    	$this->db->where('uid',$uid);
		$this->db->where('money >=',$credit);
		$this->db->set('money','money-'.$credit,FALSE);
		$this->db->update('k_user');
		if($this->db->affected_rows() == 0){
			$this->db->trans_rollback();
			return 11;
		}

		$userinfo = $this->get_user_info($uid);
		//现金记录
		$remark = "系统转出" . $g_type . ":" . $credit . " 元; ";
		$log_2 = $this->add_cash_record($uid,$userinfo['username'],$credit,$userinfo['money'],$remark);   //插入现金交易记录
		$last_id = $this->db->insert_id();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 5;
		}

		$this->load->library('Games');
		$games = new Games();

		$data = $games->GetBalance($username, $g_type);
		$result = json_decode($data);

		if ($result->data->Code == 10017) {
			$sxbalance = floatval($result->data->balance);
		} else if ($result->data->Code == 10006) {
			if($g_type == 'pt'){
				$cur = "CNY";
			}else{
				$cur = "RMB";
			}
			$data = $games->CreateAccount($username, $userinfo["agent_id"], $g_type, INDEX_ID, $cur);
			if (!empty($data)) {
				$result = json_decode($data);
				if ($result->data->Code != 10011) {
					$this->db->trans_rollback(); //数据回滚
					return 6;
				}
			} else {
				//网络无响应
				$this->db->trans_rollback(); //数据回滚
				return 7;
			}
			$sxbalance = 0;
		} else {
			$this->db->trans_rollback(); //数据回滚
			return 8;
		}
		
		if(empty($_SESSION['agent_id'])){
			$this->db->trans_rollback(); //数据回滚
			return 9;
		}
		//现金记录
		$remark = "系统转出" . $g_type . ":" . $credit . " 元," . $g_type . "余额:" . ($sxbalance + $credit) . "元";

		//更新日志
		$this->db->where('id',$last_id);
    	$this->db->where('uid',$uid);
		$this->db->set('remark',$remark);
		$this->db->update('k_user_cash_record');

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 10;
		}else{
			$this->db->trans_commit();
			//视讯开始加款
		}
	}


	//额度转出
	public function conversionOut($credit, $g_type){
		$uid = $_SESSION['uid'];
		$username = $_SESSION['username'];
		$this->db->trans_begin();
    	$this->db->where('uid',$uid);
		$this->db->set('money','money+'.$credit,FALSE);
		$this->db->update('k_user');

		$this->load->library('Games');
		$games = new Games();

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 14;
		}
		$data = $games->GetBalance($username, $g_type);
		$result = json_decode($data);
		if ($result->data->Code == 10017) {
			$sxbalance = floatval($result->data->balance);
		} else {
			$this->db->trans_rollback(); //数据回滚
			return 15;
		}
		
		if(empty($_SESSION['agent_id'])){
			$this->db->trans_rollback(); //数据回滚
			return 16;
		}

		$userinfo = $this->get_user_info($uid);
		//现金记录
		$remark = $g_type . "转系统：" . $credit . " 元," . $g_type . ":" . $sxbalance . "元";

		$log_3 = $this->add_cash_record($uid,$userinfo['username'],$credit,$userinfo['money'],$remark);   //插入现金交易记录

		if($this->db->trans_status() === FALSE){
			$this->db->trans_rollback();
			return 17;
		}else{
			$this->db->trans_commit();
			//视讯开始加款
		}
	}


	//根据uid 获取用户基本信息
	public function get_user_info($uid){
		if(!empty($uid)){
			$this->db->from('k_user');
			$this->db->where('uid',$uid);
			$this->db->where('site_id',SITEID);
			$this->db->where('index_id',INDEX_ID);
			$userinfo = $this->db->get()->row_array();
			return $userinfo;
		}
	}


}