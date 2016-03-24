<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Bank_record_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//获取公司入款
	public function get_bankin_line($map,$limit){
		$db_model['tab'] = 'k_user_bank_in_record';
		$db_model['type'] = 1;
		$thisdata=$this->M($db_model)->where($map)->limit($limit)->order('k_user_bank_in_record.id DESC,k_user_bank_in_record.make_sure ASC')->select();
                return $this->get_bankin_line2($thisdata);

        }
        //公司入款处理
        public function get_bankin_line2($data){
            $db_model['tab'] = 'k_bank';
            $db_model['type'] = 1;
            $db_model['is_port'] = 1;
            $k_bank = $this->M($db_model)->field("id,card_ID,bank_type,card_userName,remark")->where(array('site_id'=>$_SESSION['site_id']))->select('id');
            foreach ($data as $k => $v){
                $data[$k]['card_ID']       = $k_bank[$v['bid']]['card_ID'];
                $data[$k]['bank_type']     = $k_bank[$v['bid']]['bank_type'];
                $data[$k]['card_userName'] = $k_bank[$v['bid']]['card_userName'];
                $data[$k]['remark']        = $k_bank[$v['bid']]['remark'];
            }
            return $data;
        }

   //判断订单状态是否已更改
	public function is_state($id,$state = 0){
        $map['table'] = 'k_user_bank_in_record';
	    $map['where']['id'] = $id;
	    $map['where']['site_id'] = $_SESSION['site_id'];
	    $map['where']['make_sure'] = $state;
	    return $this->rfind($map);
	}

    //取消入款
    public function bank_cancel($map,$arr,$index_id,$num){
    	 //$this->db->trans_start();
         $this->db->trans_begin();
         $this->db->where($map)->update('k_user_bank_in_record',$arr);
         if (!$this->db->affected_rows()) {
             $this->db->trans_rollback();
             return false;
         }

         //发送会员消息
         $dataM = array();
		 $dataM['type'] = 3;
		 $dataM['site_id'] = $_SESSION['site_id'];
		 $dataM['index_id'] = $index_id;
		 $dataM['uid'] = $map['uid'];
		 $dataM['level'] = 2;
		 $dataM['msg_title'] = '公司入款被取消';
		 $dataM['msg_info'] = '尊敬的会员您好,公司入款:' . $num . "被取消,详情联系24小时在线客服,祝您游戏愉快！";
		 $this->db->insert('k_user_msg',$dataM);
         if ($this->db->trans_status() === TRUE) {
             $this->db->trans_commit();
             return TRUE;
          } else {
             $this->db->trans_rollback();
             return false;
          }
     	 // return $this->db->trans_complete();
    }

    //确定公司入款
    public function bank_in_do($id,$uid,$index_id,$num){
    	//获取会员信息
    	$db_model['tab'] = 'k_user';
		$db_model['type'] = 1;
        $userMoney = $this->M($db_model)
		           ->field("username,level_id,agent_id,money,(money+ag_money+og_money+mg_money+ct_money+lebo_money+bbin_money) as sum_money")
		           ->where("uid = '".$uid."'")
		           ->find();//会员余额

        $db_model['tab'] = 'k_user_level';
        $db_model['is_port'] = 1;
		$level_des = $this->M($db_model)
		           ->field("RMB_pay_set")
		           ->where("id = '".$userMoney['level_id']."'")
		           ->find();

		//稽核对应参数
		$db_model['tab'] = 'k_cash_config_view';
		$aud = $this->M($db_model)->where("id = '".$level_des['RMB_pay_set']."'")->find();
	    //订单信息
	    $db_model['tab'] = 'k_user_bank_in_record';
	    $iMoney = $this->M($db_model)->field('deposit_money,deposit_num,order_num,favourable_num,other_num,do_time,make_sure,ptype')->where("id = '".$id."'")->find();//会员存入总金额

		$now_date = date('Y-m-d H:i:s');
        //事务开启
		//$this->db->trans_start();
        $this->db->trans_begin();

        //公司入款日志表
        $datalog = array();
        $datalog['order_num'] = $num;
        $datalog['site_id'] = $_SESSION['site_id'];
        $datalog['uid'] = $uid;
        $datalog['index_id'] = $index_id;
        $datalog['log_time'] = $now_date;
        $datalog['deposit_money'] = $iMoney['deposit_money'];
        $datalog['admin_user'] = $_SESSION['login_name'];
        $this->db->insert('k_user_bank_in_record_log',$datalog);
        //更新会员余额
		$u_sql = "UPDATE `k_user` SET `money` = money + '".$iMoney['deposit_money']."' WHERE `uid` = '".$uid."'";
        $this->db->query($u_sql);
        if (!$this->db->affected_rows()) {
            $this->db->trans_rollback();
            return false;
        }

        //获取最新余额
        $this->db->select('money');
	    $user_now = $this->db->get_where("k_user",array('uid'=>$uid,'site_id'=>$_SESSION['site_id']))->row_array();

        //更新订单状态
	    $data_bin=array();
        $data_bin['make_sure'] = 1;
        $data_bin['do_time'] = $now_date;
        $data_bin['admin_user'] = $_SESSION['login_name'];
        $log_2 = $this->db->where(array('id'=>$id))
                 ->update('k_user_bank_in_record',$data_bin);
        if (!$this->db->affected_rows()) {
            $this->db->trans_rollback();
            return false;
        }

        //写入现金系统
    	$data_c=array();
        $data_c['source_id'] = $id;
        $data_c['source_type'] = 1;//表示公司线上入款
        $data_c['site_id'] = $_SESSION['site_id'];
        $data_c['index_id'] = $index_id;
        $data_c['uid'] = $uid;
        $data_c['agent_id'] = $userMoney['agent_id'];
        $data_c['username'] = $userMoney['username'];
        $data_c['cash_date'] = $now_date;
        $data_c['cash_type'] = 11;
        $data_c['cash_do_type'] = 1;
        $data_c['cash_balance'] = $user_now['money'];//当前余额
        $data_c['cash_num'] = $iMoney['deposit_num'];
        $data_c['discount_num'] = $iMoney['favourable_num']+$iMoney['other_num'];//优惠总额
        $data_c['remark'] = $num;//备注
        $data_c['ptype'] = $iMoney['ptype'];//备注
        $this->db->insert('k_user_cash_record',$data_c);

        //稽核处理
	    //更新上一个稽核的终止时间
	    $db_model['tab'] = 'k_user_audit';
        $l_audit = $this->M($db_model)->field("id")->where("type = 1 and site_id = '".$_SESSION['site_id']."' and uid = '".$uid."' ")->order("id desc")->find();
        //判断稽核是否锁定
        $is_audit_lock = $this->is_audit_lock($uid);

        //启动清除稽核
		if (($userMoney['sum_money'] < $aud['line_ct_fk_audit']) && !empty($l_audit) && empty($is_audit_lock)) {
			 //更新稽核最后一笔结束时间
            $this->db->where(array('id'=>$l_audit['id']))
                 ->update('k_user_audit',array('end_date'=>$now_date));

            $map_audit_c = array();
			$map_audit_c['site_id'] = $_SESSION['site_id'];
			$map_audit_c['id <='] = $l_audit['id'];
			$map_audit_c['type'] = 1;
			$map_audit_c['uid'] = $uid;
		    $this->db->where($map_audit_c)->update('k_user_audit',array('type'=>2));

		    //写入稽核日志
		    $data_auto = array();
	        $data_auto['update_date'] = $now_date;
	        $data_auto['uid'] = $uid;
	        $data_auto['site_id'] = $_SESSION['site_id'];
	        $data_auto['username'] = $userMoney['username'];
	        $data_auto['content'] = '會員：'.$userMoney['username'].' 餘額小於放寬額度 清除稽核點 ('.$now_date.'之前的稽核點已清除)';
		    $this->db->insert('k_user_audit_log',$data_auto);

		}elseif (!empty($l_audit) && $l_audit['id']) {
            //存在即更新上一次终止时间
            $this->db->where(array('id'=>$l_audit['id']))
                 ->update('k_user_audit',array('end_date'=>$now_date));
		}

	    $data_a = array();
	    $data_a['source_type'] = 2;
	    $data_a['source_id'] = $id;
	    $data_a['uid'] = $uid;
	    $data_a['type'] = 1;
	    $data_a['is_ct'] = $aud['ol_is_ct_audit'];//常态稽核
	    $data_a['is_zh'] = $aud['ol_is_zh_audit'];//综合稽核
	    $data_a['site_id'] = $_SESSION['site_id'];
	    $data_a['deposit_money'] = $iMoney['deposit_num'];//存款金额没优惠
	    $data_a['username'] = $userMoney['username'];
	    $data_a['begin_date'] = $now_date;
	    $data_a['relax_limit'] = $aud['line_ct_fk_audit'];//放宽额度
	    if ($aud['ol_is_ct_audit']) {
	        $data_a['normalcy_code'] = $aud['line_ct_audit']*$iMoney['deposit_num']*0.01;//常态稽核
	    }
	    if ($aud['ol_is_zh_audit']) {
	        $data_a['type_code_all'] = $aud['ol_zh_audit']*($iMoney['deposit_num']+$iMoney['other_num']+$iMoney['favourable_num']);//综合打码
	    }

	    $data_a['atm_give'] = $iMoney['other_num'];//汇款优惠
	    $data_a['catm_give'] = $iMoney['favourable_num'];//存款优惠
	    $data_a['expenese_num'] = $aud['line_ct_xz_audit'];//行政费率
	    $this->db->insert('k_user_audit',$data_a);

	     // 发送用户信息
        $dataM = array();
		$dataM['type'] = 3;//表示出入款类型
		$dataM['site_id'] = $_SESSION['site_id'];
		$dataM['uid'] = $uid;
		$dataM['index_id'] = $index_id;
		$dataM['level'] = 2;
		$dataM['msg_title'] =  $userMoney['username'].','."公司入款";
		$dataM['msg_info'] = $userMoney['username'].','."公司入款" . $iMoney['deposit_num'] .'元,其他優惠：'.$iMoney['other_num']. "元成功, 祝您游戏愉快！";
		$this->db->insert('k_user_msg',$dataM);

		if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            return TRUE;
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }

    //判断稽核
	public function is_audit_lock($uid){
		$map_lock = array();
        $map_lock['site_id'] = $_SESSION['site_id'];
        $map_lock['uid'] = $uid;
        $map_lock['out_status'] = array('in','(0,4)');

        $db_model = array();
        $db_model['tab'] = 'k_user_bank_out_record';
        $db_model['type'] = 1;
        return $this->M($db_model)->where($map_lock)->find();
	}

    //查询上一次稽核
    function last_audit($uid){
    	$db_model = array();
    	$db_model['tab'] = 'k_user_audit';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;
        return M($db_model)->field("id")->where("uid = '".$uid."' and type = '1' and site_id = '".$_SESSION['site_id']."'")->order("id desc")->find();
    }

    //线上入款
    public function get_bankin_online($map,$limit){
        $db_model['tab'] = 'k_user_bank_in_record';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;
        $thisdata = $this->M($db_model)->where($map)->limit($limit)->order('in_date DESC')->select();
        return $this->get_bankin_online2($thisdata);

    }
    //线上入款处理
    public function get_bankin_online2($data){
        $db_model['tab'] = 'k_user_level';
        $db_model['type'] = 1;
        $db_model['is_port'] = 1;
        $level = $this->M($db_model)->field("id,level_des")->where(array('site_id'=>$_SESSION['site_id']))->select('id');
        foreach ($data as $k => $v){
            $data[$k]['level_des'] = $level[$v['level_id']]['level_des'];
        }
        return $data;
    }

    //获取银行卡信息
    public function get_bank_type(){
        $redis = new Redis();
	    $redis->connect(REDIS_HOST,REDIS_PORT);
	    //$vdata = $redis->hmget('bank_type',array($type));
	    if (empty($vdata[$type])) {
	        //redis中数据为空 从数据库读取
	        $db_model['tab'] = 'k_bank_cate';
		    $db_model['type'] = 1;
            $mdata = $this->M($db_model)->where("state = 1")->select('id');
            foreach ($mdata as $key => $val) {
                $bank_json = json_encode($val, JSON_UNESCAPED_UNICODE);
		        $bank_json = str_replace('"', '-', $bank_json);
		        $hset[$key] = $bank_json;
            }
		    $redis->hmset('bank_type',$hset);
		    $bank_name = $mdata;
	    }else{
            $vdata = str_replace('-', '"', $vdata);
            $vdata = json_decode($vdata[$type],true);//转数组
            $bank_name = $vdata['bank_name'];
	    }
	    return $bank_name;
    }

	//获取第三方信息
    public function get_online_type(){
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        //$vdata = $redis->hmget('pay_type',array($type));
        if (empty($vdata[$type])) {
            //redis中数据为空 从数据库读取
            $db_model['tab'] = 'k_online_bank_cate';
                $db_model['type'] = 1;
        $mdata = $this->M($db_model)->where("state = 1")->select('id');
        //var_dump($mdata);
        foreach ($mdata as $key => $val) {
            $bank_json = json_encode($val, JSON_UNESCAPED_UNICODE);
                    $bank_json = str_replace('"', '-', $bank_json);
                    $hset[$key] = $bank_json;
        }
                $redis->hmset('pay_type',$hset);
                $online_bank_name = $mdata;
        }else{
        $vdata = str_replace('-', '"', $vdata);
        $vdata = json_decode($vdata[$type],true);//转数组
        $online_bank_name = $vdata['online_bank_name'];
        }

        return $online_bank_name;
    }


    //将数据存入redis
    public function in_data_redis($bdata) {
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $redis_key = $_SESSION['site_id'].$_SESSION["adminid"].'_bankin_data';
        $redis->delete($redis_key);
        foreach ($bdata as $key => $val) {
            $excData = json_encode($val, JSON_UNESCAPED_UNICODE);
            $excData = str_replace('"', '--', $excData);
            $hset[$key] = $excData;
        }
        $redis->hmset($redis_key,$hset);
    }

    //读取redis
    public function out_data_redis() {
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $redis_key = $_SESSION['site_id'].$_SESSION["adminid"].'_bankin_data';
        $data = $redis->hgetall($redis_key);
        foreach ($data as $key => $val) {
            $val = str_replace('--', '"', $val);
            $excel[$key] = json_decode($val,true);
        }
        return $excel;
    }

    public function get_pay_id($index_id,$paytype){
        $db_model['tab'] = 'pay_set';
        $db_model['type'] = 1;
        $map['site_id'] = $_SESSION['site_id'];
        $map['is_delete'] = 0;
        if(!empty($index_id)){
            $map['index_id'] = $index_id;
        }
        $data = $this->M($db_model)->field("id,pay_type")->where($map)->select();
        return $data;
    }



}