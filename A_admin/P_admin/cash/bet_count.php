<?php
include_once ("../../include/config.php");
include_once ("../common/login_check.php");
include_once ("../../lib/video/Games.class.php");

$where = " site_id = '" . SITEID . "'";

// 时间判断
if (! empty($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
} else {
    $start_date = date("Y-m-d");
}
if (! empty($_GET['end_date'])) {
    $end_date = $_GET['end_date'];
} else {
    $end_date = date("Y-m-d");
}

// 排序查询
$having = " HAVING 1=1";
if (is_numeric($_GET['bet_num'])) {
    $having .= " and cnum " . ($_GET['bet_num_opt'] == 1 ? '< ' : '> ') . $_GET['bet_num'];
}
if (is_numeric($_GET['bet_valid'])) {
    $having .= " and money " . ($_GET['bet_valid_opt'] == 1 ? '< ' : '> ') . $_GET['bet_valid'];
}
if (is_numeric($_GET['bet_award'])) {
    $having .= " and win " . ($_GET['bet_award_opt'] == 1 ? '< ' : '> ') . $_GET['bet_award'];
}

// 会员查找
if ($_GET['level'] == 1) {
    if ($_GET['wanfa'] == "lottery") {
        $where .= " and addtime >= '" . $start_date . "' and addtime <= '" . $end_date . "'";
        if (! empty($_GET['name'])) {
            $where .= " and username = '" . $_GET['name'] . "'";
        }
        $agent = M('c_bet as c', $db_config);
        $agent_arr = $agent->field("agent_id, username, SUM(money) as money,
            (select SUM(win) from c_bet where js = 1 and status in (1,2) and username = c.username and " . $where . ") as win,
            (select COUNT(*) from c_bet where js = 1 and status in (1,2) and username = c.username and " . $where . ") as cnum, 
            (select SUM(money) from c_bet where js = 1 and status in (1,2) and username = c.username and " . $where . ") as omoney,
            (select COUNT(*) from c_bet where win <> 0 and status in (1,2) and username = c.username and " . $where . ") as onum")
            ->where($where)
            ->group("username " . $having)
            ->select();
    } elseif ($_GET['wanfa'] == "sports") {
        $where .= " and bet_time >= '" . $start_date . "' and bet_time <= '" . $end_date . "'";
        if (! empty($_GET['name'])) {
            $where .= " and username = '" . $_GET['name'] . "'";
        }
        $agent = M('k_bet as k', $db_config);
        $agent_arr = $agent->field("agent_id, username, SUM(bet_money) as money,
            (select SUM(win) from k_bet where is_jiesuan = 1 and status in (1,2,4,5) and username = k.username and " . $where . ") as win,
            (select COUNT(*) from k_bet where is_jiesuan = 1 and status in (1,2,4,5) and username = k.username and " . $where . ") as cnum,
            (select SUM(bet_money) from k_bet where is_jiesuan = 1 and status in (1,2,4,5) and username = k.username and " . $where . ") as omoney,
            (select COUNT(*) from k_bet where win <> 0 and status in (1,2,4,5) and username = k.username and " . $where . ") as onum")
            ->where($where)
            ->group("username " . $having)
            ->select();
    }
    // 代理查找
} elseif ($_GET['level'] == 2) {
    $group = "agent_id";
    if ($_GET['wanfa'] == "lottery") {
        $where .= " and addtime >= '" . $start_date . "' and addtime <= '" . $end_date . "'";
        if (! empty($_GET['name'])) {
            $where .= " and username = c.username and agent_id = " . getAgentId($_GET['name']);
            $group = "username";
            $model = true;
        }
        $agent = M('c_bet as c', $db_config);
        $agent_arr = $agent->field("agent_id, username, SUM(money) as money,
            (select SUM(win) from c_bet where js = 1 and status in (1,2) and agent_id = c.agent_id and " . $where . ") as win,
            (select COUNT(*) from c_bet where js = 1 and status in (1,2) and agent_id = c.agent_id and " . $where . ") as cnum, 
            (select SUM(money) from c_bet where js = 1 and status in (1,2) and agent_id = c.agent_id and " . $where . ") as omoney,
            (select COUNT(*) from c_bet where win <> 0 and status in (1,2) and agent_id = c.agent_id and " . $where . ") as onum")
            ->where($where)
            ->group($group . $having)
            ->select();
    } elseif ($_GET['wanfa'] == "sports") {
        $where .= " and bet_time >= '" . $start_date . "' and bet_time <= '" . $end_date . "'";
        if (! empty($_GET['name'])) {
            $where .= " and username = k.username and agent_id = " . getAgentId($_GET['name']);
            $group = "username";
            $model = true;
        }
        $agent = M('k_bet as k', $db_config);
        $agent_arr = $agent->field("agent_id, username, SUM(bet_money) as money,
            (select SUM(win) from k_bet where is_jiesuan = 1 and status in (1,2,4,5) and agent_id = k.agent_id and " . $where . ") as win,
            (select COUNT(*) from k_bet where is_jiesuan = 1 and status in (1,2,4,5) and agent_id = k.agent_id and " . $where . ") as cnum,
            (select SUM(bet_money) from k_bet where is_jiesuan = 1 and status in (1,2,4,5) and agent_id = k.agent_id and " . $where . ") as omoney,
            (select COUNT(*) from k_bet where win <> 0 and status in (1,2,4,5) and agent_id = k.agent_id and " . $where . ") as onum")
            ->where($where)
            ->group($group . $having)
            ->select();
    }
}

//视讯统计
if ($_GET['wanfa'] != "sports" || $_GET['wanfa'] != "lottery" && ! empty($_GET['wanfa'])) {
    switch ($_GET['wanfa']) {
        case "lebogame":
            $gtype = "lebo";
            break;
        case "mggame":
            $gtype = "mg";
            break;
        case "aggame":
            $gtype = "ag";
            break;
        case "oggame":
            $gtype = "og";
            break;
        case "ctgame":
            $gtype = "ct";
            break;
        case "mgdian":
            $gtype = "mg";
            $dz = 1;
            break;
        case "bbingame":
            $gtype = "bbin";
            break;
    }
    if (! empty($gtype) && $_GET['level'] == 1 && empty($_GET['name'])) {
        $game = new Games();
        $agentid = json_decode($game->GetUserAvailableAmountBysiteid($gtype, $start_date, $end_date),$dz);
    } elseif (! empty($gtype) && $_GET['level'] == 1 && ! empty($_GET['name'])) {
        $game = new Games();
        $agentid = json_decode($game->GetUserAvailableAmountByUser($gtype, $_GET['name'], $start_date, $end_date),$dz);
    } elseif (! empty($gtype) && $_GET['level'] == 2 && ! empty($_GET['name'])) {
        $game = new Games();
        $agentid = json_decode($game->GetUserAvailableAmountByAgentid($gtype, getAgentId($_GET['name']), $start_date, $end_date),$dz);
        $group = "username";
    } elseif (! empty($gtype) && $_GET['level'] == 2 && empty($_GET['name'])) {
        $idarr = getSiteAgentId();
        $imparr = implode("|", $idarr);
        $game = new Games();
        $agentid = json_decode($game->GetUserAvailableAmountByAgentid($gtype, $imparr, $start_date, $end_date),$dz);
    }
    if (count($agentid->data->data)) {
        foreach ($agentid->data->data as $k => $a) {
            $agent_arr[$k]['agent_user'] = getAgentNameByUsername($a->username);
            $agent_arr[$k]['username'] = $a->username;
            $agent_arr[$k]['BetAll'] = $a->BetAll;
            $agent_arr[$k]['BetBS'] = $a->BetBS;
            $agent_arr[$k]['BetYC'] = $a->BetYC;
            $agent_arr[$k]['BetPC'] = $a->BetPC;
            $agent_arr[$k]['BetWBS'] = $a->BetWBS;
        }
        if (!empty($gtype) && $_GET['level'] == 2 && empty($_GET['name'])) {

            $temp = array();
            foreach ($agent_arr as $key => $value) {
                $key = $value['agent_user'];
                $temp[$key]['BetAll'] = isset($temp[$key]['BetAll']) ? $value['BetAll'] + $temp[$key]['BetAll'] : $value['BetAll'];
                $temp[$key]['BetBS'] = isset($temp[$key]['BetBS']) ? $value['BetBS'] + $temp[$key]['BetBS'] : $value['BetBS'];
                $temp[$key]['BetYC'] = isset($temp[$key]['BetYC']) ? $value['BetYC'] + $temp[$key]['BetYC'] : $value['BetYC'];
                $temp[$key]['BetPC'] = isset($temp[$key]['BetPC']) ? $value['BetPC'] + $temp[$key]['BetPC'] : $value['BetPC'];
                $temp[$key]['BetWBS'] = isset($temp[$key]['BetWBS']) ? $value['BetWBS'] + $temp[$key]['BetWBS'] : $value['BetWBS'];
            }

            foreach ($temp as $key => $value) {
                $result[] = array(
                    'agent_user' => $key,
                    'cnum' => getUserNumByName($key),
                    'BetAll' => $value['BetAll'],
                    'BetBS' => $value['BetBS'],
                    'BetYC' => $value['BetYC'],
                    'BetPC' => $value['BetPC'],
                    'BetWBS' => $value['BetWBS']
                );
                //print_r($result);
            }
            $agent_arr = $result;
            //print_r($agent_arr);
        }
    }
}

// echo $agent->getLastSql();

// 获得代理商名
function getAgentName($agent_id)
{
    global $db_config;
    $agent = M('k_user_agent', $db_config);
    $agent_user = $agent->field("agent_user")
        ->where("id = " . $agent_id)
        ->find();
    return $agent_user['agent_user'];
}

// 获得站点所有代理
function getSiteAgentId()
{
    global $db_config;
    $agent = M('k_user_agent', $db_config);
    $agent_user = $agent->field("id")
        ->where("site_id = 't' and agent_type = 'a_t'")
        ->select();
    if (count($agent_user)) {
        foreach ($agent_user as $k => $a) {
            $agent_id[$k] = $a['id'];
        }
    }
    return $agent_id;
}

// 用户名获取代理名
function getAgentNameByUsername($username)
{
    global $db_config;
    $agent = M('k_user_agent', $db_config);
    $agent_user = $agent->field("agent_user")
        ->where("id = (SELECT agent_id from k_user where username = '" . $username . "' and site_id = '" . SITEID . "')")
        ->find();
    return $agent_user['agent_user'];
}

// 获得代理商ID
function getAgentId($agent_user)
{
    global $db_config;
    $agent = M('k_user_agent', $db_config);
    $agent_user = $agent->field("id")
        ->where("agent_user = '" . $agent_user . "'")
        ->find();
    // echo $agent->getLastSql();
    return $agent_user['id'];
}

// 获取用户数量Byname
function getUserNumByName($agent_user)
{
    global $db_config;
    $user = M('k_user', $db_config);
    $user_num = $user->field("count('id') as num")
        ->where("agent_id = (select id from k_user_agent WHERE agent_user = '" . $agent_user . "')")
        ->find();
    return $user_num['num'];
}

// 获取用户数量Byid
function getUserNumById($agent_id)
{
    global $db_config;
    $user = M('k_user', $db_config);
    $user_num = $user->field("count('id') as num")
        ->where("agent_id = (select id from k_user_agent WHERE id = '" . $agent_id . "')")
        ->find();
    return $user_num['num'];
}

?>

<?php $title="会员分析系统"; require("../common_html/header.php");?>
<body>
	<script>
//分页跳转
	window.onload=function(){
		document.getElementById("page").onchange=function(){
			var val=this.value;
			window.location.href=<?=$_SERVER["REQUEST_URI"]?> + "?page="+val;
		}

	}
</script>
	<div id="con_wrap">
		<div class="input_002">会员分析系统</div>
		<div class="con_menu">
			<div>
				<a href="user_account.php">出入款统计</a>
				<!-- <a  href="agent_ad_statistics.php">优惠</a> -->
				<a href="bet_count.php" style="color: red">下注分析</a> <a
					href="bet_num.php">投注人数</a>
			</div>
			<br />
			<form name="addForm" action="" method="get">
				日期： <input class="za_text Wdate" id="start_date"
					onClick="WdatePicker()" value="<?=$start_date?>" name="start_date">
				&nbsp; <input class="za_text Wdate" id="end_date"
					onClick="WdatePicker()" value="<?=$end_date?>" name="end_date">
				&nbsp;产品： <select name="wanfa" id="wanfa" class="za_select">
					<option value="sports"
						<?php if ($_GET['wanfa'] == "sports") echo "selected" ?>>体育赛事</option>
					<option value="lottery"
						<?php if ($_GET['wanfa'] == "lottery") echo "selected" ?>>彩票游戏</option>
					<option value="lebogame"
						<?php if ($_GET['wanfa'] == "lebogame") echo "selected" ?>>lebo</option>
					<option value="mggame"
						<?php if ($_GET['wanfa'] == "mggame") echo "selected" ?>>MG视讯</option>
					<option value="ctgame"
						<?php if ($_GET['wanfa'] == "ctgame") echo "selected" ?>>CT视讯</option>
					<option value="oggame"
						<?php if ($_GET['wanfa'] == "oggame") echo "selected" ?>>OG视讯</option>
					<option value="aggame"
						<?php if ($_GET['wanfa'] == "aggame") echo "selected" ?>>AG视讯</option>
					<option value="mgdian"
						<?php if ($_GET['wanfa'] == "mgdian") echo "selected" ?>>MG电子</option>
					<option value="bbingame"
						<?php if ($_GET['wanfa'] == "bbingame") echo "selected" ?>>BBIN</option>
				</select> <br /> 帐号查询： <select name="level" id="level"
					class="za_select">
					<option value="1" <?php if ($_GET['level'] == 1) echo "selected" ?>>会员</option>
					<option value="2" <?php if ($_GET['level'] == 2) echo "selected" ?>>代理</option>
					<!--
	<option value="3">总代理</option>    
    <option value="4">股东</option>
    -->
				</select> <input type="text" name="name" id="name"
					value="<?=$_GET['name'];?>" class="za_text"
					style="min-width: 80px; width: 100px;"> &nbsp;注单数： <input
					type="text" name="bet_num" id="bet_num"
					value="<?=$_GET['bet_num'];?>" class="za_text"
					style="min-width: 80px; width: 100px;"> <select name="bet_num_opt"
					id="bet_num_opt" class="za_select">
					<option value="0">以上</option>
					<option value="1"
						<?php if ($_GET['bet_num_opt'] == 1) echo "selected" ?>>以下</option>
				</select> &nbsp;下注金额： <input type="text" name="bet_valid"
					id="bet_valid" value="<?=$_GET['bet_valid'];?>" class="za_text"
					style="min-width: 80px; width: 100px;"> <select
					name="bet_valid_opt" id="bet_valid_opt" class="za_select">
					<option value="0">以上</option>
					<option value="1"
						<?php if ($_GET['bet_valid_opt'] == 1) echo "selected" ?>>以下</option>
				</select> &nbsp;派彩： <input type="text" name="bet_award"
					id="bet_award" value="<?=$_GET['bet_award'];?>" class="za_text"
					style="min-width: 80px; width: 100px;"> <select
					name="bet_award_opt" id="bet_award_opt" class="za_select">
					<option value="0">以上</option>
					<option value="1"
						<?php if ($_GET['bet_award_opt'] == 1) echo "selected" ?>>以下</option>
				</select>

				<input type="submit" value="搜索" class="za_button">
				<!-- <input type="button" value="导出Excel" class="za_button"> -->

			</form>
		</div>
	</div>
	<div class="content">
		<table width="1024" border="0" cellspacing="0" cellpadding="0"
			bgcolor="#E3D46E" class="m_tab">
			<tbody>
				<tr class="m_title_over_co">
					<td>NO</td>
					<td>代理商</td>
					<td><?php if($_GET['level'] == 1 || $group == "username"){ echo "会员账号"; }else{ echo "会员总数"; } ?></td>
					<td>有效注单数（总计）</td>
					<td>下注金额（总计）</td>
					<td>有效投注（总计）</td>
					<td>派彩（总计）</td>
					<td>胜率（%）</td>
				</tr>
		<?
if ($_GET['level'] == 2 && ($_GET['wanfa'] == "sports" || $_GET['wanfa'] == "lottery") && empty($_GET['name'])) {
    foreach ($agent_arr as $k => $v) {
        ?>
    		<tr class="m_cen">
					<td><?=$k+1?></td>
					<td><a
						href="?name=<?=getAgentName($v['agent_id'])?>&level=2&wanfa=<?=$_GET['wanfa']?>&start_date=<?=$_GET['start_date']?>"><?=getAgentName($v['agent_id'])?></a></td>
					<td><?=getUserNumById($v['agent_id'])?></td>
					<td><?=$v['cnum']?></td>
					<td><?=$v['money']?></td>
					<td><?=$v['omoney']?></td>
					<td><?=$v['win'] - $v['omoney']?></td>
					<td><?php if(($v['win'] - $v['omoney']) < 0){echo "-"; } echo number_format(($v['onum']/$v['cnum'])*100,2); ?>%</td>
				</tr>
    		<?
    
}
} elseif ($_GET['level'] == 1 && ($_GET['wanfa'] == "sports" || $_GET['wanfa'] == "lottery") || $model) {
    foreach ($agent_arr as $k => $v) {
        ?>
		    		<tr class="m_cen">
					<td><?=$k+1?></td>
					<td><a
						href="?name=<?=getAgentName($v['agent_id'])?>&level=2&wanfa=<?=$_GET['wanfa']?>&start_date=<?=$_GET['start_date']?>"><?=getAgentName($v['agent_id'])?></a></td>
					<td><a
						href="?name=<?=$v['username']?>&level=1&wanfa=<?=$_GET['wanfa']?>&start_date=<?=$_GET['start_date']?>"><?=$v['username']?></a></td>
					<td><?=$v['cnum']?></td>
					<td><?=$v['money']?></td>
					<td><?=$v['omoney']?></td>
					<td><?=$v['win'] - $v['omoney']?></td>
					<td><?php if(($v['win'] - $v['omoney']) < 0){echo "-"; } echo number_format(($v['onum']/$v['cnum'])*100,2); ?>%</td>
				</tr>
		<?php } ?>
		<?php

} elseif (! empty($gtype)) {
    foreach ($agent_arr as $k => $v) {
        ?>
        		    <tr class="m_cen">
					<td><?=$k+1?></td>
					<td><a
						href="?name=<?=$v['agent_user']?>&level=2&wanfa=<?=$_GET['wanfa']?>&start_date=<?=$_GET['start_date']?>"><?=$v['agent_user']?></a></td>
					<td><?php if (!empty($gtype) && $_GET['level'] == 2 && empty($_GET['name'])){echo $v['cnum'];}else{echo $v['username'];}?></td>
					<td><?=$v['BetBS']?></td>
					<td><?=$v['BetAll']?></td>
					<td><?=$v['BetYC']?></td>
					<td><?=$v['BetPC']?></td>
					<td><?php if($v['BetPC'] < 0){echo "-"; }  echo number_format(($v['BetWBS']/$v['BetBS'])*100,2);?></td>
				</tr>
		<?php } ?>
		<?php }else{?>
           <tr align="center">
					<td class="table_bg1" height="27" colspan="15">暂无数据</td>
				</tr>
		<?php }?>				
		
	</tbody>
		</table>
	</div>
<?php require("../common_html/footer.php");?>
