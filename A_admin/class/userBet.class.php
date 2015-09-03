<?php
set_time_limit(0);
ini_set('memory_limit','512M');
   /**
    * 会员投注相关处理
   */
   class userBet 
   {

   	  //获取会员彩票投注
   	  static function userFcBet($uid,$date=array()){
   	  	global $db_config;
    		$fcBet = array();
    		$mapFc = array();
    		$mapFc['uid'] = $uid;
        $mapFc['status'] = array('in','(1,2)');
        if (!empty($date)) {
	        $mapFc['addtime'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }
        $fcBet = M('c_bet', $db_config)
                ->field("sum(money) as bet,count(id) as num,count(distinct uid) as userNum")
                ->where($mapFc)
                ->find();
	    	return $fcBet;
   	  }

   	  //获取会员体育投注
   	  static function userSpBet($uid,$date=array()){
   	  	global $db_config;
    		$spBet = array();
    		$mapSp = array();
    		$mapSp['uid'] = $uid;
        $mapSp['status'] = array('in','(1,2,4,5)');
        if (!empty($date)) {
	        $mapSp['bet_time'] = array(
	                        array('>=',$date[0].' 00:00:00'),
	                        array('<=',$date[1].' 23:59:59')
	                        );
        }
        $spBet = M('k_bet', $db_config)
                ->field("sum(bet_money) as bet,count(bid) as num,count(distinct uid) as userNum")
                ->where($mapSp)
                ->find();
        //体育串关
        unset($mapSp['status']);
        $mapSp['status'] = array('in','(1,2)');
        $spc_bet = M('k_bet_cg_group', $db_config)
                 ->field("sum(bet_money) as bet,count(gid) as num,count(distinct uid) as userNum")
                 ->where($mapSp)
                 ->find();
        $spBet['bet'] += $spc_bet['bet'];
        $spBet['num'] += $spc_bet['num'];
        $spBet['userNum'] += $spc_bet['userNum'];
    		return $spBet;
   	  }

   	  //获取视讯有效投注  单
   	  static function userVideoBet($loginname,$date,$g_type){
   	  	 include_once (dirname(__FILE__). "/../lib/video/Games.class.php");
    			$games = new Games();
    			$videoBet = array();
    			if (!empty($g_type) ) {
            $tmpDate = explode(':', $date[0]);
            if (count($tmpDate) < 2) {
                $date[0] = $date[0].' 00:00:00';
                $date[1] = $date[1].' 23:59:59';
            }
           
    				foreach ($g_type as $key => $val) {
              if ($val == 'mgdz') {
                  $vBet = $games->GetUserAvailableAmountByUser('mg',$loginname, $date[0], $date[1],1);
              }elseif($val == 'bbdz'){
                 continue; //接口跟新 bbdz作废
                 $vBet = $games->GetUserAvailableAmountByUser('bbin',$loginname, $date[0], $date[1],1);
              }else{
                  $vBet = $games->GetUserAvailableAmountByUser($val,$loginname, $date[0], $date[1]);
              }
    					$tmpvBet= json_decode($vBet);
              if (!empty($tmpvBet->data->data)) {
                foreach ($tmpvBet->data->data as $key => $v) {
                   $videoBet[$val]['BetYC'] = $v->BetYC;
                }
              }
    				}
    			}
    			return $videoBet;
			
   	  }

            //获取视讯有效投注 多账号
      static function usersVideoBet($loginname,$date,$g_type){
         include_once (dirname(__FILE__). "/../lib/video/Games.class.php");
          $games = new Games();
          $videoBet = array();
          $date[0] = $date[0].' 00:00:00';
          $date[1] = $date[1].' 23:59:59';
          if (!empty($g_type) ) {
            foreach ($g_type as $key => $val) {
              if ($val == 'mgdz') {
                  $vBet = $games->GetUserAvailableAmountByUser('mg',$loginname, $date[0], $date[1],1);
              }elseif($val == 'bbdz'){
                  $vBet = $games->GetUserAvailableAmountByUser('bbin',$loginname, $date[0], $date[1],1);
              }else{
                  $vBet = $games->GetUserAvailableAmountByUser($val,$loginname, $date[0], $date[1]);
              }
              
              //$videoBet[$val] = json_decode($vBet[$val]);
              $tmpvBet= json_decode($vBet);
              $tmpvideoBet = $tmpvBet->data->data;
              if (!empty($tmpvideoBet)) {
                foreach ($tmpvideoBet as $key => $v) {
                   $videoBet[$v->username][$val] = $v->BetYC;
                }
              }
            }
          }
          return $videoBet;
      
      }

                  //获取视讯有效投注
      static function get_video($user,$start_date,$end_date,$db_config){
          $tarr = array('ag','og','mg','ct','bbin','lebo');
          $videoBet = array();
          foreach ($tarr as $key => $val) {
              $tmp_func = 'get_'.$val.'_video';
              $tmp = self::$tmp_func($user,$start_date,$end_date,$db_config);
              if (!empty($tmp)) {
                  $videoBet['bet'] += $tmp['bet'];
                  $videoBet['money'] += $tmp['money'];
              }  
          }
          return $videoBet;
      }
      //mg
      static function get_mg_video($user,$start_date,$end_date,$db_config){
          $map['update_time'] = array(
                            array('>=',$start_date),
                            array('<=',$end_date)
                            );
          $map['account_number'] = SITEID.$user;
          $mg_arr = M('mg_bet_record',$db_config)->field("account_number,sum(income) as bet")->where($map)->find();
          return $mg_arr;
      }
      //ag
      static function get_ag_video($user,$start_date,$end_date,$db_config){
          $map['player_name'] = SITEID.$user;
          $map['update_time'] = array(
                            array('>=',$start_date),
                            array('<=',$end_date)
                            );
          $ag_arr = M('ag_bet_record',$db_config)->field("player_name,sum(valid_betamount) as bet")->where($map)->find();
          return $ag_arr;
      }
      //lebo
      static function get_lebo_video($user,$start_date,$end_date,$db_config){
          $map['member'] = SITEID.$user;
          $map['update_time'] = array(
                            array('>=',$start_date),
                            array('<=',$end_date)
                            );
          $lebo_arr = M('lebo_bet_record',$db_config)->field("member,sum(valid_betamount) as bet,sum(pay_out) as money")->where($map)->find();
          return $lebo_arr;
      }
     //bbin
      static function get_bbin_video($user,$start_date,$end_date,$db_config){
          $map['payoff'] = array('<>','0.0000');
          $map['username'] = SITEID.$user;
          $map['update_time'] = array(
                            array('>=',$start_date),
                            array('<=',$end_date)
                            );
          $bbin_arr= M('bbin_bet_record',$db_config)->field("username,sum(betamount) as bet,sum(payoff) as money")->where($map)->find();
          return $bbin_arr;
      }

       //ct
      static function get_ct_video($user,$start_date,$end_date,$db_config){
          $map['update_time'] = array(
                            array('>=',$start_date),
                            array('<=',$end_date)
                            );
          $map['member_id'] = SITEID.'@'.$user;
          $ct_arr = M('ct_bet_record',$db_config)->field("member_id,sum(availablebet) as bet")->where($map)->find();
          return $ct_arr;
      }

       //og
      static function get_og_video($user,$start_date,$end_date,$db_config){
          $map['update_time'] = array(
                            array('>=',$start_date),
                            array('<=',$end_date)
                            );
          $map['user_name'] = SITEID.$user;
          $og_arr = M('og_bet_record',$db_config)->field("user_name,sum(valid_amount) as bet")->where($map)->find();
          return $og_arr;
      }


   }
?>