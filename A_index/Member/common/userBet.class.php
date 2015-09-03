<?php
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
   	  	 include_once dirname(__FILE__). "/../../video/games/Games.class.php";
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
    					//$vBet = $games->GetUserAvailableAmountByUser($val,$loginname, $date[0], $date[1]);
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
         include_once dirname(__FILE__). "/../../video/games/Games.class.php";
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
              //$vBet = $games->GetUserAvailableAmountByUser($val,$loginname, $date[0], $date[1]);
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
   }
?>