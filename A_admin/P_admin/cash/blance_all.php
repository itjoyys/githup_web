<?
include_once("../../include/config.php");
include_once("../common/login_check.php");



//启用
//$user = M('k_user',$db_config)->join('join k_user_games on k_user.uid=k_user_games.uid')->field("sum(k_user.money) as money,sum(k_user_games.og_money) as og,sum(k_user_games.mg_money) as mg,sum(k_user_games.ag_money) as ag")->where("k_user.is_delete='0' and k_user.shiwan=0 and k_user.site_id='".SITEID."'")->find();
$user = M('k_user',$db_config)->field("sum(money) as money,sum(og_money) as og,sum(mg_money) as mg,sum(ag_money) as ag,sum(bbin_money) as bbin,sum(ct_money) as ct,sum(lebo_money) as lebo")->where("is_delete='0' and shiwan=0 and site_id='".SITEID."'")->find();

// p($user);
//停用
$users = M('k_user',$db_config)->field("sum(money) as money,sum(og_money) as og,sum(mg_money) as mg,sum(ag_money) as ag,sum(bbin_money) as bbin,sum(ct_money) as ct,sum(lebo_money) as lebo")->where("is_delete='1' and shiwan=0 and site_id='".SITEID."'")->find();
// p($users);

$sum=$user['money']+$user['ag']+$user['og']+$user['mg']+$user['bbin']+$user['ct']+$user['lebo'];
$sums=$users['money']+$users['ag']+$users['og']+$users['mg']+$users['bbin']+$users['ct']+$users['lebo'];
?>


<?php $title="会员餘額統計"; require("../common_html/header.php");?>
<body>
<script language="JavaScript" src="./blance_all_files/WdatePicker.js"></script>
<div id="con_wrap">
  	<div class="input_002">会员餘額統計</div>
  	<div class="con_menu">

	</div>
</div>
<div class="content">
<form action="" method="POST" name="form">
<table width="99%" border="0" cellpadding="4" cellspacing="1" class="m_tab">
	<tbody>
	<tr class="m_title">
		<td width="200"><div align="center">游戏额度</div></td>
		<td width="100"><div align="center">启用</div></td>
		<td width="100"><div align="center">停用</div></td>
		<td width="250"><div align="center">更新时间</div></td>
		<td width="100"><div align="center"></div></td>
	</tr>
	<tr class="m_cen">										
		<td><div align="center">系统额度</div></td>
		<td><div align="right" class="EnableBalance"><a href="./active_member_balance.php"><?=$user['money']?></a></div></td>
		<td><div align="right" class="DisableBalance"><?php if($users['money']){echo $users['money'];}else{echo "0.00";}?></div></td>
		<td><div align="center" class="UpdateTime"><?=date("Y-m-d H:i:s",time())?></div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen">										
		<td><div align="center">MG额度</div></td>
		<td><div align="right" class="EnableBalance"><?=$user['mg']?></div></td>
		<td><div align="right" class="DisableBalance"><?php if($users['mg']){echo $users['mg'];}else{echo "0.00";}?></div></td>
		<td><div align="center" class="UpdateTime"><?=date("Y-m-d H:i:s",time())?></div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	
	<tr class="m_cen">										
		<td><div align="center">BBIN额度</div></td>
		<td><div align="right" class="EnableBalance"><?=$user['bbin']?></div></td>
		<td><div align="right" class="DisableBalance"><?php if($users['bbin']){echo $users['bbin'];}else{echo "0.00";}?></div></td>
		<td><div align="center" class="UpdateTime"><?=date("Y-m-d H:i:s",time())?></div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen">										
		<td><div align="center">AG额度</div></td>
		<td><div align="right" class="EnableBalance"><?=$user['ag']?></div></td>
		<td><div align="right" class="DisableBalance"><?php if($users['ag']){echo $users['ag'];}else{echo "0.00";}?></div></td>
		<td><div align="center" class="UpdateTime"><?=date("Y-m-d H:i:s",time())?></div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen">										
		<td><div align="center">OG额度</div></td>
		<td><div align="right" class="EnableBalance"><?=$user['og']?></div></td>
		<td><div align="right" class="DisableBalance"><?php if($users['og']){echo $users['og'];}else{echo "0.00";}?></div></td>
		<td><div align="center" class="UpdateTime"><?=date("Y-m-d H:i:s",time())?></div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen">										
		<td><div align="center">CT额度</div></td>
		<td><div align="right" class="EnableBalance"><?=$user['ct']?></div></td>
		<td><div align="right" class="DisableBalance"><?php if($users['ct']){echo $users['ct'];}else{echo "0.00";}?></div></td>
		<td><div align="center" class="UpdateTime"><?=date("Y-m-d H:i:s",time())?></div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
	<tr class="m_cen">										
		<td><div align="center">LEBO额度</div></td>
		<td><div align="right" class="EnableBalance"><?=$user['lebo']?></div></td>
		<td><div align="right" class="DisableBalance"><?php if($users['lebo']){echo $users['lebo'];}else{echo "0.00";}?></div></td>
		<td><div align="center" class="UpdateTime"><?=date("Y-m-d H:i:s",time())?></div></td>
		<td>
		<div align="center">
		<input type="hidden" class="way" value="cash">
		<input type="hidden" class="HallID" value="3380448">
		<input type="submit" class="form_button updateBtn button_d" value="立即更新">
		</div>
		</td>
	</tr>
    <tr>										
		<td class="table_bg1" align="right"><div align="center">總計：</div></td>
		<td class="table_bg1"><div align="right" class="EnableBalance"><?=number_format($sum,2)?></div></td>
		<td class="table_bg1"><div align="right" class="DisableBalance"><?=number_format($sums,2)?></div></td>
		<td class="table_bg1"></td>
		<td class="table_bg1">
		</td>
	</tr>
	
</tbody></table>
 </form></div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>