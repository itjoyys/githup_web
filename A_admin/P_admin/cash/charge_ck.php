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

$ckfee = array();

$ht_cash = M('k_user_catm', $db_config)->field('uid,catm_give,atm_give,catm_type,username,updatetime as cash_date')->where("agent_id = '" . $agentid . "' and uid = '" . $uid . "' and catm_type in (1,3,4,5,6) and updatetime>='" . $qishu['start_date'] . "' and updatetime<='" . $qishu['end_date'] . "' and is_rebate=1")->select();
foreach ($ht_cash as $cash) {
	$ckfee[] = $cash;
	$username = $cash['username'];
}

//前台会员申请入款计算
$model = M('k_user_cash_record', $db_config);
$qt_in_cash = $model->field('uid,discount_num as catm_give,cash_date')->where("agent_id = '" . $agentid . "' and uid = '" . $uid . "' and cash_type='11'  and cash_do_type='1' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "'")
                    ->select();
foreach ($qt_in_cash as $in_cash) {
	$ckfee[] = $in_cash;
}

//线上入款申请入款计算
$model = M('k_user_cash_record', $db_config);
$xs_in_cash = $model->field('uid,discount_num as catm_give,cash_date')->where("agent_id = '" . $agentid . "' and uid = '" . $uid . "' and cash_type='10'  and cash_do_type='1' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "'")
                    ->select();
foreach ($xs_in_cash as $in_cash) {
	$ckfee[] = $in_cash;
}

$title = "用户存款优惠";
$alld = 0;
require "../common_html/header.php";
?>

<div id="con_wrap">
  <div class="input_002"><?=$qishu['qsname']?>-存款優惠</div>
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
			<td class="table_bg">優惠額度</td>
		</tr>
		<?php
foreach ($ckfee as $value) {
	$discount = floatval($value['catm_give']) + floatval($value['atm_give']);
	$alld += $discount;
	?>

		    	<tr class="m_rig">
	    	<td align="center"><?=$value['cash_date']?></td>
			<td><?=number_format($discount, 2)?></td>
    	</tr>
<?php
}
?>
				<tr class="table_total">
	    	<td >總計：</td>
			<td><?=number_format($alld, 2)?></td>
    	</tr>
</table>



<?php require "../common_html/footer.php";?>