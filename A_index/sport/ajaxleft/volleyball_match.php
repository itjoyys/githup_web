<?php
@session_start();
header("Content-type: text/html; charset=utf-8");
include_once("../../include/config.php");
include_once("../../include/private_config.php");
include_once("../../include/public_config.php");
include_once("../class/volleyball_match.php");
include_once("../common/function.php");
include_once("../common/logintu.php");
//这里要进行时间判断
$uid = $_SESSION["uid"];
sessionBet($uid); //这里要进行时间判断
islogin_match($uid); //未登陆，退出登陆状态
 
$rows=volleyball_match::getmatch_info(intval($_POST["match_id"]),$_POST["point_column"]);

$touzhuxiang	=	$_POST["touzhuxiang"];
$temp_array		=	explode("-",$touzhuxiang);
if($temp_array[0]=="让球"){
	$touzhuxiang = ereg_replace("[0-9\.\/]{1,}-",$rows["match_rgg"]."-",$touzhuxiang);
}
if($temp_array[0]=="大小"){
	$touzhuxiang = ereg_replace("[0-9.]{1,}",$rows["match_dxgg"],$touzhuxiang);
}
?>
<div class="match_msg"> 
<input type="hidden" name="ball_sort[]" value="<?=$_POST["ball_sort"]?>" />
<input type="hidden" name="point_column[]" value="<?=$_POST["point_column"]?>" />
<input type="hidden" name="match_id[]" value="<?=$_POST["match_id"]?>" />
<input type="hidden" name="match_name[]" value="<?=$rows["match_name"]?>"  />
<input type="hidden" name="match_showtype[]" value="<?=$rows["match_showtype"]?>"  />
<input type="hidden" name="match_rgg[]" value="<?=$rows["match_rgg"]?>"  />
<input type="hidden" name="match_dxgg[]" value="<?=$rows["match_dxgg"]?>"  />
<input type="hidden" name="match_nowscore[]" value="<?=$rows["Match_NowScore"]?>"  />
<input type="hidden" name="match_type[]" value="<?=$rows["match_type"]?>"  />
<input type="hidden" name="touzhuxiang[]" value="<?=$temp_array[0]?>"  />
<input type="hidden" name="master_guest[]"  value="<?=$rows["match_master"]?>VS.<?=$rows["match_guest"]?>"/>
<input type="hidden" name="bet_info[]"  value="<?=$touzhuxiang?>@<?=$rows[$_POST["point_column"]]?>"/> 
<input type="hidden" name="bet_point[]" value="<?=double_format($rows[$_POST["point_column"]])?>" />
<input type="hidden" name="match_time[]"  value="<?=$rows["match_time"]?>"/>
<input type="hidden" name="ben_add[]"  value="<?=$_POST["ben_add"]?>"/>
<input type="hidden" name="match_endtime[]"  value="<?=$rows["Match_CoverDate"]?>"/>

<div class="title">
<h1><?=$_POST["ball_sort"]?></h1>
<div class="tiTimer" onclick="orderReload();" style='background: url("../sport/public/images/order_btn.gif") no-repeat scroll 0 -122px rgba(0, 0, 0, 0);'>
<span id="bet_time">10</span>
<input id="checkOrder" type="checkbox" value="10" checked="">
</div>
</div>
		
<div class="leag"><?=$rows["match_name"]?>&nbsp;<?=$rows["match_time"]?></div>
<div class="gametype"><?=$rows["match_master"]?>&nbsp;VS.&nbsp;<?=$rows["match_guest"]?></div>
<?php
if($temp_array[0]=="让球"){ //让球
?>
<div class="gametype">
	盘口：<?=$rows["match_showtype"]=="H" ? '主让' : '客让'?>(<?=$rows["match_rgg"]?>)
</div>    
<?php
}
?>
<div class="teamName"></div>
<p class="team"><em><?=$_POST["xx"]?></em> @ <strong class="light" id="ioradio_id"><?=double_format($rows[$_POST["point_column"]])?></strong></p>







</div>
<?php
//include_once("../cache/group_".@$_SESSION["gid"].".php"); //加载权限组权限
include_once("../../set.php");
?>
<script>
if($("#touzhutype").val() == 0){
<?php
$dz=$dz_db[$_POST["ball_sort"]][$temp_array[0]];
$dc=$dc_db[$_POST["ball_sort"]][$temp_array[0]];
$dm=$dm_db[$_POST["ball_sort"]][$temp_array[0]];
if(!$dz || $dz=="") $dz=$dz_db['未定义'];
if(!$dc || $dc=="") $dc=$dc_db['未定义'];
if(!$dm || $dm=="") $dm=$dm_db['未定义'];
?>
	$("#max_ds_point_span").html('<?=$dz ? $dz : '0'?>');
	$("#max_cg_point_span").html('<?=$dc ? $dc : '0'?>');
	$("#min_point_span").html('<?=$dm ? $dm : '0'?>');
}else{
	$("#max_ds_point_span").html('<?=$dz_db['串关'] ? $dz_db['串关'] : '0'?>');
	$("#max_cg_point_span").html('<?=$dc_db['串关'] ? $dc_db['串关'] : '0'?>');
	$("#min_point_span").html('<?=$dm_db['串关'] ? $dm_db['串关'] : '0'?>');
}
window.clearTimeout(time_id);

var time_bet=10;
orderReload();
function orderReload(){
	var check=$("#checkOrder").attr('checked');
	if(check=='checked'){
		Refresh();
	}else{	
		$("#bet_time").html("10");
	}
}
function Refresh(){
	time_bet=time_bet-1;
	if(time_bet<0){
		time_bet=10;
		$("#bet_time").html(time_bet);
	}else{
		$("#bet_time").html(time_bet);
	}
	setTimeout("orderReload()",1000);
}
//waite();
</script>