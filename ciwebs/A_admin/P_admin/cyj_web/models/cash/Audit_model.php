<?php if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Audit_model extends MY_Model {

	function __construct() {
		parent::__construct();
	}

	//会员稽核
	public function get_user_audit($uid,$level_id,$username,$type,$end_date){
        $map = array();
        $map['table'] = 'k_user_audit';
        $map['where']['uid'] = $uid;
        $map['where']['type'] = 1;
        if (!empty($end_date)) {
            $map['where']['begin_date <='] = $end_date;
        }

        $map['where']['site_id'] = $_SESSION['site_id'];
        $map['order'] = 'id desc';
	      $audit_all = $this->rget($map);

        if (empty($audit_all)) {
            $map['select'] = 'end_date';
            unset($map['where']['type']);

            $enddate = $this->rfind($map);

            if (empty($enddate)) {
                $enddate['end_date'] = '2015-06-01 00:00:00';
            }
            $audit_all[0]['begin_date'] = $enddate['end_date'];
            $audit_all[0]['end_date'] = date('Y-m-d H:i:s');
            $audit_all[0]['deposit_money'] = 0;
            $audit_all[0]['username'] = $username;
            $audit_all[0]['uid'] = $uid;

            $audit_all[0]['is_ct'] = 0;
            $audit_all[0]['is_zh'] = 0;
            $fc_arr['bet0'] = 0;
            $sp_arr[0]['bet0'] = 0;
            $sp_arr[1]['bet0'] = 0;
            $video_arr['ag']['bet0'] = $video_arr['og']['bet0'] = 0;
            $video_arr['mg']['bet0'] = $video_arr['ct']['bet0'] = 0;
            $video_arr['pt']['bet0'] = $video_arr['lebo']['bet0'] = 0;
            $video_arr['bbin']['bet0'] = 0;
        }else{
            $fc_arr = $this->fc_user_bet($audit_all,$end_date,$uid);
            $sp_arr = $this->sp_user_bet($audit_all,$end_date,$uid);
            $video_arr = $this->video_user_bet($audit_all,$end_date,$username);
        }

        $cdis;//扣除的所有优惠
        //稽核判断
        foreach ($audit_all as $key => $v) {
       	 //综合稽核判断
       	    $zh_state = 0;//综合稽核
       	    $ct_state = 0;//常态稽核

       	    $fc_bet = array();
       	    $sp_bet = array();
            $video = array();
       	//  $at_allbet = 0;
       	    // if (empty($v['end_date'])) {
       	  	 //    $audit_all[$key]['end_date']=$v['end_date'] = date('Y-m-d H:i:s');
       	    // }
            //结束时间为下一次开始时间
            if ($key) {
                $i = $key-1;
                $audit_all[$key]['end_date'] = $audit_all[$i]['begin_date'];
            }else{
                $audit_all[$key]['end_date'] = $end_date;
            }

            //$audit_all[$key]['end_date'] = $v['end_date'];

            $fc_bet = $fc_arr['bet'.$key] + 0;//彩票打码
            $sp_bet = $sp_arr[0]['bet'.$key] + $sp_arr[1]['bet'.$key]+ 0;//彩票打码
            $video  = $video_arr['ag']['bet'.$key] + $video_arr['og']['bet'.$key] + $video_arr['mg']['bet'.$key] +  $video_arr['ct']['bet'.$key] + $video_arr['lebo']['bet'.$key] + $video_arr['bbin']['bet'.$key] +$video_arr['pt']['bet'.$key] + 0;
            $at_allbet = $fc_bet + $sp_bet + $video;//总计打码

            //return $username;


            $audit_all[$key]['bet_all']=$at_allbet;
            if ($key>0) {
          	    $jkey = $key-1;
	          	if ($audit_all[$jkey]['is_pass_zh']) {
	          	 	$audit_all[$key]['zh_bet'] = $audit_all[$jkey]['zh_bet'] - $audit_all[$jkey]['type_code_all']+$at_allbet;//当笔综合打码
	          	 }else{
	                $audit_all[$key]['zh_bet'] = $audit_all[$jkey]['zh_bet']+$at_allbet;//当笔综合打码
	          	 }

	          	 //常态
	          	 if ($audit_all[$jkey]['is_pass_ct']) {
	          	 	$audit_all[$key]['ct_bet'] = $audit_all[$jkey]['ct_bet'] - $audit_all[$jkey]['normalcy_code']+$at_allbet;//当笔综合打码
	          	 }else{
	                $audit_all[$key]['ct_bet'] = $audit_all[$jkey]['ct_bet']+$at_allbet;//当笔综合打码
	          	 }
          }else{
              $audit_all[$key]['ct_bet'] = $at_allbet+10;
          	  $audit_all[$key]['zh_bet'] = $at_allbet;
          }
              $audit_all[$key]['cathectic_sport'] = $sp_bet;
              $audit_all[$key]['cathectic_fc'] = $fc_bet;
              $audit_all[$key]['cathectic_video'] =$video;


          $dis = $v['catm_give'] + $v['atm_give'];//所有优惠
          //当笔稽核盈利扣除比例
          $base_money = $dis + $v['deposit_money'];//存款总金额
          $win_money = $fc_bet['win'] + $sp_bet['win'];//总计盈利

       	  if ($v['is_zh']  == '1') {
       	   	//有综合稽核
              $return_audit = $this->zh_audit($v['type_code_all'],$audit_all[$key]['zh_bet'] ,$dis);

	          if ($return_audit['state'] == '0') {
	               //表示综合稽核没有通过
	               $cdis += $dis;//扣除所有优惠
	               $audit_all[$key]['deduction_e'] = $dis;//扣除所有优惠
	               $audit_all[$key]['is_pass_zh'] = 0;//是否通过综合稽核
	               $zh_state = 0;
	            }else{

	               $audit_all[$key]['is_pass_zh'] = 1;//是否通过综合稽核
	               $zh_state = 1;
	            }
       	 }else{
       	 	$audit_all[$key]['is_pass_zh'] = 2;//没有综合稽核
       	 	$audit_all[$key]['type_code_all'] = 0;
       	 	$zh_state = 1;
       	 }

       	 //常态稽核判断
       	 if ($v['is_ct'] == '1') {
       	 	//有常态稽核
       	 	//判断存款金额 是否大于最低放宽额度
       	 	$return_ct = $this->ct_audit($v['deposit_money'],$v['relax_limit'],$v['expenese_num'],$audit_all[$key]['ct_bet'] ,$v['normalcy_code']);
       	 	if ($return_ct['state'] == '0') {
       	 		//没有通过常态稽核
       	 		$audit_all[$key]['is_pass_ct'] = 0;//没有通过常态稽核
       	 		$audit_all[$key]['deduction_xz'] = $return_ct['money'];//扣除行政费用
       	 		$audit_all['count_xz'] += $return_ct['money'];//扣除所有行政费用
       	 		if ($v['is_zh'] == '0') {
       	 		   $audit_all[$key]['deduction_e'] = $dis;
       	 		   $cdis += $dis;
       	 		}
       	 		$audit_all[$key]['deduction_e'] += $return_ct['money'];
       	 		$audit_all[$key]['is_expenese_num'] = 1;
                $ct_state = 0;
       	 	}else{
       	 		$audit_all[$key]['is_pass_ct'] = 1;//通过常态稽核
       	 		$ct_state = 1;
       	 		$audit_all[$key]['is_expenese_num'] = 0;
       	 	}
       	 }else{
       	 	//没有常态稽核
            $ct_state = 1;
       	 	$audit_all[$key]['is_pass_ct'] = 2;//不需要常态稽核
       	 	$audit_all[$key]['normalcy_code'] = '-';
       	 	$audit_all[$key]['relax_limit'] ='-';
       	 	$ct_state = 1;
       	 	$audit_all[$key]['is_expenese_num'] = 2;
       	 }

       	 //盈利扣除判断
       	 if ($zh_state && $ct_state) {
       	 	//全部稽核通过不扣除
       	 	$audit_all[$key]['de_wind'] = 0;
       	 }else{
            if ($win_money > 0 && $base_money > 0) {
            	//盈利了扣除
            	$audit_all[$key]['de_wind'] = $win_money*($dis/$base_money);
            	$audit_all[$key]['deduction_e'] += $audit_all[$key]['de_wind'];//每笔稽核扣除金额
            }else{
            	$audit_all[$key]['de_wind'] = 0;
            }
       	 }
       	 //起始稽核到当前时间所有有效打码
       	 $audit_all['bet_all'] += $at_allbet;
       	 $cdis += $audit_all[$key]['de_wind'];
       }
       $audit_all['count_dis'] = $cdis;//扣除所有优惠
       $audit_all['out_fee'] = $this->out_user_fee($uid,$level_id);//出款手续费

       return $audit_all;
	}

  //联合
  public function un_all(){
      $sql = "select count(uid) as num from k_user union all select count(*) as num from c_bet";

      $ddd = $this->db->query($sql);
      return $ddd->result_array();
  }

    //综合稽核判断
  public function zh_audit($tc,$ab,$dis){
  	 $dataA = array();
  	 if ($tc > $ab) {
     	    	//总有效打码 小于 综合打码
     	    	$dataA['dis'] = $dis;//扣除所有优惠
     	    	$dataA['bet'] = $ab;//当笔有效打码 传递到下笔
     	    	$dataA['state'] = 0;//综合稽核未通过
     	  }else{
     	    	$dataA['dis'] = 0;
     	    	$dataA['bet'] = $ab - $tc;
     	    	$dataA['state'] = 1;//综合稽核通过
     	  }
     	  return $dataA;
  }
  //常态稽核
  public function ct_audit($ak,$ac,$az,$total,$ct){
     //判断存款金额 是否大于最低放宽额度
     if ($total < $ct) {
     	      //总打码 小于常态稽核
     	      $ctdata['state'] = 0;//没有通过常态稽核
     	      $ctdata['money'] = $ak*$az*0.01;//扣除行政费用
     	   }else{
            $ctdata['state'] = 1;//通过常态稽核
     	   }
     return $ctdata;
  }

	//稽核日志记录
	public function get_audit_log($map,$limit){
		 $db_model['tab'] = 'k_user_audit_log';
		 $db_model['type'] = 1;
	     return $this->M($db_model)->where($map)->limit($limit)->order("id DESC")->select();
	}

      //彩票
  public function fc_user_bet($arr,$e_time,$uid){
      $cstr = '';
      $icount = count($arr);
      foreach ($arr as $key => $val) {
          //结束时间为上一次开始时间
          if ($key) {
              $i = $key-1;
              $val['end_date'] = $arr[$i]['begin_date'];
          }else{
              $val['end_date'] = $e_time;
          }

          $cstr .= "sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then money end) as bet".$key.',';
          //取出最大区间起始时间
          if ($icount == ($key + 1)) {
              $s_time = $val['begin_date'];
          }
      }
      $cstr .= 'uid';

      $db_model = array();
      $db_model['tab'] = 'c_bet';
      $db_model['type'] = 1;

      $mapFc = array();
      $mapFc['uid'] = $uid;
      $mapFc['site_id'] = $_SESSION['site_id'];
      $mapFc['status'] = array('in','(1,2)');
        //启用自动分表
      $beginDate = date("Y-m-d H:i:s", strtotime("$s_time -48 hours"));
      $mapFc['addtime'] = array(
                        array('>=',$beginDate),
                        array('<=',$e_time)
                        );

      return $this->M($db_model)->field($cstr)->where($mapFc)->find();
  }

  //体育
  public function sp_user_bet($arr,$e_time,$uid){
      $cstr = $cstr_cg = '';
      foreach ($arr as $key => $val) {
          //结束时间为上一次开始时间
          if ($key) {
              $i = $key-1;
              $val['end_date'] = $arr[$i]['begin_date'];
          }else{
              $val['end_date'] = $e_time;
          }
          $cstr .= "sum(case when update_time >= '".$val['begin_date']."' and update_time <= '".$val['end_date']."' then bet_money end) as bet".$key.',';
      }
      $cstr .= 'uid';

      $db_model = array();
      $db_model['tab'] = 'k_bet';
      $db_model['type'] = 1;

      $mapSp = array();
      $mapSp['uid'] = $uid;
      $mapSp['site_id'] = $_SESSION['site_id'];
      $mapSp['status'] = array('in','(1,2,4,5)');
      $sp_arr[0] = $this->M($db_model)->field($cstr)->where($mapSp)->find();

      $db_model['tab'] = 'k_bet_cg_group';
      unset($mapSp['status']);
      $mapSp['status'] = array('in','(1,2)');
      $sp_arr[1] = $this->M($db_model)->field($cstr)->where($mapSp)->find();
      return $sp_arr;
  }

  //视讯
  public function video_user_bet($arr,$e_time,$username){
      $db_model = array();
      $db_model['type'] = 3;
      //视讯字段匹配
      //注单时间 有效下注 盈利
      $vtype = array('ag'=>array('bet_time','valid_betamount'),
                     'og'=>array('add_time','valid_amount'),
                     'mg'=>array('update_time','income','payout'),
                     'ct'=>array('transaction_date_time','availablebet'),
                     'lebo'=>array('betstart_time','valid_betamount','payout'),
                     'bbin'=>array('wagers_date','commissionable','payoff'),
                     'pt'=>array('GameDate','Bet','Bet','Win'));
      $icount = count($arr);
      $s_time = '';
      //视讯数据读取
      foreach ($vtype as $k => $v) {
          $db_model['tab'] = $k.'_bet_record';
          $cstr = '';
          foreach ($arr as $key => $val) {

              //结束时间为上一次开始时间
              if ($key) {
                  $i = $key-1;
                  $val['end_date'] = $arr[$i]['begin_date'];
              }else{
                  $val['end_date'] = $e_time;
              }


              //时间转换
              if ($k === 'og' || $k === 'pt') {
                  $val['begin_date'] = date("Y-m-d H:i:s", strtotime("$val[begin_date] +12 hours"));
                  $val['end_date'] = date("Y-m-d H:i:s", strtotime("$val[end_date] +12 hours"));
              }elseif($k === 'ct'){
                  $val['begin_date'] = date("Y-m-d H:i:s", strtotime("$val[begin_date] +11 hours"));
                  $val['end_date'] = date("Y-m-d H:i:s", strtotime("$val[end_date] +11 hours"));
              }

              //取出最大区间起始时间
              if ($icount == ($key + 1)) {
                  $s_time = $val['begin_date'];
              }

               //特殊时间处理
              if ($key) {
              }else{
                  $e_time = $val['end_date'];
              }

              $cstr .= 'sum(case when '.$v[0]." >= '".$val['begin_date']."' and ".$v[0]." <= '".$val['end_date']."' then ".$v[1]." end) as bet".$key.',';
          }
          $cstr .= 'pkusername';

          $mapv = array();
          $mapv['pkusername'] = $username;
          $mapv['site_id'] = $_SESSION['site_id'];
          $mapv[$v[0]] = array(array('>=',$s_time),array('<=',$e_time));
          //单个视讯条件判断
          switch ($k) {
            case 'bbin':
              $mapv['result_type'] = array('<>','-1');
              $mapv['result'] = array('<>','D');
              $mapv['result'] = array('<>','-1');
              $mapv['Commissionable'] = array('>',0);//会员有效投注额
              break;
            case 'og':
              $mapv['result_type'] = array('in','(1,2)');
              break;
            case 'mg':
              break;
            case 'ag':
              $mapv['flag'] = 1;
              break;
            case 'ct':
              $mapv['is_revocation'] = 1;
              break;
            case 'lebo':
              break;
            case 'pt':
              $map['Bet + Win'] = array('>',0);
              break;
          }
          $video_arr[$k] = $this->M($db_model)->field(' /* parallel */ '.$cstr)->where($mapv)->find();
      }
      return $video_arr;
  }

    //获取会员手续费
  public function out_user_fee($uid,$level_id){
      $db_model = array();
      $db_model['tab'] = 'k_user_level';
      $db_model['type'] = 1;
      $pay_id = $this->M($db_model)->where("id='".$level_id."' and site_id = '".$_SESSION['site_id']."'")->getField('RMB_pay_set');
      $db_model['tab'] = 'k_cash_config_view';
      if (empty($pay_id)) {
         $pay_data = $this->M($db_model)->field("is_fee_free,fee_free_num,repeat_hour_num,out_fee")
             ->where("site_id ='".$_SESSION['site_id']."' and type='1'")
             ->find();
      }else{
         $pay_data = $this->M($db_model)->field("is_fee_free,fee_free_num,repeat_hour_num,out_fee")
             ->where("id='".$pay_id."' and site_id = '".$_SESSION['site_id']."'")
             ->find();
      }
      //开启免手续费次数
      if($pay_data['is_fee_free']==1){
          //获取当日累计出款次数
          //$endTime = date('Y-m-d H:i:s',(time()-$pay_data['repeat_hour_num']*60*60));
          $endTime = date("Y-m-d H:i:s", strtotime("-24 hours"));
          $map_ou = array();
          $map_ou['uid'] = $uid;
          $map_ou['site_id'] = $_SESSION['site_id'];
          $map_ou['out_status'] = 1;
          $map_ou['out_time'] = array('>=',$endTime);
          $db_model['tab'] = 'k_user_bank_out_record';
          $count_out = $this->M($db_model)->where($map_ou)->count();
          if($pay_data['fee_free_num'] <= $count_out){
             $out_fee=$pay_data['out_fee'];
          }else{
             $out_fee=0;
          }
      }else{
          $out_fee=$pay_data['out_fee'];
      }
      return $out_fee;
  }

}