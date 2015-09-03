<?php 
include_once("../../include/config.php");
include_once("../../common/login_check.php");
include_once("../common/audit.class.php");
include_once("../../class/user.php");
//判断用户是否设置出款银行卡
$pay_num = M('k_user',$db_config)
         ->where("site_id = '".SITEID."' and uid = '".$_SESSION['uid']."'")
         ->getField('pay_num');
if(empty($pay_num)){
    echo "<script>alert('请在入款前绑定您的入款银行！为了保证您的资金安全，请认真填写，谢谢！');</script>";
    header('Refresh: 0; url=set_card.php?pay_name='.urlencode($rs["pay_name"]));
    exit();
}


//出款判断
$status = M('k_user_bank_out_record',$db_config)
        ->field("order_num")
        ->where("uid= '".$_SESSION['uid']."' and (out_status = 0 or out_status = 4)")
        ->find();
if(!empty($status)){
    echo '您有订单号：'.$status['order_num'].',出款尚未完成.请勿频繁出款';
    exit();
}

 //稽核方法
 $audit_obj = A($_SESSION['uid']);
 $audit_data = $audit_obj->get_user_audit();
 $betAll = $audit_data['bet_all'];
 $count_dis = $audit_data['count_dis'];
 $count_xz = $audit_data['count_xz'];
 $out_data = array();
 $out_data['out_fee'] = $audit_data['out_fee'];//出款手续费
 $out_data['out_audit'] = $count_dis + $count_xz;//稽核扣除费用
 $out_data['fav_num'] = $count_dis;
 unset($_SESSION['out_money']);
 $_SESSION['out_money'] = $out_data;
 unset($audit_data['bet_all']);
 unset($audit_data['count_dis']);
 unset($audit_data['count_xz']);
 unset($audit_data['out_fee']);
 $total = $count_dis + $count_xz + $out_data['out_fee'];
 $total = sprintf("%.2f", $total); 
            //版权查询
$copy_right = user::copy_right(SITEID,INDEX_ID);

 ?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>线上取款</title>

<link type="text/css" href="./css/standard.css" rel="stylesheet">
<link rel="stylesheet" href="./css/template.css" type="text/css">
<link rel="stylesheet" href="./css/easydialog.css" type="text/css">
<link rel="stylesheet" href="./css/bank.css" type="text/css">
    <style type="text/css">
        .TdB{
            background:#C5D9F1;
        }
        .hide1, .hide2{
            display:none;
        }
        .TdR{
            background:#F2DCDB;
        }
        .TdG{
            background:#D8E4BC;
        }
        #tabalStyle td{
            padding:1px 0;
        }
        #bank_content p{
            line-height:18px;
        }
        #ShowBtn{
            margin-left: 15px;
        }
        #ShowBtn a{
            width:auto;
            border:1px solid #BBBBBB;
            display:block;
            float:left;
            padding:5px 10px;
            margin:3px 5px 0 0; 
            cursor:pointer;
        }
        #hide1{
            background:#C5D9F1;
        }
        #hide2{
            background:#F2DCDB;
        }
        #hide1:hover, #hide2:hover{
            background:#FDF5E6;
        }
				/*--- 按钮样式 ---*/
	.btn_001{
		cursor: pointer;
		margin: 0 1px 0 0;
		width: 85px;
		height: 26px;
		border: none;
		padding-top: 2px;
		color: #FFF;
		font-weight: bold;
		background: #3D3D3D url(./images/order_btn.gif) no-repeat 0 -80px;
	}
    </style>
