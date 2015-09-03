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

$tsfee = array();
//后台人工存入退水
$ht_cash = M('k_user_catm', $db_config)->field('uid,catm_money,catm_give,atm_give,catm_type,updatetime as do_time')->where("agent_id = '" . $agentid . "' and catm_type = 7 and updatetime>='" . $qishu['start_date'] . "'  and uid = '" . $uid . "' and  updatetime<='" . $qishu['end_date'] . "' and is_rebate=1")->select();
foreach ($ht_cash as $cash) {
//退水优惠
	$cash['total_e_fd'] = $cash['catm_money'] + $cash['catm_give'] + $cash['atm_give'];
	$cash['rgcrts'] = $cash['total_e_fd'];
	$tsfee[] = $cash;
}
//有效注单
//天天返水
$table = M('k_user_discount_count', $db_config);
$qt_out_cash = $table->where("uid = '" . $uid . "' and state='1' and do_time>='" . $qishu['start_date'] . "' and do_time<='" . $qishu['end_date'] . "'")
                     ->select();
foreach ($qt_out_cash as $value) {
	$tsfee[] = $value;
	$username = $value['username'];
}

//var_dump($table);
//var_dump($tsfee);

$title = "退水優惠";
//$xzfee = $tyfee = $tsfee = $ckfee = $otherfee = $allfee = 0;
require "../common_html/header.php";
?>

<body>
<div id="con_wrap">
  <div class="input_002"><?=$qishu['qsname']?>-退水優惠</div>
  <div class="con_menu"> <a href="javascript:history.go(-1);">返回上一頁</a> </div>
</div>
<table style="width:90%" class="m_tab">
  <tr class="m_title">
    <td colspan="24" class="table_bg">會員：<?=$username?>&nbsp;&nbsp;期數：<?=$qishu['qsname']?>&nbsp;&nbsp;日期：<?=$qishu['start_date']?> ~ <?=$qishu['end_date']?></td>
  </tr>
  <tr class="m_title">
  		<td rowspan="2">返水日期</td>
		<td rowspan="2">有效總<br>投注</td>
		<td colspan="10">有效投注</td>
		<td colspan="10">返點</td>
        <td rowspan="2">人工存入返點</td>
		<td rowspan="2">優惠金<br>小計</td>
	</tr>
    <tr class="m_title">
    	<td>體育</td>
        <td>彩票</td>
        <td>LEBO</td>
        <td>MG視訊</td>
        <td>MG電子</td>
        <td>BB視訊</td>
        <td>BB電子</td>
        <td>AG</td>
        <td>CT</td>
        <td>OG</td>
        <td>體育</td>
        <td>彩票</td>
        <td>LEBO</td>
        <td>MG視訊</td>
        <td>MG電子</td>
        <td>BB視訊</td>
        <td>BB電子</td>
        <td>AG</td>
        <td>CT</td>
        <td>OG</td>
    </tr>
    <?php
foreach ($tsfee as $value) {

	$date_t = $value["do_time"];
	$date = explode(" ", $date_t);

	$betall += $value['betall'];
	$sp_bet += $value['sp_bet'];
	$fc_bet += $value['fc_bet'];
	$lebo_bet += $value['lebo_bet'];
	$mg_bet += $value['mg_bet'];
	$mgdz_bet += $value['mgdz_bet'];
	$bbin_bet += $value['bbin_bet'];
	$bbdz_bet += $value['bbdz_bet'];
	$ag_bet += $value['ag_bet'];
	$ct_bet += $value['ct_bet'];
	$og_bet += $value['og_bet'];
	$sp_fd += $value['sp_fd'];
	$fc_fd += $value['fc_fd'];
	$lebo_fd += $value['lebo_fd'];
	$mg_fd += $value['mg_fd'];
	$mgdz_fd += $value['mgdz_fd'];
	$bbin_fd += $value['bbin_fd'];
	$bbdz_fd += $value['bbdz_fd'];
	$ag_fd += $value['ag_fd'];
	$ct_fd += $value['ct_fd'];
	$og_fd += $value['og_fd'];
	$rgcrts += $value['rgcrts'];
	$total_e_fd += $value['total_e_fd'];

	?>
			<tr class="m_cen">
        	<td><?=$date[0]?></td>
			<td><?=intval($value['betall'])?></td>
			<td><?=intval($value['sp_bet'])?></td>
            <td><?=intval($value['fc_bet'])?></td>
            <td><?=intval($value['lebo_bet'])?></td>
            <td><?=intval($value['mg_bet'])?></td>
            <td><?=intval($value['mgdz_bet'])?></td>
            <td><?=intval($value['bbin_bet'])?></td>
            <td><?=intval($value['bbdz_bet'])?></td>
            <td><?=intval($value['ag_bet'])?></td>
            <td><?=intval($value['ct_bet'])?></td>
            <td><?=intval($value['og_bet'])?></td>
            <td><?=number_format($value['sp_fd'], 2)?></td>
            <td><?=number_format($value['fc_fd'], 2)?></td>
            <td><?=number_format($value['lebo_fd'], 2)?></td>
            <td><?=number_format($value['mg_fd'], 2)?></td>
            <td><?=number_format($value['mgdz_fd'], 2)?></td>
            <td><?=number_format($value['bbin_fd'], 2)?></td>
            <td><?=number_format($value['bbdz_fd'], 2)?></td>
            <td><?=number_format($value['ag_fd'], 2)?></td>
            <td><?=number_format($value['ct_fd'], 2)?></td>
            <td><?=number_format($value['og_fd'], 2)?></td>
            <td><?=number_format($value['rgcrts'], 2)?></td>
			<td><?=number_format($value['total_e_fd'], 2)?></td>
		</tr>
        <?php }
?>
		<tr  class="m_title">
		<td align="right">總計：</td>
		<td><?=intval($betall)?></td>
		<td><?=intval($sp_bet)?></td>
        <td><?=intval($fc_bet)?></td>
        <td><?=intval($lebo_bet)?></td>
        <td><?=intval($mg_bet)?></td>
        <td><?=intval($mgdz_bet)?></td>
        <td><?=intval($bbin_bet)?></td>
        <td><?=intval($bbdz_bet)?></td>
        <td><?=intval($ag_bet)?></td>
        <td><?=intval($ct_bet)?></td>
        <td><?=intval($og_bet)?></td>
        <td><?=number_format($sp_fd, 2)?></td>
        <td><?=number_format($fc_fd, 2)?></td>
        <td><?=number_format($lebo_fd, 2)?></td>
        <td><?=number_format($mg_fd, 2)?></td>
        <td><?=number_format($mgdz_fd, 2)?></td>
        <td><?=number_format($bbin_fd, 2)?></td>
        <td><?=number_format($bbdz_fd, 2)?></td>
        <td><?=number_format($ag_fd, 2)?></td>
        <td><?=number_format($ct_fd, 2)?></td>
        <td><?=number_format($og_fd, 2)?></td>
        <td><?=number_format($rgcrts, 2)?></td>
		<td><?=number_format($total_e_fd, 2)?></td>
	</tr>
</table>

<?php require "../common_html/footer.php";?>