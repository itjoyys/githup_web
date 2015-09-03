<?php
include_once "../../include/config.php";
include_once "../common/login_check.php";
include_once "../../class/Level.class.php";
include_once "../../lib/video/Games.class.php";
ini_set("display_errors", "On");
error_reporting(0);

$qs = intval($_GET['qs_id']); //期数id
$agentid = intval($_GET['agentid']); //期数id

$qishu = M('k_qishu', $db_config)->where("site_id = '" . SITEID . "' and id=$qs AND is_delete = '0'")->order('id desc')->find();

if (empty($qishu) && $agentid == 0) {
	message('期数和代理不能问为空！');
}

$qishu['start_date'] = $qishu['start_date'] . ' 00:00:00';
$qishu['end_date'] = $qishu['end_date'] . ' 23:59:59';

/*****************************费用开始****************************/
//行政費用	退傭優惠	退水優惠	存款優惠	其他費用	小計金額
$uids = array();
$alluserfee = array();
/*****************************费用开始****************************/
//(1,2,3,4,5,6,7,8)
//<option value="1">人工存入</option>
//<option value="2">存款优惠</option>
//<option value="3">负数额度归零</option>
//<option value="4">取消出款</option>
//<option value="5">其他</option>
//<option value="6">体育投注余额</option>
//<option value="7">返点优惠</option>
//<option value="8">活动优惠</option>
//读取手续费设定
$fee = M('k_fee_set', $db_config)->where("site_id='" . SITEID . "' and valid_date <='" . $qishu['start_date'] . "' and is_delete='0'")->order('valid_date desc')->find();
//肯需要大内存
@ini_set('memory_limit', '128M');
//后台入款费用计算
$inmaxcash = $fee['in_max_fee'] / ($fee['in_fee'] * 0.01);
$outmaxcash = $fee['out_max_fee'] / ($fee['out_fee'] * 0.01);
$ht_cash = M('k_user_catm', $db_config)->field('uid,catm_money,catm_give,atm_give,catm_type')->where("agent_id = '" . $agentid . "' and catm_type in (1,2,3,4,5,6,7,8) and updatetime>='" . $qishu['start_date'] . "' and updatetime<='" . $qishu['end_date'] . "' and is_rebate=1")->select();
foreach ($ht_cash as $cash) {
//行政费用计算
	////在线存款、公司入款；人工存入项目：人工存入、负数额度归0、取消出款、其它
	if (in_array($cash['catm_type'], array(1, 3, 4, 5, 6))) {
		if ($cash['catm_money'] >= $inmaxcash) {
			$alluserfee[$cash['uid']]['xzfee'] += $inmaxcash * $fee['in_fee'] * 0.01;
		} else {
			$alluserfee[$cash['uid']]['xzfee'] += $cash['catm_money'] * $fee['in_fee'] * 0.01;
		}
		$uids[] = $cash['uid'];
	}

//存款優惠
	if (in_array($cash['catm_type'], array(1, 3, 4, 5, 6))) {
		$alluserfee[$cash['uid']]['ckfee'] += $cash['catm_give'] + $cash['atm_give'];
		$uids[] = $cash['uid'];
	}

//退水优惠
	if ($cash['catm_type'] == 7) {
		//TODO 游戏退水
		$alluserfee[$cash['uid']]['tsfee'] += $cash['catm_money'] + $cash['catm_give'] + $cash['atm_give'];
		$uids[] = $cash['uid'];
	}
//其它优惠
	if ($cash['catm_type'] == 2) {
		$alluserfee[$cash['uid']]['otherfee'] += $cash['catm_give'] + $cash['atm_give'];
		$uids[] = $cash['uid'];
	}
	if ($cash['catm_type'] == 8) {
		$alluserfee[$cash['uid']]['otherfee'] += $cash['catm_money'] + $cash['catm_give'] + $cash['atm_give'];
		$uids[] = $cash['uid'];
	}
//体育余额
	if ($cash['catm_type'] == 6) {
//var_dump($cash);
	}
}

