<?php

ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(1);                    //打印出所有的 错误信息
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/user.php");
include_once("common.php");
include_once("../../include/public_config.php");
include_once("../common/pager.class.php");

$arr		=	array();
$arr_m		=	array();
$mid		=	$_GET["mid"];
$sql		=	"select point_column,bet_money,match_type,bet_info,match_id from `k_bet` where match_id=$mid and `status`!=3";
$query		=	$mysqlt->query($sql);
while($rows = $query->fetch_array()){
    if(strrpos($rows["bet_info"],"波胆") === 0){
        $arr[$rows["match_id"]]["match_bd"]['num']		=	$arr[$rows["match_id"]]["match_bd"]['num']+1;
        $arr[$rows["match_id"]]["match_bd"]['money']	=	$arr[$rows["match_id"]]["match_bd"]['money']+$rows["bet_money"];
    }
    if(strrpos($rows["point_column"],"match_hr_bd") === 0){
        $arr[$mid]["match_hr_bd"]['num']				=	$arr[$mid]["match_hr_bd"]['num']+1;
        $arr[$mid]["match_hr_bd"]['money']				=	$arr[$mid]["match_hr_bd"]['money']+$rows["bet_money"];
    }
    if(strrpos($rows["point_column"],"match_total") === 0){
        $arr[$mid]["match_total"]['num']				=	$arr[$mid]["match_total"]['num']+1;
        $arr[$mid]["match_total"]['money']				=	$arr[$mid]["match_total"]['money']+$rows["bet_money"];
    }
    if(strrpos($rows["point_column"],"match_bq") === 0){
        $arr[$mid]["match_bq"]['num']					=	$arr[$mid]["match_bq"]['num']+1;
        $arr[$mid]["match_bq"]['money']					=	$arr[$mid]["match_bq"]['money']+$rows["bet_money"];
    }

    if($rows["match_type"] == 2){
        $arr[$mid][$rows["point_column"]]['gq']['num']	=	$arr[$mid][$rows["point_column"]]['gq']['num']+1;
        $arr[$mid][$rows["point_column"]]['gq']['money']=	$arr[$mid][$rows["point_column"]]['gq']['money']+$rows["bet_money"];
    }else{
        $arr[$mid][$rows["point_column"]]['num']		=	$arr[$mid][$rows["point_column"]]['num']+1;
        $arr[$mid][$rows["point_column"]]['money']		=	$arr[$mid][$rows["point_column"]]['money']+$rows["bet_money"];
    }
}
?>
<html>
    <head>
        <title>注单审核</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="../images/control_main.css" type="text/css">
        <script language="javascript">
            function winclose(){
                self.close();
            }
        </script>
