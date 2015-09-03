<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../include/private_config.php");

include("../../lib/class/model.class.php");
include("../../class/Level.class.php");


?>
<?php require("../common_html/header.php");?>
<body>
<div  id="con_wrap">
<script  language="JavaScript"  type="text/JavaScript">
var vtimeCashList = 0;
var timeGoCashList = null;

	$(document).ready(function(){
		//getdata(1);
		set_page(1,1);
	});
function set_page(all,current){
	$("#page").empty();
	for(i=1;i<=all;i++){
		$("#page").append("<option value='"+i+"'>"+i+"</option>"); 
	}
	$("#page").val(current);
}
function SetTimeCashList(otime)
{
    vtimeCashList=otime;
    if(vtimeCashList>0)
    {
        window.clearTimeout(timeGoCashList);
        document.getElementById("lblTime").innerHTML='還有'+vtimeCashList+'秒更新';  
        if(otime!=0)
        {
            timeGoCashList=setInterval("timeCashList("+otime+")",1000);
        }
    }
    else 
    {
        document.getElementById("lblTime").innerHTML="";
        window.clearTimeout(timeGoCashList);
    }
}
function timeCashList(otime)
{
    if(vtimeCashList<=0)
    {
        document.getElementById("lblTime").innerHTML=""; 
        window.clearTimeout(timeGoCashList); 
    }
    else 
    {
        vtimeCashList=vtimeCashList-1;
        if(vtimeCashList<=0)
        {        	   	
        	getdata();            
            vtimeCashList=otime;
        }
        document.getElementById("lblTime").innerHTML='還有'+vtimeCashList+'秒更新';
        
    }
}
$(document).ready(function(){	
	retime = $("select[name=reload] option:selected").val();
	var time = (retime == 0 || retime == -1) ? -1 : "" + retime;
	if(time != -1)
	{
		setTimeout("getdata()", time * 1000);		
	}    
});
function getdata(){
	var time=$("#gengxin").val();
	var type=$("#lx").val();
	var s_time=$("#s_time").val();
	var e_time=$("#e_time").val();
	var username=$("#username").val();
	var page='<?=$_GET['page']?>';
	
	var url = 'video_og_data.php';
	    url = url +"?times="+time+'&type='+type+'&s_time='+s_time+'&e_time='+e_time+'&username='+username+'&page='+page;
	$.getJSON(url, function(data){
		$("#show").html(data.html);	
		$("#t_sum").html(data.t_sum);	
		$("#allt_sum").html(data.allt_sum);
	});	
}




function go(val){
	var val=$("#lx").val();
	window.location.href="video_og.php?type="+val;
	//alert(val);
}


</script>
<script>
    //分页跳转
  window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit()
    }
  }
</script>
  <div  class="input_002">MG下注記錄</div>
  <div  class="con_menu">
	<form  id="myFORM"  action="#"  method="get"  name="FrmData">
  	<a  href="video_mg.php">MG</a>
  	<!--<a  href="video_bbin.php">BBIN</a>-->
  	<a  href="video.php">AG</a>
	<a  href="video_og.php">OG</a>
		視訊類型：
	<select  id="lx"  name="lx" onchange="go();" >
	<option  value="0">全部</option>
	<option  value="11" <?if($_GET['type']==11){echo 'selected';}?>>百家樂</option>
	<option  value="12" <?if($_GET['type']==12){echo 'selected';}?>>龍虎</option>
	<option  value="14" <?if($_GET['type']==14){echo 'selected';}?>>篩寶</option>
	<option  value="13" <?if($_GET['type']==13){echo 'selected';}?>>輪盤</option>
	<option  value="15" <?if($_GET['type']==15){echo 'selected';}?>>德州撲克</option>
	<option  value="16" <?if($_GET['type']==16){echo 'selected';}?>>番攤</option>
	
	</select>
	
	日期：
    <input class="za_text Wdate" onClick="WdatePicker()" value="" size="10" id="s_time" name="s_time"> 
	--
	 <input class="za_text Wdate" onClick="WdatePicker()" value="" size="10" id="e_time" name="e_time"> 
	会员帐号：<input  name="username"  type="text"  id="username"  class="za_text"  style="width:80px"  value="">
	<input  type="submit"  value="確定"   class="za_button">

	
	
	
	 重新整理：
		<select  name="reload" id="gengxin" onchange="SetTimeCashList(this.value);">
			<option  value="-1">不自動更新</option>
			<option  value="5">5秒</option>
			<option  value="10">10秒</option>
			<option  value="15">15秒</option>
			<option  value="30">30秒</option>
			<option  value="60">60秒</option>
			<option  value="120">120秒</option>
		</select>
		每页记录数：
  <select name="page_num" id="page_num" onchange="document.getElementById('myFORM').submit()" class="za_select">
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
  </select> <?php echo  $totalPage ;?> 頁&nbsp;
		<span  id="lblTime"  style="color:red"></span> 
	</form>
