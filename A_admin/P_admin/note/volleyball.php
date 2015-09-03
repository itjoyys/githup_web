<?php
include_once("../../include/config.php");
include_once("../common/login_check.php"); 

include_once("../../class/user.php");
include_once("../../include/public_config.php");
if($_GET['type']){
	$type=$_GET['type'];
}else{
	$type='danshi';
}
$date		=	date("Y-m-d",time());

if($_GET['date']){
	$date	=	$_GET['date'];	
}
$sqlwhere='';
if(@$_GET["lsm"]<>""){
	$leaguename	 =	explode("$",urldecode($_GET["lsm"]));
	$v			 =	(count($leaguename)>1 ? 'and (' : 'and' );
	$sqlwhere	.=	" $v match_name='".$leaguename[0]."'";
	for($is=1; $is<count($leaguename)-1; $is++){
		$sqlwhere.=	" or match_name='".$leaguename[$is]."'";
	}
	$sqlwhere	.=	(count($leaguename)>1 ? ')' : '' );
}

//单式
if($type=='danshi'){
	$match_name=array();
	$sqlname		=	"select match_name from volleyball_match WHERE Match_Type=1 AND Match_Date='".date("m-d",strtotime($date))."' order by Match_CoverDate,match_name";
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}

	$sql		=	"select id from volleyball_match WHERE Match_Type=1 AND Match_Date='".date("m-d",strtotime($date))."' ".$sqlwhere.' order by Match_CoverDate,match_name';
	//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
	$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;

 	$totalPage=ceil($sum/$pagenum); //计算出总页数
	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}
	
	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"select Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG from volleyball_match where id in($id) order by Match_CoverDate,match_name";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		while($rows = $query->fetch_array()){ 
			$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];
			$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];
			$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];
			$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];
			$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
			if ($rows["Match_IsLose"]==1){
				$mdate.= "<br><font color=red>滾球</font>";
			}
			$json["db"][$i]["Match_Date"]		=	$mdate;
			$json["db"][$i]["Match_Ho"]			=	$rows["Match_Ho"];
			$json["db"][$i]["Match_DxDpl"]		=	$rows["Match_DxDpl"];
			$json["db"][$i]["Match_DsDpl"]		=	$rows["Match_DsDpl"];
			$json["db"][$i]["Match_Ao"]			=	$rows["Match_Ao"];
			$json["db"][$i]["Match_DxXpl"]		=	$rows["Match_DxXpl"];
			$json["db"][$i]["Match_DsSpl"]		=	$rows["Match_DsSpl"];
			$json["db"][$i]["Match_RGG"]		=	$rows["Match_RGG"];
			$json["db"][$i]["Match_DxGG1"]		=	"O".$rows["Match_DxGG"];
			$json["db"][$i]["Match_ShowType"]	=	$rows["Match_ShowType"];
			$json["db"][$i]["Match_DxGG2"]		=	"U".$rows["Match_DxGG"];
			$json["db"][$i]["Match_BzM"]		=	$rows["Match_BzM"];
			$json["db"][$i]["Match_BzG"]		=	$rows["Match_BzG"];
			$json["db"][$i]["Match_RGG2"]		=	substr($rows["Match_RGG"],0,1);
			$json["db"][$i]["Match_RGG3"]		=	$rows["Match_RGG"];
			$json["db"][$i]["Match_MasterID"]		=	$rows["Match_MasterID"];  
			$json["db"][$i]["Match_GuestID"]		=	$rows["Match_GuestID"];
			$i++;
		}//print_r($json["db"]);exit;
	}
}