</head>
<body id="bank_body">
    <div id="bank_header">
        <div id="bank_logo"></div>
    </div>
    <div id="bank_content">         
        <p style="padding-left:15px;padding-top:10px;">自出款後第一次存款开始之後
        <br>总有效投注：<?=$betAll?>&nbsp;（即所有有效投注额）<br><br></p>
        <div id="ShowBtn">
              <a id="hide1"><font style="font-size:12px;">显示</font>实际有效投注额</a>
              <a id="hide2"><font style="font-size:12px;">显示</font>优惠稽核</a>
        </div>
    	<div style="width:100%;overflow-x:auto;clear:both;padding-bottom:0!important;padding:0 0 18px;">
           <table border="0" cellspacing="1" cellpadding="0" width="640" style="width: 626px;" id="tabalStyle">
            <tbody><tr>
                <td width="150" class="TdB" rowspan="2">存款日期区间</td>
                <td width="55" class="TdB" rowspan="2">存款金额</td>
                <td width="55" class="TdB" rowspan="2">存款优惠</td>
                <td width="230" class="TdB hide1" colspan="3" style="display: none;">实际有效投注额</td>
                <td width="470" class="TdR hide2" colspan="8" style="display: none;">优惠稽核</td>
                <td width="340" class="TdG" colspan="5">常态稽核</td>
            </tr>
            <tr>
                <td width="45" class="TdB hide1" style="display: none;">体育</td>
                <td width="45" class="TdB hide1" style="display: none;">彩票</td>
                <td width="45" class="TdB hide1" style="display: none;">视讯</td>

                <td width="70" class="TdR hide2" style="display: none;">体育打码量</td>
                <td width="35" class="TdR hide2" style="display: none;">通过</td>
                <td width="70" class="TdR hide2" style="display: none;">彩票打码量</td>
                <td width="35" class="TdR hide2" style="display: none;">通过</td>
                <td width="70" class="TdR hide2" style="display: none;">视讯打码量</td>
                <td width="35" class="TdR hide2" style="display: none;">通过</td>
                <td width="80" class="TdR hide2" style="display: none;">综合打码</td>
                <td width="70" class="TdR hide2" style="display: none;">是否达到</td>
                
                <td width="70" class="TdG">常态打码量</td>
                <td width="60" class="TdG">放宽额度</td>
                <td width="45" class="TdG">通过</td>
                <td width="90" class="TdG">需扣除行政费用</td>
                <td width="70" class="TdG">需扣除金额</td>
              </tr>
      <?php 
       if (!empty($audit_data)) {
          $userAudit = array();
          foreach ($audit_data as $key => $v){ 
          //当前取款稽核相关信息
          $ak = $v['id'];
          $userAudit[$ak] =  array(    
                            'id'=>$v['id'],
                            'is_pass_zh'=>$v['is_pass_zh'],
                            'is_pass_ct'=>$v['is_pass_ct'],
                            'is_expenese_num'=>$v['is_expenese_num'],
                            'deduction_e'=>$v['deduction_e'],
                            'cathectic_sport'=>$v['cathectic_sport'],
                            'cathectic_fc'=>$v['cathectic_fc'],
                            'cathectic_video'=>$v['cathectic_video']
                             );
     ?>

      <tr class="m_cen">    
        <td style="width:160px;">起始:<?=$v['begin_date']?></td>
        <td rowspan="2"><?=$v['deposit_money']?></td>
        <td rowspan="2"><?=($v['atm_give']+$v['catm_give'])?></td>
        <td class="hide1" rowspan="2" style="display: none;"><?=$v['cathectic_sport']?></td>
        <td class="hide1" rowspan="2" style="display: none;"><?=($v['cathectic_fc']+0)?></td>
        <td class="hide1" rowspan="2" style="display: none;"><?=($v['cathectic_video']+0)?></td>
        <td class="hide2" rowspan="2" style="display: none;"><?=$v['cathectic_sport']?></td>
        <td class="hide2" rowspan="2" style="display: none;">-</td>
        <td class="hide2" rowspan="2" style="display: none;"><?=($v['cathectic_fc']+0)?></td>
        <td class="hide2" rowspan="2" style="display: none;">-</td>
        <td class="hide2" rowspan="2" style="display: none;"><?=($v['video_audit']+0)?></td>
        <td class="hide2" rowspan="2" style="display: none;">-</td>
        <td class="hide2" rowspan="2" style="display: none;"><?=$v['type_code_all']?></td>
        <td class="hide2" rowspan="2" style="display: none;"><?=zh_state($v['is_pass_zh'])?></td>
        <td rowspan="2"><?=$v['normalcy_code']?></td>
        <td rowspan="2"><?=$v['relax_limit']?></td>
        <td rowspan="2"><?=ct_state($v['is_pass_ct'])?></td>
        <td rowspan="2"><?=xz_state($v['is_expenese_num'])?></td>
        <td rowspan="2"><?=sprintf("%.2f",$v['deduction_e'])?></td></tr>
      <tr class="m_cen">
        <td>结束:<?=$v['end_date']?></td></tr>
    
      <?php } 
         unset($_SESSION['userAudit']);
         $_SESSION['userAudit'] = $userAudit;
        } 
       ?>
    </tbody></table>
        </div>
        <div style="padding-left:15px;padding-top:10px;">
