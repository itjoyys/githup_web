<?php
/*ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(0);*/
@session_start();
header("Content-type: text/html; charset=utf-8");

include('../../include/filter.php');
include('../../include/login_check.php');
include('../common/logintu.php');
include_once("../../include/public_config.php");
include_once("../class/bet_match.php");
include_once("../common/function.php");
include ('../../include/redis_config.php');
include('../include/function_cj.php');
include('../include/function.php');
//这里要进行时间判断
$uid = $_SESSION["uid"];

sessionBet($uid);


islogin_match($uid); //未登陆，退出登陆状态

//echo $_POST["match_id"].$_POST["point_column"].$_POST["ball_sort"];
$rows=bet_match::getmatch_info(intval($_POST["match_id"]),$_POST["point_column"],$_POST["ball_sort"]);
//print_r($rows);
$touzhuxiang = $_POST["touzhuxiang"];

$temp_array=explode("-",$touzhuxiang);
if($temp_array[0]=="让球" || $temp_array[0]=="上半场让球"){
	$touzhuxiang = $temp_array[0]."-".ereg_replace("[0-9.\/]{1,}",$rows["match_rgg"],$temp_array[1])."-".$temp_array[2];
}
if($temp_array[0]=="大小" || $temp_array[0]=="上半场大小"){
	$uo			 = ($_POST["point_column"]=="Match_Bdpl" || $_POST["point_column"]=="Match_DxDpl" || $_POST["point_column"]=="Match_BHo") ? "O" : "U";
	$touzhuxiang = ereg_replace("[U0-9O.\/]{2,}",$uo.$rows["match_dxgg"],$touzhuxiang);
}

$tzx = $touzhuxiang;

$temp_array=explode("-",$touzhuxiang);
if(count($temp_array)>2){
	$touzhuxiang=$temp_array[0].$temp_array[1]."</p><p style=\"text-align:center\">".$temp_array[2];
}
 if (intval($_POST['touzhutype'])==0) echo '<script src="public/js/bet_match_js.js"></script>';
?>
<div>
    <?
    if (intval($_POST['touzhutype'])==0)
    {
        ?>
        <span class="tiTimer" onclick="orderReload();" style=' float:right; margin-top:3px; width:40px; text-align:right;background: url("../sport/public/images/order_btn.gif") no-repeat scroll 0px -125px rgba(0, 0, 0, 0);'>
            <span id="bet_time">10</span>
            <input id="checkOrder" type="checkbox" value="10" checked="">

        </span>
    <? }?>

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
<input type="hidden" name="bet_info[]"  value="<?=$tzx?><? if($_POST["is_lose"]==1) echo "(".$rows['Match_NowScore'].")"; ?>@<?=double_format($rows[$_POST["point_column"]])?>"/> 
<input type="hidden" name="bet_point[]" value="<?=double_format($rows[$_POST["point_column"]])?>" /> 
<input type="hidden" name="ben_add[]"  value="<?=$_POST["ben_add"]?>"/>
<input type="hidden" name="match_time[]" value="<?=$rows["match_time"]?>"  />
<input type="hidden" name="match_endtime[]"  value="<?=$rows["Match_CoverDate"]?>"/>
<input type="hidden" name="Match_HRedCard[]"  value="<?=$rows["Match_HRedCard"]?>"/>
<input type="hidden" name="Match_GRedCard[]"  value="<?=$rows["Match_GRedCard"]?>"/>
<input type="hidden" name="is_lose"  value="<?=$_POST["is_lose"]?>"/>
</div>
<div class="title"> <h1><?=$_POST["ball_sort"]?></h1></div>

