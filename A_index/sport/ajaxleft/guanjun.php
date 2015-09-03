<?php
@session_start();
header("Content-type: text/html; charset=utf-8");
include_once("../../include/config.php");
include_once("../../include/private_config.php");
include_once("../../include/public_config.php");
include_once("../class/guanjun.php");
include_once("../common/function.php");
include_once("../common/logintu.php");
//这里要进行时间判断
$uid = $_SESSION["uid"];
sessionBet($uid); //这里要进行时间判断
islogin_match($uid); //未登陆，退出登陆状态

$rows=guanjun::getmatch_info(intval(@$_POST["tid"]));
?>
<div class="match_msg"> 
<input type="hidden" name="ball_sort[]" value="冠军" />
<input type="hidden" name="touzhuxiang[]" value="冠军"  />
<input type="hidden" name="point_column[]" value="match_gj" />
<input type="hidden" name="match_id[]" value="<?=@$_POST["match_id"]?>" />
<input type="hidden" name="tid[]" value="<?=@$_POST["tid"]?>" />
<input type="hidden" name="match_name[]" value="<?=$rows["x_title"]?>"  />
<input type="hidden" name="master_guest[]"  value="<?=$rows["match_name"]?>"/>
<input type="hidden" name="bet_info[]"  value=" <?=$rows["x_title"]?>-<?=$rows["match_name"]?>-<?=$rows["team_name"]?>@<?=$rows["point"]?>"/> 
<input type="hidden" name="bet_point[]" value="<?=double_format($rows["point"])?>" />
<input type="hidden" name="ben_add[]" value="0" />
<input type="hidden" name="match_endtime[]"  value="<?=$rows["Match_CoverDate"]?>"/>
<!--
<div class="title"><h1>冠军-<?=$rows["x_title"]?></h1><img class="tiTimer" src="../../images/x.gif" alt="取消赛事"  border="0" onclick="javascript:del_bet(this)" style="cursor:pointer;width:10px;height:10px;" /></div>-->

<div class="title">
<h1>冠军-<?=$rows["x_title"]?></h1>
<div class="tiTimer" onclick="orderReload();" style='background: url("../sport/public/images/order_btn.gif") no-repeat scroll 0 -122px rgba(0, 0, 0, 0);'>
<span id="bet_time">10</span>
<input id="checkOrder" type="checkbox" value="10" checked="">
</div>
</div>

		
<div class="leag"><?=$rows["match_name"]?>&nbsp;<? if($rows["match_type"]==2) echo $rows["match_time"]; else echo $rows["match_date"]?></div>
<div class="gametype"><?=$rows["match_master"]?></div>
<div class="teamName"></div>
<p class="team"><em></em> @ <strong class="light" id="ioradio_id"><?=double_format($rows["point"])?></strong></p>

</div>

<?php
//$_SESSION["gid"]=1;
//include_once("../cache/group_".@$_SESSION["gid"].".php"); //加载权限组权限
include_once("../../set.php");
?>
<script>
if($("#touzhutype").val() == 0){
	$("#max_ds_point_span").html('<?=$dz_db["冠军"] ? $dz_db["冠军"] : '0'?>');
	$("#max_cg_point_span").html('<?=$dc_db["冠军"] ? $dc_db["冠军"] : '0'?>');
	$("#min_point_span").html('<?=$dm_db["冠军"] ? $dm_db["冠军"] : '0'?>');
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
     