//前台会员申请入款计算
$model = M('k_user_cash_record', $db_config);
$qt_in_cash = $model->field('uid,cash_num,discount_num')->where("agent_id = '" . $agentid . "' and cash_type='11' and cash_do_type='1' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "'")
                    ->select();

foreach ($qt_in_cash as $in_cash) {
//行政费用计算
	if ($in_cash['cash_num'] >= $inmaxcash) {
		$alluserfee[$in_cash['uid']]['xzfee'] += $inmaxcash * $fee['in_fee'] * 0.01;
	} else {
		$alluserfee[$in_cash['uid']]['xzfee'] += $in_cash['cash_num'] * $fee['in_fee'] * 0.01;
	}
//存款優惠
	$alluserfee[$in_cash['uid']]['ckfee'] += $in_cash['discount_num'];
	$uids[] = $in_cash['uid'];
}

//线上入款申请入款计算
$model = M('k_user_cash_record', $db_config);
$xs_in_cash = $model->field('uid,cash_num,discount_num')->where("agent_id = '" . $agentid . "' and cash_type='10' and cash_do_type='1' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "'")
                    ->select();

foreach ($xs_in_cash as $in_cash) {
//行政费用计算
	if ($in_cash['cash_num'] >= $inmaxcash) {
		$alluserfee[$in_cash['uid']]['xzfee'] += $inmaxcash * $fee['in_fee'] * 0.01;
	} else {
		$alluserfee[$in_cash['uid']]['xzfee'] += $in_cash['cash_num'] * $fee['in_fee'] * 0.01;
	}
//存款優惠
	$alluserfee[$in_cash['uid']]['ckfee'] += $in_cash['discount_num'];
	$uids[] = $in_cash['uid'];
}

//前台会员申请出款的计算
$qt_out_cash = $model->field('uid,cash_num')->where("agent_id = '" . $agentid . "' and cash_type='19' and cash_do_type='2' and cash_date>='" . $qishu['start_date'] . "' and cash_date<='" . $qishu['end_date'] . "'")
                     ->select();

foreach ($qt_out_cash as $out_cash) {
//出款行政费用计算
	if ($out_cash['cash_num'] >= $outmaxcash) {
		$alluserfee[$out_cash['uid']]['xzfee'] += $outmaxcash * $fee['out_fee'] * 0.01;
	} else {
		$alluserfee[$out_cash['uid']]['xzfee'] += $out_cash['cash_num'] * $fee['out_fee'] * 0.01;
	}
	$uids[] = $out_cash['uid'];
}

//前台注册赠送的优惠
$qt_zc_cash = $model->field('k_user_cash_record.uid,k_user_cash_record.discount_num,k_user_cash_record.cash_date,k_user.agent_id')->where("k_user.agent_id = '" . $agentid . "' AND k_user_cash_record.cash_type='6' and k_user_cash_record.cash_do_type='1' and k_user_cash_record.cash_date>='" . $qishu['start_date'] . "' and k_user_cash_record.cash_date<='" . $qishu['end_date'] . "'")->join('left join k_user on k_user.uid=k_user_cash_record.uid')
                    ->select();
foreach ($qt_zc_cash as $v) {
	$alluserfee[$v['uid']]['otherfee'] += $v['discount_num'];
	$uids[] = $v['uid'];
}

//天天返水
$table = M('k_user_discount_count', $db_config);
$qt_out_cash = $table->field('uid,total_e_fd as money,agent_id')
                     ->where("agent_id = '" . $agentid . "' and state='1' and do_time>='" . $qishu['start_date'] . "' and do_time<='" . $qishu['end_date'] . "'")->select();
foreach ($qt_out_cash as $value) {
	$alluserfee[$value['uid']]['tsfee'] += $value['money'];
	$uids[] = $value['uid'];
}

//var_dump($alluserfee);
$userinfo = array();
//var_dump($uids);
//获取所有用户名
$user = M('k_user', $db_config)->field("uid,username")->where("uid in (" . implode(",", $uids) . ")")->select();
foreach ($user as $value) {
	$userinfo[$value['uid']] = $value['username'];
}
$agentinfo = M('k_user_agent', $db_config)->field("id,agent_name")->where("id = '" . $agentid . "'")->find();
//var_dump($userinfo);
$title = "出入账目总会";
$xzfee = $tyfee = $tsfee = $ckfee = $otherfee = $allfee = 0;
require "../common_html/header.php";
?>

<div id="con_wrap">
  <div class="input_002"><?=$qishu['qsname']?>-費用明细</div>
  <div class="con_menu">
	</div>
</div>

<table style="width:600px"  class="m_tab">
		<tr class="m_title">
		  <td colspan="7" class="table_bg">代理：<?=$agentinfo['agent_name']?>&nbsp;&nbsp;<?=$qishu['qsname']?>&nbsp;&nbsp;日期：<?=$qishu['start_date']?> ~ <?=$qishu['end_date']?></td>
  </tr>
		<tr class="m_title">
			<td class="table_bg">會員帳號</td>
			<td class="table_bg">行政費用</td>
			<td class="table_bg">退傭優惠</td>
			<td class="table_bg">退水優惠</td>
			<td class="table_bg">存款優惠</td>
            <td class="table_bg">其他費用</td>
			<td class="table_bg">小計金額</td>
		</tr>
		<?php
foreach ($alluserfee as $key => $value) {
	$userallfee = floatval($value['xzfee']) + floatval($value['tsfee']) + floatval($value['ckfee']) + floatval($value['otherfee']);
	$xzfee += floatval($value['xzfee']);
	$tyfee += floatval($value['tyfee']);
	$tsfee += floatval($value['tsfee']);
	$ckfee += floatval($value['ckfee']);
	$otherfee += floatval($value['otherfee']);
	$allfee += $userallfee;
	?> 	<tr class="m_rig">
	    	<td align="left"><?=$userinfo[$key]?></td>
			<td><?php
if ($value['xzfee'] > 0) {
		echo "<a href=\"charge_xz.php?qs_id=" . $qishu['id'] . "&user_id=" . $key . "&agentid=" . $agentinfo['id'] . "\"  class=\"a_001\">" . number_format($value['xzfee'], 2) . "</a>";
	} else {
		echo "0.00";
	}
	?></td>
			<td>0.00</td>
			<td><?php
if ($value['tsfee'] > 0) {
		echo "<a href=\"charge_ts.php?qs_id=" . $qishu['id'] . "&user_id=" . $key . "&agentid=" . $agentinfo['id'] . "\"  class=\"a_001\">" . number_format($value['tsfee'], 2) . "</a>";
	} else {
		echo "0.00";
	}
	?></td>
	        <td><?php
if ($value['ckfee'] > 0) {
		echo "<a href=\"charge_ck.php?qs_id=" . $qishu['id'] . "&user_id=" . $key . "&agentid=" . $agentinfo['id'] . "\"  class=\"a_001\">" . number_format($value['ckfee'], 2) . "</a>";
	} else {
		echo "0.00";
	}
	?></td>
	        <td><?php
if ($value['otherfee'] > 0) {
		echo "<a href=\"charge_qt.php?qs_id=" . $qishu['id'] . "&user_id=" . $key . "&agentid=" . $agentinfo['id'] . "\"  class=\"a_001\">" . number_format($value['otherfee'], 2) . "</a>";
	} else {
		echo "0.00";
	}
	?></td>
            <td><?=number_format($userallfee, 2)?></td>
    	</tr>
<?php
}
?>
				<tr class="m_rig" >
	    	<td class="table_bg1">總計：</td>
			<td class="table_bg1"><?=number_format($xzfee, 2)?></td>
			<td class="table_bg1"><?=number_format($tyfee, 2)?></td>
			<td class="table_bg1"><?=number_format($tsfee, 2)?></td>
	        <td class="table_bg1"><?=number_format($ckfee, 2)?></td>
            <td class="table_bg1"><?=number_format($otherfee, 2)?></td>
	        <td class="table_bg1"><?=number_format($allfee, 2)?></td>
    	</tr>


</table>

<?php require "../common_html/footer.php";?>