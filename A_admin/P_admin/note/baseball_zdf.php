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



$date			=	date("m-d");
if($_GET['date']){
   // $date		=	$_GET['date'];
}
$page_date		=	$date;
$sql			=	"SELECT Match_ID,Match_Date, Match_Time, Match_Master, Match_Guest, Match_Name, Match_IsLose,Match_BzM, Match_BzG, Match_BzH , Match_MasterID, Match_GuestID FROM baseball_match ";

isset($_GET['match_type'])?$match_type=intval($_GET["match_type"]):$match_type=1;
$sqlwhere		=	" WHERE Match_Type=1 and Match_Date='".date("m-d")."'";
if(isset($_GET["date"])){
   // $sqlwhere	=	" WHERE Match_Type=1 AND Match_Date='$page_date' ";
}
$sql			.=	$sqlwhere;
$match_name		=	getmatch_name('baseball_match',$sqlwhere);
if(isset($_GET["match_name"])) $sql.="  and match_name='".urldecode($_GET["match_name"])."'";
$sqlorder		=	" order by Match_CoverDate,match_name asc ";
$sql			.=	$sqlorder;

$arr		=	array();
$arr_m		=	array();
$mid		=	'';
$query		=	$mysqli->query($sql);
//////////////////////////////////////
$sum    = $mysqli->affected_rows;//总页数
$thisPage = 1;
if(@$_GET['page']){
    $thisPage = $_GET['page'];
}
$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20;

$totalPage=ceil($sum/$pagenum);

