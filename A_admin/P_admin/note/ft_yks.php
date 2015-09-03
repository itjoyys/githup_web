<?php

ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(1);                    //打印出所有的 错误信息
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/user.php");
include_once("common.php");
include_once("../../include/public_config.php");//$mysqli
include_once("../common/pager.class.php");

$date		=	date("m-d");
if($_GET['date']){
    $date	=	$_GET['date'];
}
$page_date	=	$date;
$sql		=	"SELECT Match_ID,Match_Date, Match_Time, Match_Master, Match_Guest, Match_RGG, Match_Name, Match_IsLose, Match_BzM, Match_BzG, Match_BzH, Match_DxDpl, Match_DxXpl, Match_DxGG, Match_Ho, Match_Ao, Match_MasterID, Match_GuestID, Match_ShowType, Match_DsDpl, Match_DsSpl,match_js FROM bet_match ";

isset($_GET['match_type'])?$match_type=intval($_GET["match_type"]):$match_type=1;
 $sqlwhere	=	" WHERE Match_CoverDate between '".date("Y-$date 00:00:00")."' and '".date("Y-$date 23:59:59")."' AND Match_Date like('%$date%')";
$sql		.=	$sqlwhere;

$match_name	=	getmatch_name('bet_match',$sqlwhere);
if(isset($_GET["match_name"]))  $sql.=" and match_name='".urldecode($_GET["match_name"])."'";
$sql		.=	" order by match_js,Match_CoverDate,ipage,isn";

$arr	=	array();
$arr_m		=	array();
$mid		=	'';
$query		=	$mysqli->query($sql);
while($rows = $query->fetch_array()){
    $arr[$rows["Match_ID"]]["Match_ID"]			=	$rows["Match_ID"]; //赛事id
    $arr[$rows["Match_ID"]]["Match_Date"]		=	$rows["Match_Date"];
    $arr[$rows["Match_ID"]]["Match_Time"]		=	$rows["Match_Time"];
    $arr[$rows["Match_ID"]]["Match_Master"]		=	$rows["Match_Master"];
    $arr[$rows["Match_ID"]]["Match_Guest"]		=	$rows["Match_Guest"];
    $arr[$rows["Match_ID"]]["Match_RGG"]		=	$rows["Match_RGG"];
    $arr[$rows["Match_ID"]]["Match_Name"]		=	$rows["Match_Name"];
    $arr[$rows["Match_ID"]]["Match_IsLose"]		=	$rows["Match_IsLose"];
    $arr[$rows["Match_ID"]]["Match_BzM"]		=	$rows["Match_BzM"];
    $arr[$rows["Match_ID"]]["Match_BzG"]		=	$rows["Match_BzG"];
    $arr[$rows["Match_ID"]]["Match_BzH"]		=	$rows["Match_BzH"];
    $arr[$rows["Match_ID"]]["Match_DxDpl"]		=	$rows["Match_DxDpl"];
    $arr[$rows["Match_ID"]]["Match_DxXpl"]		=	$rows["Match_DxXpl"];
    $arr[$rows["Match_ID"]]["Match_DxGG"]		=	$rows["Match_DxGG"];
    $arr[$rows["Match_ID"]]["Match_Ho"]			=	$rows["Match_Ho"];
    $arr[$rows["Match_ID"]]["Match_Ao"]			=	$rows["Match_Ao"];
    $arr[$rows["Match_ID"]]["Match_MasterID"]	=	$rows["Match_MasterID"];
    $arr[$rows["Match_ID"]]["Match_GuestID"]	=	$rows["Match_GuestID"];
    $arr[$rows["Match_ID"]]["Match_ShowType"]	=	$rows["Match_ShowType"];
    $arr[$rows["Match_ID"]]["Match_DsDpl"]		=	$rows["Match_DsDpl"];
    $arr[$rows["Match_ID"]]["Match_DsSpl"]		=	$rows["Match_DsSpl"];
    $arr[$rows["Match_ID"]]["match_js"]			=	$rows["match_js"];
    $arr_m[$rows["Match_ID"]]					=	0;
    $mid.=$rows["Match_ID"].',';
}

