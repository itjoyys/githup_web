<?php
include_once "../../include/config.php";
include_once "../common/login_check.php";
include_once "../../class/Level.class.php";
include_once "../../lib/video/Games.class.php";
ini_set("display_errors", "On");
error_reporting(0);

$qs = intval($_GET['qs_id']); //期数id
$agentid = intval($_GET['agentid']); //代理id
$uid = intval($_GET['user_id']); //用户id

$username = "";
$qishu = M('k_qishu', $db_config)->where("site_id = '" . SITEID . "' and id=$qs AND is_delete = '0'")->order('id desc')->find();

if (empty($qishu) && $uid == 0) {
	message('期数和用户名不能问为空！');
}

$qishu['start_date'] = $qishu['start_date'] . ' 00:00:00';
$qishu['end_date'] = $qishu['end_date'] . ' 23:59:59';

$otherfee = array();

$ht_cash = M('k_user_catm', $db_config)->field('uid,catm_give,atm_give,catm_type,username,updatetime as cash_date')->where("agent_id = '" . $agentid . "' and uid = '" . $uid . "' and catm_type in (2,8) and updatetime>='" . $qishu['start_date'] . "' and updatetime<='" . $qishu['end_date'] . "' and is_rebate=1")->select();
foreach ($ht_cash as $cash) {
	$otherfee[] = $cash;
	$username = $cash['username'];
}

//前台注册赠送的优惠
$model = M('k_user_cash_record', $db_config);
$qt_zc_cash = $model->field('k_user_cash_record.uid,k_user_cash_record.discount_num,k_user_cash_record.cash_date,k_user.agent_id,k_user.username')->where("k_user.agent_id = '" . $agentid . "' AND k_user.uid = '" . $uid . "' AND k_user_cash_record.cash_type='6' and k_user_cash_record.cash_do_type='1' and k_user_cash_record.cash_date>='" . $qishu['start_date'] . "' and k_user_cash_record.cash_date<='" . $qishu['end_date'] . "'")->join('left join k_user on k_user.uid=k_user_cash_record.uid')
                    ->select();
foreach ($qt_zc_cash as $v) {
	$v['catm_type'] = 20;
	$otherfee[] = $v;
	$username = $v['username'];
}

$title = "其它費用";
$allfee = 0;
require "../common_html/header.php";
?>

<div id="con_wrap">
  <div class="input_002"><?=$qishu['qsname']?>-其它費用</div>
  <div class="con_menu">
  	<a href="javascript:history.go(-1);">返回上一頁</a>
	</div>
</div>

<table style="width:500px" class="m_tab">
		<tr class="m_title">
		  <td colspan="2" class="table_bg">會員：<?=$username?>&nbsp;&nbsp;期數：<?=$qishu['qsname']?>&nbsp;&nbsp;日期：<?=$qishu['start_date']?> ~ <?=$qishu['end_date']?></td>
  </tr>
		<tr class="m_title">
			<td class="table_bg">日期</td>
			<td class="table_bg">活动优惠額度</td>
		</tr>
		<?php
foreach ($otherfee as $value) {
	if($value['catm_type'] == 20){
	$money = $value['discount_num'];
	}elseif($value['catm_type'] == 2 || $value['catm_type'] == 8){
		$money = $value['catm_give'];
	}
	$allfee += $money;
	?>
	    	<tr class="m_rig">
	    	<td align="center"><?=$value['cash_date']?></td>
			<td><?=number_format($money, 2)?></td>
    	</tr>
<?php
}
?>
				<tr class="table_total">
	    	<td >總計：</td>
			<td><?=number_format($allfee, 2)?></td>
    	</tr>
</table>



<?php require "../common_html/footer.php";?>