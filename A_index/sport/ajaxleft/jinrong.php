<?php
@session_start();
header("Content-type: text/html; charset=utf-8");
include_once("../../include/config.php");
include_once("../../include/private_config.php");
include_once("../../include/public_config.php");
include_once("../class/jinrong.php");
include_once("../common/function.php");
include_once("../common/logintu.php");
//这里要进行时间判断
$uid = $_SESSION["uid"];
sessionBet($uid); //这里要进行时间判断
islogin_match($uid); //未登陆，退出登陆状态

$rows=jinrong::getmatch_info(intval($_POST["tid"]));
?>
<div class="match_msg"> 
<input type="hidden" name="ball_sort[]" value="金融" />
<input type="hidden" name="touzhuxiang[]" value="金融"  />
<input type="hidden" name="point_column[]" value="match_jr" />
<input type="hidden" name="match_id[]" value="<?=$_POST["match_id"]?>" />
<input type="hidden" name="tid[]" value="<?=$_POST["tid"]?>" />
<input type="hidden" name="match_name[]" value="<?=$rows["x_title"]?>"  />
<input type="hidden" name="master_guest[]"  value="<?=$rows["match_name"]?>"/>
<input type="hidden" name="bet_info[]"  value=" <?=$rows["x_title"]?>-<?=$rows["match_name"]?>-<?=$rows["team_name"]?>@<?=$rows["point"]?>"/> 
<input type="hidden" name="bet_point[]" value="<?=double_format($rows["point"])?>" />
<input type="hidden" name="ben_add[]" value="0" />
<input type="hidden" name="match_endtime[]"  value="<?=$rows["Match_CoverDate"]?>"/>
<div class="match_sort">金融-<?=$rows["x_title"]?></div>
<div class="match_name"><?=$rows["match_name"]?>&nbsp;<? if($rows["match_type"]==2) echo $rows["match_time"]; else echo $rows["match_date"]?></div>
<div class="match_master"><?=$rows["match_master"]?></div>
<div class="match_info">
	选项：<span class="match_master"><?=$rows["team_name"]?></span><br>
	水位：<span style="color:#D90000;"><?=double_format($rows["point"])?></span>&nbsp;&nbsp;&nbsp;&nbsp;<img src="../images/x.gif" alt="取消赛事" width="8" height="8" border="0" onclick="javascript:del_bet(this)" style="cursor:pointer;" />
	</div>
</div>
<?php
include_once("../../cache/group_".@$_SESSION["gid"].".php"); //加载权限组权限
?>
<script>
if($("#touzhutype").val() == 0){
	$("#max_ds_point_span").html('<?=$dz_db["金融"] ? $dz_db["金融"] : '0'?>');
	$("#max_cg_point_span").html('<?=$dc_db["金融"] ? $dc_db["金融"] : '0'?>');
}else{
	$("#max_ds_point_span").html('<?=$dz_db['串关'] ? $dz_db['串关'] : '0'?>');
	$("#max_cg_point_span").html('<?=$dc_db['串关'] ? $dc_db['串关'] : '0'?>');
}
window.clearTimeout(time_id);
waite();
</script>