</div>
</div>
<div  class="content"  id="show">
<table  border="0"  width="99%"  cellpadding="0"  cellspacing="0"  class="m_tab" style="margin-bottom:-1px;">
    <tbody>
      <tr  class="m_title">
			<td  width="120">時間</td>
            <td  width="70">遊戲ID</td>
            <td  width="70">桌號</td>
            <td  width="70">单號</td>
            <td  width="70">所屬上級</td>
            <td  width="70">下注帳號</td>
			<td  width="90">視訊類別</td>
			<td  width="90">有效投注</td>
			<td  width="90">退水</td>
            <td  width="90">結果</td>
			<td  width="90">状态</td>
	</tr>
		<?
		
		
		
		$where		=	'';
		$allt_sum=0;
		$allw_sum=0;


		$sql		=	"select id from k_og_bet_record where site_id='".SITEID."' ";
		$sumsql		=	"select sum(og_bettingamount) as sgold, sum(og_WinLoseAmount) as swin  from k_og_bet_record where  site_id='".SITEID."'";
		//游戏分类
		if($_GET["type"]){
				$sql		.=	" and og_game='".$_GET["type"]."'";
				$sumsql   .=	" and og_game='".$_GET["type"]."'";
				$where .=	" and og_game='".$_GET["type"]."'";
		}
	
		if($_GET["username"]){
		  $sql		.=	" and og_user like('%".trim($_GET["username"])."%')";
		  $sumsql   .=	" and og_user like('%".trim($_GET["username"])."%')"; 
		  $where .=	" and og_user like('%".trim($_GET["username"])."%')";
		}
		
		if($_GET["s_time"]) {
			$sql.=" and og_record_date>='".$_GET["s_time"]." 00:00:00'";
			$sumsql.=" and og_record_date>='".$_GET["s_time"]." 00:00:00'";
			$where .=	" and og_record_date>='".$_GET["s_time"]." 00:00:00'";
		}

		if($_GET["e_time"]) {
			$sql.=" and og_record_date<='".$_GET["e_time"]." 23:59:59'";
			$sumsql.=" and og_record_date<='".$_GET["e_time"]." 23:59:59'";
			$where .=" and og_record_date<='".$_GET["e_time"]." 23:59:59'";
		}
			 
		$querysum		=	$mysqlt->query($sumsql);//print_r($sumsql);exit;
		if($rowsum = $querysum->fetch_array()){  
			$allt_sum=$rowsum["sgold"];
			$allw_sum=$rowsum["swin"];
		}

		$sql		.=	$where." order by og_record_date desc";//exit($sql);
		
		$querysum		=	$mysqlt->query($sumsql);
		if($rowsum = $querysum->fetch_array()){ 
			$allt_sum=$rowsum["sgold"];
			$allw_sum=$rowsum["swin"];
		}

		$query		=	$mysqlt->query($sql);
		$sum		=	$mysqlt->affected_rows;//总页数
		
			
