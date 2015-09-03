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
//
@ini_set('memory_limit', '128M');
$fee = M('k_fee_set', $db_config)->where("site_id='" . SITEID . "' and valid_date <='" . $qishu['start_date'] . "' and is_delete='0'")->order('valid_date desc')->find();
$inmaxcash = $fee['in_max_fee'] / ($fee['in_fee'] * 0.01);
$outmaxcash = $fee['out_max_fee'] / ($fee['out_fee'] * 0.01);
$xzfee = array();
//<option value="3">负数额度归零</option>
//<option value="4">取消出款</option>
//<option value="5">其他</option>
//catm_type 1,3,4,5,11 前台会员申请入款手续费,12 前台会员出款手续费
$model = M('k_user_catm', $db_config);
$ht_cash = $model->field('uid,catm_money as money,catm_give,atm_give,catm_type,updatetime as cash_date,username')->where("agent_id = '" . $agentid . "' and uid = '" . $uid . "' and  catm_type in (1,3,4,5,6) and updatetime>='" . $qishu['start_date'] . "' and updatetime<='" . $qishu['end_date'] . "' and is_rebate=1")->select();

foreach ($ht_cash as $cash) {
	$xzfee[] = $cash;
	$username = $cash['username'];
	//行政费用计算
	////在线存款、公司入款；人工存入项目：人工存入、负数额度归0、取消出款、其它
}

//var_dump($model);
//前台会员申请入款计算
$model = M('k_user_cash_record', $db_config);
$qt_in_cash = $model->field('uid,cash_num as money,cash_date')->where("agent_id = '" . $agentid . "' and uid = '" . $uid . "' and cash_type='11' and cash_do_type='1' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "'")
                    ->select();
foreach ($qt_in_cash as $in_cash) {
	$in_cash['catm_type'] = 11;
	$xzfee[] = $in_cash;
	//行政费用计算
}
//var_dump($model);
//线上入款申请入款计算
$model = M('k_user_cash_record', $db_config);
$xs_in_cash = $model->field('uid,cash_num as money,cash_date')->where("agent_id = '" . $agentid . "' and uid = '" . $uid . "' and cash_type='10' and cash_do_type='1' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "'")
                    ->select();
foreach ($xs_in_cash as $in_cash) {
	$in_cash['catm_type'] = 11;
	$xzfee[] = $in_cash;
	//行政费用计算
}

//前台会员申请出款的计算
$qt_in_cash = $model->field('uid,cash_num as money,cash_date')->where("agent_id = '" . $agentid . "' and uid = '" . $uid . "' and cash_type='19' and cash_do_type='2' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "'")
                    ->select();
                   //p($qt_in_cash);
foreach ($qt_in_cash as $out_cash) {
	$out_cash['catm_type'] = 12;
	$xzfee[] = $out_cash;
//出款行政费用计算
}

$title = "用户行政费";
$incash = $outcash = $allcash = 0;
require "../common_html/header.php";
?>

<div id="con_wrap">
  <div class="input_002"><?=$qishu['qsname']?>-行政費用</div>
  <div class="con_menu">
  	<a href="javascript:history.go(-1);">返回上一頁</a>
	</div>
</div>

<table style="width:500px"  class="m_tab">
		<tr class="m_title">
		  <td colspan="5" class="table_bg">會員：<?=$username?>&nbsp;&nbsp;期數：<?=$qishu['qsname']?>&nbsp;&nbsp;日期：<?=$qishu['start_date']?> ~ <?=$qishu['end_date']?></td>
  </tr>
		<tr class="m_title">
			<td class="table_bg">日期</td>
			<td class="table_bg">出款金額</td>
			<td class="table_bg">入款金額</td>
			<td class="table_bg">手續費%</td>
			<td class="table_bg">手續費</td>
		</tr>
		<?php
foreach ($xzfee as $value) {
	//$data = $value['cash_date'];
	
	if ($value['catm_type'] == 12) {
		$fee_p = $fee['out_fee'];
		//$fee_a = $value['money'] * $fee['out_fee'] * 0.01;
		if ($value['money'] >= $outmaxcash) {
			$fee_a = $outmaxcash * $fee['out_fee'] * 0.01;
		} else {
			$fee_a = $value['money'] * $fee['out_fee'] * 0.01;
		}
		$inc = 0.00;
		$outc = $value['money'];
		$outcash += $outc;
	} else if (in_array($value['catm_type'], array(1,3, 4, 5,6,11))) {
		$fee_p = $fee['in_fee'];
		if ($value['money'] >= $inmaxcash) {
			$fee_a = $inmaxcash * $fee['in_fee'] * 0.01;
		} else {
			$fee_a = $value['money'] * $fee['in_fee'] * 0.01;
		}
		//$fee_a = $value['money'] * $fee['in_fee'] * 0.01;
		$inc = $value['money'];
		$outc = 0.00;
		$incash += $inc;
	} 
	$allcash += $fee_a;
	?>
		<tr class="m_rig">
	    	<td align="center"><?=$value['cash_date']?></td>
			<td><?=number_format($outc, 2)?></td>
			<td><?=number_format($inc, 2)?></td>
			<td><?=$fee_p?>%</td>
	        <td><?=number_format($fee_a, 2)?></td>
    	</tr>
<?php
}
?>
		<tr class="table_total">
	    	<td>總計：</td>
			<td><?=number_format($outcash, 2)?></td>
			<td><?=number_format($incash, 2)?></td>
			<td></td>
	        <td><?=number_format($allcash, 2)?></td>
    	</tr>
</table>


<?php require "../common_html/footer.php";?>