<?php
@session_start();
header("Content-type: text/html; charset=utf-8");
include('../../include/filter.php');
include('../../include/login_check.php');
//include_once("../../include/private_config.php");
include_once("../../include/public_config.php");
include_once("../class/lq_match.php");
include_once("../common/function.php");
include_once("../common/logintu.php");
include ('../../include/redis_config.php');
include('../include/function_cj.php');
//这里要进行时间判断
$uid = $_SESSION["uid"];
sessionBet($uid); //这里要进行时间判断
islogin_match($uid); //未登陆，退出登陆状态
 
$rows=lq_match::getmatch_info(intval($_POST["match_id"]),$_POST["point_column"],$_POST["ball_sort"]);
$touzhuxiang	=	$_POST["touzhuxiang"];
$temp_array		=	explode("-",$touzhuxiang);
if($temp_array[0]=="让球"){
	$touzhuxiang = ereg_replace("[0-9\.\/]{1,}-",$rows["match_rgg"]."-",$touzhuxiang);
}
if($temp_array[0]=="大小"){
	$touzhuxiang = ereg_replace("[0-9.]{1,}",$rows["match_dxgg"],$touzhuxiang);
}
if (intval($_POST['touzhutype'])==0) echo '<script src="public/js/lq_match_js.js"></script>';
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
    <?if(intval($_POST['touzhutype'])==0){?>
<div class="tiTimer" onclick="orderReload();" style='background: url("../sport/public/images/order_btn.gif") no-repeat scroll 0 -122px rgba(0, 0, 0, 0);'>
<span id="bet_time">10</span>
<input id="checkOrder" type="checkbox" value="10" checked="">
</div>

<? }?>
    </div>
<?if(intval($_POST['touzhutype'])==0){?>
<div class="leag"><?=$rows["match_name"]?>&nbsp;<?=$rows["match_type"]>0 ? $rows["match_time"] : $rows["match_date"]?></div>
<?}else{?>
<div class="leag"></div>
<?}?>
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


<?php
//include_once("../cache/group_".@$_SESSION["gid"].".php"); //加载权限组权限
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
	$("#max_ds_point_span").html('<?=$dz ? $dz : '0'?>');
	$("#max_cg_point_span").html('<?=$dc ? $dc : '0'?>');
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
        // setbet(typename_in,touzhuxiang_in,match_id_in,point_column_in,ben_add_in,is_lose_in,xx_in)
        var ball_sort = $('[name="ball_sort[]"]').val()
        var bet_info = $('[name="bet_info[]"]').val()
        var match_id = $('[name="match_id[]"]').val()
        var point_column = $('[name="point_column[]"]').val()
        var ben_add = $('[name="ben_add[]"]').val()
        var is_lose = $('[name="is_lose"]').val()
        var bet_point = $('[name="bet_point[]"]').val()
//上半场大小-O2(1:0)@1.00
        xx_in= bet_info=bet_info.split('@');
        bet_info=bet_info[0];
        xx_in=xx_in[0].split('-')
        xx_in=xx_in.pop().split('(')
        xx_in=xx_in[0]
        //  alert(bet_info+' | '+bet_point)
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
</script>