$pagenum=isset($_GET['page_num'])?$_GET['page_num']:20; //每页显示的记录数
$CurrentPage=isset($_GET['page'])?$_GET['page']:1;

 $totalPage=ceil($sum/$pagenum); //计算出总页数

			$uid    = '';
			$i      = 1; 
			$start  = ($CurrentPage-1)*$pagenum+1;
			$end  = $CurrentPage*$pagenum;
			while($row = $query->fetch_array()){
				if($i >= $start && $i <= $end){
				  $id .= "'".$row['id']."',";
				}
				if($i > $end) break;
				  $i++;
			}
			
		$c_sum	=	$m_sum	=	$t_sum	=	$f_sum	=	$sxf_sum	=	0;
		if($sum){
			$id	=	rtrim($id,','); 
			$arr	=	array();
			$sql	=	"select * from k_og_bet_record where site_id='".SITEID."' $where and id in($id)  order by og_record_date desc";
			$query	=	$mysqlt->query($sql);
			$t_sum=0;
			$w_sum=0;
			while($rows = $query->fetch_array()){
			$t_sum=$t_sum+doubleval($rows["og_bettingamount"]);
			$w_sum=$w_sum+doubleval($rows["og_WinLoseAmount"]);
			//退水设置值
			
			$video_type_name=gamefw($rows["og_game"]);
			$sql	=	"select water_break from k_user_level_video_set where video_type_name='".$video_type_name."' and aid='".$rows['uid']."' and site_id='".SITEID."'";
			$query_fw	=	$mysqlt->query($sql);
			if(!$rowsw = $query_fw->fetch_array()){
				$sql	=	"select water_break from k_user_level_video_set where video_type_name='".$video_type_name."' and aid='".$rows['agent_id']."' and site_id='".SITEID."'";
				$query_level	=	$mysqlt->query($sql);
				$rowsw = $query_level->fetch_array();
				$zrfsbl=$rowsw['water_break'];
			}else{
				$zrfsbl=$rowsw['water_break'];
			}
			//print_r($zrfsbl);exit;
		//所屬上級代理
		if($rows['agent_id']){
			$sql	=	"select id,agent_user,agent_company,pid from k_user_agent where id='".$rows['agent_id']."' and site_id='".SITEID."'";
			$query_g	=	$mysqlt->query($sql);
			$row_agent = $query_g->fetch_array();
		}
		//所屬上級总代理
		if($row_agent['pid']){
			$sql	=	"select id,agent_user,agent_company,pid from k_user_agent where id='".$row_agent['pid']."' and site_id='".SITEID."'";
			$query_z	=	$mysqlt->query($sql);
			$rows_agent = $query_z->fetch_array();
		}
		//所屬上股东
		if($rows_agent['pid']){
			$sql	=	"select id,agent_user,agent_company,pid from k_user_agent where id='".$rows_agent['pid']."' and site_id='".SITEID."'";
			$query_d	=	$mysqlt->query($sql);
			$rows_agents = $query_d->fetch_array();
		}
		//公司：<br>股東：<br>總代理：<br>代理：
		$agent='公司:'.$rows_agents['agent_company'].'<br>股東：'.$rows_agents['agent_user'].'<br>總代理:'.$rows_agent['agent_user'].'<br>代理:'.$row_agent['agent_user'];
		
			
		?>
		<tr  class="m_rig"  align="left">
			<td  align="center"><?=$rows['og_record_date']?></td>
            <td  align="center"><font  color="#0000FF"><?=$rows["og_stage"]?></font></td>
            <td  align="center"><?=$rows["og_table"]?></td>
            <td  align="center"><?=$rows["og_order"]?></td>
            <td  align="left"  nowrap="nowrap"><?=$agent?></td>
            <td  align="center"><?=$rows["og_user"]?></td>
			<td  align="center"><?=gameType($rows["og_game"])?></td>
			<td><?=$rows["og_bettingamount"]?></td>
			<td><?=round($rows["og_bettingamount"]*$zrfsbl,2)?></td>
			<td  align="center"><?=gameresult($rows["ResultType"])?></td>
            <td  align="center"><?if($rows['id']){ echo '已结算';}else{echo '未结算';}?></td>
		</tr>
	  <?php
	    }
		}
	  ?>
	  </tbody></table></div>
	  <div>
	  <table  border="0"  width="99%"  cellpadding="0"  cellspacing="0"  class="m_tab"><tbody>
	  
	 
        <tr class="m_cen">
          <td colspan="16"  height="30" id="feny"><?php echo $pageStr;?></td>
       </tr>
		<tr  class="m_rig"  style="background-Color:#EBF0F1 ;display:;">
			<td  colspan="6"  align="right">小計：</td>
			<td  colspan="5"  align="left" id="t_sum"><?=$t_sum?></td>
		</tr>
	<tr  class="m_rig"  style="background-Color:#EBF0F1 ;display:;">
			<td  colspan="6"  align="right">总計：</td>
			<td  colspan="5"  align="left" id="allt_sum"><?=$allt_sum?></td>
		</tr>
</tbody></table></div>



<script>

function viewchart(grid,uid){
art.dialog.open('/app/daili/adminsave/report/zr_view.php?uid={UID}&langx={LANGX}&grid='+grid+"&userid="+uid,{
  title: '下注明細',
  width:700,
  height:330
 });
};

</script>


</div>

</body></html>
<?php 

function gameType($gameType)
{
	$arr  = array(
		"11"=>"百家乐",
		"12"=>"龙虎",
		"14"=>"骰宝",
		"13"=>"轮盘",
		"16"=>"番攤",
		"15"=>"德州扑克"
		);
	return $arr[$gameType];
}

function gameresult($gameType)
{
	$arr  = array(
		"1"=>"输",
		"2"=>"赢",
		"3"=>"和",
		"4"=>"无效",
		);
	return $arr[$gameType];
}
function gamefw($gameType)
{
	$arr  = array(
		"11"=>"bjl",
		"12"=>"lh",
		"14"=>"sb",
		"13"=>"lunp",
		"15"=>"lunp",
		"16"=>"lunp"
		);
	return $arr[$gameType];
}

?>