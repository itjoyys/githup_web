<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once ("../../lib/video/Games.class.php");
//print_r($_GET);
//echo "<br />";

$where = " site_id = '".SITEID."'";

//时间判断
if (!empty($_GET['start_date'])) {
	$start_date = $_GET['start_date'];
}else{
	$start_date = date("Y-m-d");
}
if (!empty($_GET['end_date'])) {
	$end_date = $_GET['end_date'];
}else{
	$end_date = date("Y-m-d");
}

//当天用户
$users = M('k_user',$db_config);
$user = $users->field("username")->where("to_days(`reg_date`) = to_days(now())")->select();
//echo $users->getLastSql();
foreach($user as $u){
    $newuser[] = "'" . $u['username'] . "'";
}
$newuser = implode(",",$newuser);
//echo $newuser;
//体育
$k_bet = M('k_bet',$db_config);
$k_num = count($k_bet->where($where." and bet_time >= '" . $start_date . " 00:00:00' and bet_time <= '" . $end_date . " 23:59:59'")->group("username")->select());
$k_num_n = count($k_bet->where($where." and bet_time >= '" . $start_date . " 00:00:00' and bet_time <= '" . $end_date . " 23:59:59' and username in (" . $newuser . ")")->group("username")->select());
//echo $k_bet->getLastSql();
//彩票
$c_bet = M('c_bet',$db_config);
$c_num = count($c_bet->where($where." and addtime >= '" . $start_date . " 00:00:00' and addtime <= '" . $end_date . " 23:59:59'")->group("username")->select());
$c_num_n = count($c_bet->where($where." and addtime >= '" . $start_date . " 00:00:00' and addtime <= '" . $end_date . " 23:59:59' and username in (" . $newuser . ")")->group("username")->select());

$game = new Games();
//lebo
$lebo = json_decode($game->GetUserAvailableAmountBySiteid("lebo",$start_date,$end_date));
$lebo_num = count($lebo->data->data);
$lebo_num_n = 0;
foreach ($lebo->data->data as $name){
    $lebo_num_n = strstr($newuser,$name->username) ? $lebo_num_n + 1 : $lebo_num_n;
}

//mg
$mg = json_decode($game->GetUserAvailableAmountBySiteid("mg",$start_date,$end_date));
$mg_num = count($mg->data->data);
$mg_num_n = 0;
foreach ($mg->data->data as $name){
    $mg_num_n = strstr($newuser,$name->username) ? $mg_num_n + 1 : $mg_num_n;
}

//cg
$cg = json_decode($game->GetUserAvailableAmountBySiteid("cg",$start_date,$end_date));
$cg_num = count($cg->data->data);
$cg_num_n = 0;
foreach ($cg->data->data as $name){
    $cg_num_n = strstr($newuser,$name->username) ? $cg_num_n + 1 : $cg_num_n;
}

//og
$og = json_decode($game->GetUserAvailableAmountBySiteid("og",$start_date,$end_date));
$og_num = count($og->data->data);
$og_num_n = 0;
foreach ($og->data->data as $name){
    $og_num_n = strstr($newuser,$name->username) ? $og_num_n + 1 : $og_num_n;
}

//ag
$ag = json_decode($game->GetUserAvailableAmountBySiteid("ag",$start_date,$end_date));
$ag_num = count($ag->data->data);
$ag_num_n = 0;
foreach ($ag->data->data as $name){
    $ag_num_n = strstr($newuser,$name->username) ? $ag_num_n + 1 : $ag_num_n;
}

//ct
$ct = json_decode($game->GetUserAvailableAmountBySiteid("ct",$start_date,$end_date));
$ct_num = count($ct->data->data);
$ct_num_n = 0;
foreach ($ct->data->data as $name){
    $ct_num_n = strstr($newuser,$name->username) ? $ct_num_n + 1 : $ct_num_n;
}

//mg电子
$mg1 = json_decode($game->GetUserAvailableAmountBySiteid("mg",$start_date,$end_date),1);
$mg1_num = count($mg1->data->data);
$mg1_num_n = 0;
foreach ($mg1->data->data as $name){
    $mg1_num_n = strstr($newuser,$name->username) ? $mg1_num_n + 1 : $mg1_num_n;
}

//bbin
$bbin = json_decode($game->GetUserAvailableAmountBySiteid("bbin",$start_date,$end_date));
$bbin_num = count($bbin->data->data);
$bbin_num_n = 0;
foreach ($bbin->data->data as $name){
    $bbin_num_n = strstr($newuser,$name->username) ? $bbin_num_n + 1 : $bbin_num_n;
}
//echo $ag_num;
//print_r($lebo->data->data);


$count = $k_num + $c_num + $lebo_num + $mg_num + $ag_num + $og_num + $ct_num + $mg1_num + $bbin_num;
//echo $k_bet->getLastSql();

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
<div  id="con_wrap">
<div  class="input_002">会员分析系统</div>
<div  class="con_menu">
<div>
<a  href="user_account.php">出入款统计</a>
<!-- <a  href="agent_ad_statistics.php">优惠</a> -->
<a  href="bet_count.php">下注分析</a>
<a  href="bet_num.php" style="color:red">投注人数</a>
</div><br />
<form name="addForm"  action=""  method="get">
日期：
<input class="za_text Wdate" id="start_date" onClick="WdatePicker()" value="<?=$start_date?>" name="start_date"> &nbsp;
<input class="za_text Wdate" id="end_date" onClick="WdatePicker()" value="<?=$end_date?>" name="end_date">

<input  type="submit" value="搜索" class="za_button"> 

</form>
</div>
</div>
<div  class="content">
	<table  width="1024"  border="0"  cellspacing="0"  cellpadding="0"  bgcolor="#E3D46E"  class="m_tab">
		<tbody><tr  class="m_title_over_co">
			<td>日期</td>
			<td>体育赛事(新加入)</td>
			<td>彩票游戏(新加入)</td>
			<td>lebo</td>
			<td>MG视讯</td>
			<td>CT视讯</td>
			<td>OG视讯</td>
			<td>AG视讯</td>
			<td>MG电子</td>
			<td>BBIN电子</td>
			<td>总投注</td>
		</tr>

    		<tr  class="m_cen">
    		    <td><?=$start_date?> 到 <?=$end_date?></td>
    			<td><?=$k_num?>(<?=$k_num_n + 0?>)</td>
    			<td><?=$c_num?>(<?=$c_num_n + 0?>)</td>
    			<td><?=$lebo_num?>(<?=$lebo_num_n?>)</td>
    			<td><?=$mg_num?>(<?=$mg_num_n?>)</td>
    			<td><?=$ct_num?>(<?=$ct_num_n?>)</td>
		    	<td><?=$og_num?>(<?=$og_num_n?>)</td>
		    	<td><?=$ag_num?>(<?=$ag_num_n?>)</td>                
                <td><?=$mg1_num?>(<?=$mg1_num_n?>)</td>
                <td><?=$bbin_num?>(<?=$bbin_num_n?>)</td>
    			<td><?=$count?></td>
    		</tr>
				
		
	</tbody></table>
</div>
<?php require("../common_html/footer.php");?>