<body>
<table width="1054" border="0" cellpadding="0" cellspacing="1" bgcolor="#009900" id="gdiv_table">
    <tbody>
    <tr class="m_title_ft">
        <td width="80" height="24" nowrap="nowrap"><strong>獨贏</strong></td>
        <td width="80" nowrap="nowrap"><strong>滾球獨贏</strong></td>
        <td width="80" nowrap="nowrap"><strong>波膽</strong></td>
        <td width="80" nowrap="nowrap"><strong>半場波膽</strong></td>
        <td width="80" nowrap="nowrap"><strong>單雙</strong></td>
        <td width="80" nowrap="nowrap"><strong>總入球</strong></td>
        <td width="80" nowrap="nowrap"><strong>半全場</strong></td>
        <td width="80" nowrap="nowrap"><strong>半場滾球讓球</strong></td>
        <td width="80" nowrap="nowrap"><strong>半場滾球大小</strong></td>
        <td width="80" nowrap="nowrap"><strong>半場滾球獨贏</strong></td>
        <td width="80" nowrap="nowrap"><strong>半場讓球</strong></td>
        <td width="80" nowrap="nowrap"><strong>半場大小</strong></td>
        <td width="80" nowrap="nowrap"><strong>半場獨贏</strong></td>
    </tr>
    <tr>
        <td align="right" valign="top" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_BzM" <?=getAC($arr[$mid]["match_bzm"]['num'])?> ><?=getString($arr[$mid]["match_bzm"]['num'])?>/<?=getString($arr[$mid]["match_bzm"]['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_BzG" <?=getAC($arr[$mid]["match_bzg"]['num'])?> ><?=getString($arr[$mid]["match_bzg"]['num'])?>/<?=getString($arr[$mid]["match_bzg"]['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_BzH" <?=getAC($arr[$mid]["match_bzh"]['num'])?> ><?=getString($arr[$mid]["match_bzh"]['num'])?>/<?=getString($arr[$mid]["match_bzh"]['money'])?></a> </td>
        <td align="right" valign="top" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_BzM" <?=getAC($arr[$mid]["match_bzm"]['gq']['num'])?> ><?=getString($arr[$mid]["match_bzm"]['gq']['num'])?>/<?=getString($arr[$mid]["match_bzm"]['gq']['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_BzG" <?=getAC($arr[$mid]["match_bzg"]['gq']['num'])?> ><?=getString($arr[$mid]["match_bzg"]['gq']['num'])?>/<?=getString($arr[$mid]["match_bzg"]['gq']['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_BzH" <?=getAC($arr[$mid]["match_bzh"]['gq']['num'])?> ><?=getString($arr[$mid]["match_bzh"]['gq']['num'])?>/<?=getString($arr[$mid]["match_bzh"]['gq']['money'])?></a></td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&column_like=Match_Bd" <?=getAC($arr[$mid]["match_bd"]['num'])?> ><?=getString($arr[$mid]["match_bd"]['num'])?>/<?=getString($arr[$mid]["match_bd"]['money'])?></a> </td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&column_like=Match_Hr_Bd" <?=getAC($arr[$mid]["match_hr_bd"]['num'])?> ><?=getString($arr[$mid]["match_hr_bd"]['num'])?>/<?=getString($arr[$mid]["match_hr_bd"]['money'])?></a> </td>
        <td align="right" bgcolor="#FFFFFF"> <a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_DsDpl" <?=getAC($arr[$mid]["match_dsdpl"]['num'])?> ><?=getString($arr[$mid]["match_dsdpl"]['num'])?>/<?=getString($arr[$mid]["match_dsdpl"]['money'])?></a><br/>
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_DsSpl" <?=getAC($arr[$mid]["match_dsspl"]['num'])?> ><?=getString($arr[$mid]["match_dsspl"]['num'])?>/<?=getString($arr[$mid]["match_dsspl"]['money'])?></a> </td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&column_like=Match_Total" <?=getAC($arr[$mid]["match_total"]['num'])?> ><?=getString($arr[$mid]["match_total"]['num'])?>/<?=getString($arr[$mid]["match_total"]['money'])?></a> </td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&column_like=Match_Bq" <?=getAC($arr[$mid]["match_bq"]['num'])?> ><?=getString($arr[$mid]["match_bq"]['num'])?>/<?=getString($arr[$mid]["match_bq"]['money'])?></a> </td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&match_type=2&point_column=Match_BHo" <?=getAC($arr[$mid]["match_bho"]['num'])?> ><?=getString($arr[$mid]["match_bho"]['num'])?>/<?=getString($arr[$mid]["match_bho"]['money'])?></a> <br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&match_type=2&point_column=match_bao" <?=getAC($arr[$mid]["match_bao"]['num'])?> ><?=getString($arr[$mid]["match_bao"]['num'])?>/<?=getString($arr[$mid]["match_bao"]['money'])?></a> </td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&match_type=2&point_column=Match_Bdpl" <?=getAC($arr[$mid]["match_bdpl"]['gq']['num'])?> ><?=getString($arr[$mid]["match_bdpl"]['gq']['num'])?>/<?=getString($arr[$mid]["match_bdpl"]['gq']['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&match_type=2&point_column=Match_Bxpl" <?=getAC($arr[$mid]["match_bxpl"]['gq']['num'])?> ><?=getString($arr[$mid]["match_bxpl"]['gq']['num'])?>/<?=getString($arr[$mid]["match_bxpl"]['gq']['money'])?></a> </td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&match_type=2&point_column=Match_Bmdy" <?=getAC($arr[$mid]["match_bmdy"]['gq']['num'])?> ><?=getString($arr[$mid]["match_bmdy"]['gq']['num'])?>/<?=getString($arr[$mid]["match_bmdy"]['gq']['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&match_type=2&point_column=Match_Bgdy" <?=getAC($arr[$mid]["match_bgdy"]['gq']['num'])?> ><?=getString($arr[$mid]["match_bgdy"]['gq']['num'])?>/<?=getString($arr[$mid]["match_bgdy"]['gq']['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&match_type=2&point_column=Match_Bhdy" <?=getAC($arr[$mid]["match_bhdy"]['gq']['num'])?> ><?=getString($arr[$mid]["match_bhdy"]['gq']['num'])?>/<?=getString($arr[$mid]["match_bhdy"]['gq']['money'])?></a> </td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&match_type=2&point_column=Match_BHo" <?=getAC($arr[$mid]["match_bho"]['num'])?> ><?=getString($arr[$mid]["match_bho"]['num'])?>/<?=getString($arr[$mid]["match_bho"]['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&match_type=2&point_column=match_bao" <?=getAC($arr[$mid]["match_bao"]['num'])?> ><?=getString($arr[$mid]["match_bao"]['num'])?>/<?=getString($arr[$mid]["match_bao"]['money'])?></a></td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_Bdpl" <?=getAC($arr[$mid]["match_bdpl"]['num'])?> ><?=getString($arr[$mid]["match_bdpl"]['num'])?>/<?=getString($arr[$mid]["match_bdpl"]['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_Bxpl" <?=getAC($arr[$mid]["match_bxpl"]['num'])?> ><?=getString($arr[$mid]["match_bxpl"]['num'])?>/<?=getString($arr[$mid]["match_bxpl"]['money'])?></a></td>
        <td align="right" bgcolor="#FFFFFF"><a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_Bmdy" <?=getAC($arr[$mid]["match_bmdy"]['num'])?> ><?=getString($arr[$mid]["match_bmdy"]['num'])?>/<?=getString($arr[$mid]["match_bmdy"]['money'])?></a> <br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_Bgdy" <?=getAC($arr[$mid]["match_bgdy"]['num'])?> ><?=getString($arr[$mid]["match_bgdy"]['num'])?>/<?=getString($arr[$mid]["match_bgdy"]['money'])?></a><br />
            <a target="mainFrame"href="list.php?match_id=<?=$mid?>&point_column=Match_Bhdy" <?=getAC($arr[$mid]["match_bhdy"]['num'])?> ><?=getString($arr[$mid]["match_bhdy"]['num'])?>/<?=getString($arr[$mid]["match_bhdy"]['money'])?></a></td>
    </tr>
    <tr align="right" bgcolor="#cccccc">
        <td height="24" colspan="13" align="center" bgcolor="#FFFFFF"><input name="button" type="button" onClick="winclose();" value="關閉" /></td>
    </tr>
    </tbody>
</table>
<?php require("../common_html/footer.php");?>