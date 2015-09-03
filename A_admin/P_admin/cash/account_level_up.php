<?php
set_time_limit(0);
include_once("../../include/config.php");
include_once("../common/login_check.php");

//会员层级分层升级

$u = M('k_user_level',$db_config);
//入选当前层级条件
$levelA = $u->field("level_name,start_date,end_date,deposit_num,deposit_count")->where("site_id = '".SITEID."' and id = '".$_POST['level_id_1']."'")->find();

//移动所有符合条件的层级用户
if(!empty($_POST['level_id_1']) &&!empty($_POST['level_ids']) && $_POST['level_set'] == 'user_level'){
  $data1['level_id']=$_POST['level_id_1'];//转入层id
  foreach ($_POST['level_ids'] as $k => $v) {
    //获取层级下的会员
      $map_u = array();
      // if (!empty($levelA['start_date']) && !empty($levelA['end_date'])) {
      //     $map_u['reg_date'] = array(
      //                              array('>=',$levelA['start_date']),
      //                              array('<=',$levelA['end_date'])
      //                            );
      //  }elseif (!empty($levelA['start_date'])) {
      //     $map_u['reg_date'] = array('>=',$levelA['start_date']);

      //  }elseif (!empty($levelA['end_date'])) {
      //     $map_u['reg_date'] = array('<=',$levelA['end_date']);
      //  }
       $map_u['is_delete'] = 0;
       $map_u['site_id'] = SITEID;
       $map_u['level_id'] = $v;
       $map_u['shiwan'] = 0;
       $map_u['is_locked'] = 0;
       $level_user = M('k_user',$db_config)->field("uid,reg_date")->where($map_u)->select('uid');
       if (!empty($level_user)) {
           $uid_strs = '('.implode(',',array_keys($level_user)).')';
       }
       
       $map_catm = array();
       $map_catm['uid'] = array('in',$uid_strs);
       $map_catm['site_id'] = SITEID;
       $map_catm['type'] = 1;
       $map_catm['catm_type'] = 1;

       $catm_data;
       $catm_data = M('k_user_catm',$db_config)->field("count(id) as catmNum,sum(catm_money) as catm_money,uid")->where($map_catm)->group("uid")->select('uid');

      //公司入款 线上入款
       $map_cash = array();
       $map_cash['uid'] = array('in',$uid_strs);
       $map_cash['site_id'] = SITEID;
       $map_cash['make_sure'] = 1;
       $cash_data=M('k_user_bank_in_record',$db_config)
          ->field("sum(deposit_num) as cash_money,count(id) as cashNum,uid")->where($map_cash)->group('uid')->select('uid'); 
    //判断是否满足该层级条件，满足才能移动到该层级
    foreach ($level_user as $kk => $vv) {
        $inum = $cash_data[$vv['uid']]['cashNum']+$catm_data[$vv['uid']]['catmNum'];
        $imoney = $cash_data[$vv['uid']]['cash_money']+$catm_data[$vv['uid']]['catm_money'];
        if ($inum >= $levelA['deposit_num'] && $imoney >= $levelA['deposit_count']) {
        }else{
           unset($level_user[$kk]);
        }
    }
    if (!empty($level_user)) {
        $level_user = '('.implode(',',array_keys($level_user)).')';
    }

    $map_u_up = array();
    $map_u_up['site_id'] = SITEID;
    $map_u_up['uid'] = array('in',$level_user);
    $move= M("k_user",$db_config)->where($map_u_up)->update($data1);
  }
     $do_str = '层级'.$levelA['level_name'];
     $do_log = $_SESSION['login_name'].'对'.$do_str.'进行了分层操作';
     admin::insert_log($_SESSION['adminid'],$_SESSION['login_name'],$do_log); 
     message('分层成功','./account_level.php');
}else{
  message('数据不能为空','./account_level.php');
}

?>