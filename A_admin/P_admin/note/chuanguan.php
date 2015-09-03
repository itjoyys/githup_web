<?php

ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
//error_reporting(-1);                    //打印出所有的 错误信息
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../class/user.php");
include_once("common.php");
include_once("../../include/public_config.php");
include_once("../../include/public_sport_user.php");
include_once("../common/pager.class.php");


$date			=	date("m-d");
if($_GET['date']){
    $date		=	$_GET['date'];
}
$page_date		=	$date;
isset($_GET['match_type'])?$match_type=intval($_GET["match_type"]):$match_type=1;

if(isset($_GET["date"])){
    $sqlwhere	=	" where bet_time like('%$page_date%')";
}
$arr			=	array();
$sql			=	"select bet_money from `k_bet_cg_group` ".$sqlwhere;
$query			=	$mysqlit->query($sql);
while($rows		=	$query->fetch_array()){
    $arr['num']		=	$arr['num']+1;
    $arr['money']	=	$arr['money']+$rows["bet_money"];
}
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

<body bgcolor="#FFFFFF" text="#000000" leftmargin="0" topmargin="0" vlink="#0000FF" alink="#0000FF" class="leg_type" >
<table width="100%" height="0" cellpadding="0" cellspacing="0" class="leg_type">
    <tr style="width:150px;">
        <td width="150">
            <div  class="input_002"><a href="ft_danshi.php?match_type=1"><span style="<? if($match_type==1) echo 'color:#ff6600';?>">即時注單</span> </a> /
                <a href="ft_danshi.php?match_type=0"><span style="<? if($match_type==0) echo 'color:#ff6600';?>">早餐</span></a> 管理</div>
        </td>
        <td width="119">类型：
            <select id="select" name="table" onChange="gopage(this.value);" class="za_select">
                <option value="ft_danshi.php?match_type=<?=$match_type?>" >足球</option>
                <option value="bk_danshi.php?match_type=<?=$match_type?>">篮球</option>
                <option value="tennis_danshi.php?match_type=<?=$match_type?>">网球</option>
                <option value="volleyball_danshi.php?match_type=<?=$match_type?>">排球</option>
                <option value="baseball_danshi.php?match_type=<?=$match_type?>" >棒球</option>
                <option value="guanjun.php?match_type=<?=$match_type?>">冠軍</option>
                <option value="jinrong.php?match_type=<?=$match_type?>" >金融</option>
                <option value="chuanguan.php?date=<?=date("m-d")?>"  selected>串关</option>
            </select></td>
        <td width="250">美东时间：
            <select id="DropDownList1" onChange="javascript:gopage(this.value)" name="DropDownList1">
                <option value="<?=$_SERVER['PHP_SELF']?>?match_type=1">查看所有串关</option>
                <? for ($i=0;$i<=6;$i++){
                    $s=strtotime("-$i day");
                    $date=date("m-d",$s);
                    ?>
                    <option value="<?=$_SERVER['PHP_SELF']?>?match_type=1&date=<?=$date?>" <?=@$page_date==$date ? "selected" : "" ?>>
                        <?=$date?>
                    </option>
                <?}?>
            </select>&nbsp;&nbsp;<a href="javascript:re_load();">刷新</a></td>
    </tr>
</table>
<table width="302" border="0" cellpadding="0" cellspacing="1"  bgcolor="006255" class="m_tab" id="glist_table">
    <tr class="m_title_ft">
        <td width="300" height="24"><strong>結果</strong></td>
    </tr>
    <?php
    $color	=	getColor($arr['money']);
    ?>
    <tr bgcolor="<?=$color?>" onMouseOver="this.style.backgroundColor='#EBEBEB'" onMouseOut="this.style.backgroundColor='<?=$color?>'">
        <td height="24" align="center"><a href="look_cg.php?date=<?=$page_date?>" <?=getAC($arr['num'])?> ><?=getString($arr['num'])?>/<?=getString($arr['money'])?>
            </a></td>
    </tr>
</table>

<?php require("../common_html/footer.php");?>