//kaisai
if($type=='kaisai'){
	$match_name=array();
	$sqlname		="select match_name from volleyball_match WHERE Match_Type=1 AND Match_Date='".date("m-d",strtotime($date))."' and Match_CoverDate<'".date("Y-m-d H:i:s")."' order by Match_CoverDate,match_name";
	$query		=	$mysqli->query($sqlname);
	while($row = $query->fetch_array()){
		if(!in_array($row['match_name'],$match_name)){
			$match_name[]=$row['match_name'];
		}
	}

	$sql		=	"select id from volleyball_match WHERE Match_Type=1 AND Match_Date='".date("m-d",strtotime($date))."' and Match_CoverDate<'".date("Y-m-d H:i:s")."' ".$sqlwhere.' order by Match_CoverDate,match_name';
	//print_r($sql);exit;
	$query		=	$mysqli->query($sql);
	//分页
	$sum    = $mysqli->affected_rows;//总页数
$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
	$CurrentPage=isset($_GET['page'])?$_GET['page']:1;

 	$totalPage=ceil($sum/$pagenum); //计算出总页数
	$id    = '';
	$i      = 1; //记录 uid 数
	$start  = ($CurrentPage-1)*$pagenum+1;
	$end  = $CurrentPage*$pagenum;
	while($row = $query->fetch_array()){ 
		if($i >= $start && $i <= $end){
		  $id .= $row['id'].',';
		}
		if($i > $end) break;
		  $i++;
	}
	
	if($id){
		$id			=	rtrim($id,',');
		//赛事数据
		$sql	=	"select Match_ID, Match_Master, Match_Guest, Match_Name, Match_Time, Match_Date, Match_IsLose, Match_Ho, Match_DxDpl,  Match_DsDpl, Match_Ao, Match_DxXpl, Match_DsSpl, Match_RGG, Match_DxGG, Match_ShowType, Match_BzM, Match_BzG from volleyball_match where id in($id) order by Match_CoverDate,match_name";
		$query	=	$mysqli->query($sql);
		$i		=	0;
		while($rows = $query->fetch_array()){ 
			$json["db"][$i]["Match_ID"]			=	$rows["Match_ID"];
			$json["db"][$i]["Match_Master"]		=	$rows["Match_Master"];
			$json["db"][$i]["Match_Guest"]		=	$rows["Match_Guest"];
			$json["db"][$i]["Match_Name"]		=	$rows["Match_Name"];
			$mdate	=	$rows["Match_Date"]."<br>".$rows["Match_Time"];
			if ($rows["Match_IsLose"]==1){
				$mdate.= "<br><font color=red>滾球</font>";
			}
			$json["db"][$i]["Match_Date"]		=	$mdate;
			$json["db"][$i]["Match_Ho"]			=	$rows["Match_Ho"];
			$json["db"][$i]["Match_DxDpl"]		=	$rows["Match_DxDpl"];
			$json["db"][$i]["Match_DsDpl"]		=	$rows["Match_DsDpl"];
			$json["db"][$i]["Match_Ao"]			=	$rows["Match_Ao"];
			$json["db"][$i]["Match_DxXpl"]		=	$rows["Match_DxXpl"];
			$json["db"][$i]["Match_DsSpl"]		=	$rows["Match_DsSpl"];
			$json["db"][$i]["Match_RGG"]		=	$rows["Match_RGG"];
			$json["db"][$i]["Match_DxGG1"]		=	"O".$rows["Match_DxGG"];
			$json["db"][$i]["Match_ShowType"]	=	$rows["Match_ShowType"];
			$json["db"][$i]["Match_DxGG2"]		=	"U".$rows["Match_DxGG"];
			$json["db"][$i]["Match_BzM"]		=	$rows["Match_BzM"];
			$json["db"][$i]["Match_BzG"]		=	$rows["Match_BzG"];
			$json["db"][$i]["Match_RGG2"]		=	substr($rows["Match_RGG"],0,1);
			$json["db"][$i]["Match_RGG3"]		=	$rows["Match_RGG"];
			$json["db"][$i]["Match_MasterID"]		=	$rows["Match_MasterID"];  
			$json["db"][$i]["Match_GuestID"]		=	$rows["Match_GuestID"];
			$i++;
		}//print_r($json["db"]);exit;
	}
}

?>

<?php require("../common_html/header.php");?>

<script>
function go(value){//alert(value);
	window.location.href=value;
}
//下拉框选中
$(document).ready(function(){
   $("#lsm").val('<?=$_GET['lsm']?>');
    $("#retime").val('<?=$_GET['time']?>');
	$("#type").val('volleyball.php');
	$("#date").val('<?=$_GET['date']?>');
})
</script>
<?
if($type=='danshi'){
	$type_name='單式';
}elseif($type=='shangbanchang'){
	$type_name='上半場';
}elseif($type=='gunqiu'){
	$type_name='滾球';
}elseif($type=='bodan'){
	$type_name='波膽';
}elseif($type=='shangbanbodan'){
	$type_name='上半波膽';
}elseif($type=='ruqiushu'){
	$type_name='總入球';
}elseif($type=='banquanchang'){
	$type_name='半全場';
}elseif($type=='kaisai'){
	$type_name='已开赛';
}
?>
<div  id="con_wrap">

<div  class="con_menu"  style="min-width:1px;margin-right:15px;">
<div  class="input_002">排球-<?=$type_name?></div>

