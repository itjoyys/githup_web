<?php
   /**
    * 股东代理商相关数据处理
   */
   class agentCenter 
   {

   	  //获取股东旗下会员
   	  Static function getShuser($id,$levelStr){
	   	  	global $db_config;
	        $agents_id = agentCenter::getSh_at('a_t',$id); // 所有代理商id
  		    if (!empty($agents_id)) {
  		        // 股东旗下会员
  		        $map_u = array();
  		        $map_u['shiwan'] = 0;
  		        $map_u['agent_id'] = array('in','('.$agents_id.')');
  		        if(!empty($levelStr)){
                  $map_u['level_id'] = array('in','('.$levelStr.')');
  		        }
  		        $userIds = M('k_user', $db_config)
  		            ->join("join k_user_level on k_user.level_id = k_user_level.id")
  		            ->field('k_user.uid,k_user.username,k_user.agent_id,k_user_level.level_des,k_user.level_id,k_user.money')
  		            ->where($map_u)
  		            ->select();
  		    }
  		    return $userIds;
   	  }
      //获取股东旗下所有代理商
   	  Static function getSh_at($type,$id){
            global $db_config;
	   	   	$agent = M('k_user_agent', $db_config)
	               ->where("is_demo = '0' and is_delete = '0' and site_id = '" . SITEID . "'")
	               ->select('id');
	        $agentsId = implode(',', self::getChildsId($agent,$id,$type)); // 所有代理商id
	        return $agentsId;
   	  }

       //获取总代旗下所有代理商
      Static function getUa_at($type,$id){
            global $db_config;
          $agent = M('k_user_agent', $db_config)
                 ->where("is_demo = '0' and is_delete = '0' and site_id = '".SITEID."' and agent_type <> 's_h'")
                 ->select('id');
          $agentsId = implode(',', self::getChildsId($agent,$id,$type)); // 所有代理商id
          return $agentsId;
      }

        //传递股东id，返回所有代理商id  $cate原数组 $pid父id $cate_type 子类 比如u_a a_t
        Static Public function getChildsId($cate,$pid,$cate_type) {
            $arr = array();
            foreach ($cate as $v) {
                if ($v['pid'] == $pid) {
                    if ($v['agent_type'] == $cate_type) {
                      $arr[] = $v['id'];
                    }
                    $arr = array_merge($arr, self::getChildsId($cate, $v['id'],$cate_type));
                }
            }
            return $arr;
        }

     //获取代理商旗下有效会员数量
     static function agentUserYX($agent_id,$date=array()){
        include_once(dirname(__FILE__)."/../lib/video/Games.class.php");
        global $db_config;
        $numYX = 0;
          //有效会员
          //彩票
          $fc_uid_YX = array();
          $map_fc['agent_id'] = $agent_id;
          $map_fc['status'] = array('in','(1,2)');
          if (!empty($date)) {
            $map_fc['addtime'] = array(
                          array('>=',$date[0].' 00:00:00'),
                          array('<=',$date[1].' 23:59:59')
                          );
          }
          $fc_uid_YX = M('c_bet',$db_config)
                     ->field("distinct uid,username")
                     ->where($map_fc)
                     ->select('username');

          //体育
          $sp_uid_YX = array();
          $map_sp['agent_id'] = $agent_id;
          $map_sp['status'] = array('in','(1,2,4,5)');
          if (!empty($date)) {
            $map_sp['bet_time'] = array(
                            array('>=',$date[0].' 00:00:00'),
                            array('<=',$date[1].' 23:59:59')
                            );
          }
          $sp_uid_YX = M('k_bet',$db_config)
                     ->field("distinct uid,username")
                     ->where($map_sp)
                     ->select('username');
          //体育串关
          $cg_uid_YX = array();
          unset($mapSp['status']);
          $map_sp['status'] = array('in','(1,2)');
          $cg_uid_YX = M('k_bet_cg_group',$db_config)
                     ->field("distinct uid,username")
                     ->where($map_sp)
                     ->select('username');
          //视讯
            $games = new Games();
            $s_time = $date[0]." 00:00:00";
            $e_time = $date[1]." 23:59:59";

            $Vdata = $games->GetAllUserAvailableAmountByAgentid($agent_id, $s_time, $e_time);
            $Vdata = json_decode($Vdata);
            $tmpd = $Vdata->data->data;
            $video_uid_YX = array();
            foreach ($tmpd as $k => $v) {
               $video_uid_YX[$v->username] = $v->username;
            }

           
          // $uid_YX[1] = array_flip(array_keys($fc_uid_YX));
          // $uid_YX[2] = array_flip(array_keys($sp_uid_YX));
          // $uid_YX[3] = array_flip(array_keys($cg_uid_YX));
          $uid_YX = array_flip(array_keys( $fc_uid_YX))+array_flip(array_keys($sp_uid_YX))+array_flip(array_keys($cg_uid_YX));
          return $uid_YX;
     }
 
   }
?>