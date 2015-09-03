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
 <?php $title="足球比赛结果"; require("../common_html/header.php");?>
<script language="javascript" src="../../js/jquery.js"></script>
<script language="javascript">
function gopage(url){
	alert(url);
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
    <div class="input_002">足球赛程</div>
    <div class="con_menu">
      <form name="game_result" action="" method="post" id="game_result">
      <a href="football.php" target="_self">足球</a>
      <a href="basketball.php" target="_self">籃球</a>
      <a href="tennis.php" target="_self">網球</a>
      <a href="volleyball.php" target="_self">排球</a>
      <a href="basebal.php" target="_self">棒球</a>
      <a href="champion.php?type=3" target="_self">冠军</a>

	  选择日期 :
	  <input style="width:75px;height:18px;" type="text" id="date_kaijiang" value="<?=$page_date2?>"  onClick="WdatePicker()" size="10" maxlength="10" readonly class="Wdate" />
	  <input class="za_button" type="button" value="搜索" style="line-height:10px;" onclick="javascript:gopages()">
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
	$sql		=	"SELECT ID,Match_ID, Match_Date, Match_Time, Match_Master, Match_Guest,Match_Name,MB_Inball,TG_Inball,MB_Inball_HR,TG_Inball_HR,match_sbjs,Match_Time,Match_CoverDate FROM bet_match where match_js=1 and (match_date='$page_date' or match_date='$page_date2') group by Match_Name order by Match_CoverDate,Match_Name,Match_Master,iPage,iSn desc";
    $query		=	$mysqli->query($sql);
	$arr_bet	=	array();
	while($rows	=	$query->fetch_array()){
        $i++;
		if($rows["match_sbjs"]>0) $bgcolor="#FF9999";
		else $bgcolor="#ffffff";

		$rows["Match_Name"]		=	trim($rows["Match_Name"]);
		$rows["Match_Master"]	=	trim($rows["Match_Master"]);
		$rows["Match_Guest"]	=	trim($rows["Match_Guest"]);

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

		$arr     =	explode('[上半',$rows["Match_Master"]);
		$couarr  =	count($arr);
		if($couarr>1){

		}else{
	 ?>
                <tr>
                  <td colspan="6" class="b_hline">
                  <span id="leg_<?=$i?>" onclick="$('.leg_<?=$i?>').toggle()" class="showleg">
                    <span id="LegOpen"></span>
                  </span>
                  <span class="leg_bar"><?=$rows["Match_ID"]?><?=$rows["Match_Name"]?></span></td>
                </tr>
            <tbody class="leg_<?=$i?>">
                <tr class="b_cen" id="TR_108_1817926" style="display:">
                  <td rowspan="3" class="time"><?=$rows["Match_CoverDate"]?>
                  </td>
                  <td class="team">比賽隊伍</td>
                  <td colspan="2" class="team_out_ft">
                    <table border="0" cellpadding="0" cellspacing="0" class="team_main">
                      <tbody>
                        <tr class="b_cen">
                          <td width="12"></td>
                          <td class="team_c_ft"><?=$rows["Match_Master"]?></td>
                          <td class="vs">vs.</td>
                          <td class="team_h_ft"><?=$rows["Match_Guest"]?></td>
                          <td width="12"></td>
                        </tr>
                      </tbody>
                    </table>
                  </td>
                  <td class="more_td"></td>
                </tr>
                <tr id="TR_1_108_1817926" style="display:" class="hr">
                  <td class="hr_title">半場</td>
                  <td class="hr_main_ft">
                    <span style="overflow:hidden;"> <?=$rows["MB_Inball_HR"]==-1? '赛事无效':$rows["MB_Inball_HR"]?>
     </span>
                  </td>
                  <td class="hr_main_ft">
                    <span style="overflow:hidden;"> <?=$rows["TG_Inball_HR"]==-1? '赛事无效':$rows["TG_Inball_HR"]?></span>
                  </td>
                  <td rowspan="2" class="more_td"></td>
                </tr>
                <tr id="TR_2_108_1817926" style="display:" class="full">
                  <td class="full_title">全場</td>
                  <td class="full_main_ft">
                    <span style="overflow:hidden;"> <?=$rows["MB_Inball"]==-1? '赛事无效':$rows["MB_Inball"]?></span>
                  </td>
                  <td class="full_main_ft">
                    <span style="overflow:hidden;">  <?=$rows["TG_Inball"]==-1? '赛事无效':$rows["TG_Inball"]?></span>
                  </td>
                </tr>
                </tbody>
                    <?php
		}
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
<script>
$()
</script>
</body>
</html>