<div>
&nbsp;赛事选择:
<select  name="here" id="type"  class="za_select" onchange="go($(this).val());" > 
	<option  value="sports_note.php?type=danshi">足球賽事</option> 
	<option  value="ftz.php?type=danshi">足球早餐賽事</option>
	<option  value="bk.php?type=danshi">籃球賽事</option> 
	<option  value="bkz.php">籃球早餐賽事</option> 
	<option  value="tennis.php">網球賽事</option>
	<option  value="volleyball.php">排球賽事</option>
	<option  value="baseball.php">棒球賽事</option> 
	<option  value="guanjun.php">冠軍賽事</option> 
</select>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myform').submit()
    }
  }
</script>
<script>
var times=180;
//刷新
function shuaxin(){
	var time=$("#retime").val();//alert(time);
	if(time=='180'){
		times=times-1;
		if(times<1){
			var type='<?=$type?>';
			var lsm=$("#lsm").val();
			location.href='sports_note.php?type='+type+'&lsm='+lsm+'&time=180';
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
	<option  value="180">180 sec</option>
</select>&nbsp;&nbsp;<span id="time"></span>


</div>
<div  id="dt_now">--美東時間:2014-11-29 04:36:31</div>
 &nbsp;&nbsp;
<a  href="volleyball.php?type=danshi" <?if($_GET['type']=='danshi'){echo 'style="color:#f00;"';}?>>單式</a>
<a  href="volleyball.php?type=kaisai" <?if($_GET['type']=='kaisai'){echo 'style="color:#f00;"';}?>>已开赛</a>
</div>
<div  class="con_menu"  style="min-width:1px;">
<form  name="REFORM" id="myform" action="#"  method="get">
<input type="hidden" name="type" value="<?=$type?>">
<span  id="show_h">選擇聯盟:
	<select  id="lsm"  name="lsm"  onchange="document.getElementById('myform').submit()"  class="za_select">
	<option  value="">全部</option>
	<?
	if(is_array($match_name)){
		foreach($match_name as $v){
	?>
	<option  value="<?=$v?>"><?=$v?></option>
	<?}}?>
	</select>&nbsp;&nbsp;日期：
	<input type="text"  id="date"  name="date" value="" style="width:100px;height:15px;"  onClick="WdatePicker()">
	<input type="submit" value="查询" class="za_button">
</span>
	 每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myform').submit()" class="za_select">
    <option value="20" <?=select_check(20,$pagenum)?>>20条</option>
    <option value="30" <?=select_check(30,$pagenum)?>>30条</option>
    <option value="50" <?=select_check(50,$pagenum)?>>50条</option>
    <option value="100" <?=select_check(100,$pagenum)?>>100条</option>
  </select>
  &nbsp;頁數：
 <select id="page" name="page" class="za_select"> 
  <?php  

    for($i=1;$i<=$totalPage;$i++){
      if($i==$CurrentPage){
        echo  '<option value="'.$i.'" selected>'.$i.'</option>';
      }else{
        echo  '<option value="'.$i.'">'.$i.'</option>';
      }  
    } 
   ?>
  </select> <?php echo  $totalPage ;?> 頁&nbsp;<br>
</form>

</div>
</div>
<div  class="content">
<!--单式-->
<?if($type=='danshi'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="40">時間</td>
			<td  width="50"  nowrap="">聯盟</td>
			<td  width="40">場次</td>
			<td  width="200">隊伍</td>
			<td  width="195">讓球 / 注單</td>
			<td  width="195">大小盤 / 注單</td>
		</tr>
		
	 
	<?for($i=0; $i<count($json['db']); $i++){?>	
		<tr  class="m_cen_top">
		<td><?=$json['db'][$i]["Match_Date"]?></td>
		<td><?=$json['db'][$i]["Match_Name"]?></td>
		<td><?=$json['db'][$i]["Match_MasterID"]?><br><?=$json['db'][$i]["Match_GuestID"]?></td>
		<td  align="left">
			<?=$json['db'][$i]["Match_Master"]?>  <br> <?=$json['db'][$i]["Match_Guest"]?>  <div  align="right"><font  color="#009900">和局</font></div>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
			<tbody>
				<tr  align="right">
					<td  width="52%">
						<?if($json['db'][$i]["Match_ShowType"]=="H"&&$json['db'][$i]["Match_Ho"]!="0"){echo $json['db'][$i]["Match_RGG"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_Ho"]!=null&&$json['db'][$i]["Match_Ho"]!="0"){echo $json['db'][$i]["Match_Ho"];}?>
					</td>
					<td><a href="#" style="color:#CF2878">0/0</a></td>
				</tr>
				<tr  align="right">
					<td>
						<?if($json['db'][$i]["Match_ShowType"]=="C"&&$json['db'][$i]["Match_Ao"]!="0"){echo $json['db'][$i]["Match_RGG"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_Ao"]!=null&&$json['db'][$i]["Match_Ao"]!="0"){echo $json['db'][$i]["Match_Ao"];}?>
					
					</td>
					<td><a href="#" style="color:#CF2878">0/0</a></td>
				</tr>
				<tr>
					<td  colspan="2">&nbsp;</td>
				</tr>
			</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_DxGG1"]=="O" || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxGG1"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_DxDpl"]==null || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxDpl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr  align="right">
						<td>
							<?if($json['db'][$i]["Match_DxGG2"]=="U" || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxGG2"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_DxXpl"]==null || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxXpl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr>
						<td  colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		
		</tr>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="7"><?php echo $pageStr;?></td>
		</tr>
	
	</tbody>
	</table>
	
	<?}?>
	
	<!--开赛-->
<?if($type=='kaisai'){?>
	<table  id="glist_table"  border="0"  cellspacing="0"  cellpadding="0"  class="m_tab">	
		<tbody>
		<tr class="m_title_over_co">
			<td  width="40">時間</td>
			<td  width="50"  nowrap="">聯盟</td>
			<td  width="40">場次</td>
			<td  width="200">隊伍</td>
			<td  width="195">讓球 / 注單</td>
			<td  width="195">大小盤 / 注單</td>
		</tr>
		
	 
	<?for($i=0; $i<count($json['db']); $i++){?>	
		<tr  class="m_cen_top">
		<td><?=$json['db'][$i]["Match_Date"]?></td>
		<td><?=$json['db'][$i]["Match_Name"]?></td>
		<td><?=$json['db'][$i]["Match_MasterID"]?><br><?=$json['db'][$i]["Match_GuestID"]?></td>
		<td  align="left">
			<?=$json['db'][$i]["Match_Master"]?>  <br> <?=$json['db'][$i]["Match_Guest"]?>  <div  align="right"><font  color="#009900">和局</font></div>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
			<tbody>
				<tr  align="right">
					<td  width="52%">
						<?if($json['db'][$i]["Match_ShowType"]=="H"&&$json['db'][$i]["Match_Ho"]!="0"){echo $json['db'][$i]["Match_RGG"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_Ho"]!=null&&$json['db'][$i]["Match_Ho"]!="0"){echo $json['db'][$i]["Match_Ho"];}?>
					</td>
					<td><a href="#" style="color:#CF2878">0/0</a></td>
				</tr>
				<tr  align="right">
					<td>
						<?if($json['db'][$i]["Match_ShowType"]=="C"&&$json['db'][$i]["Match_Ao"]!="0"){echo $json['db'][$i]["Match_RGG"];}?>&nbsp;&nbsp;&nbsp;<?if($json['db'][$i]["Match_Ao"]!=null&&$json['db'][$i]["Match_Ao"]!="0"){echo $json['db'][$i]["Match_Ao"];}?>
					
					</td>
					<td><a href="#" style="color:#CF2878">0/0</a></td>
				</tr>
				<tr>
					<td  colspan="2">&nbsp;</td>
				</tr>
			</tbody>
			</table>
		</td>
		<td>
			<table  width="100%"  border="0"  cellspacing="0"  cellpadding="0">
				<tbody>
					<tr  align="right">
						<td  width="52%">
							<?if($json['db'][$i]["Match_DxGG1"]=="O" || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxGG1"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_DxDpl"]==null || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxDpl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr  align="right">
						<td>
							<?if($json['db'][$i]["Match_DxGG2"]=="U" || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxGG2"];}?>&nbsp;&nbsp;&nbsp;
							<? if($json['db'][$i]["Match_DxXpl"]==null || $json['db'][$i]["Match_DxXpl"]=="0"){echo '';}else{echo $json['db'][$i]["Match_DxXpl"];}?>
						</td>
						<td><a href="#" style="color:#CF2878">0/0</a></td>
					</tr>
					<tr>
						<td  colspan="3">&nbsp;</td>
					</tr>
				</tbody>
			</table>
		</td>
		
		</tr>
	<?}?>
		<tr  class="m_cen_top">
		<td colspan="7"><?php echo $pageStr;?></td>
		</tr>
	
	</tbody>
	</table>
	
	<?}?>
</div>	




<?php require("../common_html/footer.php");?>