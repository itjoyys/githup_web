<?php 
include_once("../../include/config.php");
include_once("../../common/login_check.php");

//出款判断
$status = M('k_user_bank_out_record',$db_config)
        ->field("order_num")
        ->where("uid= '".$_SESSION['uid']."' and (out_status = 0 or out_status = 4)")
        ->find();
if(!empty($status)){
    echo '您有订单号：'.$status['order_num'].',出款尚未完成.请勿频繁出款';
    exit();
}


if(!empty($_POST['qk_pwd']) && $_POST['uu_out'] == 'oucd'){
    //获取用户取款密码 金额
    $user = M('k_user',$db_config)
        ->field('qk_pwd,pay_card,level_id,money,pay_num,pay_address,pay_name')
        ->where("uid = '".$_SESSION['uid']."' and site_id='".SITEID."'")
        ->find();

    if($user['qk_pwd'] != $_POST["qk_pwd"]){
        message("取款密码错误，请重新输入。");
    }


    $pay_value  =  doubleval($_POST["cash"]);//提款金额
    //会员出款上限下限
    $level_u = M('k_user_level',$db_config)
             ->field("RMB_pay_set,min_out")
             ->where("id = '".$user['level_id']."'")
             ->find();
    if (!empty($level_u['min_out']) && $level_u['min_out'] > $pay_value) {
        message("取款额度不能低于最低取款额度!!!");
    }

    if(!empty($level_u) && !empty($level_u['RMB_pay_set'])){
        //获取用户出款优惠
        $pay_data = M('k_cash_config_view',$db_config)
              ->field("ol_atm_max,ol_atm_min")
              ->where("id='".$level_u['RMB_pay_set']."'")
              ->find();
    }else{
        $pay_data = M('k_cash_config_view',$db_config)
                 ->field("ol_atm_max,ol_atm_min")
                 ->where("site_id ='".SITEID."' and type='1' and type_name = 'RMB'")
                 ->find();
    }
 
    if(($pay_value<0)||($pay_value>$user["money"])||($pay_value>$pay_data['ol_atm_max'])||($pay_value<$pay_data['ol_atm_min'])){
        message('提款金额错误，请重新输入。');
    }


    //查询此次出款的稽核ID
    $audit = M('k_user_audit',$db_config)
           ->field('id')
           ->where("uid = '".$_SESSION['uid']."' and type='1'")
           ->order('id desc')
           ->select('id');
    $audit_id;

    if (!empty($audit)) {
        $ia = 0;
        foreach ($audit as $key => $v) {
            $audit_str .= ','.$key;
            //更新稽核对应的信息
            $userAudit = $_SESSION['userAudit'];
            $data_na = array();
            $data_na['is_pass_zh'] = $userAudit[$key]['is_pass_zh'];
            $data_na['is_pass_ct'] = $userAudit[$key]['is_pass_ct'];
            $data_na['is_expenese_num'] = $userAudit[$key]['is_expenese_num'];
            $data_na['deduction_e'] = $userAudit[$key]['deduction_e'];
            $data_na['cathectic_sport'] = $userAudit[$key]['cathectic_sport'];
            $data_na['cathectic_fc'] = $userAudit[$key]['cathectic_fc'];
            $data_na['cathectic_video'] = $userAudit[$key]['cathectic_video'];
            $auditState = M('k_user_audit',$db_config)
                        ->where("id = '".$key."'")
                        ->update($data_na);
            $ia += $auditState;
        }
        // if ($ia != count($_SESSION['userAudit'])) {
        //     message("提交出款失败,错误代码AA001");
        // }
        unset($_SESSION['userAudit']);
        $audit_id = ltrim($audit_str,',');
    }
  
    //判断是否首次出款
    $outward_style=1;
    $is_first = M("k_user_bank_out_record",$db_config)
              ->where("uid = '".$_SESSION['uid']."' and out_status = '1'")
              ->find();
    if($is_first){
        $outward_style=0;//不是首次出款
    }else{
        $outward_style=1;
    }
    //获取代理商账号
    $agent_user = M("k_user_agent",$db_config)
           ->where("id = '".$_SESSION['agent_id']."'")
           ->getField('agent_user');
    //扣除费用
     $out_data = array();
     $out_data = $_SESSION['out_money'];
     //是否扣除优惠
     if (!empty($out_data['fav_num'])) {
        $is_fav = 1;
     }else{
        $is_fav = 0;
     }
       //判断提出额度是否大于扣除
    $tmpUY = $pay_value - $out_data['out_audit'] - $out_data['out_fee'];
    if ($tmpUY < 0) {
       message('减去扣除额度后提款额度小于0，请重新提交出款额度。');
       exit();
    }
    $order_num=date("YmdHis").mt_rand(1000,9999);//订单号
    //出款处理
    $Buser = M('k_user',$db_config);
    $Buser->begin();
    try {
        $data_u = array();
        $data_u['money'] = array('-',$pay_value);
        $log_1 = $Buser->where("uid = '".$_SESSION['uid']."'")
               ->update($data_u);
        //写入出款记录
        //获取用户当前余额
        $umban = M('k_user',$db_config)
               ->where("uid = '".$_SESSION['uid']."'")
               ->getField('money');
        if($umban < 0){
          $Buser->rollback();
          message("确定入款失败,错误代码001");
        }
        $data_o = array();
        $data_o['site_id'] = SITEID;
        $data_o['uid'] = $_SESSION['uid'];
        $data_o['agent_user'] = $agent_user;
        $data_o['username'] = $_SESSION['username'];
        $data_o['level_id'] = $user['level_id'];
        $data_o['audit_id'] = $audit_id;
        $data_o['balance'] = $umban;
        $data_o['do_url'] =  $_SERVER["HTTP_HOST"];//提交网址
        $data_o['order_num'] = $order_num;
        $data_o['out_time'] = date('Y-m-d H:i:s');
        $data_o['outward_style'] = $outward_style;//是否首次出款
        $data_o['outward_num'] = $pay_value;//提交额度
        $data_o['charge'] = $out_data['out_fee'];//手续费
        $data_o['favourable_num'] = $out_data['fav_num'];//优惠金额
        $data_o['expenese_num'] = ($out_data['out_audit'] - $out_data['fav_num']);//行政费用扣除
        $data_o['outward_money'] = ($pay_value - $out_data['out_audit'] - $out_data['out_fee']);//实际出款额度
        $data_o['favourable_out'] = $is_fav;//是否扣除优惠
        $log_2 = $Buser->setTable('k_user_bank_out_record')
               ->add($data_o);
        //写入现金系统
        $dataC = array();
        $dataC['uid'] = $_SESSION['uid'];
        $dataC['username'] = $_SESSION['username'];
        $dataC['site_id'] = SITEID;
        $dataC['agent_id'] = $_SESSION['agent_id'];
        $dataC['cash_balance'] = $umban;
        $dataC['source_id'] = $log_2;
        $dataC['cash_type'] = 19;//线上取款
        $dataC['cash_do_type'] = 2;
        $dataC['source_type'] = 4;//线上取款类型
        $dataC['cash_num'] = $pay_value;
        $dataC['cash_date'] = date('Y-m-d H:i:s');
        $dataC['remark'] = $order_num;
        $log_3 = $Buser->setTable('k_user_cash_record')
               ->add($dataC);

        if ($log_1 && $log_2 && $log_3) {
            $Buser->commit();
            unset($_SESSION['out_money']);
            echo "<script>alert('提款申请已经提交，等待财务人员给您转账！');window.close();</script>";
        }else{
             $Buser->rollback(); 
             message("由于网络堵塞，本次申请提款失败,错误代码UCK001。\\n请联系在线客服。");
        }
    } catch (Exception $e) {
        $Buser->rollback(); 
        message("由于网络堵塞，本次申请提款失败,错误代码UCK002。\\n请联系在线客服。");
    }
}else{
    echo "<script>alert('提款申请失败！');window.close();</script>";
}

function message($value,$url=""){ //默认返回上一页
    header("Content-type: text/html; charset=utf-8");   
    $js  = "<script type=\"text/javascript\" language=\"javascript\">\r\n";
    $js .= "alert(\"".$value."\");\r\n";
    if($url) $js .= "window.location.href=\"$url\";\r\n";
    else $js .= "window.history.go(-1);\r\n";
    $js .= "</script>\r\n";
    echo $js;
    exit;
}
?>