<?php
 if (intval($_POST['touzhutype'])==1)
 {
?>
     <span onclick="javascript:del_bet(this)" style="float:right; margin:8px 3px 0px 0px;cursor:pointer; width: 10px; height: 10px; background: url(../sport/public/images/order_icon.gif)" />
     </span>
     <div class="leag"><?=$rows["match_name"]?>&nbsp;<?=$rows["match_type"]>0 ? $rows["match_time"] : $rows["match_date"];?></div>
     <div class="gametype"><?=$rows["match_master"]?>&nbsp;VS.&nbsp;<?=$rows["match_guest"]?></div>
     <?php
     if($temp_array[0]=="让球" || $temp_array[0]=="上半场让球"){ //让球
         ?>
         <div class="gametype">盘口：<?=$rows["match_showtype"]=="H" ? '主让' : '客让'?>(<?=$rows["match_rgg"]?>)</div>
     <?php
     }elseif(($temp_array[0]=="波胆" || $temp_array[0]=="上半波胆") && $_POST["xx"]!=$temp_array[1]){ //波胆
         ?>
         <div class="gametype">盘口：<?=$temp_array[1]?></div>
     <?php
     }
     ?>
     <div class="teamName"></div>
     <p class="team"><em><?=$_POST["xx"]?> <?=$_POST["is_lose"]==1? '('.$rows["Match_NowScore"].')':''?></em> @ <strong class="light" id="ioradio_id"><?=double_format($rows[$_POST["point_column"]])?></strong></p>
 <?
 }
 else
 {
?>

<!--
<div class="title"><h1 id="match_sort"><?=$_POST["ball_sort"]?></h1><img class="tiTimer" src="../../images/x.gif" alt="取消赛事"  border="0" onclick="javascript:del_bet(this)" style="cursor:pointer;width:10px;height:10px;" /></div>-->
<div class="leag"><?=$rows["match_name"]?>&nbsp;<?=$rows["match_type"]>0 ? $rows["match_time"] : $rows["match_date"];?></div>
<div class="gametype"><?=$rows["match_master"]?>&nbsp;VS.&nbsp;<?=$rows["match_guest"]?></div>
<?php
if($temp_array[0]=="让球" || $temp_array[0]=="上半场让球"){ //让球
?>
	<div class="gametype">盘口：<?=$rows["match_showtype"]=="H" ? '主让' : '客让'?>(<?=$rows["match_rgg"]?>)</div>
<?php
}elseif(($temp_array[0]=="波胆" || $temp_array[0]=="上半波胆") && $_POST["xx"]!=$temp_array[1]){ //波胆
?>
	<div class="gametype">盘口：<?=$temp_array[1]?></div>

<?php
}
?>

<div class="teamName"></div>

<p class="team"><em><?=$_POST["xx"]?> <?=$_POST["is_lose"]==1? '('.$rows["Match_NowScore"].')':''?></em> @ <strong class="light" id="ioradio_id"><?=double_format($rows[$_POST["point_column"]])?></strong></p>

<?
 }
?>

</div>
<?php
include_once("../set.php");
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
	$("#max_ds_point_span").html('<?=$dz ? $dz : '0'?>');//单注
	$("#max_cg_point_span").html('<?=$dc ? $dc : '0'?>');//单场
	$("#min_point_span").html('<?=$dm ? $dm : '0'?>');

    /*----------------s--------------*/
    var bet_point=parseFloat($("input[name='bet_point[]']").val())*1;
     if(bet_point==0.01){
     alert("赔率已改变，请重新下单");
     $("#submit_from").attr("disabled",false); //按钮有效
     }
    var bet_money = $("#bet_money").val();
    $("#bet_money").val(bet_money);
     bet_point = bet_point+parseInt($("input[name='ben_add[]']").val(),10);
     bet_point=(bet_money*bet_point).toFixed(2);
    $("#win_span").html(bet_point);
    $("#win_span1").html(bet_point);

    /*-----------------e-------------*/
}else{

	$("#max_ds_point_span").html('<?=$dz_db['串关'] ? $dz_db['串关'] : '0'?>');
	$("#max_cg_point_span").html('<?=$dc_db['串关'] ? $dc_db['串关'] : '0'?>');
	$("#min_point_span").html('<?=$dm_db['串关'] ? $dm_db['串关'] : '0'?>');
}

window.clearTimeout(time_id);

clearTimeout(time_id);

var time_bet=10;
orderReload();
function orderReload(){
    clearTimeout(time_id);
	var check=$("#checkOrder").attr('checked');
	if(check=='checked'){
		Refresh();
	}else{
		$("#bet_time").html("10");
	}
}

function Refresh(){
	time_bet=time_bet-1;
    if(time_bet==0){

        var ball_sort = $('[name="ball_sort[]"]').val()
        var bet_info = $('[name="bet_info[]"]').val()
        var match_id = $('[name="match_id[]"]').val()
        var point_column = $('[name="point_column[]"]').val()
        var ben_add = $('[name="ben_add[]"]').val()
        var is_lose = $('[name="is_lose"]').val()
        var bet_point = $('[name="bet_point[]"]').val()
        var match_nowscore = $('[name="match_nowscore[]"]').val()

//上半场大小-O2(1:0)@1.00 match_nowscore
        xx_in= bet_info=bet_info.split('@');
        bet_info=bet_info[0];
        xx_in=xx_in[0].split('-')
        xx_in=xx_in.pop().split('(')
        xx_in=xx_in[0]

        //alert(bet_info+'\n|xx_in '+xx_in)
        if(bet_info.indexOf('胆')>=0){
            xx_in="<?=$_POST["xx"]?>";
        }
        else if(bet_info.indexOf('独赢')>=0){
            xx_in="<?=$_POST["xx"]?>";
        }
        //if(is_lose==1) xx_in+=match_nowscore;
        setbet(ball_sort,bet_info,match_id,point_column,ben_add,is_lose,xx_in);
    }
	if(time_bet<0){
		time_bet=10;
		$("#bet_time").html(time_bet);
	}else{
		$("#bet_time").html(time_bet);
	}
    time_id =setTimeout("orderReload()",1000);
}
//waite();
</script>