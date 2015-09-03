<?php
   /**
    * 股东代理商相关投注
   */
   class agentBet 
   {
   	      //获取代理彩票投注
      static function fcBet($agent_id,$date=array()){
        global $db_config;
        $fcBet = array();
        $mapFc = array();
        $mapFc['agent_id'] = $agent_id;
        $mapFc['status'] = array('in','(1,2)');
        if (!empty($date)) {
          $mapFc['addtime'] = array(
                          array('>=',$date[0].' 00:00:00'),
                          array('<=',$date[1].' 23:59:59')
           );
        }
        $fcBet = M('c_bet', $db_config)
                ->field("sum(money) as bet,sum(win) as win")
                ->where($mapFc)
                ->find();
       return $fcBet;
      }
      
      //获取会员体育投注
      static function spBet($agent_id,$date=array()){
        global $db_config;
        $spBet = array();
        $mapSp = array();
        $mapSp['agent_id'] = $agent_id;
        $mapSp['status'] = array('in','(1,2,4,5)');
        if (!empty($date)) {
          $mapSp['bet_time'] = array(
                          array('>=',$date[0].' 00:00:00'),
                          array('<=',$date[1].' 23:59:59')
                          );
        }
        $spBet = M('k_bet', $db_config)
                ->field("sum(bet_money) as bet,sum(win) as win")
                ->where($mapSp)
                ->find();
        //体育串关
        unset($mapSp['status']);
        $mapSp['status'] = array('in','(1,2)');
        $spc_bet = M('k_bet_cg_group', $db_config)
                 ->field("sum(bet_money) as bet,sum(win) as win")
                 ->where($mapSp)
                 ->find();
        $spBet['bet'] += $spc_bet['bet'];
        $spBet['win'] += $spc_bet['win'];
        return $spBet;
      }

          //获取视讯有效投注
      static function videoBet($agent_id,$date,$g_type){
          include_once dirname(__FILE__). "/../lib/video/Games.class.php";
          $games = new Games();
          $videoBet = array();
          if (empty($g_type)) {
             $g_type = array("og", "ag", "mg", "ct", "bbin", "lebo");
          }
          if (!empty($date)) {
              $tmpDate = explode(':', $date[0]);
              if (count($tmpDate) < 2) {
                  $date[0] = $date[0].' 00:00:00';
                  $date[1] = $date[1].' 23:59:59';
              }
          }

          foreach ($g_type as $key => $val) {
            $vBet[$val] = $games->GetUserAvailableAmountByAgentid($val,$agent_id, $date[0], $date[1]);
            //$videoBet[$val] = json_decode($vBet[$val]);
            $tmpvBet = json_decode($vBet[$val]);
            $videoBet[$val]['BetYC'] = $tmpvBet->data->data->BetYC+0;
            $videoBet[$val]['BetPC'] = $tmpvBet->data->data->BetPC+0;
          }
          return $videoBet;
      
      }

                //获取电子有效投注
      static function gameBet($agent_id,$date,$g_type){
          include_once dirname(__FILE__). "/../lib/video/Games.class.php";
          $games = new Games();
          $videoBet = array();
          if (empty($g_type)) {
             $g_type = array("mg","bbin");
          }
          if (!empty($date)) {
              $tmpDate = explode(':', $date[0]);
              if (count($tmpDate) < 2) {
                  $date[0] = $date[0].' 00:00:00';
                  $date[1] = $date[1].' 23:59:59';
              }
          }

          foreach ($g_type as $key => $val) {
            $vBet[$val] = $games->GetUserAvailableAmountByAgentid($val,
              $agent_id, $date[0], $date[1],1);
            //$videoBet[$val] = json_decode($vBet[$val]);
            $tmpvBet = json_decode($vBet[$val]);
            $gameBet[$val]['BetYC'] = $tmpvBet->data->data->BetYC+0;
            $gameBet[$val]['BetPC'] = $tmpvBet->data->data->BetPC+0;
          }
          return $gameBet;
      
      }
 
   }
?>