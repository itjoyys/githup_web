<?php
set_time_limit(0);
ini_set('memory_limit','512M');
   /**
    * 天天返水处理
   */
 
   class Discount 
   {
     
   	  //天天返水 按照账号查询
   	  static function DayDiscount($userIds,$date=array(),$g_type)
   	  {
   	  	    $disType= array('fc','sp','ag','og','mg','ct','lebo','bbin');//种类
   	  	    include(dirname(__FILE__)."/userBet.class.php");
   	  	    global $db_config;
            $agent = M('k_user_agent', $db_config)
                   ->field("id,agent_user")
                   ->where("site_id = '". SITEID."' and agent_type = 'a_t' and is_demo = '0'")
			       ->select('id');
		    $discount = M('k_user_discount_set', $db_config)
		              ->where("site_id = '" . SITEID . "'")
					  ->order('count_bet desc')
					  ->select();
			 if (!empty($userIds)) {
	   	       //会员账号拼接
               $VideoUser = Discount::unameJoin($userIds);
	           //获取视讯打码
	           $VideoBet = userBet::usersVideoBet($VideoUser,$date,$g_type);

		       foreach ($userIds as $k => $val) {
	              $uname = $val['username'];
	              $fcbet = userBet::userFcBet($val['uid'],$date);
	              $spbet = userBet::userSpBet($val['uid'],$date);

	              $mg_bet = $VideoBet[$uname]['mg']+0;
	              //$mgdz_bet = $VideoBet[$uname]['mgdz']+0;
	              $ag_bet = $VideoBet[$uname]['ag']+0;
	              $og_bet = $VideoBet[$uname]['og']+0;
	              $ct_bet = $VideoBet[$uname]['ct']+0;
	              $bbin_bet = $VideoBet[$uname]['bbin']+0;
	              $bbdz_bet = 0;
	              $lebo_bet = $VideoBet[$uname]['lebo']+0;

		          $betAll = $fcbet['bet'] + $spbet['bet'] 
		                  + $mg_bet + $ag_bet + $og_bet
		                  + $bbin_bet + $lebo_bet + $ct_bet; // 总有效下注
		         
		      
	                // 有优惠判断是否有 有效下注
	              if (empty($betAll)) {
	                  unset($userIds[$k]); // 没有去掉会员
	              }else {
	              	 // 返点判断
		             foreach ($discount as $kt => $v) {
				        if ($v['count_bet'] <= $betAll) {
				            $re = $v;
				            break;
				        }
					 }
	                 $userIds[$k]['agent_user'] = $agent[$val['agent_id']]['agent_user'];
	                 $userIds[$k]['betall'] = $betAll; // 总有效投注

                     foreach ($disType as $kd => $vd) {
                       //循环赋值
                       $tmpBfield = $vd.'_bet';//打码字段
                       $tmpFfield = $vd.'_fd';//返点字段
                       $res = $vd.'_discount';
                       if ($vd == 'fc' || $vd == 'sp') {
                       	   $tmpA = $vd.'bet';
                       	   $arrTmpA = $$tmpA;
                       	   $userIds[$k][$tmpBfield] = $arrTmpA['bet']+0;
                       	   $numTwo = $arrTmpA['bet']*$re[$res]*0.01;
                       	   $userIds[$k][$tmpFfield] = sprintf("%.2f", $numTwo);//格式化两位小数
                       	   
                       }else{
                       	   $tmpA = $vd.'_bet';
                       	   $arrTmpA = $$tmpA;
                       	   $userIds[$k][$tmpBfield] = $arrTmpA +0;
                       	   $numTwo = $arrTmpA*$re[$res]*0.01;
                       	   $userIds[$k][$tmpFfield] = sprintf("%.2f", $numTwo);
                       }
                     }
                     //单个会员总返点
	                 $userIds[$k]['total_e_fd'] = 
	                                $userIds[$k]['fc_fd'] + 
	                                $userIds[$k]['sp_fd'] + 
	                                $userIds[$k]['mg_fd'] + 
	                                $userIds[$k]['ag_fd'] + 
	                                $userIds[$k]['ct_fd'] + 
	                                $userIds[$k]['og_fd'] + 
	                                $userIds[$k]['bbin_fd'] +
	                                $userIds[$k]['lebo_fd'];
	                }
		        }
		        unset($user_tmp_ids);
		        unset($agent);
		    }
		    return $userIds;
   	  }

      //天天返水 股东层级查询
   	  static function DayDiscount_sh($userIds,$date=array(),$g_type)
   	  {
   	  	    $disType= array('fc','sp','ag','og','mg','mgdz','ct','lebo','bbin');//种类
   	  	    include(dirname(__FILE__)."/userBet.class.php");
   	  	    global $db_config;
            $agent = M('k_user_agent', $db_config)
                   ->field("id,agent_user")
                   ->where("site_id = '". SITEID."' and agent_type = 'a_t' and is_demo = '0'")
			       ->select('id');
		    $discount = M('k_user_discount_set', $db_config)
		              ->where("site_id = '" . SITEID . "'")
					  ->order('count_bet desc')
					  ->select();
			 if (!empty($userIds)) {
	   	       //会员账号拼接
	   	       if (count($userIds) >= 500) {
	   	       	  $user_tmp_ids = array_chunk($userIds,500,true);
	   	       }else{
	   	       	  $user_tmp_ids[] = $userIds;
	   	       }
	   	       foreach ($user_tmp_ids as $tmp_k => $tmp_v) {
                  $VideoUser = Discount::unameJoin($tmp_v);
		           //获取视讯打码
		          $VideoBet = userBet::usersVideoBet($VideoUser,$date,$g_type);

		       foreach ($tmp_v as $k => $val) {
	              $uname = $val['username'];
	              $fcbet = userBet::userFcBet($val['uid'],$date);
	              $spbet = userBet::userSpBet($val['uid'],$date);

	              $mg_bet = $VideoBet[$uname]['mg']+0;
	              $mgdz_bet = $VideoBet[$uname]['mgdz']+0;
	              $ag_bet = $VideoBet[$uname]['ag']+0;
	              $og_bet = $VideoBet[$uname]['og']+0;
	              $ct_bet = $VideoBet[$uname]['ct']+0;
	              $bbin_bet = $VideoBet[$uname]['bbin']+0;
	              $bbdz_bet = 0;
	              $lebo_bet = $VideoBet[$uname]['lebo']+0;

		          $betAll = $fcbet['bet'] + $spbet['bet'] 
		                  + $mg_bet + $ag_bet + $og_bet
		                  + $bbin_bet + $mgdz_bet + $bbdz_bet
		                  + $lebo_bet + $ct_bet; // 总有效下注
		         
		      
	                // 有优惠判断是否有 有效下注
	              if (empty($betAll)) {
	                  unset($userIds[$k]); // 没有去掉会员
	              }else {
	              	 // 返点判断
		             foreach ($discount as $kt => $v) {
				        if ($v['count_bet'] <= $betAll) {
				            $re = $v;
				            break;
				        }
					 }
	                 $userIds[$k]['agent_user'] = $agent[$val['agent_id']]['agent_user'];
	                 $userIds[$k]['betall'] = $betAll; // 总有效投注

                     foreach ($disType as $kd => $vd) {
                       //循环赋值
                       $tmpBfield = $vd.'_bet';//打码字段
                       $tmpFfield = $vd.'_fd';//返点字段
                       $res = $vd.'_discount';
                       if ($vd == 'fc' || $vd == 'sp') {
                       	   $tmpA = $vd.'bet';
                       	   $arrTmpA = $$tmpA;
                       	   $userIds[$k][$tmpBfield] = $arrTmpA['bet']+0;
                       	   $numTwo = $arrTmpA['bet']*$re[$res]*0.01;
                       	   $userIds[$k][$tmpFfield] = sprintf("%.2f", $numTwo);//格式化两位小数
                       	   
                       }else{
                       	   $tmpA = $vd.'_bet';
                       	   $arrTmpA = $$tmpA;
                       	   $userIds[$k][$tmpBfield] = $arrTmpA +0;
                       	   $numTwo = $arrTmpA*$re[$res]*0.01;
                       	   $userIds[$k][$tmpFfield] = sprintf("%.2f", $numTwo);
                       }
                     }
                     //单个会员总返点
	                 $userIds[$k]['total_e_fd'] = 
	                                $userIds[$k]['fc_fd'] + 
	                                $userIds[$k]['sp_fd'] + 
	                                $userIds[$k]['mg_fd'] + 
	                                $userIds[$k]['ag_fd'] + 
	                                $userIds[$k]['ct_fd'] + 
	                                $userIds[$k]['og_fd'] + 
	                                $userIds[$k]['bbin_fd'] + 
	                                $userIds[$k]['mgdz_fd'] + 
	                                $userIds[$k]['lebo_fd'];
	                }
		        }
		       }
		        unset($user_tmp_ids);
		        unset($agent);
		    }
		    return $userIds;
   	  }


      //会员账号处理
      static function unameJoin($userIds){
      	   $VideoUser = '';
      	   if (is_array($userIds)) {
      	   	  foreach ($userIds as $k => $val) {
                $VideoUser .= '|'.$val['username'];
              }
              $VideoUser = ltrim($VideoUser,'|');
      	   }else{
      	   	  $VideoUser = 0;
      	   }
          return $VideoUser;
      }
   }
?>