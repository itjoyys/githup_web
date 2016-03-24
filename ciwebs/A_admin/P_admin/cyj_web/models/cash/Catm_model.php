<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Catm_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//人工入款
	public function catm_do($uid,$arr,$type_memo){
		//统一时间
		$now_date = date('Y-m-d H:i:s');
		//获取会员总余额
		$db_model = array();
		$db_model['tab'] = 'k_user';
		$db_model['type'] = 1;
		$user_old = $this->M($db_model)->field("(money+ag_money+og_money+mg_money+ct_money+lebo_money+bbin_money) as sum_money")->where("uid = '".$uid."'")->find();

        //稽核状态查询
        $is_audit = $this->get_audit($uid);
        //判断稽核是否锁定
        $is_audit_lock = $this->is_audit_lock($uid);

        //更新余额
    	$this->db->trans_start();
    	$u_sql = "update k_user set money = money + ". $arr['deposit_money'] . " where uid = '" .$uid. "'";
        $this->db->query($u_sql);

		//会员最新余额
		$user = $this->get_user($uid);

		//启动清除稽核
		if (($user_old['sum_money'] < $arr['relax_limit']) && !empty($is_audit) && empty($is_audit_lock)) {
			 //更新稽核最后一笔结束时间
            $this->db->where(array('id'=>$is_audit['id']))
                 ->update('k_user_audit',array('end_date'=>$now_date));

            $map_audit_c = array();
			$map_audit_c['site_id'] = $_SESSION['site_id'];
			$map_audit_c['id <='] = $is_audit['id'];
			$map_audit_c['type'] = 1;
			$map_audit_c['uid'] = $uid;
		    $this->db->where($map_audit_c)->update('k_user_audit',array('type'=>2));

		    //写入稽核日志
		    $data_auto = array();
	        $data_auto['update_date'] = $now_date;
	        $data_auto['uid'] = $uid;
	        $data_auto['site_id'] = $_SESSION['site_id'];
	        $data_auto['username'] = $user['username'];
	        $data_auto['content'] = '會員：'.$user['username'].' 餘額小於放寬額度 清除稽核點 ('.$now_date.'之前的稽核點已清除)';
		    $this->db->insert('k_user_audit_log',$data_auto);

		}elseif (!empty($is_audit)) {
            //存在即更新上一次终止时间
            $this->db->where(array('id'=>$is_audit['id']))
                 ->update('k_user_audit',array('end_date'=>$now_date));
		}

		//写入人工记录
		$data_ca = array();
		$data_ca['uid'] = $uid;
		$data_ca['agent_id'] = $user['agent_id']; //代理商id
		$data_ca['site_id'] = $_SESSION['site_id'];
		$data_ca['index_id'] = $user['index_id'];
		$data_ca['username'] = $user['username'];
		$data_ca['type'] = $arr['op_type']; //存款与取款
		$data_ca['balance'] = $user['money'];
		$data_ca['catm_money'] = $arr['amount']; //存款金额
		$data_ca['catm_give'] = $arr['catm_give']; //存款优惠
		$data_ca['atm_give'] = $arr['atm_give']; //汇款优惠
		$data_ca['is_code_count'] = $arr['is_code_count']; //是否有综合打码
		$data_ca['code_count'] = $arr['code_count']; //综合打码
		$data_ca['routine_check'] = $arr['isnormality']; //常态打码
		$data_ca['catm_type'] = $type_memo; //存款项目
		$data_ca['updatetime'] = $now_date;
		$data_ca['is_rebate'] = $arr['is_rebate']; //是否退佣
		$data_ca['remark'] = $arr['remark']; //备注
		$data_ca['do_admin_id'] = $_SESSION["login_name"]; //操作人
		$this->db->insert('k_user_catm',$data_ca);
		$sid = $this->db->insert_id();

		//写入现金系统
		$dtype = 1;
		$for_what = 12;
		//人工存入现金记录那边对应的是人工存入 还有取消出款
		if ($type_memo == 1 or $type_memo == 4) {
			$dtype = 3;
		} elseif ($type_memo == 7) {
			$for_what = 9; //表示现金系统的优惠退水
			$cash_only = 1;
		} elseif ($type_memo == 2 || $type_memo == 8) {
			$cash_only = 1;
		}
		$data_c = array();
		$data_c['source_id'] = $sid;
		$data_c['source_type'] = 3;
		$data_c['site_id'] = $_SESSION['site_id'];
		$data_c['index_id'] = $user['index_id'];
		$data_c['agent_id'] = $user['agent_id'];
		$data_c['uid'] = $uid;
		$data_c['username'] = $user['username'];
		$data_c['cash_date'] = $now_date;
		$data_c['cash_type'] = $for_what;
		$data_c['cash_do_type'] = $dtype;
		$data_c['cash_balance'] = $user['money']; //当前余额

		//负数额度归0 其他属于优惠
	    if ($type_memo == '3' || $type_memo == '5') {
	    	$data_c['cash_num'] = 0; //存款金额
	    	$cash_only = 1;
	    	$data_c['discount_num'] = $arr['amount'];
	    	if($arr['ifSp'] == 1){
	    		$data_c['discount_num'] += $arr['sp_other'];; //优惠总额
	    	}
	    	if($arr['ifSp_other'] == 1){
	    		$data_c['discount_num'] += $arr['spAmount'];
	    	}
		    //$data_c['discount_num'] = $arr['amount'] + $arr['sp_other'] + $arr['spAmount']; //优惠总额
	    }else{
	    	$data_c['cash_num'] = $arr['amount']; //存款金额
		    //$data_c['discount_num'] = $arr['sp_other'] + $arr['spAmount']; //优惠总额
		    $data_c['discount_num'] = 0;
	    	if($arr['ifSp'] == 1){
	    		$data_c['discount_num'] += $arr['sp_other'];; //优惠总额
	    	}
	    	if($arr['ifSp_other'] == 1){
	    		$data_c['discount_num'] += $arr['spAmount'];
	    	}
	    }
		$data_c['cash_only'] = $cash_only; //表示活动优惠特殊
		$data_c['remark'] = $arr['remark']; //备注
		$this->db->insert('k_user_cash_record',$data_c);

		//稽核写入
		$data_a = array();
		$data_a['site_id'] = $_SESSION['site_id'];
		$data_a['uid'] = $uid;
		$data_a['username'] = $user['username'];
		$data_a['begin_date'] = $now_date;
		$data_a['deposit_money'] = $arr['amount']; //存款金额
		$data_a['atm_give'] = $arr['atm_give'];
		$data_a['catm_give'] = $arr['catm_give'];
		$data_a['source_id'] = $sid;
		$data_a['source_type'] = 1; //表示存入与取出
		$data_a['type'] = 1;
		$data_a['is_ct'] = $arr['is_isnormality']; //是否常态稽核
		$data_a['normalcy_code'] = $arr['isnormality']; //常态稽核
		$data_a['is_zh'] = $arr['is_code_count']; //是否综合稽核
		$data_a['type_code_all'] = $arr['code_count']; //综合稽核量
		$data_a['relax_limit'] = $arr['relax_limit'];
		$data_a['expenese_num'] = $arr['line_ct_xz_audit'];

		$this->db->insert('k_user_audit',$data_a);
		return $this->db->trans_complete();
	}

	//人工取款
	public function atm_do($uid,$amount,$type_memo,$remark,$delete_audit){
        $catmT = $type_memo - 1;
        $atmArray = array('重复出款', '公司入款存误', '公司负数回冲', '手动申请出款', '扣除非法下注派彩', '放弃存款优惠', '其他', '体育投注余额');
		$remark = $atmArray[$catmT] . ',' . $remark . '操作者：' . $_SESSION['login_name']; //备注
		//统一时间
		$now_date = date('Y-m-d H:i:s');

	    $this->db->trans_start();
    	$u_sql = "update k_user set money = money - ". $amount . " where uid = '" .$uid. "'";
        $this->db->query($u_sql);
        //会员最新余额
		$user = $this->get_user($uid);

		//写入人工记录
		$data_oa = array();
		$data_oa['uid'] = $uid;
		$data_oa['agent_id'] = $user['agent_id']; //代理商id
		$data_oa['site_id'] = $_SESSION['site_id'];
		$data_oa['index_id'] = $user['index_id'];
		$data_oa['username'] = $user['username'];
		$data_oa['type'] = 2; //存款与取款
		$data_oa['balance'] = $user['money'];
		$data_oa['catm_money'] = $amount; //取款金额
		$data_oa['catm_type'] = $type_memo; //存款项目
		$data_oa['updatetime'] = $now_date;
		$data_oa['remark'] = $remark; //备注
		$data_oa['do_admin_id'] = $_SESSION["login_name"]; //操作人
		$data_oa['remark'] = $atmArray[$atmT] . ',' . $remark; //备注

		$this->db->insert('k_user_catm',$data_oa);
		$souid = $this->db->insert_id();

        //人工申请出款 清除稽核
		if ($delete_audit) {
			//稽核状态查询
            $is_audit = $this->get_audit($uid);
            if ($is_audit) {
	            //更新稽核最后一笔结束时间
	            $this->db->where(array('id'=>$is_audit['id']))
	                 ->update('k_user_audit',array('end_date'=>$now_date));

	            $map_audit_c = array();
				$map_audit_c['site_id'] = $_SESSION['site_id'];
				$map_audit_c['id <='] = $is_audit['id'];
				$map_audit_c['type'] = 1;
			    $this->db->where($map_audit_c)->update('k_user_audit',array('type'=>2));
			}
		}

		$do_type = ($type_memo == 4)?4:2;
		//写入现金系统
		$data_c = array();
		$data_c['source_id'] = $souid;
		$data_c['source_type'] = 3;
		$data_c['site_id'] = $_SESSION['site_id'];
		$data_c['index_id'] = $user['index_id'];
		$data_c['uid'] = $uid;
		$data_c['agent_id'] = $user['agent_id'];
		$data_c['username'] = $user['username'];
		$data_c['cash_date'] = $now_date;
		$data_c['cash_type'] = 12;
		$data_c['cash_do_type'] = $do_type;
		$data_c['cash_balance'] = $user['money']; //当前余额
		$data_c['cash_num'] = $amount; //取款金额
		$data_c['remark'] = $remark; //备注
	    $this->db->insert('k_user_cash_record',$data_c);

	    return $this->db->trans_complete();
	}

	//人工额度转换
	public function conversion_in($uid,$credit,$g_type){

		$this->db->trans_start();
    	$u_sql = "update k_user set money = money - ". $credit . " where uid = '" .$uid. "' and money >= '".$credit."' ";
        $this->db->query($u_sql);

        $this->db->select('username,money,agent_id,index_id,level_id');
	    $user_now_money = $this->db->get_where("k_user",array('uid'=>$uid,'site_id'=>$_SESSION['site_id']))->row_array();

        $is_video = $this->get_video_balance($user_now_money['username'], $g_type,$user_now_money['agent_id'],$user_now_money['index_id']);
        if ($is_video['code'] != 'OK999') {
            $this->db->trans_rollback();
            return false;
        }
        $sxbalance = $is_video['balance'];

        //现金记录
		$data_c = array();
		$remark = "人工额度转换,系统转出" . $g_type . ":" . $credit . " 元," . $g_type . "余额:" . ($sxbalance + $credit) . "元";
		$data_c['uid'] = $uid;
		$data_c['agent_id'] = $user_now_money["agent_id"];
		$data_c['username'] = $user_now_money['username'];
		$data_c['site_id'] = $_SESSION['site_id'];
		$data_c['index_id'] = $user_now_money['index_id'];
		$data_c['cash_type'] = 1;
		$data_c['cash_do_type'] = 1; //表示存入
		$data_c['cash_num'] = $credit;
		$data_c['cash_balance'] = $user_now_money['money'];
		$data_c['cash_date'] = date('Y-m-d H:i:s');
		$data_c['remark'] = $remark;
		$this->db->insert('k_user_cash_record',$data_c);

		return $this->db->trans_complete();

	}

	//额度转换 取出
	public function conversion_out($uid,$credit,$g_type){

		$this->db->trans_start();
    	$u_sql = "update k_user set money = money + ". $credit . " where uid = '" .$uid. "' and site_id = '".$_SESSION['site_id']."' ";
        $this->db->query($u_sql);

        $this->db->select('username,money,agent_id,index_id');
	    $user_now_money = $this->db->get_where("k_user",array('uid'=>$uid,'site_id'=>$_SESSION['site_id']))->row_array();

        $this->load->library('video/Games');
        $games = new games();
    	$data = $games->GetBalance($user_now_money['username'], $g_type);
		$result = json_decode($data);

		if ($result->data->Code == 10017) {
			$sxbalance = floatval($result->data->balance);
		} else {
			$this->db->trans_rollback();
            return false;
		}

	    //现金记录
		$data_c = array();
		$remark = '人工额度转换，'.$g_type . "转系统：" . $credit . " 元," . $g_type . ":" . $sxbalance . "元";
		$data_c['uid'] = $uid;
		$data_c['username'] = $user_now_money['username'];
		$data_c['agent_id'] = $user_now_money['agent_id'];
		$data_c['index_id'] = $user_now_money['index_id'];
		$data_c['site_id'] = $_SESSION['site_id'];
		$data_c['cash_type'] = 1;
		$data_c['cash_do_type'] = 1;
		$data_c['cash_num'] = $credit;
		$data_c['cash_balance'] = $user_now_money['money'];
		$data_c['cash_date'] = date('Y-m-d H:i:s');
		$data_c['remark'] = $remark;
		$this->db->insert('k_user_cash_record',$data_c);

		return $this->db->trans_complete();

	}

	//获取视讯余额
	public function get_video_balance($username, $g_type, $agent_id,$index_id){
		$this->load->library('video/Games');
		$data = '';
		$result = '';
		$state_arr = array();
		$games = new games();
	    $data = $games->GetBalance($username, $g_type);
		$result = json_decode($data);
		if ($result->data->Code == 10017) {
			$state_arr = array('code'=>'OK999','balance' =>$result->data->balance);
		} else if ($result->data->Code == 10006) {
			$data = $games->CreateAccount($username, $agent_id, $g_type,$index_id);
			if (!empty($data)) {
				$result = json_decode($data);
				if ($result->data->Code != 10011) {
					$state_arr = array('code'=>'R0000',
	                                   'log' =>'创建'.$g_type.'视讯账号之后出现异常');
				}else{
					$state_arr = array('code'=>'OK999','balance' =>0
					             );
				}
			} else {
			    $state_arr = array('code'=>'R0001',
	                                 'log' =>'创建'.$g_type.'视讯账号失败'
					             );
			}
		} else {
			$state_arr = array('code'=>'R0002',
	                           'log' =>'获取'.$g_type.'视讯余额失败' );
		}
		return $state_arr;
	}

	//获取额度转换记录
	public function get_conversion_log($map,$limit){
		 $db_model['tab'] = 'game_cash_record';
		 $db_model['type'] = 3;
	     return $this->M($db_model)->where($map)->limit($limit)->order('update_time DESC')->select();
	}


	//获取会员信息
	public function get_user($uid){
		$this->db->select('username,money,agent_id,index_id,level_id');
	    return $this->db->get_where("k_user",array('uid'=>$uid,'site_id'=>$_SESSION['site_id']))->row_array();
	}

	//稽核查询
	public function get_audit($uid){
		$map_audit = $db_model = array();
        $map_audit['site_id'] = $_SESSION['site_id'];
        $map_audit['type'] = 1;
        $map_audit['uid'] = $uid;

        $db_model['tab'] = 'k_user_audit';
        $db_model['type'] = 1;
        return $this->M($db_model)->where($map_audit)->order("id desc")->find();
	}

	//判断稽核
	public function is_audit_lock($uid){
		$map_lock = array();
        $map_lock['site_id'] = $_SESSION['site_id'];
        $map_lock['uid'] = $uid;
        $map_lock['out_status'] = array('in','(0,4)');

        $db_model = array();
        $db_model['tab'] = 'k_user_bank_out_record';
        $db_model['base_type'] = 1;
        return $this->M($db_model)->where($map_lock)->find();
	}

	//获取记录
	public function get_catm_record($map,$limit){
        $db_model['tab'] = 'k_user_catm';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;//读取从库
		return $this->M($db_model)->where($map)->limit($limit)->order('updatetime DESC')->select();
	}

	//人工统计
	public function catm_count($map){
        $db_model['tab'] = 'k_user_catm';
		$db_model['type'] = 1;
		$db_model['is_port'] = 1;//读取从库
		return $this->M($db_model)->field('sum(catm_money) as catm_moneyc,sum(catm_give) as catm_givec,sum(atm_give) as atm_givec')->where($map)->find();
	}

	//综合稽核
	public function complex_do($arr){
		$this->db->trans_start();
		$this->db ->where("source_id = '".$arr['id']."' and source_type = '1'")->update('k_user_audit',array('type_code_all'=>$arr['complex']));
	    $this->db ->where("id = '".$arr['id']."' and site_id = '".$_SESSION['site_id']."'")->update('k_user_catm',array('code_count'=>$arr['complex']));
	    return $this->db->trans_complete();
	}

	//常态稽核
	public function normality_do($arr){
		$this->db->trans_start();
		$this->db ->where("source_id = '".$arr['id']."' and source_type = '1'")->update('k_user_audit',array('normalcy_code'=>$arr['complex']));
	    $this->db ->where("id = '".$arr['id']."' and site_id = '".$_SESSION['site_id']."'")->update('k_user_catm',array('routine_check'=>$arr['complex']));
	    return $this->db->trans_complete();
	}

		//批量入款
	public function cash_operation2($arr){
        //账号过滤
        $usernames = "'".str_replace(",","','",$arr['username'])."'";//字符串元素加单引号
	    $usernames = strtr($usernames, array(' '=>''));//清除空格
	    $db_model = array();
	    $db_model['tab'] = 'k_user';
	    $db_model['type'] = 1;
	    $uids = $this->M($db_model)->field('username,index_id,uid,level_id,agent_id')->where("username in ($usernames) and site_id = '".SITEID."' and shiwan = '0' and is_delete = '0'")->select();
	    if (empty($uids)) {
	        return 'E0';
	        die();
	    }

        //写入数据
        $this->db->trans_start();
	    foreach ($uids as $key => $val) {
            $now_date = date('Y-m-d H:i:s');
	        //更新会员余额
	        $all_money = $arr['amount'] + $arr['sp_other'] + $arr['spAmount'];
	        $u_sql = "update k_user set money = money + ". $all_money. " where uid = '" .$val['uid']. "' and site_id = '".$_SESSION['site_id']."' ";
	        $this->db->query($u_sql);

	        $map_um = array();
            $map_um['table'] = 'k_user';
            $map_um['select'] = 'money';
            $map_um['where']['uid'] = $val['uid'];
            $map_um['where']['site_id'] = $_SESSION['site_id'];
            $useMoney = $this->rfind($map_um);

	              //写入人工记录
	        $data_ca = array();
	        $data_ca['uid'] = $val['uid'];
	        $data_ca['agent_id'] = $val['agent_id'];//代理商id
	        $data_ca['site_id'] = $_SESSION['site_id'];
	        $data_ca['username'] = $val['username'];
	        $data_ca['type'] = 1;//存款
	        $data_ca['balance'] = $useMoney['money'];
	        $data_ca['catm_money'] = $arr['amount'];//存款金额
	        $data_ca['catm_give'] = $arr['sp_other'];//存款优惠
	        $data_ca['atm_give'] = $arr['spAmount'];//汇款优惠
	        $data_ca['is_code_count'] = $arr['isComplex'];//是否有综合打码
	        $data_ca['code_count'] = $arr['ComplexValue'];//综合打码
	        $data_ca['routine_check'] = $arr['isnormality'];//常态打码
	        $data_ca['catm_type'] = $arr['type_memo'];//存款项目
	        $data_ca['updatetime'] = $now_date;
	        $data_ca['is_rebate'] = $arr['c_is_rebate'];//是否退佣
	        $data_ca['remark'] = $arr['remark'];//备注
	        $data_ca['do_admin_id']=$_SESSION["login_name"];//操作人
	        $this->db->insert('k_user_catm',$data_ca);
	        $sid = $this->db->insert_id();//数据源ID

	        //稽核状态查询
            $is_audit = $this->get_audit($val['uid']);
            //判断稽核是否锁定
            $is_audit_lock = $this->is_audit_lock($val['uid']);

            if (!empty($is_audit) && empty($is_audit_lock)) {
                 //存在即更新上一次终止时间
                 $this->db->where(array('id'=>$is_audit['id']))
                 ->update('k_user_audit',array('end_date'=>$now_date));
		    }

		    //写入现金系统
		    $dtype = 1;
		    $for_what = 12;
			//人工存入现金记录那边对应的是人工存入 还有取消出款
			if ($arr['type_memo'] == 1 or $arr['type_memo'] == 4) {
				$dtype = 3;
			} elseif ($arr['type_memo'] == 7) {
				$for_what = 9; //表示现金系统的优惠退水
				$cash_only = 1;
			} elseif ($arr['type_memo'] == 2 || $arr['type_memo'] == 8) {
				$cash_only = 1;
			}
			$data_c = array();
			$data_c['source_id'] = $sid;
			$data_c['source_type'] = 3;
			$data_c['site_id'] = $_SESSION['site_id'];
			$data_c['index_id'] = $val['index_id'];
			$data_c['agent_id'] = $val['agent_id'];
			$data_c['uid'] = $val['uid'];
			$data_c['username'] = $val['username'];
			$data_c['cash_date'] = $now_date;
			$data_c['cash_type'] = $for_what;
			$data_c['cash_do_type'] = $dtype;
			$data_c['cash_balance'] = $useMoney['money']; //当前余额

			//负数额度归0 其他属于优惠
		    if ($arr['type_memo'] == '3' || $arr['type_memo'] == '5') {
		    	$data_c['cash_num'] = 0; //存款金额
		    	$cash_only = 1;
			    $data_c['discount_num'] = $arr['amount'] + $arr['sp_other'] + $arr['spAmount']; //优惠总额
		    }else{
		    	$data_c['cash_num'] = $arr['amount']; //存款金额
			    $data_c['discount_num'] = $arr['sp_other'] + $arr['spAmount']; //优惠总额
		    }
			$data_c['cash_only'] = $cash_only; //表示活动优惠特殊
			$data_c['remark'] = $arr['remark']; //备注
			$this->db->insert('k_user_cash_record',$data_c);

			//稽核写入
			$data_a = array();
			$data_a['site_id'] = $_SESSION['site_id'];
			$data_a['uid'] = $val['uid'];
			$data_a['username'] = $val['username'];
			$data_a['begin_date'] = $now_date;
			$data_a['deposit_money'] = $arr['amount']; //存款金额
			$data_a['atm_give'] = $arr['sp_other'];
			$data_a['catm_give'] = $arr['spAmount'];
			$data_a['source_id'] = $sid;
			$data_a['source_type'] = 1; //表示存入与取出
			$data_a['type'] = 1;
			$data_a['is_ct'] = $arr['is_isnormality']; //是否常态稽核
			$data_a['normalcy_code'] = $arr['isnormality']; //常态稽核
			$data_a['is_zh'] = $arr['isComplex']; //是否综合稽核
			$data_a['type_code_all'] = $arr['ComplexValue']; //综合稽核量
			$data_a['relax_limit'] = 10;
			$data_a['expenese_num'] = 50;

			$this->db->insert('k_user_audit',$data_a);
	    }
	    return $this->db->trans_complete();
	}

		//批量入款合并执行版
   public function cash_operation($arr){
        //账号过滤
        $usernames = "'".str_replace(",","','",$arr['username'])."'";//字符串元素加单引号
	    $usernames = strtr($usernames, array(' '=>''));//清除空格
	    $db_model = array();
	    $db_model['tab'] = 'k_user';
	    $db_model['type'] = 1;
	    $uids = $this->M($db_model)->field('username,index_id,uid,level_id,agent_id')->where("username in ($usernames) and site_id = '".SITEID."' and shiwan = '0' and is_delete = '0'")->select('uid');
	    if (empty($uids)) {
	        return 'E0';
	        die();
	    }


	    $uid_in = array_keys($uids);
        $uid_in = implode(',',array_keys($uids));

        $now_date = date('Y-m-d H:i:s');
        //事务开始

        $this->db->trans_start();
        $all_money = $arr['amount'] + $arr['sp_other'] + $arr['spAmount'];

        //更新会员余额
        $update_money_sql = "update k_user set money=(case uid ";
        foreach ($uids as $key => $val) {
            $update_money_sql .= " when ".$val['uid']."  THEN money+$all_money ";
        }
        $update_money_sql .=  " else money end) WHERE uid IN($uid_in)  and site_id = '".$_SESSION['site_id']."'";
        $this->db->query($update_money_sql);

        //获取更改后用户余额
        $u_money_sql="select uid,money from k_user where uid in($uid_in) and site_id='".$_SESSION['site_id']."'";
        $u_money = $this->db->query($u_money_sql)->result_array();

        $datac = $data_c = array();
        foreach ($u_money as $k => $v) {

            $val = $uids[$v['uid']];
            //写入人工记录数据
	        $datac[$k]['site_id'] = $_SESSION['site_id'];
	        $datac[$k]['index_id'] = $val['index_id'];
	        $datac[$k]['uid'] = $val['uid'];
	        $datac[$k]['agent_id'] = $val['agent_id'];
	        $datac[$k]['username'] = $val['username'];
	        $datac[$k]['type'] = 1;
	        $datac[$k]['balance'] = $v['money'];//会员余额
	        $datac[$k]['catm_money'] = $arr['amount'];
	        $datac[$k]['catm_give'] = $arr['sp_other'];
	        $datac[$k]['atm_give'] = $arr['spAmount'];
	        $datac[$k]['is_code_count'] = $arr['isComplex'];
	        $datac[$k]['code_count'] = $arr['ComplexValue'];
	        $datac[$k]['routine_check'] = $arr['isnormality'];
	        $datac[$k]['catm_type'] = $arr['type_memo'];
	        $datac[$k]['updatetime'] = $now_date;
	        $datac[$k]['is_rebate'] = $arr['c_is_rebate'];
	        $datac[$k]['remark'] = $arr['remark'];
	        $datac[$k]['do_admin_id'] = $_SESSION["login_name"];

	        //写入现金记录数据
	        $cash_type = 12;
	        $cash_do_type = 1;
	        if ($arr['type_memo'] == 1 or $arr['type_memo'] == 4) {
                    $cash_do_type = 3;
            } elseif ($arr['type_memo'] == 7) {
                    $cash_type = 9; //表示现金系统的优惠退水
                    $cash_only = 1;
            } elseif ($arr['type_memo'] == 2 || $arr['type_memo'] == 8) {
                    $cash_only = 1;
            }

            //负数额度归0 其他属于优惠
            if ($arr['type_memo'] == '3' || $arr['type_memo'] == '5') {
                $cash_num = 0; //存款金额
                $cash_only = 1;
                $discount_num = $arr['amount'] + $arr['sp_other'] + $arr['spAmount']; //优惠总额
            }else{
                $cash_num = $arr['amount']; //存款金额
                $discount_num = $arr['sp_other'] + $arr['spAmount']; //优惠总额
            }

	        $data_c[$k]['site_id'] = $_SESSION['site_id'];
	        $data_c[$k]['index_id'] = $val['index_id'];
	        $data_c[$k]['source_id'] = '';
	        $data_c[$k]['uid'] = $val['uid'];
	        $data_c[$k]['username'] = $val['username'];
	        $data_c[$k]['source_type'] = 3;
	        $data_c[$k]['agent_id'] = $val['agent_id'];
	        $data_c[$k]['cash_date'] = $now_date;
	        $data_c[$k]['cash_type'] = $cash_type;
	        $data_c[$k]['cash_do_type'] = $cash_do_type;
	        $data_c[$k]['cash_balance'] = $v['money'];
	        $data_c[$k]['cash_num'] = $cash_num;
	        $data_c[$k]['discount_num'] = $discount_num;
	        $data_c[$k]['cash_only'] = $cash_only;
	        $data_c[$k]['remark'] = $arr['remark'];

            //稽核数据
            $data_au[$k]['uid'] = $val['uid'];
            $data_au[$k]['username'] = $val['username'];
            $data_au[$k]['site_id'] = $_SESSION['site_id'];
            $data_au[$k]['begin_date'] = $now_date;
            $data_au[$k]['deposit_money'] = $arr['amount'];
            $data_au[$k]['atm_give'] = $arr['sp_other'];
            $data_au[$k]['catm_give'] = $arr['sp_other'];
            $data_au[$k]['source_id'] = '';
            $data_au[$k]['source_type'] = 1;
            $data_au[$k]['type'] = 1;
            $data_au[$k]['is_ct'] = $arr['is_isnormality'];
            $data_au[$k]['normalcy_code'] = $arr['isnormality'];
            $data_au[$k]['is_zh'] = $arr['isComplex'];
            $data_au[$k]['type_code_all'] = $arr['ComplexValue'];
            $data_au[$k]['relax_limit'] = 10;
            $data_au[$k]['expenese_num'] = 50;

        }
        //人工记录插入
        $this->db->query($this->madd_sql($datac,'k_user_catm'));
        // $sid = $this->db->insert_id();//数据源ID当多条同时插入的时候获取的是第一条id

        //现金记录插入
        $this->db->query($this->madd_sql($data_c,'k_user_cash_record'));

        //获取所有稽核状态
        $is_audit_sql = "select id,uid from k_user_audit where uid in($uid_in) and site_id='".$_SESSION['site_id']."' and type=1  order by id desc";
        $is_audit_arr = $this->db->query($is_audit_sql)->result_array();

        //获取稽核是否锁定
        $is_audit_lock_sql = "select uid from k_user_bank_out_record where uid in($uid_in) and out_status in (0,4) and site_id='".$_SESSION['site_id']."'";
        $is_audit_lock_arr = $this->db->query($is_audit_lock_sql)->result_array();
        //二维转一维
        $is_audit_lock_arr = array_column($is_audit_lock_arr,'uid');

        $is_audit_arr_new = array();
        //筛选出需要更新上一次终止时间的id(有稽核状态且稽核锁定里面没有的用户)
        foreach ($is_audit_arr as $k => $v) {
        	if (!in_array($v['uid'],$is_audit_lock_arr)) {
                $is_audit_arr_new[$v['id']] = $v['uid'];
        	}
        }

        //存在即更新上一次终止时间
        if (!empty($is_audit_arr_new)) {

            $is_audit_id_in = implode(',',array_keys($is_audit_arr_new));
            $update_audit_sql = "update k_user_audit set end_date='$now_date' where id in($is_audit_id_in)";
            $this->db->query($update_audit_sql);
        }
        //稽核数据插入
        $this->db->query($this->madd_sql($data_au,'k_user_audit'));

        return $this->db->trans_complete();
   }

    //按层级批量入款,获取该层级所有会员名称
    public function GetUserName($tier){
        $db_model['tab'] = 'k_user';
        $db_model['type'] = 1;
        $map ="site_id = '".$_SESSION['site_id']."' and level_id = ".$tier;
        return $this->M($db_model)->field('username')->where($map)->getField('username',True);
    }

}