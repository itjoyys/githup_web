<?php 
    include_once("../../include/config.php");
    include_once "../common/member_config.php";
    include_once("../../common/login_check.php");
    include_once("../../class/user.php");
    //判断用户是否设置银行卡
    $user = M('k_user',$db_config)
        ->field('pay_card,level_id,money,pay_num,pay_address,pay_name')
        ->where("uid = '".$_SESSION['uid']."' and site_id='".SITEID."'")
        ->find();
    if(empty($user['pay_card'])){
        header('Refresh: 0; url=set_card.php');
        exit();
    }

    //会员出款上限下限
    $level_u=M('k_user_level',$db_config)
        ->where("id='".$user['level_id']."'")
        ->getField('RMB_pay_set');
    if(!empty($level_u)){
        //获取用户出款优惠
        $pay_data = M('k_cash_config_view',$db_config)
              ->field("ol_atm_max,ol_atm_min")
              ->where("id='".$level_u."'")
              ->find();
    }else{
        $pay_data = M('k_cash_config_view',$db_config)
                 ->field("ol_atm_max,ol_atm_min")
                 ->where("site_id ='".SITEID."' and type='1'")
                 ->find();
    }
    //扣除费用
     $out_data = array();
     $out_data = $_SESSION['out_money'];
 
//版权查询
$copy_right = user::copy_right(SITEID,INDEX_ID);
 ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title></title>
        <link href="./css/jquery-ui.css" type="text/css" rel="stylesheet">
        <link type="text/css" href="./css/standard.css" rel="stylesheet">
        <link rel="stylesheet" href="./css/template.css" type="text/css">
        <link rel="stylesheet" href="./css/easydialog.css" type="text/css">
        <link rel="stylesheet" href="./css/bank.css" type="text/css">
        <script type="text/javascript"src="../public/js/jquery-1.8.3.min.js"></script>
    </head>
    <body id="bank_body">
        <div id="bank_header">
            <div id="bank_logo"></div>
        </div>
        <div id="bank_content">
            <div id="pay_title">&nbsp;</div>
            <div id="pay_info">
                <span></span>
                <ul>
                    <li class="pay_info">
                        <div class="input yue">当前余额：<?=$user['money']  ?></div>&nbsp;&nbsp;
                        <input type="button" class="inputSub2" value="修改取款密码" onclick="window.open('edit_pass.php','Chg_pass','width=380,height=200,status=no,scrollbars=no');">
                    </li>
                </ul>
                <form name="withdrawal" action="getmoneydo.php" method="post" id="withdrawal_form">
                    <input type="hidden" name="uu_out" readonly="" value="oucd">
                    <ul>
                        <li class="pay_info">
                            <div class="lable"><span class="star">*</span>取款密码：</div>
                            <div class="input"><input type="password" id="password" name="qk_pwd" size="5" maxlength="4"></div>
                        </li>
                        <li class="pay_info">
                            <div class="lable"><span class="star"></span>提款人：</div>
                            <div class="input"><?=$user['pay_name']  ?></div>
                        </li>
                        <li class="pay_info">
                            <div class="lable"><span class="star">*</span>取款金额：</div>
                            <div class="input">
                                <input type="text" id="cash" name="cash" size="5" maxlength="10" onblur="calculate(); this.value = this.value.replace(/[^0-9]/g,'');" onkeyup="this.value = this.value.replace(/[^0-9]/g,'')">
                                -
                                手续费
                                <input type="text" id="COM" name="COM" size="5" readonly="" value="<?=$out_data['out_fee']?>">
                                =
                                实收金额
                                <input type="text" id="real_cash" name="real_cash" size="5" readonly="">
                            </div>
                        </li>
                        <li class="pay_info" style="display:none">
                            <div class="lable">&nbsp;</div>
                            <div class="input"><font color="#FF0000"></font></div>
                        </li>
                        <li class="pay_info"><div class="lable">&nbsp;</div>
                            <div class="input"><font color="#444444">出款上限:<?=$pay_data['ol_atm_max']?> &nbsp;&nbsp;出款下限:<?=$pay_data['ol_atm_min']?></font></div>
                        </li>

                        <li class="pay_info" style="display:none">
                            <input type="hidden" name="pay_address" size="6" maxlength="10" value="<?=$user['pay_address']  ?>">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        </li>
                        <li class="pay_info">
                            <div class="lable"><span class="star"></span>所属银行：</div><div class="input"><?=bank_type($user['pay_card'])?></div>
                        </li>
                        <li class="pay_info"><div class="lable">
                            <span class="star"></span>银行帐号：</div><div class="input"><?=$user['pay_num']  ?></div>
                        </li>

                        <li class="pay_info">
                            <div class="lable">&nbsp;</div>
                            <div class="input">
                                <input name="reset" type="reset" class="btn_001" value="重设">&nbsp;&nbsp;&nbsp;
                                <input type="button" name="button" id="button" class="btn_001" value="确定送出" onclick="check_all()"><br><br> 
                            </div>
                        </li>
                    </ul>
                    <br>
                </form>
            </div> 
            <div style="clear: both;"></div>
        </div>
        <div id="bank_footer" align="center">
            <p>Copyright © <?=$copy_right['copy_right']?> Reserved</p>
        </div> 
    </body>
