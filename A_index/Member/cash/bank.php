<?php
    include_once("../../include/config.php");
    include_once("../../common/login_check.php");
	include_once("../common/member_config.php");
    include_once("../../common/function.php");
    include_once("../../class/user.php");

//版权查询
$copy_right = user::copy_right(SITEID,INDEX_ID);

    // 查询用户基本信息
    $user=M('k_user',$db_config)->where('uid='.$_SESSION['uid'])->find();
    if(empty($user['pay_num'])){
        echo "<script>alert('请在入款前绑定您的入款银行！为了保证您的资金安全，请认真填写，谢谢！');";
        echo "self.location.href='hk_money.php'</script>";exit;
    }

    $level_des = M('k_user_level',$db_config)->field("RMB_pay_set")->where("id = '".$user['level_id']."'")->find();


    $data_1 = M('k_cash_config',$db_config)->join(" join k_cash_config_line on k_cash_config_line.cash_id = k_cash_config.id")->field("k_cash_config_line.*")->where("k_cash_config.id = '".$level_des['RMB_pay_set']."'")->find();


    //入款银行卡
    $banks=M('k_bank',$db_config)->where("is_delete='0' and site_id='".SITEID."' and locate('".$user['level_id']."',level_id)>0")->select();

    foreach ($banks as $key => $value) {
        $money=M('k_user_bank_in_record',$db_config)->field('sum(deposit_money) as c,bid')->where('bid='.$value['id'].' and log_time>"'.date("Y-m-d").' 00.00.00"')->find();
        if($money['c']>$value['stop_amount']){
            unset($banks[$key]);
        }
    }
    shuffle($banks);
    if(empty($banks)){
        echo "<script>alert('没有可用的银行卡，请联系客服！');";
        echo "window.close();</script>";exit;
    }
    $order=date("YmdHis").mt_rand(1000,9999);//订单号
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7">
		<title>公司入款</title>
		<link href="css/jquery-ui.css" type="text/css" rel="stylesheet">
		<link type="text/css" href="css/standard.css" rel="stylesheet">
		<link rel="stylesheet" href="css/template.css" type="text/css">
		<link rel="stylesheet" href="css/easydialog.css" type="text/css">
		<link rel="stylesheet" href="css/bank.css" type="text/css">
		<script  type="text/javascript" src="../public/date/WdatePicker.js"></script>
		<script  type="text/javascript" src="../public/js/PCASClass.js"></script>
		<script type="text/javascript" src="js/jquery-1.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script type="text/javascript" src="js/jquery.js"></script>
	</head>
	<body id="bank_body">
		<div id="bank_header">
		<div id="bank_logo"></div>
		</div>
		<div id="bank_content">
			<div style="margin-left:10px"> 
				<a style="color:red;">公司银行帐号随时更换! 请每次存款都至 [公司入款] 进行操作。 入款至已过期帐号，公司无法查收，恕不负责!</a><br>
				欢迎使用公司入款平台!请依照以下步骤进行存款。如需说<a href='javascript:;' onclick="javascript:window.open('pay_explain.htm','','width=650,height=400,scrollbars=yes')">公司入款流程解说</a>。 <br>
				<br>
			</div>
			<div class="ui-tabs ui-widget ui-widget-content ui-corner-all" id="DepositTabs" style="">
				<ul role="tablist" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
					<li aria-selected="true" aria-labelledby="ui-id-1" aria-controls="deposit_tab1" tabindex="0" role="tab" class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a id="ui-id-1" tabindex="-1" role="presentation" class="ui-tabs-anchor" href="#deposit_tab1">步骤1:请选择您所使用的银行</a></li>
					<li aria-selected="false" aria-disabled="true" aria-labelledby="ui-id-2" aria-controls="deposit_tab2" tabindex="-1" role="tab" class="ui-state-default ui-corner-top ui-state-disabled"><a id="ui-id-2" tabindex="-1" role="presentation" class="ui-tabs-anchor" href="#deposit_tab2">步骤2:请选择您欲转入的银行帐号</a></li>
					<li aria-selected="false" aria-disabled="true" aria-labelledby="ui-id-3" aria-controls="deposit_tab3" tabindex="-1" role="tab" class="ui-state-default ui-corner-top ui-state-disabled"><a id="ui-id-3" tabindex="-1" role="presentation" class="ui-tabs-anchor" href="#deposit_tab3">步骤3:填写您的转帐资料</a></li>
					<li aria-selected="false" aria-disabled="true" aria-labelledby="ui-id-4" aria-controls="deposit_tab4" tabindex="-1" role="tab" class="ui-state-default ui-corner-top ui-state-disabled"><a id="ui-id-4" tabindex="-1" role="presentation" class="ui-tabs-anchor" href="#deposit_tab4">步骤4:完成</a></li>
				</ul>
				<!-- 确认页面 -->
				<div style="width:100%height:100%;" id="tanchuceng">
					<div id="ui_dialog" class="ui-dialog ui-widget ui-widget-content ui-corner-all ui-draggable ui-resizable ui-dialog-buttons" tabindex="-1" role="dialog" aria-labelledby="ui-id-5" style="display:none ;border :0px ; z-index: 1002; position: absolute; height: 100%; width: 500px; top: 30%; left:20%; ">
						<div class="ui-dialog-titlebar ui-widget-header ui-corner-all ui-helper-clearfix">
							<span id="ui-id-5" class="ui-dialog-title">请再次确认提交资讯!</span>
							<a href="#" class="ui-dialog-titlebar-close ui-corner-all" role="button"><span class="ui-icon ui-icon-closethick">close</span></a>
						</div>
						<div id="checkInfo" style="width: auto; min-height: 40px; height: auto;" class="ui-dialog-content ui-widget-content" scrolltop="0" scrollleft="0">
							<br>
							<form id="bankForm" name="bankForm" method="post">
								<input type="hidden" id="s_way" name="s_way" value="0">
								<input type="hidden" name="uid" value="095e2697e4a2ee8299e7d">
								<input type="hidden" id="abandon_sp" name="abandon_sp" value="N">
								<table width="100%">
									<tbody>
										<tr>
											<td width="100">订单号:</td>
											<td id="s_order_num" name="s_order_num"  namea="s_order_num" ></td>
										</tr>
										<tr>
											<td>存入金额:</td>
											<td id="s_amount" name="s_amount" namea="s_amount"></td>
										</tr>
										<tr>
											<td>存入时间:</td>
											<td id="s_datetime" name="s_datetime" namea="s_datetime"></td>
										</tr>
										<tr>
											<td>存款人姓名:</td>
											<td id="s_name" name="s_name" namea="s_name"></td>
										</tr>
										<tr>
											<td>存款方式:</td>
											<td id="s_deposit_way" name="s_deposit_way"  namea="s_deposit_way" ></td>
											<td  name="s_deposit_way_1"  namea="s_deposit_way_1" style="display:none;"></td>
										</tr>
										<tr id="s_bank_location_row" style="">
											<td>所属分行:</td>
											<td id="s_bank_location" name="s_bank_location" namea="s_bank_location"></td>
										</tr>  
									</tbody>
								</table>
							</form>
						</div>
						<div class="ui-resizable-handle ui-resizable-n" style="z-index: 1000;"></div>
						<div class="ui-resizable-handle ui-resizable-e" style="z-index: 1000;"></div>
						<div class="ui-resizable-handle ui-resizable-s" style="z-index: 1000;"></div>
						<div class="ui-resizable-handle ui-resizable-w" style="z-index: 1000;"></div>
						<div class="ui-resizable-handle ui-resizable-se ui-icon ui-icon-gripsmall-diagonal-se ui-icon-grip-diagonal-se" style="z-index: 1000;"></div>
						<div class="ui-resizable-handle ui-resizable-sw" style="z-index: 1000;"></div>
						<div class="ui-resizable-handle ui-resizable-ne" style="z-index: 1000;"></div>
						<div class="ui-resizable-handle ui-resizable-nw" style="z-index: 1000;"></div>
						<div class="ui-dialog-buttonpane ui-widget-content ui-helper-clearfix">
							<div class="ui-dialog-buttonset">
								<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="tijiao()";>
								<span class="ui-button-text">确认</span>
								</button>
								<button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" role="button" aria-disabled="false" onclick="document.getElementById('ui_dialog').style.display='none';"><span class="ui-button-text">关闭</span>
								</button>
							</div>
						</div>
					</div>
				</div>
				<!-- 步骤1 -->
				<div aria-hidden="false" aria-expanded="true" role="tabpanel" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-1" id="deposit_tab1" style="background-color: rgb(255, 255, 231);">
					<input name="uid" value="f836e1086f38fae69b0e8" type="hidden">
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="2" style="vertical-align:middle;" type="radio"></td>
								<td id="b2" style="vertical-align:middle;" width="50"><a href="http://www.icbc.com.cn/" target="_blank"><img src="./images/bank_6.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="3" style="vertical-align:middle;" type="radio"></td>
								<td id="b3" style="vertical-align:middle;" width="50"><a href="http://www.ccb.com/" target="_blank"><img src="./images/bank_4.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="9" style="vertical-align:middle;" type="radio"></td>
								<td id="b9" style="vertical-align:middle;" width="50"><a href="http://www.abchina.com/" target="_blank"><img src="./images/bank_2.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="8" style="vertical-align:middle;" type="radio"></td>
								<td id="b8" style="vertical-align:middle;" width="50"><a href="http://www.chinapost.com.cn/" target="_blank"><img src="./images/bank_8.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="1" style="vertical-align:middle;" type="radio"></td>
								<td id="b1" style="vertical-align:middle;" width="50"><a href="http://www.boc.cn/" target="_blank"><img src="./images/bank_1.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="4" style="vertical-align:middle;" type="radio"></td>
								<td id="b4" style="vertical-align:middle;" width="50"><a href="http://www.cmbchina.com/" target="_blank"><img src="./images/bank_5.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="7" style="vertical-align:middle;" type="radio"></td>
								<td id="b7" style="vertical-align:middle;" width="50"><a href="http://www.bankcomm.com/" target="_blank"><img src="./images/bank_7.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="5" style="vertical-align:middle;" type="radio"></td>
								<td id="b5" style="vertical-align:middle;" width="50"><a href="http://www.cmbc.com.cn/" target="_blank"><img src="./images/bank_3.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="18" style="vertical-align:middle;" type="radio"></td>
								<td id="b18" style="vertical-align:middle;" width="50"><a href="http://bank.ecitic.com/" target="_blank"><img src="./images/bank_24.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="50" style="vertical-align:middle;" type="radio"></td>
								<td id="b50" style="vertical-align:middle;" width="50"><a href="http://www.cib.com.cn/" target="_blank"><img src="./images/bank_9.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="11" style="vertical-align:middle;" type="radio"></td>
								<td id="b11" style="vertical-align:middle;" width="50"><a href="http://ebank.spdb.com.cn/" target="_blank"><img src="./images/bank_11.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="14" style="vertical-align:middle;" type="radio"></td>
								<td id="b14" style="vertical-align:middle;" width="50"><a href="http://www.18ebank.com/" target="_blank"><img src="./images/bank_18.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="23" style="vertical-align:middle;" type="radio"></td>
								<td id="b23" style="vertical-align:middle;" width="50"><a href="" target="_blank"><img src="./images/bank_40.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="17" style="vertical-align:middle;" type="radio"></td>
								<td id="b17" style="vertical-align:middle;" width="50"><a href="http://www.cebbank.com/" target="_blank"><img src="./images/bank_23.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="100" style="vertical-align:middle;" type="radio"></td>
								<td id="b100" style="vertical-align:middle;" width="50"><a href="https://www.alipay.com/" target="_blank"><img src="./images/zfb.png" width="154px" height='33px'/></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="101" style="vertical-align:middle;" type="radio"></td>
								<td id="b101" style="vertical-align:middle;" width="50"><a href="https://wx.qq.com/" target="_blank"><img src="./images/wechat.png" width="154px" height='33px'/></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="102" style="vertical-align:middle;" type="radio"></td>
								<td id="b102" style="vertical-align:middle;" width="50"><a href="https://www.tenpay.com/v2/" target="_blank"><img src="./images/cft.png" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="22" style="vertical-align:middle;" type="radio"></td>
								<td id="b22" style="vertical-align:middle;" width="50"><a href="http://www.gdb.com.cn/" target="_blank"><img src="./images/bank_31.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="24" style="vertical-align:middle;" type="radio"></td>
								<td id="b24" style="vertical-align:middle;" width="50"><a href="http://www.sdb.com.cn/" target="_blank"><img src="./images/bank_32.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="25" style="vertical-align:middle;" type="radio"></td>
								<td id="b25" style="vertical-align:middle;" width="50"><a href="http://www.cbhb.com.cn/" target="_blank"><img src="./images/bank_25.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="26" style="vertical-align:middle;" type="radio"></td>
								<td id="b16" style="vertical-align:middle;" width="50"><a href="http://www.dongguanbank.cn/" target="_blank"><img src="./images/bank_33.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="12" style="vertical-align:middle;" type="radio"></td>
								<td id="b12" style="vertical-align:middle;" width="50"><a href="http://www.gzcb.com.cn/" target="_blank"><img src="./images/bank_12.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="15" style="vertical-align:middle;" type="radio"></td>
								<td id="b15" style="vertical-align:middle;" width="50"><a href="http://www.hccb.com.cn/" target="_blank"><img src="./images/bank_19.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="19" style="vertical-align:middle;" type="radio"></td>
								<td id="b19" style="vertical-align:middle;" width="50"><a href="http://www.czbank.com/czbank/" target="_blank"><img src="./images/bank_26.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="27" style="vertical-align:middle;" type="radio"></td>
								<td id="b27" style="vertical-align:middle;" width="50"><a href="http://www.nbcb.com.cn/" target="_blank"><img src="./images/bank_34.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="28" style="vertical-align:middle;" type="radio"></td>
								<td id="b28" style="vertical-align:middle;" width="50"><a href="http://www.hkbea.com/hk/index_tc.htm" target="_blank"><img src="./images/bank_13.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="16" style="vertical-align:middle;" type="radio"></td>
								<td id="b16" style="vertical-align:middle;" width="50"><a href="http://www.wzcb.com.cn/" target="_blank"><img src="./images/bank_20.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="29" style="vertical-align:middle;" type="radio"></td>
								<td id="b29" style="vertical-align:middle;" width="50"><a href="http://www.jshbank.com/" target="_blank"><img src="./images/bank_27.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="30" style="vertical-align:middle;" type="radio"></td>
								<td id="b30" style="vertical-align:middle;" width="50"><a href="http://www.njcb.com.cn/" target="_blank"><img src="./images/bank_35.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="31" style="vertical-align:middle;" type="radio"></td>
								<td id="b31" style="vertical-align:middle;" width="50"><a href="http://www.961111.cn/" target="_blank"><img src="./images/bank_14.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="32" style="vertical-align:middle;" type="radio"></td>
								<td id="b32" style="vertical-align:middle;" width="50"><a href="http://www.shrcb.com/" target="_blank"><img src="./images/bank_21.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="20" style="vertical-align:middle;" type="radio"></td>
								<td id="b20" style="vertical-align:middle;" width="50"><a href="http://www.hkbchina.com/" target="_blank"><img src="./images/bank_28.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="33" style="vertical-align:middle;" type="radio"></td>
								<td id="b33" style="vertical-align:middle;" width="50"><a href="http://www.zhnx.com.cn/" target="_blank"><img src="./images/bank_36.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="34" style="vertical-align:middle;" type="radio"></td>
								<td id="b34" style="vertical-align:middle;" width="50"><a href="http://www.sdebank.com/" target="_blank"><img src="./images/bank_16.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="35" style="vertical-align:middle;" type="radio"></td>
								<td id="b35" style="vertical-align:middle;" width="50"><a href="http://www.ydnsh.com/" target="_blank"><img src="./images/bank_22.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="36" style="vertical-align:middle;" type="radio"></td>
								<td id="b36" style="vertical-align:middle;" width="50"><a href="http://www.czcb.com.cn/" target="_blank"><img src="./images/bank_29.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="37" style="vertical-align:middle;" type="radio"></td>
								<td id="b37" style="vertical-align:middle;" width="50"><a href="http://www.bjrcb.com/" target="_blank"><img src="./images/bank_37.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="38" style="vertical-align:middle;" type="radio"></td>
								<td id="b38" style="vertical-align:middle;" width="50"><a href="http://www.cqcbank.com/" target="_blank"><img src="./images/bank_38.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="39" style="vertical-align:middle;" type="radio"></td>
								<td id="b39" style="vertical-align:middle;" width="50"><a href="http://www.gx966888.com/" target="_blank"><img src="./images/bank_39.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="40" style="vertical-align:middle;" type="radio"></td>
								<td id="b40" style="vertical-align:middle;" width="50"><a href="http://www.jsbchina.cn/" target="_blank"><img src="./images/bank_42.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="41" style="vertical-align:middle;" type="radio"></td>
								<td id="b41" style="vertical-align:middle;" width="50"><a href="http://www.jlbank.com.cn/" target="_blank"><img src="./images/bank_41.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="42" style="vertical-align:middle;" type="radio"></td>
								<td id="b42" style="vertical-align:middle;" width="50"><a href="http://www.bocd.com.cn/" target="_blank"><img src="./images/bank_43.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="10" style="vertical-align:middle;" type="radio"></td>
								<td id="b10" style="vertical-align:middle;" width="50"><a href="http://www.hxb.com.cn/" target="_blank"><img src="./images/bank_10.jpg" /></a></td>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="13" style="vertical-align:middle;" type="radio"></td>
								<td id="b13" style="vertical-align:middle;" width="50"><a href="http://www.bankofbeijing.com.cn/" target="_blank"><img src="./images/bank_17.jpg" /></a></td>
							</tr>
							<tr>
								<td style="vertical-align:middle;" align="center" width="5" height="40"><input name="bank_id" value="21" style="vertical-align:middle;" type="radio"></td>
								<td id="b21" style="vertical-align:middle;" width="50"><a href="http://www.bankofshanghai.com/" target="_blank"><img src="./images/bank_30.jpg" /></a></td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<td colspan="8" align="center"><input value="下一步" class="btn_001" onClick="setBank(1);isnullbank();" type="button"></td>
							</tr>
						</tbody>
					</table>
					<span>提醒您：同银行转帐才能立即到帐喔!</span> 
				</div>
				<!-- 步骤2 -->
				<div aria-hidden="true" aria-expanded="false" role="tabpanel" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-2" id="deposit_tab2" style="background-color: rgb(255, 255, 231); display: none;">
					<table width="100%" border="0">
						<tbody>
							<tr>
								<td colspan="6">
									<span style="font-size:12pt; color:#910000; background-color:#efb2b2;">优先推荐!</span>
									<span style="color:#e58383; font-weight:bold;">※提醒您：同银行转帐才能立即到帐喔!</span>
								</td>
							</tr>
							<tr style="vertical-align:middle;"> </tr>
							<tr>
								<td colspan="6">&nbsp;</td>
							</tr>
							<tr>
								<?$i = 0;foreach($banks as $k=>$v){?>
								<td style="padding: 10px 0 10px 10px; vertical-align:middle;" align="center" width="20" height="80"><input name="deposit_id" value="<?=$v['id']?>" style="vertical-align:middle;" type="radio">
								</td>
								<td id="<?=$v['id']?>" width="200" style="height:30px;" bgcolor=""><b>开户行网点：</b><span class="bank"><?=$v['card_address']?></span><br>
								<b>收款人：</b><span class="payee"><?=$v['card_userName']?></span><br>
								<b>银行：</b><span class="depositbank"><?=bank_type($v['bank_type'])?></span><br>
								<b>帐号：</b><span class="account"><?=$v['card_ID']?></span> </td>
								<?php if($i%2==1){echo '</tr><tr>';} $i++;?>

								<?}?>
							</tr>
							<tr>
								<td colspan="8">&nbsp;</td>
							</tr>
							<tr>
								<td colspan="8" align="center">
									<input value="上一步" class="btn_001" onClick="setBank(0)" type="button">
									&nbsp;
									<input value="下一步" class="btn_001" onClick="setDeposit(1);" type="button">
								</td>
							</tr>
							<tr>
								<td colspan="8"><br>
								※提醒您：<br>
								以上银行帐号限本次存款用，帐号不定期更换!每次存款前请依照本页所显示的银行帐号入款。如入款至已过期帐号，线上娱乐城无法查收，恕不负责! </td>
							</tr>
						</tbody>
					</table>
				</div>
				<!-- 步骤3 -->
				<div aria-hidden="true" aria-expanded="false" role="tabpanel" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-3" id="deposit_tab3" style="background-color: rgb(255, 255, 231); display: none;">
					<div> <span class="pay_under"><font color="2b78e4">■</font> 选择银行已完成!以下是您欲转帐的银行帐户资料..</span><br>
						<table width="300px" border="0">
							<tbody>
							<tr>
								<td>
									<span id="DepositInfo" name="DepositInfo"></span><br>
									<span><b>订单号：</b></span><span id="OrderNum"><?=$order?></span><br>
								</td>
								<td>
									<input value="点选复制" onClick="CopyCode(0)" style="font-size:8pt; width:100px; height:18px;line-height:10px;" type="button">
									<br>
									<input value="点选复制" onClick="CopyCode(1)" style="font-size:8pt; width:100px; height:18px;line-height:10px;" type="button">
									<br>
									<input value="点选复制" onClick="CopyCode(2)" style="font-size:8pt; width:100px; height:18px;line-height:10px;" type="button">
									<br>
									<input value="点选复制" onClick="CopyCode(3)" style="font-size:8pt; width:100px; height:18px;line-height:10px;" type="button">
									<br>
									<input value="点选复制" onClick="CopyCode(4)" style="font-size:8pt; width:100px; height:18px;line-height:10px;" type="button">
									<br>
								</td>
							</tr>
							</tbody>
						</table>
						<span>※请备份订单号，并复制进您的工作汇款备注栏</span><br>
						<br>
						<br>
						<span class="pay_under"><font color="2b78e4">■</font> 接下来您可以透过以下方式完成转帐...</span><br>
						<span>1.网络银行转帐：登入您的网络银行完成转帐。</span><br>
						<span id="BankInfo" name="BankInfo"></span><span>点银行图示前往网银登入页面</span><br>
						<span>2. ATM转帐: 到您最近的ATM将款项转到上述银行账号。</span><br>
						<span>3. ATM现存: 到上述银行ATM以现金存入银行账号</span><br>
						<span>4. 银行柜台: 到您最近的银行将款项转到上述银行账号。</span><br>
						<br>
						<br>
						<span>※1. 请勿存入整数金额，以免延误财务查收。</span><br>
						<span>※2. 转帐完成后请保留单据作为核对证明。</span><br>
						<br>
						<span class="pay_under"><font color="2b78e4">■</font> 请填写您的转帐资料:</span><br>
					</div>

					<input id="bank_card" name="bank_card" value="" type="hidden">
					<input id="bid" name="bid" value="" type="hidden">
					<input id="is_firsttime" name="is_firsttime" value="<?if($user_record['uid']){echo '1';}else{ echo '0';}?>" type="hidden">
					<input id="order_num" name="order_num" value="<?=$order?>" type="hidden">

					<table id="depositTable" style="border-top-style:solid; border-bottom-style:solid; border-left-style:solid;border-right-style:solid" width="650">
					<tbody>
					<tr height=25>
					<td width="21%">．订单号:</td>
					<td><input id="order_num" name="order_num" disabled="disabled" value="<?=$order?>" type="text"></td>
					</tr>
					<tr height=25>
					<td width="21%">．存入金额:</td>
					<td><input id="deposit_num" name="deposit_num" onkeyup="clearNoNum(this);" onBlur="offerPrompt();" type="text"></td>
					</tr>
					<tr height=25>
					<td width="21%">．存入时间:</td>
					<td><input id="in_date" name="in_date" value="<?=date('Y-m-d H:i:s')?>" type="text"  onClick="WdatePicker({dateFmt:'yyyy-MM-dd HH:mm:ss'})" readonly="readonly" style="width: 140px;"  class="za_text Wdate">
					</tr>
					<tr height=25>
					<td width="21%">．存款人姓名/微信昵称:</td>
					<td><input id="in_name" name="in_name" type="text"></td>
					</tr>
					<tr height=25>
					<td width="21%">．存款方式:</td>
					<td><label>
					<input name="deposit_way" value="1" id="deposit_way" type="radio" checked='' fangshi="网银转帐">
					网银转帐</label>
					<label>
					<input name="deposit_way" value="4" id="deposit_way" type="radio" fangshi="银行柜台">
					银行柜台</label>
					<label>
					<input name="deposit_way" value="3" id="deposit_way" type="radio" fangshi="ATM现金入款">
					ATM现金入款</label>
					<label>
					<input name="deposit_way" value="2" id="deposit_way" type="radio" fangshi="ATM自动柜员机">
					ATM自动柜员机</label><br>
					<label>
					<input name="deposit_way" value="5" id="deposit_way" type="radio" fangshi="手机转帐">
					手机转帐</label>
					<font color="red"><span id="deposit_way2"></span></font> 
					<label>
					<input name="deposit_way" value="6" id="deposit_way" type="radio" fangshi="支付宝转账">
					支付宝转账</label>
					<label>
					<input name="deposit_way" value="7" id="deposit_way" type="radio" fangshi="财付通">
					财付通</label>
					<label>
					<input name="deposit_way" value="8" id="deposit_way" type="radio" fangshi="微信支付">
					微信支付</label></td>
					</tr>
					<tr id="bank_location_row" style="display: none;" height=25>
					<td id="bankBranches" width="21%">．所属分行:</td>
					<td>
					<select name="bank_location1" id="bank_location1"></select>
					<select name="bank_location2" id="bank_location2"></select>
					<input name="bank_location4" id="bank_location4"onkeyup="value=value.replace(/[^\a-zA-Z\u4E00-\u9FA5]/g,'')" size="8" type="text">
					(例：“广东省 佛山市 豪苑支行”) </td>
					</tr>
					</tbody>
					</table>
					<span>※1.请确实填写转帐金额与时间。</span><br>
					<span>※2.每笔转帐请提交一次。</span><br>
					<span>※3.若您使用ATM存款，请填写ATM所属分行，会加快您的款项到帐时间。</span><br>
					<br>
					<center>
					<input value="上一步" class="btn_001" onClick="setDeposit(0)" type="button">
					&nbsp;  
					<input value="提交申请" class="btn_001" onClick="submit_check();" type="button">
					</center>
					<input type='hidden' id='max' value="<?=$data_1['line_catm_max']?>"/>
					<input type='hidden' id='min' value="<?=$data_1['line_catm_min']?>"/>
					</form>
				</div>
				<!-- 步骤4 -->
				<div aria-hidden="true" aria-expanded="false" role="tabpanel" class="ui-tabs-panel ui-widget-content ui-corner-bottom" aria-labelledby="ui-id-4" id="deposit_tab4" style="background-color: rgb(255, 255, 231); display: none;">
					<div> 
						<span class="pay_under"><font color="2b78e4">■</font> 您的存款申请已成功提交!以下是您的存款资料，请妥善保存。</span> <br>
						<br>
						<ul>
							<li><b>存入银行：</b><span id="d_deposit_bank"></span></li>
							<li><b>存入金额：</b><span id="d_amount"></span></li>
							<li><b>存入时间：</b><span id="d_datetime"></span></li>
							<li><b>您使用的银行：</b><span id="d_bank"></span></li>
							<li><b>存款人姓名：</b><span id="d_payee"></span></li>
							<li><b>存款方式：</b><span id="d_way"></span></li>
							<li id="d_bank_location_row" style="display:none;"><b>所属分行:</b><span id="d_bank_location"></span></li>
							<li><b>订单号：</b><span id="d_order_num"></span></li>
						</ul>
						<br>
						<ul>
							<li>※1.<b>同行转帐:</b>完成转帐后请于30分钟内查收您的会员账号余额，如未加上请联系在线客服。</li>
							<li>※2.<b>跨行转帐:</b>银行不承诺跨行汇款到帐时间， 如您的款项在24小时内未加上， 烦请您联系在线客服为您提供帮助。</li>
						</ul>
						<br>
						<center>
							<input value="离开本页" class="btn_001" onClick="window.close();" type="button">
							&nbsp;&nbsp;&nbsp;
						</center>
					</div>
				</div>
			</div>
		</div>
		<br><div id="s_deposit_way_hh" style="display:none"></div>
		<div id="bank_id" style="display:none"></div>
		<div id="bank_footer" align="center">
			<p>Copyright © <?=$copy_right['copy_right']?>  Reserved</p>
		</div>
		<!-- 优惠弹跳讯息框 -->
		<div id="dialog" title="讯息窗口" style="background-color:white;display:none"> 尊敬的会员您好：<br>
		感谢您选择线上娱乐城！恭喜您可获得0.00%存款优惠，最高￥2999，达到有效投注倍即可提款！(活动详情请参阅优惠活动)<br>
		<input id="abandon_sp_radio1" name="abandon_sp_radio" value="Y" onClick="$('#abandon_sp').val('Y')" type="radio">
		我要获取存款优惠<br>
		<input id="abandon_sp_radio2" name="abandon_sp_radio" checked="checked" value="N" onClick="$('#abandon_sp').val('N');" type="radio">
		我要放弃存款优惠 </div>
		<!-- 再次检查表单框 -->
		<div id="checkInfo" title="请再次确认提交资讯!" style="display:none"> <br>
			<form id="bankForm" name="bankForm" method="post">
				<input id="s_way" name="s_way" type="hidden">
				<input name="uid" value="f836e1086f38fae69b0e8" type="hidden">
				<input id="abandon_sp" name="abandon_sp" value="N" type="hidden">
				<table width="100%">
				<tbody>
					<tr>
						<td width="100">订单号:</td>
						<td id="s_order_num" name="s_order_num"><?=$order?></td>
					</tr>
					<tr>
						<td>存入金额:</td>
						<td id="s_amount" name="s_amount"></td>
					</tr>
					<tr>
						<td>存入时间:</td>
						<td id="s_datetime" name="s_datetime"></td>
					</tr>
					<tr>
						<td>存款人姓名:</td>
						<td id="s_name" name="s_name"></td>
					</tr>
					<tr>
						<td>存款方式:</td>
						<td id="s_deposit_way" name="s_deposit_way"></td>
					</tr>
					<tr id="s_bank_location_row" style="display:none;">
						<td>所属分行:</td>
						<td id="s_bank_location" name="s_bank_location"></td>
					</tr>
					</tbody>
				</table>
			</form>
		</div>
	</body>
