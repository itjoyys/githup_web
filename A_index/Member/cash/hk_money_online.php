<?php
    include_once("../../include/config.php");
    include_once("../../common/login_check.php");
    include_once("../../class/user.php");

//版权查询
$copy_right = user::copy_right(SITEID,INDEX_ID);

    $userinfo=user::getinfo($_SESSION['uid']);

    $user_record=M('k_user_bank_in_record',$db_config)->field('id')->where("uid='".$_SESSION["uid"]."' and site_id='".SITEID."' and make_sure = '1'")->find();

    $level_des = M('k_user_level',$db_config)->field("RMB_pay_set")->where("id = '".$userinfo['level_id']."'")->find();

    //读取线上入款设定
    $data_1 = M('k_cash_config',$db_config)->join(" join k_cash_config_ol on k_cash_config_ol.cash_id = k_cash_config.id")->field("k_cash_config_ol.*")->where("k_cash_config.id = '".$level_des['RMB_pay_set']."'")->find();

    if(empty($userinfo['pay_num'])){
        echo "<script>alert('请在入款前绑定您的入款银行！为了保证您的资金安全，请认真填写，谢谢！');";
        echo "self.location.href='hk_money_1.php'</script>";exit;
    }

    $uid=intval($_SESSION["uid"]);
    $payid="";
    $paytype="";
    $payurl="";
    $info = '';

    $resulet = '';
    //and money_Already <".$money."           

    $info = M('pay_set',$db_config)->where("is_delete=0  and site_id='".SITEID."' and locate('".$userinfo[level_id]."',level_id)>0")->select();
    
    if($info){
        foreach ($info as $key => $value) {
            $table = M('k_user_bank_in_record',$db_config);
            $resulet = $table
             ->field('sum(deposit_num) as money')->where("site_id = '".SITEID."' and pay_id = '".$value['id']."' and into_style = 2 and make_sure = 1 and do_time like '%".date('Y-m-d')."%'")->find();
            $info[$key]['pay_money'] = !empty($resulet['money'])?$resulet['money']:0;

        }
        foreach ($info as $key => $value) {
            if( floatval($value['money_limits']) <= floatval($value['pay_money'])){
                unset($info[$key]);
            }
        }
        
        shuffle($info);

        $payid   = $info[0]['id'];
        $paytype = $info[0]['pay_type'];
    }else{
        echo "<script>alert(\"非常抱歉，在线支付暂时无法使用！请联系客服！\");</script>";
        echo "非常抱歉，在线支付暂时无法使用！请联系客服！";
        exit();
    }


    if($payid=="")
    {
        echo "<script>alert(\"非常抱歉，在线支付暂时无法使用！请联系客服！\");window.close();</script>";
        echo "非常抱歉，在线支付暂时无法使用！请联系客服！";
        exit();
    }

    $_SESSION['payid']=$payid;
    $_SESSION['paytype']=$paytype;

    if($_SESSION['paytype']==6){
    	header('Location:../api/baofoo/data.php');
    }elseif ($_SESSION['paytype']==2) {
    	header('Location:../api/yeepay/data.php');
    }elseif($_SESSION['paytype']==3){
        header('Location:../api/ips/data.php');
    }elseif($_SESSION['paytype']==7){
        header('Location:../api/Dinpay/data.php');
    }elseif($_SESSION['paytype']==8){
        header('Location:../api/ecpss/data.php');
    }elseif($_SESSION['paytype']==9){
        header('Location:../api/states/data.php');
    }elseif($_SESSION['paytype']==1){
        header('Location:../api/hnapay/data.php');
    }

?>