</html>


<script type="text/javascript">
    var all_fee;
    var obj_cash;
    function check_all(){
        $("input[type=button]").disabled = true;
        var obj_pwd = $("#password");
        obj_cash = $("#cash");
        all_fee = <?=($out_data['out_fee']+$out_data['out_audit'])?>;
        if(obj_pwd.val()=='' || obj_pwd.val().length < 4){
            // 密码 不得为空
            obj_pwd.focus();
            alert("密码不能为空并且为4位数字!");
            $("input[type=button]").disabled = false;
            return false;
        }
        if(obj_cash.val()==''){
            // 额度 不得为空
            obj_cash.focus();
            alert("额度请务必输入!");
            $("input[type=button]").disabled = false;
            return false;
        }
        var atm_max = <?=$pay_data['ol_atm_max']?>;
        var atm_min = <?=$pay_data['ol_atm_min']?>;
        var usermoney = <?=$user["money"]?>;
        if(obj_cash.val() > usermoney){
            obj_cash.focus();
            alert("取款额度不能超过余额"+usermoney);
            $("input[type=button]").disabled = false;
            return false;
        }
        if(obj_cash.val()>atm_max){
            obj_cash.focus();
            alert("取款额度不能超过出款上限"+atm_max);
            $("input[type=button]").disabled = false;
            return false;
        }
        if(obj_cash.val()<atm_min){
            obj_cash.focus();
            alert("取款额度不能低于"+atm_min);
            $("input[type=button]").disabled = false;
            return false;
        }
        var creal_cash = eval(obj_cash.val() - all_fee);
        var audit_b = <?=$out_data['out_audit']?>;
        if(audit_b>0){
            if(!confirm("尊敬的会员您好：\r\n您的有效打码量未达到,取款将扣除金额"+audit_b.toFixed(2)+"，并需经财务审核方可出款\r\n(请参阅优惠活动规则)")){
                return false;
            }
        }
        $("#withdrawal_form").submit();
    }
    function calculate(){
        obj_cash = $("#cash");
        all_fee = <?=$out_data['out_fee']?>;
        var obj_real_c = $("#real_cash");
        real_cash = eval(obj_cash.val() - all_fee);
        if( real_cash <= 0 ) {
            obj_real_c.val('0');
        }else {
            obj_real_c.val(real_cash);
        }
    }

    function max_MAX() {
        if(obj_cash.val() >(5000000) ){
            alert("您提款金额超过5000000需经过审核，将于24小时内到帐，请耐心等候!");
        }
    }
</script>


<style>
    /*--- 按钮样式 ---*/
    .btn_001{cursor: pointer;margin: 0 1px 0 0;width: 85px;height: 26px;border: none;padding-top: 2px;color: #FFF;font-weight: bold;background: #3D3D3D url(./images/order_btn.gif) no-repeat 0 -80px;}
    .inputSub2{color: #FFF;cursor: pointer;height: 26px;width: 99px;border: none;padding-top: 2px;margin: 0 1px 0 0;background: url(./images/mem_league_btn.gif) no-repeat;}
    .yue{width: 270px;text-align: right;height: 30px;line-height: 30px}
</style>