</html>


	<style>
		/*Mem2Bank*/
		body{font-size:12px;margin:0 auto;}
		.za_button {font-family:"Arial"; font-size:12px;height:25px;margin-right:10px;padding-right:1px;padding-top:1px;float:right;}

		/*線上支付完成*/
		#mainBody{float:left;width:800px;height:582px;margin:0 auto; background:url(../public/images/bg.jpg) no-repeat top left;}
		#main{ float:left;padding:170px 0px 0 200px}
		#titTx{ margin-left:170px;line-height:12px;height: 17px;width: 158px;padding-top: 6px;}
		#data{ float:left;width:340px;height:180px;padding:30px 50px 0 50px;}
		#data .list{ background:url(images/line.gif) bottom repeat-x; height:28px;}
		#data .tx1{ float:left;width:100px; text-align:right; line-height:12px; color:#18438E;height: 18px;padding-top: 7px;}
		#data .tx2{ float:left;width:195px; text-align:left; line-height:26px; font-family:Verdana, Geneva, sans-serif; font-size:12px; padding-left:5px}
		#copyright{ float:left; width:380px; margin:5px 0 0 10px; color:#FFF; font-family:Verdana, Geneva, sans-serif; font-size:10px}
	</style>
	<style type="text/css">
		#DepositTabs{font-size: 12px;}
		.ui-state-default a, .ui-state-default a:link{color:#000}
		.ui-state-active a, .ui-state-active a:link{color:#740625}
		.btn_001{cursor: pointer;margin: 0 1px 0 0;width: 85px;height: 26px;border: none;padding-top: 2px;color: #FFF;font-weight: bold;background: #3D3D3D url(images/order_btn.gif) no-repeat 0 -80px;}
	</style>
	
	<script type="text/javascript" src="js/pay_menu_company.js"></script>
	<script type="text/javascript">
	var AlertText = '抱歉!您操作时间超过了5分钟,请重新确认最新的银行资讯！';
	//var AbandonSp = '';
	var uidrand   = 'f836e1086f38fae69b0e8';
	var splimit = '2000';	//最小优惠标准
	var spmax = '2999';	//最大优惠上限
	var minLimit = '10';	//最少存款金额
	var maxLimit = '1000000';	//最多存款金额
	var config = 'N';
	var usabledbnak = '2';
	var S_ONLY_NUMBER = '仅能输入数字';
	var $tabs = $("#DepositTabs" ).show().tabs({ disabled: [1, 2, 3] });
	var myDate = new Date();   
	$("select[name='Time_Hour']").val(myDate.getHours());
	$("select[name='Time_Minute']").val(myDate.getMinutes());
	//字典档
	var TXT = {
		'PAY_EX_COM17' : 'ATM自动柜员机',
		'S_BANK_PROVINCE' : '省',
		'S_PQ_YES' : '是',
		'S_PQ_NO' : '否',
		'S_SUBMIT' : '确认',
		'S_CLOSE' : '关闭',
		'S_OK' : '确定',
		'S_SELECT_BANK' : '请选择银行',
		'MENU_COMP_S01' : '请选择您所使用的银行',
		'MENU_COMP_S02' : '请选择您欲转入的银行帐号',
		'MENU_COMP_S03' : '请选择存款方式',
		'MENU_COMP_S04' : '请输入正确资讯',
		'MENU_COMP_S05' : '请输入您的入款资料',
		'MENU_COMP_S06' : '汇款金额格式错误(小数最多只能输入至第二位)!!',
		'MENU_COMP_S07' : '汇款金额超过上限',
		'MENU_COMP_S08' : '请选择获取/放弃首次存款优惠',
		'MENU_COMP_S09' : '网银转帐',
		'MENU_COMP_S10' : 'ATM现金入款',
		'MENU_COMP_S11' : '银行柜台',
		'MENU_COMP_S30' : '手机转帐 ',
		'MENU_COMP_S31' : '支付宝转帐 ',
		'MENU_COMP_S12' : '是否确定写入',
		'MENU_COMP_S13' : '提交申请成功，请联系客服为您添加，谢谢配合。',
		'MENU_COMP_S14' : '提交申请成功，财务将在5分钟内为您加入额度，谢谢您!',
		'MENU_COMP_S15' : '提交申请失败，请确认输入的资讯是否正确',
		'MENU_COMP_S16' : '请输入汇款金额!!',
		'MENU_COMP_S17' : '汇款金额格式错误!!',
		'MENU_COMP_S18' : '汇款金额格式错误(小数最多只能数入至第二位)!!',
		'MENU_COMP_S19' : '请选择汇款帐号',
		'MENU_COMP_S20' : '目前由於在线银行技术升级中请稍侯!!',
		'MENU_COMP_S21' : '银行已复制',
		'MENU_COMP_S22' : '收款人已复制',
		'MENU_COMP_S23' : '开户行网点已复制',
		'MENU_COMP_S24' : '帐号已复制',
		'MENU_COMP_S25' : '订单号已复制',
		'MENU_COMP_S26' : '此复制功能只支援IE!!',
		'MENU_COMP_S29' : '汇款金额低于下限',
		'S_VOICE_TRANSFER' : '语音转账',
		'S_CHEQUE_DEPOSIT' : '支票存款',
		'S_CREDIT_CARD' : '信用卡',
		'S_TRAN_BUSY' : '系统繁忙，请稍后再试',
		'MENU_COMP_S27' : '提交申请成功，财务将在%s分钟内为您加入额度，谢谢您!'
	};
	function submit_check(){
		var  max=$('#max').val();
		var  min=$('#min').val();
		//表单验证
		if($("#in_name").val().length < 2){
			alert("姓名填写不正确！");
			return false;
		}
		if($("#in_date").val()==''){
			alert("存款时间不能为空！");
			return false;
		}

		if($("#deposit_num").val()!=""){
			var reg = /([`~!@#$%^&*()_+<>?:"{},\/;'[\]])/ig; 
			var reg1 = /([·~！#@￥%……&*（）——+《》？：“{}，。\、；’‘【\】])/ig;    

			if(reg.test($("#deposit_num").val()) || reg1.test($("#deposit_num").val())){    
				alert('存款金额格式错误！'); 
				return false;  
			}else{
				if($("#deposit_num").val() < 10){
					alert("存款金额最少为10元！");
					return false;
				}else if($("#deposit_num").val() - min <0){
					alert("存款金额低于该层级下限"+min+"！");
					return false;
				}else if($("#deposit_num").val() - max >0){
					alert("存款金额超过该层级上限"+max+"！");
					return false;
				}
			} 
		}else{
			alert("存款金额错误！");
			return false;
		}

		//保存入款银行id 
		var deposit_way;
		$("input[name = 'deposit_way']").each(function() {
			if($(this).attr('checked')){
				deposit_way = $(this).val();
			}
		});

		if(deposit_way==2||deposit_way==3||deposit_way==4){
			if($('#bank_location1').val()==''||$('#bank_location2').val()==''||$('#bank_location4').val()==''){
				alert('请填写正确的银行讯息 ！');
				return false;
			}
		}


		$("#tanchuceng").css({
			opercity: '0.1',
			backgroundColor: '#999'
		}); 

		//传值到确认框
		var in_type_1;var in_type_2;    
		$("input[name='deposit_way']").each(function() {
			if($(this).attr("checked")){
				in_type_2 = $(this).val();
				in_type_1 = $(this).attr('fangshi');
			}
		});
		var in_date_1 = $("#in_date").val();
		var in_date_2 = in_date_1 ;
		var province = $("#bank_location1").val();//．所属分行 省
		var city = $("#bank_location2").val();//．所属分行 
		var place = $("#bank_location4").val();//．所属分行 
		var s_bank_location = province+city+place;
		$("td[name = 's_order_num']").text($("#order_num").val());//订单号
		$("td[name = 's_amount']").text($("#deposit_num").val());//存入金额
		$("td[name = 's_datetime']").text(in_date_2);//存入时间
		$("td[name = 's_name']").text($("#in_name").val());//存款人姓名
		$("td[name = 's_deposit_way']").text(in_type_1);//存款方式中文
		$("td[name = 's_deposit_way_1']").text(in_type_2);//存款方式数字
		$("#s_deposit_way_hh").text(in_type_1);//存款方式中文
		$("td[name = 's_bank_location']").text(s_bank_location);//所属分行  
		$("#ui_dialog").css('display', 'block');
		$("#d_order_num").html($("#order_num").val());
	}

	function offerPrompt()
	{
		var amount = parseFloat($("#deposit_amount").val());
		var minstand = parseFloat(splimit);
		var maxstand = parseFloat(spmax);
		var minamount = parseFloat(minLimit);
		var maxamount = parseFloat(maxLimit);
		if(amount > maxamount || amount < minamount)
		{
			alert("您的存款金额限定是：[" + minLimit + "~" + maxLimit + " ]之间\r\n请重新输入存款金额.");  
			return false;
		}

		if(config == 'Y' && amount >= minstand)
		{
			$("#dialog").dialog({
				buttons: {
					"确定":function(){
						$(this).dialog("close");  
					} ,
					"取消":function(){
						$('#abandon_sp').val('N');
						$(this).dialog("close");  
					}  
				}
			});    		
		}
	}

	function isnullbank()
	{
		if(usabledbnak == '0')
		{
			alert('尊敬的会员：公司入款页面维护中，请联系在线客服咨询公司入款帐号。谢谢！！.');	
		}
	}

	$("input[name='deposit_way']").click(function(){
		var c = $('input[name=deposit_way]:checked').val();
		var currency = 'RMB';
		if ((c==2 || c==3 || c==4) && currency != 'THB') {
			$('#bank_location_row').show();
			$('#s_bank_location_row').show();
			$('#d_bank_location_row').show();
		} else {
			$('#bank_location_row').hide();
			$('#s_bank_location_row').hide();
			$('#d_bank_location_row').hide();
		}
	});

	$("#deposit").validate({
		rules: {
			deposit_way:{
				required: true                  
			}           
		},
		messages: {
			deposit_way: ""
		}
	});        

	$("#deposit input[name=deposit_amount]").rules('add', {
		'required' : true,
		'messages' : {'required' : "<font color='red'>栏位不能为空</font>"}
	});
	$("#deposit input[name=deposit_amount]").rules('add', {
		'number' : true,
		'messages' : {'number' : "<font color='red'>只能输入数字</font>"}
	});
	$("#deposit input[name=deposit_name]").rules('add', {
		'required' : true,
		'messages' : {'required' : "<font color='red'>栏位不能为空</font>"}
	});

	$("#deposit input[name=deposit_name]").rules('add', {
		'nosymbol' : true,
		'messages' : {'nosymbol' : "<font color='red'>请勿输入特殊字元</font>"}
	});




	var CloseT;
	//五分钟闲至 close window
	CloseT = setTimeout("AutoClose();",300000); 
	</script>
	
	<script>
	$(function(){
		var bank_id;//保存出款银行
		$("input[name = 'bank_id']").click(function() {
			$("input[name = 'bank_id']").each(function() {
				if($(this).attr('checked')){
					bank_id = $(this).parent("td").next('td').text();
					$("#bank_id").text(bank_id);
				}
			});
		});

		//保存入款银行id 
		var bid;
		$("input[name = 'deposit_id']").click(function() {
			$("input[name = 'deposit_id']").each(function() {
				if($(this).attr('checked')){
					bid = $(this).val(); 
					$("#bid").val(bid);
				}
			});
		});
	})
	function tijiao(){
		if(!confirm("是否确认提交？")){
			return false;
		}else{
			//ajax提交到本页面
			//组合数据
			var order_num = $("td[namea = 's_order_num']").text();//订单号
			var deposit_num= $("td[namea = 's_amount']").text();//存入金额
			var in_date = $("input[name = 'in_date']").val();//存入时间
			var in_name= $("td[namea = 's_name']").text();//存款人姓名
			var deposit_way= $("td[namea = 's_deposit_way_1']").text();//存款方式
			var s_bank_location= $("td[namea = 's_bank_location']").text();//所属分行
			var bank_location1 = $("#bank_location1").val();//所属分行 省
			var bank_location2 = $("#bank_location2").val();//所属分行 
			var bank_location4 = $("#bank_location4").val();//所属分行 
			var bid = $("#bid").val();
			var bank_style = $('input[name="bank_id"]:checked').val();
			var dt = new Date();
			var now_date =  (dt.getFullYear()+'-'+(dt.getMonth()+1)+'-'+dt.getDate()+' '+dt.getHours()+':'+dt.getMinutes()+':'+dt.getSeconds()).replace(/([\-\: ])(\d{1})(?!\d)/g,'$10$2');

			$.ajax({
				type: "POST",  
				url: "bank_ajax.php",  
				dataType: "json",  
				data: {action:"add_form",order_num:order_num,bank_style:bank_style,deposit_num:deposit_num,in_date:in_date,in_name:in_name,deposit_way:deposit_way,s_bank_location:s_bank_location,bank_location1:bank_location1,bank_location2:bank_location2,bank_location4:bank_location4,bid:bid,now_date:now_date
				},  
				success:function(msg){
					if(msg.ok=="1"){
						alert("提交申请成功，财务将在15分钟内为您加入额度，谢谢您!");

						//再次确认
						var bank_id = $("#bank_id").text();  //您使用的银行：
						var bank =  msg.bank;//存入银行：
						var bank_type = $("#s_deposit_way_hh").text();//存款方式：

						$("#d_deposit_bank").text(bank);
						$("#d_amount").text(msg.deposit_num);
						$("#d_datetime").text(msg.now_date);
						$("#d_bank").text(msg.bank_style);
						$("#d_payee").text(msg.in_name);
						$("#d_way").text(bank_type);
						$("#d_bank_location").text(msg.d_bank_location);
						//切换显示
						$("#ui_dialog").css('display', 'none');
						$("#deposit_tab3").css('display', 'none');
						$("#deposit_tab4").css('display', 'block');
						$("#DepositTabs" ).show().tabs({ disabled: [0,1, 2] });


					}else if(msg.statu==1){
						alert("存款金额超过该层级上限"+msg.infos+"！");
					}else if(msg.statu==2){
						alert("存款金额低于该层级下限"+msg.infos+"！");
					}else if(msg.statu==3){
						alert("操作非法。请联系客服人员");
						self.opener = null;
						self.close();
					}else{
						alert("存入失败！请联系客服！");
					}
				} 
			});
		}
	}


	//数字验证 过滤非法字符
	function clearNoNum(obj) {
		//先把非数字的都替换掉，除了数字和.
		obj.value = obj.value.replace(/[^\d.]/g, "");
		//必须保证第一个为数字而不是.
		obj.value = obj.value.replace(/^\./g, "");
		//保证只有出现一个.而没有多个.
		obj.value = obj.value.replace(/\.{2,}/g, ".");
		//保证.只出现一次，而不能出现两次以上
		obj.value = obj.value.replace(".", "$#$").replace(/\./g, "").replace("$#$", ".");
		if (obj.value != '') {
			var re = /^\d+\.{0,1}\d{0,2}$/;
			if (!re.test(obj.value))
			{
				obj.value = obj.value.substring(0, obj.value.length - 1);
				return false;
			}
		}
	}
	new PCAS("bank_location1","bank_location2");
	</script>
