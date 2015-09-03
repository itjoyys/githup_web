<?php

ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(1);                    //打印出所有的 错误信息
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/user.php");
include_once("../../include/public_config.php");//$mysqli
include_once("common.php");

include_once("../common/pager.class.php");


$date			=	date("m-d");
if($_GET['date']){
    $date		=	$_GET['date'];
}
$page_date		=	$date;
$sql			=	"SELECT Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest,Match_MasterID, Match_GuestID,Match_Name FROM tennis_match";

isset($_GET['match_type'])?$match_type=intval($_GET["match_type"]):$match_type=1;
$sqlwhere		=	" where match_date='".date("m-d")."' and Match_Bd21>0 ";

if($match_type==1){   //单式
    $sqlwhere		=	" WHERE Match_Type=1 AND Match_Date='$date' and Match_Bd21>0 ";
}else if($match_type==0){ //早餐
    $sqlwhere		=	" WHERE Match_Type=0 AND Match_CoverDate>now() AND Match_Date<>'$date' and Match_Bd21>0 ";
    if(isset($_GET["date"])){
        $page_date	=	$_GET["date"];
        // $sqlwhere	=	" where Match_Type=0 AND Match_Date='$page_date'";
    }
}

$sql			.=	$sqlwhere;
$match_name		=	getmatch_name('tennis_match',$sqlwhere);
if(isset($_GET["match_name"])) $sql.="  and match_name='".urldecode($_GET["match_name"])."'";
$sql			.=	" order by Match_CoverDate";

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
    $arr[$rows["Match_ID"]]["Match_Name"]		=	$rows["Match_Name"];
    $arr[$rows["Match_ID"]]["Match_MasterID"]	=	$rows["Match_MasterID"];
    $arr[$rows["Match_ID"]]["Match_GuestID"]	=	$rows["Match_GuestID"];
    $arr_m[$rows["Match_ID"]]					=	0;
    $mid.=$rows["Match_ID"].',';
}
if($mid){
    $mid	=	rtrim($mid,",");
    $sql	=	"select match_id,bet_money,bet_info from `k_bet` where match_type<2 and match_id in($mid) and `status`!=3 and `site_id`='".SITEID."'";
    $query	=	$mysqlt->query($sql);
    while($rows = $query->fetch_array()){
        if(strrpos($rows["bet_info"],"波胆") === 0){
            $arr[$rows["match_id"]]["match_bd"]['num']	=	$arr[$rows["match_id"]]["match_bd"]['num']+1;
            $arr[$rows["match_id"]]["match_bd"]['money']=	$arr[$rows["match_id"]]["match_bd"]['money']+$rows["bet_money"];
            $arr_m[$rows["match_id"]]					+=	$rows["bet_money"];
        }
    }
}
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
        <tr style="width:150px;">
            <td width="100">
                <div  class="input_002">  <?
                    if($_GET['match_type']==1) $mach_type_name='';
                    else  $mach_type_name='早餐';
                    if($_GET['MacthType'] ){
                        $macth_name_=$_GET['MacthType'];
                        echo      $macth_name= $_GET['MacthType'].'-單式';
                    }
                    else{
                        $macth_name_="网球$mach_type_name";
                        echo $macth_name="网球$mach_type_name-單式";
                    }
                    ?></div>
            </td>
            <td width="150">
                &nbsp; &nbsp;赛事选择：
                <select id="select" name="table" onChange="gopage(this.value);" class="za_select">
                    <?='<option value="'.$_SERVER['PHP_SELF'].'?match_type='.$_GET['match_type'].'&MacthType='.$_GET['MacthType'].'">'.$macth_name_.'</option> ';  ?>

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
            <td  width="300">頁數：
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
                        cleartime =	setInterval("refresh()",1000);
                    }
                    function refresh(){
                        //alert(i)
                        if(i <=0){
                            var reload=$("#reload").val();
                            var jump_url=$("#page").val()+'?time='+reload;
                            window.location.href=jump_url;//调转
                        }else{
                            $('#time').html(i);
                            i--;
                        }
                    }
                </script>
                重新整理:
                <select  id="retime"  name="retime"   class="za_select"  onchange="timeout();">

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
            <td width="179"><A href="tennis_danshi.php?match_type=<?=$match_type?>">單式</A>&nbsp;&nbsp;<a href="tennis_bodan.php?match_type=<?=$match_type?>">波胆</a></td>
        </tr>
    </table>
    <table width="526" border="0" cellpadding="0" cellspacing="1"  bgcolor="006255" class="m_tab" id="glist_table">
        <tr class="m_title_ft">
            <td width="60" height="24"><strong>時間</strong></td>
            <td nowrap="nowrap" width="160"><strong>聯盟</strong></td>
            <td width="60"><strong>場次</strong></td>
            <td width="160"><strong>隊伍</strong></td>
            <td width="80"><strong>波胆</strong></td>
        </tr>
        <?php
        foreach($arr_m as $k=>$v){
            $rows	=	$arr[$k];
            $color	=	getColor($v);
            ?>
            <tr bgcolor="<?=$color?>" onMouseOver="this.style.backgroundColor='#EBEBEB'" onMouseOut="this.style.backgroundColor='<?=$color?>'">
                <td align="center"><?=$rows["Match_Date"]?><br><?=$rows["Match_Time"]?><br><? if($rows["Match_IsLose"]==1){?>  <span style="color:#FF0000">滾球</span><? } ?></td>
                <td><?=$rows["Match_ID"]?><br /><?=$rows["Match_Name"]?></td>
                <td><?=$rows["Match_MasterID"]?><br><?=$rows["Match_GuestID"]?></td>
                <td align="left"><?=$rows["Match_Master"]?><br><?=$rows["Match_Guest"]?></td>
                <td align="right"><a href="list.php?match_id=<?=$rows["Match_ID"]?>&column_like=Match_Bd" <?=getAC($arr[$rows["Match_ID"]]["match_bd"]['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_bd"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_bd"]['money'])?></a></td>
            </tr>
        <? }?>
    </table>
<?php require("../common_html/footer.php");?>