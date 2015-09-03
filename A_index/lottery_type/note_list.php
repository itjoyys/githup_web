<?php
include_once ("../include/config.php");

include ("../include/public_config.php");
include ("./include/function.php");
function select_check($field, $val) {
  if ($field == $val) {
    echo "selected=\"selected\"";
  }
}

if (empty($_GET['type']))
    $_GET['type'] = '全部';
    // 单个当前期数
if ($_GET['type'] == 0) {
    // 循环14张表 读出当前期数
    for ($i = 1; $i <= 14; $i ++) {
        $qishu .= "'" . dq_qishu($i, $db_config) . "',";
    }
    $qishu = rtrim($qishu, ',');
    $name_qishu = '本期最新下注状况';
} else {
    $qishu = dq_qishu($_GET['type'], $db_config);
    $name_qishu = $qishu . '期';
}
switch ($_GET['type']) {
    case '0':
        $_GET['type'] = '全部';
        break;
    case '1':
        $_GET['type'] = '广东快乐十分';
        break;
    case '2':
        $_GET['type'] = '重庆时时彩';
        break;
    case '3':
        $_GET['type'] = '北京赛车pk拾';
        break;
    case '4':
        $_GET['type'] = '重庆快乐十分';
        break;
    case '5':
        $_GET['type'] = '福彩3D';
        break;
    case '6':
        $_GET['type'] = '排列三';
        break;
    case '7':
        $_GET['type'] = '六合彩';
        break;
    case '8':
        $_GET['type'] = '北京快乐8';
        break;
    // case '9':
    //     $_GET['type'] = '上海时时彩';
    //     break;
    case '10':
        $_GET['type'] = '天津时时彩';
        break;
    case '11':
        $_GET['type'] = '江西时时彩';
        break;
    case '12':
        $_GET['type'] = '新疆时时彩';
        break;
    case '13':
        $_GET['type'] = '江苏快3';
        break;
    case '14':
        $_GET['type'] = '吉林快3';
        break;
}
include ("../include/private_config.php");
if (empty($_GET['kithe'])) {
    // $kithe = "and addtime >= NOW() - interval 1 day"; 最近24小时搜素
    $kithe = "and qishu in(" . $qishu . ")"; // 本期搜索
} else {
    $kithe = substr($_GET['kithe'], 0, strlen($_GET['kithe']) - 9);
    $kithe = "and DATE_FORMAT(addtime,'%Y-%m-%d') = '" . $kithe . "'";
}
if (! empty($_GET['type']) && $_GET['type'] == '全部') {

    $map = "js=0 and site_id='" . SITEID . "' and uid='" . $_SESSION['uid'] . "' " . $kithe . "";
} else {
    $map = "js=0 and site_id='" . SITEID . "' and uid='" . $_SESSION['uid'] . "' and type='" . $_GET['type'] . "' " . $kithe . "";
}

 //echo $map."---".$_GET['type'];

$b = M("c_bet", $db_config);
$count = $b->field("id")
    ->where($map)
    ->count(); // 获得记录总数
               // 分页
$perNumber = isset($_GET['page_num']) ? $_GET['page_num'] : 20; // 每页显示的记录数
$totalPage = ceil($count / $perNumber); // 计算出总页数
$page1 = isset($_GET['page']) ? $_GET['page'] : 1;
if ($totalPage < $page1) {
    $page1 = 1;
}

$startCount = ($page1 - 1) * $perNumber; // 分页开始,根据此方法计算出开始的记录
$limit = $startCount . "," . $perNumber;

$data = $b->field("*")
    ->where($map)
    ->order("addtime desc")
    ->limit($limit)
    ->select();

$page = $b->showPage($totalPage, $page1);

?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>webcom</title>
<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
<link rel="stylesheet" href="./public/css/xp.css" type="text/css">
</head>
<body marginwidth="3" marginheight="3" id="HOP"
	ondragstart="window.event.returnValue=false"
	oncontextmenu="window.event.returnValue=false"
	onselectstart="event.returnValue=false" style="padding-bottom: 80px;">
	<link rel="stylesheet" href="./public/css/mem_body_ft_ssc.css?="
		type="text/css">
	<script language="javascript" type="text/javascript"
		src="./public/My97DatePicker/WdatePicker.js"></script>
	<script language="javascript" type="text/javascript"
		src="./public/js/jquery-1.8.3.min.js"></script>
	<script language="javascript" type="text/javascript"
		src="./public/js/jquery.js"></script>
	<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit();
    }
  }