if($mid){
    $mid	=	rtrim($mid,",");
    $sql	=	"select match_id,point_column,bet_money,match_type from `k_bet` where match_id in($mid) and Match_CoverDate between '".date("Y-$date 00:00:00")."' and '".date("Y-$date 23:59:59")."' and `status`!=3 and `site_id`='".SITEID."'";
    $query	=	$mysqlt->query($sql);
    while($rows = $query->fetch_array()){
        if($rows["match_type"] == 2){
            $arr[$rows["match_id"]][$rows["point_column"]]['gq']['num']		=	$arr[$rows["match_id"]][$rows["point_column"]]['gq']['num']+1;
            $arr[$rows["match_id"]][$rows["point_column"]]['gq']['money']	=	$arr[$rows["match_id"]][$rows["point_column"]]['gq']['money']+$rows["bet_money"];
        }else{
            $arr[$rows["match_id"]][$rows["point_column"]]['num']			=	$arr[$rows["match_id"]][$rows["point_column"]]['num']+1;
            $arr[$rows["match_id"]][$rows["point_column"]]['money']			=	$arr[$rows["match_id"]][$rows["point_column"]]['money']+$rows["bet_money"];
        }
        $arr_m[$rows["match_id"]]											+=	$rows["bet_money"];
    }
}

