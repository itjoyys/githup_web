<?php


/**
* 
*/
class setCenter
{

    static function agent_setAdd($aid,$pid){
       $spState = setCenter::agent_sport_set($aid,$pid);
       $fcState = setCenter::agent_fc_set($aid,$pid);
       if ($spState && $fcState) {
           return 1;
       }else{
           return 0;
       }
    }
    
    //股东添加 代理 aid 当前股东id pid上一级id

    static function agent_sport_set($aid,$pid){
       global $db_config;
       $Cagent = M('k_user_agent_sport_set',$db_config);
       if (empty($pid)) {
           //为空表示股东添加
            $spSetdata = $Cagent->where("site_id = '".SITEID."' and is_default = '1'")->select();
       }else{
            $spSetdata = $Cagent->where("site_id = '".SITEID."' and aid = '".$pid."'")->select();
       }
       $inum = 0;
       foreach ($spSetdata as $key => &$val) {
            $val['aid'] = $aid;
            unset($val['id']);
            unset($val['is_default']);
            $itate = $Cagent->add($val);
            if ($itate) {
                $inum++;
            }  
        }
        return $inum;
    }
    //彩票设定添加
    static function agent_fc_set($aid,$pid){
       global $db_config;
       $Cagent = M('k_user_agent_fc_set',$db_config);
       if (empty($pid)) {
           //为空表示股东添加
            $spSetdata = $Cagent->where("site_id = '".SITEID."' and is_default = '1'")->select();
       }else{
            $spSetdata = $Cagent->where("site_id = '".SITEID."' and aid = '".$pid."'")->select();
       }
       $inum = 0;
       foreach ($spSetdata as $key => &$val) {
            $val['aid'] = $aid;
            unset($val['id']);
            unset($val['is_default']);
            $itate = $Cagent->add($val);
            if ($itate) {
                $inum++;
            }  
        }
        return $inum;
    }
}
?>