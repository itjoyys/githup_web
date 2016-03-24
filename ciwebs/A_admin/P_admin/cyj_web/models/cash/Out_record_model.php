<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Out_record_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//获取线上出款数据
    public function get_out($map,$limit){
            $db_model['tab'] = 'k_user_bank_out_record';
            $db_model['type'] = 1;
            $thisdata = $this->M($db_model)->where($map)->limit($limit)->order("out_time desc")->select();
            return $this->get_out2($thisdata);
    }
    //处理线上出款数据
    public function get_out2($data){
            $db_model['tab'] = 'k_user_level';
            $db_model['type'] = 1;
            $user_level = $this->M($db_model)->field('id,level_name,level_des')->select('id');
            foreach ($data as $k => $v){
                $data[$k]['level_name'] = $user_level[$v['level_id']]['level_name'];
                $data[$k]['level_des'] = $user_level[$v['level_id']]['level_des'];
            }
            return $data;
    }

    //总计
    public function all_report($map){
		$db_model['tab'] = 'k_user_bank_out_record';
		$db_model['type'] = 1;
		return $this->M($db_model)->join("left join k_user_level on k_user_level.id = k_user_bank_out_record.level_id")->field("sum(k_user_bank_out_record.charge) as charge,sum(k_user_bank_out_record.favourable_num) as favourable_num,sum(k_user_bank_out_record.expenese_num) as expenese_num,sum(k_user_bank_out_record.outward_money) as outward_money")->where($map)->find();
    }
    //最近三笔入款
    public function get_user_3($uid){
    	$db_model['tab'] = 'k_user_cash_record';
		$db_model['type'] = 1;
		return $this->M($db_model)->field("cash_num,cash_type")->where("uid = '".$uid."' and (cash_type = '10' or cash_type = '11' or cash_type = '12')")->order("cash_date desc")->limit("3")->select();
    }

    //取消出款
    public function bank_out_cancel($arr){
        $now_date = date('Y-m-d H:i:s');

		$this->db->trans_begin();
    	//会员余额更新
		$u_sql = "UPDATE `k_user` SET `money` = money + '".$arr['outward_num']."' WHERE `uid` = '".$arr['uid']."' and site_id = '".$_SESSION['site_id']."' ";
        $this->db->query($u_sql);
        if (!$this->db->affected_rows()) {
            $this->db->trans_rollback();
            return false;
        }

	    //会员余额
	    $map_um = array();
        $map_um['table'] = 'k_user';
        $map_um['select'] = 'money,index_id,agent_id';
        $map_um['where']['uid'] = $arr['uid'];
        $map_um['where']['site_id'] = $_SESSION['site_id'];
        $useMoney = $this->rfind($map_um);

	    $data_c=array();
	    $data_c['source_id'] = $arr['id'];
	    $data_c['site_id'] = $_SESSION['site_id'];
	    $data_c['uid'] = $arr['uid'];
	    $data_c['index_id'] = $useMoney['index_id'];
	    $data_c['agent_id'] = $useMoney['agent_id'];
	    $data_c['username'] = $arr['username'];
	    $data_c['cash_date'] = $now_date;
	    $data_c['cash_type'] = 23;
	    $data_c['cash_do_type'] = 1;
	    $data_c['cash_balance'] = $useMoney['money'];//当前余额
	    $data_c['cash_num'] = $arr['outward_num'];
	    $data_c['remark'] = $arr['order_num'];//备注
	    $this->db->insert('k_user_cash_record',$data_c);

	       //出款状态更新
        $data_o = array();
        $data_o['out_status'] = 3;
        $data_o['do_time'] = $now_date;
        $data_o['admin_user'] = $_SESSION['login_name'];
        $data_o['remark'] = $arr['remarks'];//取消备注
        $this->db->where(array('id'=>$arr['id'],'site_id'=>$_SESSION['site_id']))->update('k_user_bank_out_record',$data_o);
        if (!$this->db->affected_rows()) {
            $this->db->trans_rollback();
            return false;
        }

        if ($this->db->trans_status() === TRUE) {
            $this->db->trans_commit();
            return TRUE;
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }

    //确定入款 拒绝出款
    public function bank_out_do($arr,$type = 1,$audit = array()){
        $now_date = date('Y-m-d H:i:s');

    	$this->db->trans_begin();
        //拒绝出款更新现金系统记录
	    if($type == 2){
	        $this->db->where(array('source_id'=>$arr['id'],'site_id'=>$_SESSION['site_id'],'source_type'=>'4'))
	             ->update('k_user_cash_record',array('remark'=>$arr['order_num'].',拒绝出款'));
		 }

	       //出款状态更新
	     $data_o = array();
	     $data_o['out_status'] = $type;
	     $data_o['do_time'] = $now_date;
	     $data_o['admin_user'] = $_SESSION['login_name'];
	     $this->db->where(array('id'=>$arr['id'],'site_id'=>$_SESSION['site_id']))->update('k_user_bank_out_record',$data_o);

         if (!$this->db->affected_rows()) {
             $this->db->trans_rollback();
             return false;
         }

	       //稽核状态更新
	     if(!empty($audit)){
             $this->db->where('uid',$arr['uid']);
             $this->db->where_in('id',$audit);
		     $this->db->update('k_user_audit',array('type'=>2));

		     //更新稽核最后一笔结束时间
		     $this->db->where(array('id'=>$audit[0],'site_id'=>$_SESSION['site_id']))->update('k_user_audit',array('end_date'=>$arr['out_time']));
		 }

	       //发送消息 //只有成功出款才发消息
	     if ($type == 1) {
		     $dataM = array();
		     $dataM['type'] = 3;//表示出入款类型
			 $dataM['site_id'] = $_SESSION['site_id'];
			 $dataM['uid'] = $arr['uid'];
			 $dataM['level'] = 2;
			 $dataM['msg_title'] = "出款成功";
			 $dataM['msg_info'] = '订单'.$arr['order_num']."，出款金额:".$arr['outward_num'].'扣除金额:'.($arr['outward_num']-$arr['outward_money']).", 祝您游戏愉快！";

		     $this->db->insert('k_user_msg',$dataM);
		 }
	     if ($this->db->trans_status() === TRUE) {
             $this->db->trans_commit();
             return TRUE;
          } else {
             $this->db->trans_rollback();
             return false;
          }
    }

    //获取稽核
    public function get_audit($uid,$end_date){
        $db_model = array();
        $db_model['tab'] = 'k_user_audit';
        $db_model['type'] = 1;
        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['type'] = 1;
        $map['uid'] = $uid;
        $map['begin_date'] = array('<=',$end_date);
        return $this->M($db_model)->field('id')->order('id DESC')->where($map)->select('id');
    }

    //将数据存入redis
    public function in_data_redis($hset) {
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $redis_key = $_SESSION['site_id'].$_SESSION["adminid"].'_bankout_data';
        $redis->delete($redis_key);
        $redis->hmset($redis_key,$hset);
    }

    //读取redis
    public function out_data_redis() {
        $redis = new Redis();
        $redis->connect(REDIS_HOST,REDIS_PORT);
        $redis_key = $_SESSION['site_id'].$_SESSION["adminid"].'_bankout_data';
        $data = $redis->hgetall($redis_key);
        foreach ($data as $key => $val) {
            $val = str_replace('--', '"', $val);
            $excel[$key] = json_decode($val,true);
        }
        return $excel;
    }

      //获取层级信息
    public function get_level_data(){
        $db_model = array();
        $db_model['tab'] = 'k_user_level';
        $db_model['type'] = 1;

        $map = array();
        $map['site_id'] = $_SESSION['site_id'];
        $map['is_delete'] = 0;

        return $this->M($db_model)->field("id,site_id,is_delete,level_name,level_des")->where($map)->select();
    }

}