arsort($arr_m);
require("../common_html/header.php");?>
    <style>
        .leg_type a:link,.leg_type a:visited{color: #000004; margin-left: 5px; border-bottom: #000000 solid 1px;}
    </style>
    <link rel="stylesheet" href="../images/control_main.css" type="text/css">
    <script language="javascript">
        function gopage(url){
            location.href=url;
        }
        function re_load(){
            location.reload();
        }
        function winopen(url){
            window.open(url,"list","width=1060,height=100,left=150,top=300,scrollbars=no");
        }
    </script>
<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" vlink="#0000FF" alink="#0000FF" >
<table width="100%" height="0" cellpadding="0" cellspacing="0" class="leg_type">
    <tr style="width:150px;">
        <td width="150">
            <div  class="input_002"><?
                if($_GET['match_type']==1) $mach_type_name='';
                else  $mach_type_name='早餐';
                if($_GET['MacthType'] ){
                    $macth_name_=$_GET['MacthType'];
                    echo      $macth_name= $_GET['MacthType'].'-已开赛';
                }
                else{
                    $macth_name_="足球$mach_type_name";
                    echo $macth_name="足球$mach_type_name-已开赛";
                }
                ?></div>
        </td>
        <td width="250">
            &nbsp; &nbsp;赛事选择：
            <select id="select" name="table" onChange="gopage(this.value);" class="za_select">
                <?if($_GET['MacthType']){
                    $RefUrl=$_SERVER['HTTP_REFERER'];
                    $RefUrl=explode('/',$RefUrl);
                    $RefUrl=end($RefUrl);
                    $RefUrl=explode('?',$RefUrl);
                    $RefUrl=$RefUrl[0];
                    echo '<option value="'.$RefUrl.'?match_type='.$_GET['match_type'].'&MacthType='.$_GET['MacthType'].'">'.$_GET['MacthType'].'</option> ';
                }?>
                <option value="ft_danshi.php?match_type=1&MacthType=足球" >足球</option>
                <option value="ft_danshi.php?match_type=0&MacthType=足球早餐" >足球早餐</option>
                <option value="bk_danshi.php?match_type=1&MacthType=篮球">篮球</option>
                <option value="bk_danshi.php?match_type=0&MacthType=篮球早餐">篮球早餐</option>
                <option value="tennis_danshi.php?match_type=1&MacthType=网球">网球</option>
                <option value="tennis_danshi.php?match_type=0&MacthType=网球早餐">网球早餐</option>
                <option value="volleyball_danshi.php?match_type=1&MacthType=排球">排球</option>
                <option value="volleyball_danshi.php?match_type=0&MacthType=排球早餐">排球早餐</option>
                <option value="baseball_danshi.php?match_type=1&MacthType=棒球">棒球</option>
                <option value="baseball_danshi.php?match_type=0&MacthType=棒球早餐">棒球早餐</option>
                <option value="guanjun.php?match_type=1&MacthType=冠軍">冠軍</option>
            </select></td>
        <td width="230">美东时间：<?php if($match_type==1){?>
                <select id="DropDownList1" onChange="javascript:gopage(this.value)" name="DropDownList1">
                <option value="<?=$_SERVER['PHP_SELF']?>?match_type=1">==选择时间==</option>
                <? for ($i=0;$i<=6;$i++){
                    $s=strtotime("-$i day");
                    $date=date("m-d",$s);
                    $page_date==$date ? $date_=date("Y-m-d",$s):"";
                    ?>
                    <option value="<?=$_SERVER['PHP_SELF']?>?match_type=1&date=<?=$date?>" <?=@$page_date==$date ? "selected" : "" ?>><?=$date?></option>
                <?}?>
                </select><?php }else if($match_type==0){?>

                <select id="DropDownList1" onChange="javascript:gopage(this.value)" name="DropDownList1">
                    <option value="<?=$_SERVER['PHP_SELF']?>?match_type=0">==选择时间==</option>
                    <? for ($i=1;$i<=7;$i++){
                        $s=strtotime("+$i day");
                        $date=date("m-d",$s);
                        $page_date==$date ? $date_=date("Y-m-d",$s):"";
                        ?>
                        <option value="<?=$_SERVER['PHP_SELF']?>?match_type=0&date=<?=$date?>" <?=@$page_date==$date ? "selected" : "" ?>><?=$date?></option>
                    <?}?>
                </select>
            <?}?>&nbsp;&nbsp;<a href="javascript:re_load();">刷新</a></td>
        <td width="262" align="left">&nbsp;&nbsp;选择联盟&nbsp;
            <select id="set_account" name="match_name" onChange="gopage(this.value);" class="za_select">
                <option value="<?=$_SERVER['PHP_SELF']?>?match_type=<?=$match_type?>&amp;date=<?=$page_date?>">==选择联盟==</option>
                <?php
                foreach ($match_name as $k=>$v){?>
                    <option <? if(urldecode(@$_GET["match_name"])==$v){?> selected="selected" <? }?> value="<?=$_SERVER['PHP_SELF']?>?match_name=<?=urlencode(@$v)?>&amp;match_type=<?=$match_type?>&amp;date=<?=$page_date?>"><?=$v?></option>
                <?php }?>
            </select>	</td>
        <td width="435"><A  href="ft_danshi.php?match_type=<?=$match_type?>">單式</A>&nbsp;&nbsp;<?php if($match_type==1){?> <A href="ft_gunqiu.php">滾球</A><?}?>&nbsp;&nbsp;<a  href="ft_shangbanchang.php?match_type=<?=$match_type?>">上半場</a>&nbsp;&nbsp;<A href="ft_shangbanbodan.php?match_type=<?=$match_type?>">上半波膽</A>&nbsp;&nbsp;<span id="pg_txt"><a href="ft_bodan.php?match_type=<?=$match_type?>">波膽</a>&nbsp;&nbsp;<A href="ft_ruqiushu.php?match_type=<?=$match_type?>">入球数</A>&nbsp;&nbsp;<A  href="ft_banquanchang.php?match_type=<?=$match_type?>">半全場</A>&nbsp;&nbsp;<A  href="ft_yks.php">已开赛</A></span></td>
    </tr>
</table>
<table width="1140" border="0" cellpadding="0" cellspacing="1"  bgcolor="006255" class="m_tab" id="glist_table">
    <tr class="m_title">
        <td width="60" ><strong>時間</strong></td>
        <td nowrap="nowrap" width="160"><strong>聯盟</strong></td>
        <td width="50"><strong>場次</strong></td>
        <td width="160"><strong>隊伍</strong></td>
        <td width="160"><strong>讓球/注單</strong></td>
        <td width="160"><strong>大小盤/注單</strong></td>
        <td width="160"><strong>讓球/滚球</strong></td>
        <td width="160"><strong>大小盤/滚球</strong></td>
        <td width="60"><strong>更多</strong></td>
    </tr>
    <?php
    foreach($arr_m as $k=>$v){
        $rows	=	$arr[$k];
        $color	=	getColor($v);
        ?>
        <tr bgcolor="<?=$color?>" onMouseOver="this.style.backgroundColor='#EBEBEB'" onMouseOut="this.style.backgroundColor='<?=$color?>'">
            <td align="center"><?=$rows["Match_Date"]?><br><?=$rows["Match_Time"]?><br><? if(@$rows["Match_IsLose"]==1){?><span style="color:#FF0000">滾球</span><? } ?></td>
            <td><?=$rows["Match_ID"]?><br /><?=$rows["Match_Name"]?></td>
            <td><?=$rows["Match_MasterID"]?><br><?=$rows["Match_GuestID"]?></td>
            <td align="left"><?=$rows["Match_Master"]?><br><?=$rows["Match_Guest"]?>
                <div style=" color:#009933">和局</div></td>
            <td><table cellspacing="0" cellpadding="0" width="100%" border="0">
                    <tbody>
                    <tr align="right">
                        <td width="50%"><? if($rows["Match_ShowType"]=="H"){?>  <font color="#0033FF"><?=$rows["Match_RGG"]?> </font> <?}?>
                            <? if($rows["Match_Ho"]>0) echo double_format($rows["Match_Ho"]);?></font>              </td>
                        <td width="50%"><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=Match_Ho&amp;match_type=1" <?=getAC($arr[$rows["Match_ID"]]["match_ho"]['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_ho"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_ho"]['money'])?></a></td>
                    </tr>
                    <tr align="right">
                        <td><? if($rows["Match_ShowType"]=="C"){?>  <font color="#0033FF"><?=$rows["Match_RGG"]?> </font> <?}?>
                            <? if($rows["Match_Ao"]>0) echo double_format($rows["Match_Ao"]);?>             </td>
                        <td><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=match_ao&amp;match_type=1" <?=getAC($arr[$rows["Match_ID"]]["match_ao"]['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_ao"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_ao"]['money'])?></a></td>
                    </tr>
                    <tr>
                        <td colspan="2"> </td>
                    </tr>
                    </tbody>
                </table></td>
            <td><table cellspacing="0" cellpadding="0" width="100%" border="0">
                    <tbody>
                    <tr align="right">
                        <td width="50%"><? if ($rows["Match_DxGG"]>0){?><font color="#0033FF"><?="O".$rows["Match_DxGG"]?> </font>
                            <?}?>                <? if($rows["Match_DxDpl"]>0) echo double_format($rows["Match_DxDpl"]);?>              </td>
                        <td width="50%"><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=Match_DxDpl&amp;match_type=1" <?=getAC($arr[$rows["Match_ID"]]["match_dxdpl"]['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_dxdpl"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_dxdpl"]['money'])?></a> </td>
                    </tr>
                    <tr align="right">
                        <td>  <? if ($rows["Match_DxGG"]>0){?>   <font color="#0033FF"><?="U".$rows["Match_DxGG"]?> </font><?}?>  <? if($rows["Match_DxXpl"]>0) echo double_format($rows["Match_DxXpl"]);?>     <br />              </td>
                        <td><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=Match_DxXpl&amp;match_type=1" <?=getAC($arr[$rows["Match_ID"]]["match_dxxpl"]['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_dxxpl"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_dxxpl"]['money'])?></a> </td>
                    </tr>
                    <tr>
                        <td colspan="3"> </td>
                    </tr>
                    </tbody>
                </table></td>
            <td><table cellspacing="0" cellpadding="0" width="100%" border="0">
                    <tbody>
                    <tr align="right">
                        <td width="50%"><? if($rows["Match_ShowType"]=="H"){?>  <font color="#0033FF"><?=$rows["Match_RGG"]?> </font> <?}?>
                            <? if($rows["Match_Ho"]>0) echo double_format($rows["Match_Ho"]);?></font>              </td>
                        <td width="50%"><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=Match_Ho&amp;match_type=2" <?=getAC($arr[$rows["Match_ID"]]["match_ho"]['gq']['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_ho"]['gq']['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_ho"]['gq']['money'])?></a></td>
                    </tr>
                    <tr align="right">
                        <td><? if($rows["Match_ShowType"]=="C"){?>  <font color="#0033FF"><?=$rows["Match_RGG"]?> </font> <?}?>
                            <? if($rows["Match_Ao"]>0) echo double_format($rows["Match_Ao"]);?>             </td>
                        <td><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=match_ao&amp;match_type=2" <?=getAC($arr[$rows["Match_ID"]]["match_ao"]['gq']['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_ao"]['gq']['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_ao"]['gq']['money'])?></a></td>
                    </tr>
                    <tr>
                        <td colspan="2"> </td>
                    </tr>
                    </tbody>
                </table></td>
            <td><table cellspacing="0" cellpadding="0" width="100%" border="0">
                    <tbody>
                    <tr align="right">
                        <td width="50%"><? if ($rows["Match_DxGG"]>0){?>   <font color="#0033FF"><?="O".$rows["Match_DxGG"]?> </font>
                            <?}?>                <? if($rows["Match_DxDpl"]>0) echo double_format($rows["Match_DxDpl"]);?></td>
                        <td width="50%"><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=Match_DxDpl&amp;match_type=2" <?=getAC($arr[$rows["Match_ID"]]["match_dxdpl"]['gq']['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_dxdpl"]['gq']['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_dxdpl"]['gq']['money'])?></a> </td>
                    </tr>
                    <tr align="right">
                        <td>  <? if ($rows["Match_DxGG"]>0){?>   <font color="#0033FF"><?="U".$rows["Match_DxGG"]?> </font><?}?>  <? if($rows["Match_DxXpl"]>0) echo double_format($rows["Match_DxXpl"]);?>     <br />              </td>
                        <td><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=Match_DxXpl&amp;match_type=2" <?=getAC($arr[$rows["Match_ID"]]["match_dxxpl"]['gq']['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_dxxpl"]['gq']['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_dxxpl"]['gq']['money'])?></a> </td>
                    </tr>
                    <tr>
                        <td colspan="3"> </td>
                    </tr>
                    </tbody>
                </table></td>
            <td align="center"><a href="javascript:winopen('yks_gn.php?mid=<?=$rows["Match_ID"]?>')">more..</a></td>
        </tr>
    <? }?>
</table>
<?php require("../common_html/footer.php");?>