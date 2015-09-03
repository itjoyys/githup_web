<?php
   /**
    * 股站点相关数据处理
   */
   class siteCenter 
   {

     //获取有效会员数量
     static function siteUserYX($siteid,$date=array(),$uid=''){
        if (empty($uid)) {
           $uidStrs = siteCenter::siteUid($siteid);
           $map_fc['uid'] = array('in','('.$uidStrs.')');
           $map_sp['uid'] = array('in','('.$uidStrs.')');
        }else{
           $map_fc['uid'] = $uid;
           $map_sp['uid'] = $uid;
        }
        
        //return $uidStrs;
        if (!empty($uidStrs) || !empty($uid)) {
            global $db_config;
            $numYX = 0;
              //有效会员
              //彩票
              $fc_uid_YX = array();
              $map_fc['site_id'] = $siteid;
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
              //return $fc_uid_YX;
              //体育
              $sp_uid_YX = array();
              $map_sp['site_id'] = $siteid;
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
              $uid_YX = array_flip(array_keys($fc_uid_YX))+array_flip(array_keys($sp_uid_YX))+array_flip(array_keys($cg_uid_YX));
              return $uid_YX;
        }
     }

     //站点下所有会员uid
     static function siteUid($siteid){
       global $db_config;
       $map = array();
       $map['shiwan'] = 0;
       $map['site_id'] = $siteid;
       $uidStr = M('k_user',$db_config)->where($map)
               ->getField("uid",true);
       $Str =  implode(',',$uidStr);
       return $Str;
     }
 
   }
?>