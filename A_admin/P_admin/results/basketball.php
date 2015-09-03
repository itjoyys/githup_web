<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include_once("../../include/public_config.php");
$page_date	=	date("m-d");
$page_date2	=	date("Y-m-d");

if(isset($_GET["date"])){
	$page_date	=	$_GET["date"];
	$page_date2	=	date("Y-").$_GET["date"];
}
if(isset($_GET["dates"])){
	$page_date	=	substr($_GET["dates"],5);
	$page_date2	=	$_GET["dates"];
}
?>
 <?php $title="篮球比赛结果"; require("../common_html/header.php");?>
<script language="javascript" src="../../js/jquery.js"></script>
<script language="javascript">
function gopage(url){
	location.href = url;
}
function gopages(){
	var date=$("#date_kaijiang").val();
	var url="<?=$_SERVER['PHP_SELF']?>?dates="+date;
	location.href = url;
}
function check(){
    var len = document.form1.elements.length;
	var num = false;
    for(var i=0;i<len;i++){
		var e = document.form1.elements[i];
        if(e.checked && e.name=='mid[]'){
			num = true;
			break;
		}
    }
	if(num){
		var action = $("#s_action").val();
		if(action=="0"){
			alert("请您选择要执行的相关操作！");
			return false;
		}else{
			if(action=="2") document.form1.action="ft_list.php?type=bet_match&php=zqbf";
			if(action=="1") document.form1.action="ft_shangbanchang_list.php";
			if(action=="3") document.form1.action="ft_shangbanchang_list_re.php";
			if(action=="4") document.form1.action="ft_nullity.php?type=bet_match&php=zqbf";
		}
	}else{
        alert("您未选中任何复选框");
        return false;
    }
}

function ckall(){
    for (var i=0;i<document.form1.elements.length;i++){
	    var e = document.form1.elements[i];
		if (e.name != 'checkall') e.checked = document.form1.checkall.checked;
	}
}

function zqlrbf(zqmid,fid){
	var md = "<?=$page_date?>";
	var a = "mi"+zqmid;
	var b = "ti"+zqmid;
	var c = "mih"+zqmid;
	var d = "tih"+zqmid;
	var m = $("#"+a).val();
	var t = $("#"+b).val();
	var l = $("#"+c).val();
	var n = $("#"+d).val();
	
	if(l.length>0 && n.length>0){
		if(m.length>0 && t.length>0){
			$.post(
				"zqlr.php",
				{r:Math.random(),value:m+"|||"+t+"|||"+md+"|||"+zqmid+"|||qc"},
				function(date){
					 if(date==3){
						alert("系统没有查找到您要结算的赛事！")
					}else if(date==1){
						ftbf(m,t,1,fid);
					}
				}
			);
		}
	}else{
		alert("请输入上半场比分！");
		$("#"+a).val("");
		$("#"+b).val("");
	}
}

function zqlrbf_sb(zqmid,fid){
	var md = "<?=$page_date?>";
	var c = "mih"+zqmid;
	var d = "tih"+zqmid;
	var l = $("#"+c).val();
	var n = $("#"+d).val();
	//alert(c+'-'+d+'-'+l+'-'+n);
	if(l.length>0 && n.length>0){
		$.post(
			"zqlr.php",
			{r:Math.random(),value:l+"|||"+n+"|||"+md+"|||"+zqmid+"|||sb"},
			function(date){
				if(date==3){
					alert("系统没有查找到您要结算的赛事！")
				}else if(date==2){
					ftbf(l,n,2,fid);
				}
			}
		);
	}
}

