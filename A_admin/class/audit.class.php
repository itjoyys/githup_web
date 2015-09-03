<?php
/**
 *
 *稽核处理中心
 * 综合未达到扣除存款优惠 和汇款优惠
 * 常态未达到扣除本金的 百分比  还有所有优惠
 */
class audit {
    public $zhBet;//综合打码量
    public $fcBet;//彩票有效打码
    public $spBet;//体育有效打码
    public $videoBet;//视讯有效打码
    public $allBet;//总计有效打码
    public $is_zh;//是否综合稽核
    public $is_normalcy;//是否常态稽核
    public $deposit_money;//存入金额
    public $ck_discount;//存款优惠金额
    public $hk_discount;//汇款优惠金额
    public $relax_money;//放宽额度
    public $uid;//会员id
    protected $deduction_d;//总计扣除的优惠金额
    protected $deduction_xz;//总计扣除的行政费
    public $fc_status = '(1,2)';//彩票有效状态
    public $sp_status = '(1,2,4,5)';//体育有效状态
    public $sp_cg_status = '(1,2)';//体育串关状态.
    public $audit_admin_html;//后台输出样式
    public $audit_index_html;//前台输出样式


    public function __construct($uid){
    	$this->uid = $uid;
    }
    //获取会员所有稽核存款信息
    public function get_user_audit(){
       global $db_config;
       global $dbv_config;
       include (dirname(__FILE__). "/userBet.class.php");
       $audit_all = M('k_user_audit',$db_config)
            ->where("uid = '".$this->uid."' and type = '1' ")
            ->order("id desc")
            ->select();
       if (empty($audit_all)) {
          $end_date = M('k_user_audit',$db_config)
                    ->where("uid = '".$this->uid."'")
                    ->order("id desc")
                    ->find();
          $username = M('k_user',$db_config)
                    ->where("uid = '".$this->uid."'")
                    ->getField("username");
          if (empty($end_date)) {
             $end_date['end_date'] = '2015-06-01 00:00:00';
          }
          $audit_all[0]['begin_date'] = $end_date['end_date'];
          $audit_all[0]['end_date'] = date('Y-m-d H:i:s');
          $audit_all[0]['deposit_money'] = 0;
          $audit_all[0]['username'] = $username;
          $audit_all[0]['uid'] = $this->uid;
       }
        $cdis;//扣除的所有优惠
       //稽核判断
       foreach ($audit_all as $key => $v) {
       	 //综合稽核判断
       	  $zh_state = 0;//综合稽核
       	  $ct_state = 0;//常态稽核
         
       	  $at_fcbet = array();
       	  $at_spbet = array();
       	//  $at_allbet = 0;
       	  if (empty($v['end_date'])) {
       	  	// $v['end_date'] = date('Y-m-d H:i:s');
       	  	 $audit_all[$key]['end_date']=$v['end_date'] = date('Y-m-d H:i:s');
       	  }
       	  $at_fcbet = $this->FcBet($v['begin_date'],$v['end_date'],$v['uid']);//彩票打码
       	  $at_spbet = $this->SpBet($v['begin_date'],$v['end_date'],$v['uid']);//体育打码
       	  //视讯打码
       	  $VideoBet = 0;
          $VideoBet = userBet::userVideoBet($v['username'],$v['begin_date'],$v['end_date'],$dbv_config);
          $at_allbet = $at_fcbet['fcbet'] + $at_spbet['spbet']
                       +$VideoBet;
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
          $audit_all[$key]['cathectic_sport'] = $at_spbet['spbet'];
          $audit_all[$key]['cathectic_fc'] = $at_fcbet['fcbet'];
          $audit_all[$key]['cathectic_video'] = $VideoBet;
          $dis = $v['catm_give'] + $v['atm_give'];//所有优惠
          //当笔稽核盈利扣除比例
          $base_money = $dis + $v['deposit_money'];//存款总金额
          $win_money = $at_fcbet['win'] + $at_spbet['win'];//总计盈利
          
       	 if ($v['is_zh']  == '1') {
       	   	//有综合稽核  
            $return_audit = $this->zh_audit($v['type_code_all'],$audit_all[$key]['zh_bet'] ,$dis);

            if ($return_audit['state'] == '0') {
               //表示综合稽核没有通过
               $cdis += $dis;//扣除所有优惠
               $audit_all[$key]['deduction_e'] = $dis;//扣除所有优惠
               $audit_all[$key]['is_pass_zh'] = 0;//是否通过综合稽核
               $zh_state = 0;
               //有效投注叠加
	           // $kkey = $key+1;
	           // if ($kkey != count($audit_all)) {
	           //     	  //非最后一笔稽核
	           //      $audit_all[$kkey]['bet_all'] = $return_audit['bet'];
	           // }
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
       $audit_all['out_fee'] = $this->out_user_fee($this->uid);//出款手续费
       return $audit_all;
    }


    //获取会员当笔稽核期间彩票有效打码量
    public function FcBet($s_date,$e_date,$uid){
       global $db_config;
       $map_fc=array();
       $map_fc['uid'] = $uid;
       if (!empty($s_date) && !empty($e_date)) {
          $map_fc['addtime'] = array(
       	                    array('>=',$s_date),
       	                    array('<=',$e_date)
       	                  );
        }
       $map_fc['status'] = array('in',$this->fc_status);
       $fcbet = M('c_bet',$db_config)
              ->field("sum(money) as fcbet,sum(win) as fcwin")
              ->where($map_fc)
              ->find();
       $fc_bet = array();
       $fc_bet['fcbet'] = $fcbet['fcbet'];
       $win_tmp = $fcbet['fcbet'] - $fcbet['fcwin'];//彩票盈利
       $fc_bet['win'] = $fcbet['fcwin'] - $fcbet['fcbet'];//彩票盈利
       return $fc_bet;
    }


     //获取会员当笔稽核期间体育有效打码量
    public function SpBet($s_date,$e_date,$uid){
       global $db_config;
       $map_sp=array();
       $map_sp['uid'] = $uid;
       if (!empty($s_date) && !empty($e_date)) {
          $map_sp['bet_time'] = array(
       	                    array('>=',$s_date),
       	                    array('<=',$e_date)
       	                  );
       }
       $map_sp['status'] = array('in',$this->sp_status); // 表示赢或者输
       $sp_bet = M('k_bet', $db_config)
                ->field("sum(bet_money) as spbet,sum(win) as spwin")
                ->where($map_sp)
                ->find();
       
       //体育串关
       $map_sp['status'] = array('in',$this->sp_cg_status);
       $sp_bet_cg = M('k_bet_cg_group',$db_config)
                 ->field("sum(bet_money) as spbet,sum(win) as spwin")
                 ->where($map_sp)
                 ->find();
       $spdata = array();
       $spdata['spbet'] = $sp_bet['spbet'] + $sp_bet_cg['spbet'];
       $spdata['win'] = $sp_bet['spwin'] + $sp_bet_cg['spwin']-$spdata['spbet'];//体育盈利
       return $spdata;
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
       // if ($ak <= $ac) {
       // 	   //存款金额小于放宽 稽核通过
       // 	   $ctdata['state'] = 1;//通过常态稽核
       // }else{
       // 	   if ($total < $ct) {
       // 	      //总打码 小于常态稽核
       // 	      $ctdata['state'] = 0;//没有通过常态稽核
       // 	      $ctdata['money'] = $ak*$az*0.01;//扣除行政费用
       // 	   }else{
       //        $ctdata['state'] = 1;//通过常态稽核
       // 	   }
       // }
       return $ctdata;
    }

    //综合稽核状态返回
    public function zh_state($st){
    	switch ($st) {
    		case '0':
    			return "<font color=\"#ff0000\">否</font>";
    			break;
    		case '1':
    			return "<font color=\"#00cc00\">是</font>";
    			break;
    		case '2':
    			return "不需要稽核";
    			break;
    	}
    }

    //扣除行政费用状态
    public function xz_state($xz){
    	switch ($xz) {
    		case '0':
    			return "<font color=\"#ff0000\">否</font>";
    			break;
    		case '1':
    			return "<font color=\"#00cc00\">是</font>";
    			break;
    		case '2':
    			return "不需要稽核";
    			break;
    	}
    }

    //常态稽核状态返回
    public function ct_state($ct){
    	switch ($ct) {
    		case '0':
    			return "<font color=\"#ff0000\">未通過</font>";
    			break;
    		case '1':
    			return "<font color=\"#00cc00\">通過</font>";
    			break;
    		case '2':
    			return "-";
    			break;
    	}
    }

    //样式头部
    public function header_html(){
    	$header_html = "<table width=\"99%\" class=\"m_tab\">
  <tbody><tr class=\"m_title\">
    <td class=\"TdB\" rowspan=\"2\" width=\"150\">存款日期區間</td>
    <td class=\"TdB\" rowspan=\"2\" width=\"55\">存款金额</td>
    <td class=\"TdB\" rowspan=\"2\" width=\"55\">存款优惠</td>
    <td class=\"TdB hide1\" width=\"230\" colspan=\"3\">實際有效投注額</td>
    <td class=\"TdR hide2\" width=\"470\" colspan=\"8\">優惠稽核</td>
    <td class=\"TdG\" width=\"340\" colspan=\"5\">常態稽核</td>
  </tr>
  <tr class=\"m_title\">
  	<td class=\"TdB hide1\" width=\"45\">體育</td>    
    <td class=\"TdB hide1\" width=\"45\">彩票</td>
    <td class=\"TdB hide1\" width=\"45\">視訊</td>
    <td class=\"TdR hide2\" width=\"70\">體育打碼</td>
    <td class=\"TdR hide2\" width=\"35\">通過</td>
    <td class=\"TdR hide2\" width=\"70\">彩票打碼</td>
    <td class=\"TdR hide2\" width=\"35\">通過</td>
    <td class=\"TdR hide2\" width=\"70\">視訊打碼</td>
    <td class=\"TdR hide2\" width=\"35\">通過</td>
    <td class=\"TdR hide2\" width=\"80\">綜合打碼</td>
    <td class=\"TdR hide2\" width=\"70\">是否達到</td>
    <td class=\"TdG\" width=\"70\">常態打碼</td>
    <td class=\"TdG\" width=\"60\">放寬額度</td>
    <td class=\"TdG\" width=\"45\">通过</td>
    <td class=\"TdG\" width=\"90\">需扣除行政費用</td>
    <td class=\"TdG\" width=\"70\">需扣除金額</td>
    </tr>";
     return $header_html;
    }

    //尾部样式
    public function bottom_html(){
    	return "</tbody></table>";
    }

    //内容样式
    public function main_html($arr){
       if (!empty($arr)) {
       	  $main_html = '';
       	  foreach ($arr as $k => $v) {
       	  	 if (empty($v['end_date'])) {
       	  	 	$v['end_date'] = date('Y-m-d H:i:s');
       	  	 }
       	     $main_html .= "<tr class=\"m_cen\">  	
	    <td style=\"width:160px;\">起始:".$v['begin_date']."</td>
	    <td rowspan=\"2\">".$v['deposit_money']."</td>
	    <td rowspan=\"2\">".($v['atm_give']+$v['catm_give'])."</td>
	    <td class=\"hide1\" rowspan=\"2\">".$v['cathectic_sport']."</td> 
	    <td class=\"hide2\" rowspan=\"2\">".($v['cathectic_fc']+0)."</td>
	    <td class=\"hide2\" rowspan=\"2\">".($v['cathectic_video']+0)."</td>
	    <td class=\"hide2\" rowspan=\"2\">".$v['cathectic_sport']."</td>
	    <td class=\"hide2\" rowspan=\"2\">-</td>
	    <td class=\"hide2\" rowspan=\"2\">".($v['cathectic_fc']+0)."</td>
	    <td class=\"hide2\" rowspan=\"2\">-</td>
	    <td class=\"hide2\" rowspan=\"2\">".($v['cathectic_video']+0)."</td>
	    <td class=\"hide2\" rowspan=\"2\">-</td>
	    <td class=\"hide2\" rowspan=\"2\">".$v['type_code_all']."</td>
	    <td class=\"hide2\" rowspan=\"2\">".$this->zh_state($v['is_pass_zh'])."</td>
	    <td class=\"hide2\" rowspan=\"2\">".$v['normalcy_code']."</td>
	    <td class=\"hide2\" rowspan=\"2\">".$v['relax_limit']."</td>
	    <td class=\"hide2\" rowspan=\"2\">".$this->ct_state($v['is_pass_ct'])."</td>
	    <td class=\"hide2\" rowspan=\"2\">".$this->xz_state($v['is_expenese_num'])."</td>
	    <td class=\"hide2\" rowspan=\"2\">".sprintf("%.2f",$v['deduction_e']).'<br>'.sprintf("%.2f", $v['de_wind'])."</td></tr><tr class=\"m_cen\"><td>結束:".$v['end_date']."</td></tr>";
       	  }
       	  return $main_html;
       }
    }
    //判断用户出款信息
    public function out_user_fee(){
      global $db_config;
      $pay_id = $this->get_level_c();
      if (empty($pay_id)) {
         $pay_data = M('k_cash_config_view',$db_config)
             ->where("site_id ='".SITEID."' and type='1'")
             ->find();
      }else{
         $pay_data = M('k_cash_config_view',$db_config)
             ->where("id='".$pay_id."'")
             ->find();
      }
      //开启免手续费次数
      if($pay_data['is_fee_free']==1){
          //获取当日累计出款次数
          $endTime = date('Y-m-d H:i:s',(time()-$pay_data['repeat_hour_num']*60*60));
          $map_ou = array();
          $map_ou['uid'] = $this->uid;
          $map_ou['site_id'] = SITEID;
          $map_ou['out_status'] = 1;
          $map_ou['out_time'] = array('>=',$endTime);
          $count_out = M('k_user_bank_out_record',$db_config)
                     ->where($map_ou)
                     ->count();
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

    //获取用户层级
    public function get_user_level(){
       global $db_config;
       $level = M('k_user',$db_config)
              ->where("uid = '".$this->uid."'")
              ->getField('level_id');
       if (empty($level)) {
          $level = $this->get_default_level();
       }
       return $level;
    }

   //获取站点默认层级
   public function get_default_level(){
      $default_level = M('k_user_level',$db_config)
              ->where("site_id = '".SITEID."' and is_default = '1'")
              ->getField("id");
      return $default_level;
   }
   //获取用户层级信息
   public function get_level_c(){
      global $db_config;
      $id = $this->get_user_level($this->uid);
      $pay_id = M('k_user_level',$db_config)
            ->where("id='".$id."'")
            ->getField('RMB_pay_set');
      return $pay_id;
   }

   //查看取款稽核
   public function audit_get($str){
       global $db_config;
       if (!empty($str)) {
         $map_a = array();
         $map_a['id'] = array('in','('.$str.')');
         $audit_all = M('k_user_audit',$db_config)
              ->where($map_a)
              ->order("id desc")
              ->select();
         $arr = array();
         foreach ($audit_all as $k => $v) {
            $arr['fee_all'] += $v['deduction_e'];
         }
         $arr['content'] = $this->header_html().$this->main_html($audit_all);
       }else{
         $arr = 0;
       }
         return $arr;
   }




    //后台稽核样式输出
    public function admin_show(){
       $allData = $this->get_user_audit();
       $arr = array();
       if (!empty($allData)) {
          //数据非空
          $arr['betAll'] = $allData['bet_all'];
          $arr['count_dis'] = $allData['count_dis'];
          $arr['count_xz'] = $allData['count_xz'];
          $arr['out_fee'] = $allData['out_fee'];
          unset($allData['bet_all']);
          unset($allData['count_dis']);
          unset($allData['count_xz']);
          unset($allData['out_fee']);
          $arr['content'] = $this->header_html().$this->main_html($allData);
       }
       return $arr;
    }
    
}

//稽核A方法
function A($uid){
	return new audit($uid);
}

?>