<p>
<?php if (!empty($count_dis)): ?>
<span style="color:#0202FE">优惠稽核：</span>
<span style="#ff0000">未通過優惠稽核，需扣除存款優惠：<?=sprintf("%.2f",$count_dis)?><br></span>
<?php endif ?>
<?php if (!empty($count_xz)): ?>
<span style="color:#0202FE">常态性稽核：</span>
<span style="color:#ff0000">未達常態性稽核！需扣除50%行政費：<?=$count_xz?><br></span>
<?php endif ?>
<span style="color:#fff;background-Color:#900;padding:2px">優惠稽核 + 常態性稽核 + 手续费 共需扣除：<?=$total?></span><br><br>
此次出款时间为：<?=date('Y-m-d H:i:s')?>
</p>
</div>
 <div align="center" style="padding-bottom:10px;">
            <input type="button" class="btn_001" value="我要继续出款" name="continueWithdraw" id="continueWithdraw" onclick="setDepositsetDeposit('0')">
            <input type="submit" class="btn_001" value="放弃出款" name="cancelWithdraw" id="cancelWithdraw">
        </div>
     </div>
     <div id="bank_footer" align="center">
        <p>Copyright © <?=$copy_right['copy_right']?> Reserved</p>
     </div>
<script type="text/javascript"src="../public/js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
    document.getElementById('continueWithdraw').onclick=function() {
		window.location.replace("get_money_1.php?uid=6c1e559ea8ce11ed57d39");
	};
    document.getElementById('cancelWithdraw').onclick=function() {
		window.close();
	};
    
	var flashvars = {};
	var params = {  
		wmode : "Transparent"//加入这个参数后，那个嵌入的swf文件就不会被致于最上顶层，而是嵌入到指定的div里面  
	}; 			
	//swfobject.embedSWF('flash/logo.swf', 'logo_fl', '298', '78', "11.0.0", null,flashvars,params);
	var tabalStyle = $('#tabalStyle');
	var hide1 = $('.hide1'), hide2 = $('.hide2');
	$('#hide1').toggle(function(){
		$(this).find('font').html('隐藏');
		tabalStyle.animate({'width' : '+=230'},100);
		hide1.show();
	},function(){
		$(this).find('font').html('显示');
		tabalStyle.animate({'width' : '-=230'},100);
		hide1.hide();
	});
	$('#hide2').toggle(function(){
		$(this).find('font').html('隐藏');
		tabalStyle.animate({'width' : '+=470'},100);
		hide2.show();
	},function(){
	$(this).find('font').html('显示');
		tabalStyle.animate({'width' : '-=470'},100);
		hide2.hide();
	}); 
	$("input[name=btn_001]").button();
    $(function(){
        $(".show_1").mouseover(function(event) {
            $(this).css('background-Color', '#FAD8D9');
        });
        $(".show_1").mouseout(function(event) {
            $(this).css('background-Color', '');
        });
    })
</script>
</body></html>
<?php 
      //常态稽核状态返回
     function ct_state($ct){
        switch ($ct) {
            case '0':
                return "<font color=\"#ff0000\">未通過</font>";
                break;
            case '1':
                return "<font color=\"#00cc00\">通過</font>";
                break;
            case '2':
                return "-";
                break;
        }
    }

       //扣除行政费用状态
    function xz_state($xz){
        switch ($xz) {
            case '0':
                return "<font color=\"#ff0000\">否</font>";
                break;
            case '1':
                return "<font color=\"#00cc00\">是</font>";
                break;
            case '2':
                return "不需要稽核";
                break;
        }
    }

      //综合稽核状态返回
    function zh_state($st){
        switch ($st) {
            case '0':
                return "<font color=\"#ff0000\">否</font>";
                break;
            case '1':
                return "<font color=\"#00cc00\">是</font>";
                break;
            case '2':
                return "不需要稽核";
                break;
        }
    }

?>