function ftbf(m,t,p,d){
	if(p == 1){     //////全场
		var mid = document.getElementsByName("mi"+d)
		var tid = document.getElementsByName("ti"+d)
	}else{
		var mid = document.getElementsByName("mh"+d)
		var tid = document.getElementsByName("th"+d)
	}
	for(var i=0; i<mid.length; i++){
		mid[i].value = m;
		tid[i].value = t;
	}
}
</script>
</head>

  <link rel="stylesheet" type="text/css" href="../public/css/mem_body_result.css">
   <link rel="stylesheet" type="text/css" href="../public/css/mem_body_ft.css">
  <div id="con_wrap">
    <div class="input_002">篮球赛程</div>
    <div class="con_menu">
      <form name="game_result" action="" method="post" id="game_result">
    <a href="football.php" target="_self">足球</a> 
      <a href="basketball.php" target="_self">籃球</a> 
      <a href="tennis.php" target="_self">網球</a> 
      <a href="volleyball.php" target="_self">排球</a> 
      <a href="basebal.php" target="_self">棒球</a> 
      <a href="champion.php" target="_self">冠军</a> 
	  选择日期 : 
	  <input style="width:75px;height:18px;" type="text" id="date_kaijiang" value="<?=$page_date2?>"  onClick="WdatePicker()" size="10" maxlength="10" readonly class="Wdate" />
	  <input type="button" class="za_button" value="搜索" style="line-height:10px;" onclick="javascript:gopages()">
	  
	  
      <span id="pg_txt"></span></form>
    </div>
  </div>
  <div class="content" id="MRSU">
    <table border="0" cellpadding="0" cellspacing="0" id="box">
      <tbody>
        <tr>
          <td>
            <table border="0" cellspacing="0" cellpadding="0" class="game">
              <tbody>
                <tr>
                  <th class="time">时间</th>
                  <th class="rsu">赛果</th>
                </tr>
              </tbody>
            </table>
            <table border="0" cellspacing="0" cellpadding="0" class="game">
              <tbody>
 	<?php
	$sql		=	"select   Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest,Match_MasterID,Match_GuestID,Match_Name,MB_Inball_1st,TG_Inball_1st,MB_Inball_2st,TG_Inball_2st,MB_Inball_3st,TG_Inball_3st,MB_Inball_4st,TG_Inball_4st,MB_Inball_HR,	TG_Inball_HR,MB_Inball_ER,TG_Inball_ER,MB_Inball,TG_Inball,MB_Inball_Add,TG_Inball_Add ,MB_Inball_OK,TG_Inball_OK,match_js from  lq_match where  match_js=1 and (match_Date='$page_date' or match_date='$page_date2') order by  Match_CoverDate,match_name,Match_Master,iPage,iSn desc";//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	$arr_bet	=	array();
	while($rows	=	$query->fetch_array()){
        $i++;
		$ftid	=	$rows["Match_ID"];
		$bool	=	true;
		foreach($arr_bet as $k=>$arr){
			if(in_array(array($rows['Match_Name'],$rows['Match_Master'],$rows['Match_Guest']),$arr)){
				$ftid	=	$arr['Match_ID'];
				$bool	=	false;
				break;
			}
		}
		if($bool){
			array_unshift($arr_bet,array(array($rows['Match_Name'],$rows['Match_Master'],$rows['Match_Guest']),'Match_ID'=>$rows['Match_ID']));
		}
	?>
                    <tr>
                  <td colspan="6" class="b_hline">
                       <span id="leg_<?=$i?>" onclick="$('.leg_<?=$i?>').toggle()" class="showleg">
                      <span id="LegOpen"></span>
                    </span>
                    <span class="leg_bar"><?=$rows["Match_ID"]?><?=$rows["Match_Name"]?></span>
                  </td>
                </tr>
                <tbody class="leg_<?=$i?>">
                <tr class="b_cen" id="TR_100_1453860" style="display:">
                  <td rowspan="9" class="time"><?=$rows["Match_Time"]?></td>
                  <td class="team">比賽隊伍</td>
                  <td colspan="2" class="team_out">
                    <table border="0" cellpadding="0" cellspacing="0" class="team_main">
                      <tbody>
                        <tr class="b_cen">
                          <td width="12"></td>
                          <td class="team_c"><?=$rows["Match_Master"]?></td>
                          <td class="vs">vs.</td>
                          <td class="team_h"><?=$rows["Match_Guest"]?></td>
                          <td width="12"></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                </tr>
                <tr class="b_cen" id="TR_1_100_1453860" style="display:">
                  <td>第1節</td>
                  <td>
                    <strong><?=$rows["MB_Inball_1st"]?></strong>
                  </td>
                  <td>
                    <strong><?=$rows["TG_Inball_1st"]?></strong>
                  </td>
                </tr>
                <tr class="b_cen" id="TR_2_100_1453860" style="display:">
                  <td>第2節</td>
                  <td>
                    <strong><?=$rows["MB_Inball_2st"]?></strong>
                  </td>
                  <td>
                    <strong><?=$rows["TG_Inball_2st"]?></strong>
                  </td>
                </tr>
                <tr class="b_cen" id="TR_3_100_1453860" style="display:">
                  <td>第3節</td>
                  <td>
                    <strong><?=$rows["MB_Inball_3st"]?></strong>
                  </td>
                  <td>
                    <strong><?=$rows["TG_Inball_3st"]?></strong>
                  </td>
                </tr>
                <tr class="b_cen" id="TR_4_100_1453860" style="display:">
                  <td>第4節</td>
                  <td>
                    <strong><?=$rows["MB_Inball_4st"]?></strong>
                  </td>
                  <td>
                    <strong><?=$rows["TG_Inball_4st"]?></strong>
                  </td>
                </tr>
                <tr id="TR_5_100_1453860" style="display:" class="hr">
                  <td class="hr_title">上半</td>
                  <td class="hr_main"><?=$rows["MB_Inball_HR"]?></td>
                  <td class="hr_main"><?=$rows["TG_Inball_HR"]?></td>
                </tr>
                <tr id="TR_6_100_1453860" style="display:" class="hr">
                  <td class="hr_title">下半</td>
                  <td class="hr_main"><?=$rows["MB_Inball_ER"]?></td>
                  <td class="hr_main"><?=$rows["TG_Inball_ER"]?></td>
                </tr>
                <tr class="b_cen" id="TR_7_100_1453860" style="display:">
                  <td>加時</td>
                  <td><? if ($rows["MB_Inball_Add"]>0) echo $rows["MB_Inball_Add"]; ?></td>
                  <td><? if ($rows["TG_Inball_Add"]>0) echo $rows["TG_Inball_Add"];?></td>
                </tr>
                <tr id="TR_8_100_1453860" style="display:" class="full">
                  <td class="full_title">全場</td>
                  <td class="full_main"><?=$rows["MB_Inball"]?></td>
                  <td class="full_main"><?=$rows["TG_Inball"]?></td>
                </tr>
                </tbody>
                    <?php 
	}
	unset($arr_bet);
	?>
              </tbody>
            </table>
 
          </td>
        </tr>
      </tbody>
    </table>
  </div>
<!-- 公共尾部 -->
<?php require("../common_html/footer.php"); ?>