$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
$mid    = '';
$i      = 1; //记录 uid 数
$start  = ($CurrentPage-1)*$pagenum+1;
$end  = $CurrentPage*$pagenum;
////////////////////////////////////////////////
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
    $arr[$rows["Match_ID"]]["Match_MasterID"]	=	$rows["Match_MasterID"];
    $arr[$rows["Match_ID"]]["Match_GuestID"]	=	$rows["Match_GuestID"];
    $arr_m[$rows["Match_ID"]]					=	0;
    if($i >= $start && $i <= $end){
        $mid .= $rows["Match_ID"].',';
    }
    if($i > $end) break;
    $i++;
}
if($mid){
    $mid	=	rtrim($mid,",");
    $sql	=	"select match_id,point_column,bet_money from `k_bet` where match_type=1 and match_id in($mid) and `status`!=3 and `site_id`='".SITEID."'";
    $query	=	$mysqlt->query($sql);
    while($rows = $query->fetch_array()){
        if(strrpos($rows["point_column"],"match_total") === 0){
            $arr[$rows["match_id"]]['zdf']['num']					=	$arr[$rows["match_id"]]['zdf']['num']+1;
            $arr[$rows["match_id"]]['zdf']['money']					=	$arr[$rows["match_id"]]['zdf']['money']+$rows["bet_money"];
        }else{
            $arr[$rows["match_id"]][$rows["point_column"]]['num']	=	$arr[$rows["match_id"]][$rows["point_column"]]['num']+1;
            $arr[$rows["match_id"]][$rows["point_column"]]['money']	=	$arr[$rows["match_id"]][$rows["point_column"]]['money']+$rows["bet_money"];
        }
        $arr_m[$rows["match_id"]]									+=	$rows["bet_money"];
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
    <table width="100%" height="0" cellpadding="0" cellspacing="0" class="leg_type">
        <tr>
            <td width="100">
                <div  class="input_002">  <?
                    if($_GET['match_type']==1) $mach_type_name='';
                    else  $mach_type_name='早餐';
                    if($_GET['MacthType'] ){
                        $macth_name_=$_GET['MacthType'];
                        echo      $macth_name= $_GET['MacthType'].'-總得分';
                    }
                    else{
                        $macth_name_="棒球$mach_type_name";
                        echo $macth_name="棒球$mach_type_name-總得分";
                    }
                    ?></div>
            </td>
            <td width="250">
                &nbsp; &nbsp;赛事选择：
                <select id="select" name="table" onChange="gopage(this.value);" class="za_select">
                    <?='<option value="'.$_SERVER['PHP_SELF'].'?match_type='.$_GET['match_type'].'&MacthType='.$_GET['MacthType'].'">'.$macth_name_.'</option> ';  ?>
                    <option value="ft_danshi.php?match_type=1"  >足球</option>
                    <option value="ft_danshi.php?match_type=0"  >足球早餐</option>
                    <option value="bk_danshi.php?match_type=1">篮球</option>
                    <option value="bk_danshi.php?match_type=0">篮球早餐</option>
                    <option value="tennis_danshi.php?match_type=1">网球</option>
                    <option value="tennis_danshi.php?match_type=0">网球早餐</option>
                    <option value="volleyball_danshi.php?match_type=1">排球</option>
                    <option value="volleyball_danshi.php?match_type=0">排球早餐</option>
                    <option value="baseball_danshi.php?match_type=1">棒球</option>
                    <option value="baseball_danshi.php?match_type=0">棒球早餐</option>
                    <option value="guanjun.php?match_type=1" >冠軍</option>
                </select>
            </td>
            <td width="300">
                頁數：
                <select id="page" name="page" class="za_select" onchange="gopage(this.value);">
                    <?php
                    if(isset($_GET["match_name"])) $match_name_url="match_name=".urlencode(@$v)."&";
                    else $match_name_url='';
                    for($i=1;$i<=$totalPage;$i++){
                        $pageurl=$_SERVER['PHP_SELF']."?{$match_name_url}match_type=$match_type&page=$i";
                        if($i==$CurrentPage){
                            echo  "<option   value='$pageurl' selected> $i </option>";
                        }else{
                            echo  "<option value='$pageurl'>$i</option>";
                        }
                    }
                    if($totalPage==0){
                        $pageurl=$_SERVER['PHP_SELF']."?{$match_name_url}match_type=$match_type&page=$i";
                        echo  "<option   value='$pageurl' selected> $i </option>";
                    }

                    ?>
                </select> <?php echo  $totalPage ;?> 頁&nbsp;
                <script>
                    var i=<?=intval($_GET['time'])?>;
                    var cleartime=true;
                    if(i==''){
                        var i=0;
                    }
                    $(document).ready(function(){
                        if(i!=0){
                            setInterval("timeout(i)",1000);
                        }
                    });
                    function timeout(time){
                        i = time;
                        var reload=i;
                        clearInterval(cleartime);
                        if(i!=-1){

                            cleartime =	setInterval("refresh()",1000);
                        }

                    }
                    function refresh(){
                        //alert(i)
                        if(i <=0){
                            var reload=$("#retime").val();
                            var jump_url=$("#page").val()+'&time='+reload;
                            window.location.href=jump_url;//调转
                        }else{
                            $('#time').html(i);
                            i--;
                        }
                    }
                </script>
                重新整理:
                <select  id="retime"  name="retime"   class="za_select"   onchange="timeout(this.value);">

                    <option  value="-1"  selected="">不更新</option>
                    <option  value="180" <?if($_GET['time']){?> selected="selected"<?}?>>180 sec</option>
                </select>&nbsp;&nbsp;<span id="time"></span>
            </td>
            <td width="370" align="left">&nbsp;&nbsp;选择联盟&nbsp;
                <select id="set_account" name="match_name" onChange="gopage(this.value);" class="za_select">
                    <option value="<?=$_SERVER['PHP_SELF']?>?match_type=<?=$match_type?>&amp;date=<?=$page_date?>">==选择联盟==</option>
                    <?php
                    foreach ($match_name as $k=>$v){?>
                        <option <? if(urldecode($_GET["match_name"])==$v){?> selected="selected" <? }?> value="<?=$_SERVER['PHP_SELF']?>?match_name=<?=urlencode($v)?>&amp;match_type=<?=$match_type?>&amp;date=<?=$page_date?>"><?=$v?></option>
                    <?}?>
                </select>	</td>
            <td width="179"><A href="baseball_danshi.php?match_type=<?=$match_type?>">單式</A>&nbsp;&nbsp;<a href="baseball_zdf.php?match_type=<?=$match_type?>">总得分</a></td>
        </tr>
    </table>
    <table width="785" border="0" cellpadding="0" cellspacing="1"  bgcolor="006255" class="m_tab" id="glist_table">
        <tr class="m_title">
            <td width="60">時間</td>
            <td nowrap="nowrap" width="160">聯盟</td>
            <td width="60">場次</td>
            <td width="300">隊伍</td>
            <td width="119"><span style="width:121px;">獨贏</span></td>
            <td width="79">总得分</td>
        </tr>
        <?php
        foreach($arr_m as $k=>$v){
            $rows	=	$arr[$k];
            $color	=	getColor($v);
            ?>
            <tr bgcolor="<?=$color?>" onMouseOver="this.style.backgroundColor='#EBEBEB'" onMouseOut="this.style.backgroundColor='<?=$color?>'">
                <td align="center"><?=$rows["Match_Date"]?><br>
                    <?=$rows["Match_Time"]?><br><? if($rows["Match_IsLose"]==1){?>  <span style="color:#FF0000">滾球</span><? } ?></td>
                <td><?=$rows["Match_ID"]?><br /><?=$rows["Match_Name"]?></td>
                <td><?=$rows["Match_MasterID"]?><br>
                    <?=$rows["Match_GuestID"]?></td>
                <td align="left"><?=$rows["Match_Master"]?><br><?=$rows["Match_Guest"]?></td>
                <td valign="middle"><table cellspacing="0" cellpadding="0" width="100%" border="0">
                        <tr align="right">
                            <td align="right" width="32%">
                                <? if($rows["Match_BzM"]>0) echo double_format($rows["Match_BzM"]);?>
                                <br /></td>
                            <td width="68%"><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=Match_BzM" <?=getAC($arr[$rows["Match_ID"]]["match_bzm"]['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_bzm"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_bzm"]['money'])?></a></td>
                        </tr>
                        <tr align="right">
                            <td align="right"><? if($rows["Match_BzG"]>0) echo double_format($rows["Match_BzG"]);?><br /></td>
                            <td><a href="list.php?match_id=<?=$rows["Match_ID"]?>&point_column=Match_BzG" <?=getAC($arr[$rows["Match_ID"]]["match_bzg"]['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_bzg"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_bzg"]['money'])?></a></td>
                        </tr>
                    </table></td>
                <td align="right"><a href="list.php?match_id=<?=$rows["Match_ID"]?>&column_like=Match_Total" <?=getAC($arr[$rows["Match_ID"]]["zdf"]['num'])?> ><?=getString($arr[$rows["Match_ID"]]["zdf"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["zdf"]['money'])?></a></td>
            </tr>
        <? }?>
    </table>
<?php require("../common_html/footer.php");?>