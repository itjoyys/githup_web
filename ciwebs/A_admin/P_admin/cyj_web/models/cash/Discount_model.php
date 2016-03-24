<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Discount_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//按照股东返水
	public function Dis_agentsh($pid,$date,$level,$index_id,$type){
	    $this->load->model('cash/Agentsh_bet_model');

      $ARR = $this->Agentsh_bet_model->agentsh_bet_all($pid,$date,$type,$index_id);

      //会员信息
      $users = array_keys($ARR);

      $user_arr_data = array();
      $user_arr_data = $this->get_user_data($users,$index_id,$level);

      $agents = $this->Agentsh_bet_model->get_agentsid($pid,$index_id);

      $agents_arr = $this->get_agent_data($pid,$index_id);

      //优惠信息
      $discount = $this->map_dis($index_id);

      //获取自助返水数据
      $selfdata = $this->get_self_user_data($date);

      //层级信息
      $level_arr = $this->get_user_level($index_id);

      $i = 0;
      $redis_dis = 'discount_'.$_SESSION['site_id'].'_'.$index_id;
      $hset = array();
      foreach ($ARR as $key => $val) {
          $j = $j + 1;
          $tmp_user = str_replace('u-','',$key);

          if (empty($user_arr_data[$tmp_user])) {
              continue;
          }
          $user_data = $user_arr_data[$tmp_user];
          $userIds[$i]['username'] = $tmp_user;
          $userIds[$i]['level_id'] = $user_data['level_id'];
          $userIds[$i]['level_des'] = $level_arr[$user_data['level_id']]['level_des'];
          $userIds[$i]['agent_id'] = $user_data['agent_id'];
          $userIds[$i]['uid'] = $user_data['uid'];
          $userIds[$i]['agent_user'] = $agents_arr[$user_data['agent_id']]['agent_user'];

          $userIds[$i]['betall'] = $val['fc_bet'] + $val['sp_cg_bet'] + $val['sp_bet'];
          foreach ($type as $kt => $vt) {
              if ($vt == 'mg') {
          	      $userIds[$i]['betall'] += $val['mg_bet'] + $val['mgdz_bet'];//总有效投注
          	  }elseif($vt == 'bbin'){
          	  	  $userIds[$i]['betall'] += $val['bbin_bet'] + $val['bbdz_bet']+$val['bbsp_bet']+$val['bbfc_bet'];//总有效投注
          	  }else{
          	  	  $userIds[$i]['betall'] += $val[$vt.'_bet'];
          	  }
              //$userIds[$i]['betall'] += $val[$v.'_bet'];//总有效投注
          }

             //获取返点优惠
          foreach ($discount as $dt => $dv) {
              //存在该层级优惠
              if ($dv['level_id'] == $user_data['level_id']) {
                  if ($dv['count_bet'] <= $userIds[$i]['betall']) {
                       $re = $dv;
                       break;
                   }
              }elseif(!$dv['level_id']){
                  if ($dv['count_bet'] <= $userIds[$i]['betall']) {
                       $re = $dv;
                       break;
                   }
              }
          }

          unset($val['player_name']);
          unset($val['member']);
          unset($val['account_number']);
          unset($val['member_id']);
          $userIds[$i]['fc_bet'] = $val['fc_bet']+0;
          $userIds[$i]['sp_bet'] = $val['sp_bet']+$val['sp_cg_bet']+0;
          $userIds[$i]['fc_fd'] = sprintf("%.2f",$val['fc_bet']*$re['fc_discount']*0.01);
          $userIds[$i]['sp_fd'] = sprintf("%.2f",$userIds[$i]['sp_bet']*$re['sp_discount']*0.01);

          $userIds[$i]['total_e_fd'] = $userIds[$i]['fc_fd'] +$userIds[$i]['sp_fd'];
          //自助返水数据匹配
          if ($selfdata && $selfdata[$user_data['uid']] && $selfdata[$user_data['uid']]['total_e_fd']) {
              $userIds[$i]['self_fd'] = $selfdata[$user_data['uid']]['total_e_fd'];
          }else{
              $userIds[$i]['self_fd'] = 0;
          }

          foreach ($type as $k => $v) {

             //  $userIds[$i]['betall'] += $val[$v.'_bet'];//总有效投注
              $userIds[$i][$v.'_bet'] = sprintf("%.2f",($val[$v.'_bet']+0));
              $userIds[$i][$v.'_fd'] = sprintf("%.2f",$val[$v.'_bet']*$re[$v.'_discount']*0.01);
              $userIds[$i]['total_e_fd'] += $userIds[$i][$v.'_fd'];//总计返点

              if ($v == 'mg') {
                  $userIds[$i]['mgdz_bet'] = sprintf("%.2f",($val['mgdz_bet']+0));
                  $userIds[$i]['mgdz_fd'] = sprintf("%.2f",$val['mgdz_bet']*$re['mgdz_discount']*0.01);
                  $userIds[$i]['total_e_fd'] += $userIds[$i]['mgdz_fd'];
              }elseif($v == 'bbin'){
                  $userIds[$i]['bbdz_bet'] = sprintf("%.2f",($val['bbdz_bet']+0));
                  $userIds[$i]['bbdz_fd'] = sprintf("%.2f",$val['bbdz_bet']*$re['bbdz_discount']*0.01);
                  $userIds[$i]['bbsp_bet'] = sprintf("%.2f",($val['bbsp_bet']+0));
                  $userIds[$i]['bbsp_fd'] = sprintf("%.2f",$val['bbsp_bet']*$re['bbsp_discount']*0.01);

                  $userIds[$i]['bbfc_bet'] = sprintf("%.2f",($val['bbfc_bet']+0));
                  $userIds[$i]['bbfc_fd'] = sprintf("%.2f",$val['bbfc_bet']*$re['bbfc_discount']*0.01);

                  $userIds[$i]['total_e_fd'] += $userIds[$i]['bbdz_fd'];
                  $userIds[$i]['total_e_fd'] += $userIds[$i]['bbsp_fd'];
                  $userIds[$i]['total_e_fd'] += $userIds[$i]['bbfc_fd'];
              }

              //返点上限判断
              if ($userIds[$i]['total_e_fd'] > $re['max_discount']) {
                  $userIds[$i]['total_e_fd'] = $re['max_discount'];
              }

          }
          //扣除掉会员自助返水金额
          $userIds[$i]['total_e_fd'] = $userIds[$i]['total_e_fd'] - $userIds[$i]['self_fd'] ;

          $countJson = json_encode($userIds[$i], JSON_UNESCAPED_UNICODE);
          $countJson = str_replace('"', '-', $countJson);
          $userIds[$i]['countJson'] = $countJson;
          $hset[$user_data['uid']] = $countJson;
          $i++;
      }
      unset($ARR);
      unset($user_arr_data);
      unset($agents_arr);
      unset($agents);
      $redis = new Redis();
      $redis->connect(REDIS_HOST,REDIS_PORT);
      $redis->hmset($redis_dis,$hset);
      return $userIds;
	}

  //获取返点优惠
  public function map_dis($index_id){
      $map_dis = array();
      $map_dis['site_id'] = $_SESSION['site_id'];
      if (!empty($index_id)) {
          $map_dis['index_id'] = $index_id;
      }
      $map_dis['is_delete'] = 0;
      $db_model = array();
      $db_model['tab'] = 'k_user_discount_set';
      $db_model['type'] = 1;
      return $this->M($db_model)->where($map_dis)->order('count_bet desc,level_id desc')->select();
  }

  //获取层级信息
  public function get_user_level($index_id){
      $db_model = $map = array();
      $db_model['tab'] = 'k_user_level';
      $db_model['type'] = 1;
      $db_model['is_port'] = 1;//读取从库
      $map['site_id'] = $_SESSION['site_id'];
      $map['is_delete'] = 0;
      if (!empty($index_id)) {
           $map['index_id'] = $index_id;
      }

      return $this->M($db_model)->field("id,level_des")->where($map)->select('id');
  }

  //获取会员信息
  public function get_user_data($users,$index_id,$level = ''){
      //$users = substr_replace($users, '', 0,1);
      $users = str_replace('u-','',$users);
      $users = "('".implode("','",$users)."')";

      $db_model = $map_user = array();
      $map_user['username'] = array('in',$users);
      $map_user['site_id'] = $_SESSION['site_id'];
      $map_user['index_id'] = $index_id;

      if (!empty($level)) {
          $map_user['level_id'] = array('in','('.implode(',', $level).')');
      }

      $db_model['tab'] = 'k_user';
      $db_model['type'] = 1;
      $db_model['is_port'] = 1;//读取从库
      return $this->M($db_model)->field("uid,username,agent_id,level_id")->where($map_user)->select('username');
  }

    //获取自助返水的会员
  public function get_self_user_data($date = array()){
      $db_model = $map = array();
      $db_model['tab'] = 'k_user_self_fd';
      $db_model['type'] = 1;

      $map['site_id'] = $_SESSION['site_id'];
      $str = '';
      if ($date[0] == $date[1]) {
          $map['order'] = str_replace('-','',$date[0]);
          //$map['order'] = date('Ymd');
          $str = 'uid,total_e_fd';
          return $this->M($db_model)->field($str)->where($map)->select('uid');
      }else{
          $date[0] = str_replace('-','',$date[0]);
          $date[1] = str_replace('-','',$date[1]);

          $map['order'] = array('>=',$date[0]);
          $map['order'] = array('<=',$date[1]);

          $str = 'uid,sum(total_e_fd) as total_e_fd';
          return $this->M($db_model)->field($str)->where($map)->group('uid')->select('uid');
      }
  }

  //获取代理数据
  public function get_agent_data($pid,$index_id){
      $map = array();
      $map_arr = array();
      // $map['pid'] = $pid;
      // $map['index_id'] = $index_id;
      // $map['site_id'] = $_SESSION['site_id'];
      // $map['is_demo'] = 0;
      // if (!empty($index_id)) {
      //     $map['index_id'] = $index_id;
      // }

      $obj = $this->M(array('tab'=>'k_user_agent','type'=>1));

      // $sh_id = $obj->field("id")->where($map)->select("id");
      // if (empty($sh_id)) {
      //     return false;
      // }

      // $sh_id = implode(',',array_keys($sh_id));
      // $map_arr['pid'] = array('in','('.$sh_id.')');
      $map_arr['site_id'] = $_SESSION['site_id'];
      $map_arr['is_demo'] = 0;
      if (!empty($index_id)) {
          $map_arr['index_id'] = $index_id;
      }
      return $obj->field("agent_user,id")->where($map_arr)->select('id');
  }

  //获取优惠数据
  public function get_dis_save($arr,$index_id){
      $redis = new Redis();
      $redis->connect(REDIS_HOST,REDIS_PORT);

      $redis_dis = 'discount_'.$_SESSION['site_id'].'_'.$index_id;
      foreach ($arr as $key => $val) {
          $vdata = $redis->hmget($redis_dis,array($val));
          $user_data = str_replace('-', '"', $vdata[$val]);
          $user_data = json_decode($user_data, true);

          $ccc[$val] = $user_data;
      }
      return $ccc;
  }

  //返水写入
  public function dis_save($uid,$data){
      $user_dis = $this->get_dis_save($uid,$data['index_id']);

      $typeArr = array('fc','sp','ag','mg','pt','mgdz','bbin','bbdz','og','ct','lebo','eg');
            //统一时间
      $nowDate = date('Y-m-d H:i:s');
      $data['addtime'] = $nowDate;

      foreach ($user_dis as $k => $v) {
          $data['total_fd'] += $v['total_e_fd'];
          $data['money'] += $v['total_e_fd'];
          $data['totalbet'] += $v['betall'];
          foreach ($typeArr as $kt => $vt) {
               //打码
              $bField = 'to'.$vt.'bet';
              $b_Field = $vt.'_bet';
              if ($v[$b_Field] > 0) {
                $data[$bField] += $v[$b_Field];
              }

              $fField = 'to'.$vt.'fd';
              $f_Field = $vt.'_fd';
              //返点
              if ($v[$f_Field] > 0) {
                $data[$fField] += $v[$f_Field];
              }

          }
      }

      //写入返水
      //$this->db->trans_start();
      $this->db->trans_begin();

      $this->db->insert('k_user_discount_search',$data);
      $kds_id = $this->db->insert_id();

      $data_all = array();
      foreach ($user_dis as $key => $val) {
          //屏蔽额度0
          if (empty($val['total_e_fd'])) {
              continue;
          }
          //会员明细写入
          $data_all = $val;
          unset($data_all['level_id']); // 删除不要的数据
          unset($data_all['money']); // 删除不要的数据
          $data_all['date'] = $data['dis_date'];
          $data_all['do_time'] = $nowDate;
          $data_all['kds_id'] = $kds_id;
          $data_all['site_id'] = $_SESSION['site_id'];
          $data_all['index_id'] = $data['index_id'];
          $this->db->insert('k_user_discount_count',$data_all);
          $sou_id = $this->db->insert_id();

          //更新会员余额
          $u_sql = "update k_user set money = money + ". $val['total_e_fd'] . " where uid = '" .$val['uid']. "' and site_id = '".$_SESSION['site_id']."' ";
          $this->db->query($u_sql);

          if (!$this->db->affected_rows()) {
              $this->db->trans_rollback();
              return false;
          }

           //如果综合打码不为0
          if (!empty($data['bet'])) {
              //写入稽核记录
              // $l_audit = $this->get_user_audit($val['uid']);
              // if (!empty($l_audit)) {
              //     //存在更新终止时间
              //     $this->db->where(array('id'=>$l_audit['id']))->update('k_user_audit',array('end_date'=>$nowDate));
              // }

              $datae = array();
              $datae['username']=$val['username'];
              $datae['site_id'] = $_SESSION['site_id'];
              $datae['uid'] = $val['uid'];
              $datae['begin_date'] = $nowDate;
              $datae['type'] = 1;
              $datae['is_zh'] = 1;//有综合稽核
              $datae['is_ct'] = 0;//无常态稽核
              $datae['relax_limit'] = 10;
              $datae['source_id'] = $sou_id;//稽核数据来源id
              $datae['source_type'] = 4;//优惠退水稽核
              $datae['catm_give'] = $val['total_e_fd'];
              $datae['type_code_all'] = $data['bet']*$val['total_e_fd'];
              $this->db->insert('k_user_audit',$datae);
          }

           // 现金系统写入记录
          $map_um = array();
          $map_um['table'] = 'k_user';
          $map_um['select'] = 'money';
          $map_um['where']['uid'] = $val['uid'];
          $map_um['where']['site_id'] = $_SESSION['site_id'];
          $useMoney = $this->rfind($map_um);

          $dataR = array();
          $dataR['uid'] = $val['uid'];
          $dataR['username'] = $val['username'];
          $dataR['agent_id'] = $val['agent_id'];
          $dataR['site_id'] = $_SESSION['site_id'];
          $dataR['index_id'] = $data['index_id'];
          $dataR['cash_balance'] = $useMoney['money'];// 用户当前余额;
          $dataR['cash_date'] = $nowDate;
          $dataR['cash_type'] = 9;
          $dataR['cash_do_type'] = 1;
          $dataR['discount_num'] = $val['total_e_fd']; // 返点金额
          $dataR['remark'] = $data['back_time_start'].'-'.$data['back_time_end'].'(美东),優惠退水';
          $this->db->insert('k_user_cash_record',$dataR);

          // 发送用户信息
          $dataM = array();
          $dataM['type'] = 2;
          $dataM['site_id'] = $_SESSION['site_id'];
          $dataM['index_id'] = $data['index_id'];
          $dataM['uid'] = $val['uid'];
          $dataM['level'] = 2;
          $dataM['msg_title'] = $data['back_time_start'].'-'.$data['back_time_end']."(美东)優惠退水" . $val['total_e_fd'] . "元";
          $dataM['msg_info'] = $val['username'] . "優惠退水" . $val['total_e_fd'] . "元 账户余额" .$useMoney['money']. "元 祝您游戏愉快！";
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

  //获取会员稽核信息
  public function get_user_audit($uid){
      $map = array();
      $map['table'] = 'k_user_audit';
      $map['select'] = 'id';
      $map['where']['uid'] = $uid;
      $map['where']['site_id'] = $_SESSION['site_id'];
      $map['where']['type'] = 1;
      $map['order'] = 'id DESC';

      return $this->rfind($map);
  }

  //获取会员明细
  public function get_discount_user_list($map){
      $db_model = array();
      $db_model['tab'] = 'k_user_discount_count';
      $db_model['type'] = 1;

      return $this->M($db_model)->field("id,uid,username,site_id,date,level_des,agent_user,agent_id,username,kds_id,betall,sp_bet,fc_bet,pt_bet,eg_bet,ag_bet,og_bet,mg_bet,mgdz_bet,ct_bet,bbin_bet,bbdz_bet,bbsp_bet,bbfc_bet,lebo_bet,sp_fd,fc_fd,pt_fd,eg_fd,ag_fd,og_fd,mg_fd,mgdz_fd,ct_fd,bbin_fd,bbdz_fd,bbsp_fd,bbfc_fd,lebo_fd,total_e_fd,self_fd,state")->where($map)->select();
  }

  //会员冲销
  public function discount_cx($arr,$sid,$rtitle,$zh){

      $no_people = count($arr);
      $typeArr = array('fc','sp','ag','pt','mg','mgdz','bbin','bbdz','bbsp','bbfc','og','ct','lebo','eg');
      //$this->db->trans_start();
      $this->db->trans_begin();

      foreach ($arr as $key => $val) {
          $val = str_replace('-', '"', $val);
          $val = json_decode($val, true);

          //更新会员余额
          $u_sql = "update k_user set money = money - ". $val['total_e_fd'] . " where uid = '" .$val['uid']. "' and site_id = '".$_SESSION['site_id']."' ";
          $this->db->query($u_sql);

          if (!$this->db->affected_rows()) {
              $this->db->trans_rollback();
              return false;
          }

          //写入现金记录
          $map_um = array();
          $map_um['table'] = 'k_user';
          $map_um['select'] = 'money';
          $map_um['where']['uid'] = $val['uid'];
          $map_um['where']['site_id'] = $_SESSION['site_id'];
          $u_money = $this->rfind($map_um);

          $dataR = array();
          $dataR['uid'] = $val['uid'];
          $dataR['username'] = $val['username'];
          $dataR['agent_id'] = $val['agent_id'];
          $dataR['site_id'] = $_SESSION['site_id'];
          $dataR['cash_balance'] = $u_money['money'];// 用户当前余额;
          $dataR['cash_date'] = date('Y-m-d H:i:s');
          $dataR['cash_type'] = 13;//表示优惠冲销类别
          $dataR['cash_do_type'] = 2;//表示取出类别
          $dataR['cash_num'] = $val['total_e_fd']; // 冲销金额
          $dataR['remark'] = $rtitle.'(美东) 優惠冲销';
          $this->db->insert('k_user_cash_record',$dataR);

          //更新返水状态为冲销
          $this->db->where(array('id'=>$val['id'],'site_id'=>$_SESSION['site_id']))->update('k_user_discount_count',array('state'=>2));

          //更新总数和余额
          $data_s = array();
          $data_s['no_people_num'] = array('+','1');//冲销人数
          $data_s['people_num'] = array('-','1');//返水人数
          $data_s['money'] = array('-',$val['total_e_fd']);
          $data_s['totalbet'] = array('-',$val['betall']);

          foreach ($typeArr as $kt => $vt) {
              $sField = 'to'.$vt.'bet';
              $s_Field = 'to'.$vt.'fd';
              $eField = $vt.'_bet';
              $e_Field = $vt.'_fd';
              if ($val[$eField] > 0) {
                  $data_s[$sField] = array('-',$val[$eField]);
              }
              if ($val[$e_Field] > 0) {
                  $data_s[$s_Field] = array('-',$val[$e_Field]);
              }
          }

          $map_uf = array();
          $map_uf['sql'] = 1;
          $map_uf['table'] = 'k_user_discount_search';
          $map_uf['where'] = " id = '".$sid."' and site_id = '".$_SESSION['site_id']."' ";
          $data_s['total_fd'] = array('-',$val['total_e_fd']);
          $this->rupdate($map_uf,$data_s);

         //判断是否有综合稽核
          if (!empty($zh)) {
              //有综合稽核的去掉综合稽核
              $data_z = array();
              $data_z['is_zh'] = 0;//更改综合稽核状态为0
              $data_z['type_code_all'] = 0;
              $data_z['catm_give'] = 0;
              $this->db->where(array('source_id'=>$val['id'],'site_id'=>$_SESSION['site_id'],'source_type'=>4))->update('k_user_audit',$data_z);
          }
      }

      if ($this->db->trans_status() === TRUE) {
          $this->db->trans_commit();
          return TRUE;
      } else {
          $this->db->trans_rollback();
          return false;
      }
  }

    //获取视讯配置
   public function get_video_config(){
       $db_model = array();
       $db_model['tab'] = 'web_config';
       $db_model['type'] = 1;

       $data = $this->M($db_model)->where(array('site_id'=>$_SESSION['site_id']))->getField('video_module');

       if ($data) {
           return explode(',',$data);
       }else{
           return 0;
       }
   }

   public function get_self_fd($site_id,$date,$index_id='',$username='',$order=''){
       $this->private_db->from('k_user_self_fd');
       $this->private_db->select('*,sum(total_e_fd) as total_e_fd');
       $this->private_db->where('site_id',$site_id);
       $this->private_db->where('do_time >=', $date['start_date']."00:00:00");
       $this->private_db->where('do_time <=', $date['end_date']."23:59:59");
       if(!empty($index_id)){
           $this->private_db->where('index_id', $index_id);
       }
       if(!empty($username)){
           $this->private_db->like('username', $username);
       }
       if(!empty($order)){
           $this->private_db->where('order', $order);
       }
       $this->private_db->group_by('username');
       $this->private_db->order_by('do_time', 'DESC');

       return $this->private_db->get()->result_array();
   }

   public function get_user_fd_list($uid,$title){
       $this->private_db->from('k_user_self_fd_log');
       $this->private_db->where('uid', $uid);
       $this->private_db->where('do_time >=', $title['back_time_start']."00:00:00");
       $this->private_db->where('do_time <=', $title['back_time_end']."23:59:59");
       return $this->private_db->get()->result_array();
   }


}