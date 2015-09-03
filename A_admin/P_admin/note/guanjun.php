<?php

ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(1);                    //打印出所有的 错误信息
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/user.php");
include_once("../../include/public_config.php");//$mysqli
include_once("common.php");


$date			=	date("m-d");
if($_GET['date']){
    $date		=	$_GET['date'];
}
$page_date		=	$date;
$sql			=	"SELECT Match_ID, Match_Date, Match_Time, x_title, Match_Name FROM t_guanjun";

isset($_GET['match_type'])?$match_type=intval($_GET["match_type"]):$match_type=1;
$sqlwhere		=	" where match_type=1 and match_coverdate>now()";
if(isset($_GET["date"])){
    $sqlwhere=" where match_type=1 and match_date='$page_date'";
}
$sql			.=	$sqlwhere;
$match_name		=	getmatch_name('t_guanjun',$sqlwhere,1);
if(isset($_GET["match_name"])) $sql.="  and x_title='".urldecode($_GET["match_name"])."'";
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
    $arr[$rows["Match_ID"]]["Match_ID"]		=	$rows["Match_ID"]; //赛事id
    $arr[$rows["Match_ID"]]["Match_Date"]	=	$rows["Match_Date"];
    $arr[$rows["Match_ID"]]["Match_Time"]	=	$rows["Match_Time"];
    $arr[$rows["Match_ID"]]["Match_Name"]	=	$rows["Match_Name"];
    $arr[$rows["Match_ID"]]["x_title"]		=	$rows["x_title"];
    $arr_m[$rows["Match_ID"]]				=	0;
    if($i >= $start && $i <= $end){
        $mid .= $rows["Match_ID"].',';
    }
    if($i > $end) break;
    $i++;
}
if($mid){
    $mid	=	rtrim($mid,",");
    $sql	=	"select match_id,bet_money,point_column from `k_bet` where match_type<2 and match_id in($mid) and `status`!=3 and `site_id`='".SITEID."'";
    $query	=	$mysqlt->query($sql);
    while($rows = $query->fetch_array()){
        if(strrpos($rows["point_column"],"match_gj") === 0){
            $arr[$rows["match_id"]]["match_gj"]['num']		=	$arr[$rows["match_id"]]["match_gj"]['num']+1;
            $arr[$rows["match_id"]]["match_gj"]['money']	=	$arr[$rows["match_id"]]["match_gj"]['money']+$rows["bet_money"];
            $arr_m[$rows["match_id"]]						+=	$rows["bet_money"];
        }
    }
}

arsort($arr_m);
 require("../common_html/header.php");?>
    <style>
        .leg_type a:link,.leg_type a:visited{color: #000004; margin-left: 5px; border-bottom: #000000 solid 1px;}
    </style>
       
        <script language="javascript">
            function gopage(url){
                location.href=url;
            }
            function re_load(){
                location.reload();
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
                <option value="ft_danshi.php?match_type=<?=$match_type?>" >足球</option>
                <option value="bk_danshi.php?match_type=<?=$match_type?>">篮球</option>
                <option value="tennis_danshi.php?match_type=<?=$match_type?>">网球</option>
                <option value="volleyball_danshi.php?match_type=<?=$match_type?>">排球</option>
                <option value="baseball_danshi.php?match_type=<?=$match_type?>" >棒球</option>
                <option value="guanjun.php?match_type=<?=$match_type?>" selected>冠軍</option>
            </select></td>
        <td>
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



                ?>
            </select> <?php echo  $totalPage ;?> 頁&nbsp;
            <script>
                var times=180;
                //刷新
                function shuaxin(){
                    var time=$("#retime").val();//alert(time);

                    if(time=='180'){
                        times=times-1;
                        if(times<1){

                            var url=$("#page").val();
                            location.href=url+'&time=180';
                        }else{
                            $("#time").html(times);
                        }
                        setTimeout("shuaxin()",1000);
                    }
                }
                $(document).ready(function(){

                    var time='<?=$_GET['time']?>';
                    if(time){

                        setTimeout("shuaxin()",1000);
                    }
                });
            </script>
            重新整理:
            <select  id="retime"  name="retime"   class="za_select"  onchange="shuaxin();">

                <option  value="-1"  selected="">不更新</option>
                <option  value="180" <?if($_GET['time']){?> selected="selected"<?}?>>180 sec</option>
            </select>&nbsp;&nbsp;<span id="time"></span>
        </td>
    </tr>
</table>
<table width="745" border="0" cellpadding="0" cellspacing="1"  bgcolor="006255" class="m_tab" id="glist_table">
    <tr class="m_title">
        <td width="60"><strong>時間</strong></td>
        <td nowrap="nowrap" width="300"><strong>聯盟</strong></td>
        <td width="300"><strong>隊伍</strong></td>
        <td width="80"><strong>賠率</strong></td>
    </tr>
    <?php
    foreach($arr_m as $k=>$v){
        $rows	=	$arr[$k];
        $color	=	getColor($v);
        ?>
        <tr bgcolor="<?=$color?>" onMouseOver="this.style.backgroundColor='#EBEBEB'" onMouseOut="this.style.backgroundColor='<?=$color?>'">
            <td align="center"><?=$rows["Match_Date"]?><br><?=$rows["Match_Time"]?></td>
            <td><?=$rows["Match_ID"]?><br /><?=$rows["x_title"]?></td>
            <td align="left" valign="middle"><?=$rows["Match_Name"]?></td>
            <td align="right"><a href="list.php?match_id=<?=$rows["Match_ID"]?>&column_like=match_gj" <?=getAC($arr[$rows["Match_ID"]]["match_gj"]['num'])?> ><?=getString($arr[$rows["Match_ID"]]["match_gj"]['num'])?>/<?=getString($arr[$rows["Match_ID"]]["match_gj"]['money'])?></a></td>
        </tr>
    <? }?>
</table>
<?php require("../common_html/footer.php");?>