</script>
	<table border="0" cellpadding="0" cellspacing="0" id="box">
		<tbody>
			<tr>
				<td class="mem">
					<h2>
						<form name="myFORM" method="get" id="myFORM" action="#">
							<table width="100%" border="0" cellpadding="0" cellspacing="0"
								id="fav_bar">
								<tbody>
									<tr>
										<td id="page_no">&nbsp;&nbsp;&nbsp;&nbsp;<?=$_GET['type']?>      <?= $_GET['kithe']?><?=$name_qishu?>
			每页记录数：
			<input type="hidden" name="kithe" value="<?= $_GET['kithe']?>" /> <select
											name="page_num" id="page_num"
											onchange="document.getElementById('myFORM').submit()"
											class="za_select">
												<option value="20" <?=select_check(20,$perNumber)?>>20条</option>
												<option value="30" <?=select_check(30,$perNumber)?>>30条</option>
												<option value="50" <?=select_check(50,$perNumber)?>>50条</option>
												<option value="100" <?=select_check(100,$perNumber)?>>100条</option>
										</select>
	&nbsp;<?=$page?>&nbsp;
				</td>


										<td align="left"><select name="type" id="type"
											onchange="document.getElementById('myFORM').submit()"
											style="float: right; height: 20px">
												<option value="0">全部</option>
												<option value="7"
													<?php if($_GET['type']=="六合彩"){echo 'selected="selected"';}?>>六合彩</option>
												<option value="5"
													<?php if($_GET['type']=="福彩3D"){echo 'selected="selected"';}?>>福彩3D</option>
												<option value="6"
													<?php if($_GET['type']=="排列三"){echo 'selected="selected"';}?>>排列三</option>
												<option value="2"
													<?php if($_GET['type']=="重庆时时彩"){echo 'selected="selected"';}?>>重庆时时彩</option>
												<option value="10"
												<?php if($_GET['type']=="天津时时彩"){echo 'selected="selected"';}?>>天津时时彩</option>
												<option value="11"
													<?php if($_GET['type']=="江西时时彩"){echo 'selected="selected"';}?>>江西时时彩</option>
												<option value="12"
													<?php if($_GET['type']=="新疆时时彩"){echo 'selected="selected"';}?>>新疆时时彩</option>
												<option value="8"
													<?php if($_GET['type']=="北京快乐8"){echo 'selected="selected"';}?>>北京快乐8</option>
												<option value="3"
													<?php if($_GET['type']=="北京赛车pk拾"){echo 'selected="selected"';}?>>北京赛车pk拾</option>
												<option value="1"
													<?php if($_GET['type']=="广东快乐十分"){echo 'selected="selected"';}?>>广东快乐十分</option>
												<option value="4"
													<?php if($_GET['type']=="重庆快乐十分"){echo 'selected="selected"';}?>>重庆快乐十分</option>
												<option value="13"
													<?php if($_GET['type']=="江苏快3"){echo 'selected="selected"';}?>>江苏快3</option>
												<option value="14"
													<?php if($_GET['type']=="吉林快3"){echo 'selected="selected"';}?>>吉林快3</option>
										</select></td>
									</tr>
								</tbody>
							</table>
						</form>
					</h2>
					<table border="0" cellspacing="0" cellpadding="0" class="game">
						<tbody>
							<tr class="center">
<!-- 								<th class="his_wag">序号</th> -->
								<th class="his_time">下注单号</th>
								<th class="his_time">下注时间</th>
								<th class="his_wag">期数</th>
								<th class="his_wag">类型</th>
								<th class="his_wag">内容</th>
								<th class="his_wag">赔率</th>
								<th class="his_wag">下注金额</th>
								<th class="his_wag">佣金</th>
								<th class="his_wag">可蠃金额</th>
								<th class="his_wag">注单状态</th>
							</tr>
			<?
$money = 0;
$result = 0;
if (! empty($data)) {

    $i = 0;

    foreach ($data as $key => $row) {
        $i ++;
        if ($i > $perNumber) {
            break;
        }
        if ($row['js'] != 0) {
            if ($row['win'] == 0) {
                $row['result'] -= $row['money'];
            } else {
                $row['result'] += $row['money'] * ($row['odds'] - 1);
            }
        } else {
            $row['result'] = $row['result'];
        }

        $money += $row['money'];
        $money += $row['sum_m'];
        $result += $row['win'];
        $result += $row['sum_m'] * $row['rate'];
        ?>

		<tr class="items center"
								onmouseover="javascript:this.bgColor='EDFF6C'"
								onmouseout="javascript:this.bgColor='f2f2f2'" bgcolor="f2f2f2">
								<!-- <td height="25"><?=$row['id']?></td> -->
								<td><b><?=$row['did']?><?=$row['num']?></b></a></td>
								<td class="his_total right" nowrap="nowrap"><font
									style="color: #EECA0B">
				<?php
        if (empty($row['adddate'])) {
            echo $row['addtime'];
        } else {
            echo $row['adddate'];
        }
        ;
        ?>
					</font></td>
								<td class="his_total right" nowrap="nowrap"><font
									style="color: #05940A"><?=$row['qishu']?><?=$row['kithe']?></font></td>
								<td class="his_total right" nowrap="nowrap"><font
									style="color: #ff0000"><?=$row['type']?><?=$row['class2']?></font></td>

								<td class="his_total right" nowrap="nowrap"><font
									style="color: #ff0000"><?=$row['mingxi_1']?><?=$row['class3']?></font></td>
								<td class="his_total right" nowrap="nowrap"><font
									style="color: #ff0000"><?=$row['odds']?><?=$row['rate']?></font></td>
								<td class="his_total right" nowrap="nowrap"><font
									style="color: #ff0000"><?=$row['money']?><?=$row['sum_m']?></font></td>
								<td class="his_total right" nowrap="nowrap"><font
									style="color: #ff0000">0</font></td>
								<td class="his_total right" nowrap="nowrap"><font
									style="color: #ff0000"><?=$row['win']  ?></font></td>

								<td class="his_total right" nowrap="nowrap"><font
									style="color: #ff0000">
							<?php
        echo $row['js'] != 0 ? "结算" : "未结算";
        ?>
							</font></td>
							</tr>
		<?}	}?>
		<tr class="sum_bar right">
								<td colspan="6" class="center his_total">小计</td>
								<td class="his_total"><span class="STYLE4"><font
										style="color: #EE8A0B"><?=$money?></font></span></td>
								<td class="his_total"><span class="STYLE4"><b><font
											style="color: #9F0">0</font></b></span></td>
								<td class="his_total"><span class="STYLE4"><b><font
											style="color: #EECA0B"><?=$result?></font></b></span></td>

								<td></td>
							</tr>
						</tbody>
					</table>
				</td>
			</tr>
		</tbody>
	</table>
</body>
</html>