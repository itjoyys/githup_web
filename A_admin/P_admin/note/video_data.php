<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
//include("../../include/private_config.php");
include("../../include/pager.class.php");
include("../../lib/class/model.class.php");
function getstatus($status){
   $return="";
   switch ($status){
   	case 0:$return="失败";break;
   	case 1:$return="成功";break;
   	case 2:$return="待处理";break;
   	default:break;
   }
   return $return;
}
$time	=	'CN'; //默认为中国时间
if(isset($_GET['time'])) $time = $_GET['time'];




$html='';


$html.='<table  border="0"  width="99%"  cellpadding="0"  cellspacing="0" style="margin-bottom:-1px;" class="m_tab">';
$html.='    <tbody>';
$html.='      <tr  class="m_title">';
$html.='			<td  width="120">時間</td>';
$html.='            <td  width="70">局號</td>';
 $html.='           <td  width="70">桌號</td>';
$html.='            <td  width="70">单號</td>';
 $html.='           <td  width="70">所屬上級</td>';
$html.='            <td  width="70">下注帳號</td>';
$html.='			<td  width="90">視訊類別</td>';
$html.='			<td  width="90">有效投注</td>';
$html.='			<td  width="90">退水</td>';
$html.='            <td  width="90">結果</td>';
$html.='			<td  width="90">状态</td>';
$html.='	</tr>';
		
		
		
		
		$where		=	'';
		$allt_sum=0;
		$allw_sum=0;


		$sql		=	"select billNo from K_betDetail where site_id='".SITEID."' ";
		$sumsql		=	"select sum(betAmount) as sgold, sum(netAmount) as swin  from K_betDetail where  site_id='".SITEID."'  ";
		//游戏分类
		if($_GET["type"]){
			if($_GET["type"]==1){
				$sql		.=	" and gameType in ('BAC','CBAC','LINK')";
				$sumsql   .=	" and gameType in ('BAC','CBAC','LINK')"; 
				$where .=	" and gameType in ('BAC','CBAC','LINK')";
			}elseif($_GET["type"]==2){
				$sql		.=	" and gameType='DT'";
				$sumsql   .=	" and gameType='DT'";
				$where .=	" and gameType='DT'";
			}elseif($_GET["type"]==3){
				$sql		.=	" and gameType='SHB'";
				$sumsql   .=	" and gameType='SHB'";
				$where .=	" and gameType='SHB'";
			}else{
				$sql		.=	" and gameType in ('ROU','FT','FIFA','SL1','SL2','SL3')";
				$sumsql   .=	" and gameType in ('ROU','FT','FIFA','SL1','SL2','SL3')"; 
				$where .=	" and gameType in ('ROU','FT','FIFA','SL1','SL2','SL3')";
			}
			
		}

		if($_GET["username"]){
		  $sql		.=	" and playerName like('%".trim($_GET["username"])."%')";
		  $sumsql   .=	" and playerName like('%".trim($_GET["username"])."%')"; 
		  $where .=	" and playerName like('%".trim($_GET["username"])."%')";
		}
		
		if($_GET["s_time"]) {
			$sql.=" and betTime>='".$_GET["s_time"]." 00:00:00'";
			$sumsql.=" and betTime>='".$_GET["s_time"]." 00:00:00'";
			$where .=	" and betTime>='".$_GET["s_time"]." 00:00:00'";
		}

		if($_GET["e_time"]) {
			$sql.=" and betTime<='".$_GET["e_time"]." 23:59:59'";
			$sumsql.=" and betTime<='".$_GET["e_time"]." 23:59:59'";
			$where .=" and betTime<='".$_GET["e_time"]." 23:59:59'";
		}
			  
		$querysum		=	$mysqlt->query($sumsql);
		if($rowsum = $querysum->fetch_array()){  //print_r($rowsum);exit;
			$allt_sum=$rowsum["sgold"];
			$allw_sum=$rowsum["swin"];
		}

		$sql		.=	$where." order by betTime desc";//exit($sql);
		
		$querysum		=	$mysqlt->query($sumsql);
		if($rowsum = $querysum->fetch_array()){ 
			$allt_sum=$rowsum["sgold"];
			$allw_sum=$rowsum["swin"];
		}

		$query		=	$mysqlt->query($sql);
		$sum		=	$mysqlt->affected_rows;//总页数
			$thisPage = 1;
			if(@$_GET['page']){
			  $thisPage = $_GET['page'];
			}
			$pagenum = 40;
			$CurrentPage=isset($_GET['page'])?$_GET['page']:1;
			if($CurrentPage==''){
				$CurrentPage=1;
			}
			$myPage=new pager($sum,intval($CurrentPage),$pagenum);
			$pageStr= $myPage->GetPagerContent();

			$uid    = '';
			$i      = 1; 
			$start  = ($CurrentPage-1)*$pagenum+1;
			$end  = $CurrentPage*$pagenum;
			while($row = $query->fetch_array()){
				if($i >= $start && $i <= $end){
				  $billNo .= "'".$row['billNo']."',";
				}
				if($i > $end) break;
				  $i++;
			}
			
		$c_sum	=	$m_sum	=	$t_sum	=	$f_sum	=	$sxf_sum	=	0;
		if($sum){
			$billNo	=	rtrim($billNo,','); 
			$arr	=	array();
			$sql	=	"select * from K_betDetail where site_id='".SITEID."' $where and billNo in($billNo)  order by betTime desc";
			$query	=	$mysqlt->query($sql);
			$t_sum=0;
			$w_sum=0;
			while($rows = $query->fetch_array()){
			$t_sum=$t_sum+doubleval($rows["betAmount"]);
			$w_sum=$w_sum+doubleval($rows["netAmount"]);
			//退水设置值
			
			$video_type_name=gamefw($rows["gameType"]);
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
		//	print_r($zrfsbl);exit;
		
		$sqluser	=	"select u.agent_id from k_user u,k_user_games g where u.site_id='".SITEID."' and u.uid=g.uid and g.ag_username='".API_PRE."_".$rows["playerName"]."'";
		$query_user	=	$mysqlt->query($sqluser);
		$row_user = $query_user->fetch_array();
		//print_r($row_user);exit;
		
		//所屬上級代理
		if($row_user['agent_id']){
			$sql	=	"select id,agent_user,agent_company,pid from k_user_agent where id='".$row_user['agent_id']."' and site_id='".SITEID."'";
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
		
			
	
		$html.='<tr  class="m_rig"  align="left">';
		$html.='	<td  align="center">'.$rows['betTime'].'</td>';
        $html.='    <td  align="center"><font  color="#0000FF">'.$rows["gameCode"].'</font></td>';
        $html.='    <td  align="center">'.$rows["tableCode"].'</td>';
        $html.='    <td  align="center">'.$rows["billNo"].'</td>';
        $html.='    <td  align="left"  nowrap="nowrap">'.$agent.'</td>';
        $html.='    <td  align="center">'.$rows["playerName"].'</td>';
		$html.='	<td  align="center">'.gameType($rows["gameType"]).'</td>';
		$html.='	<td>'.$rows["validBetAmount"].'</td>';
		$html.='	<td>'.round($rows["validBetAmount"]*$zrfsbl,2).'</td>';
		$html.='	<td>'.$rows["result"].'</td>';
        $html.='    <td  align="center">'.flag($rows["flag"]).'</td>';
		$html.='</tr>';
	 
	    }
		}
		$html.='	</tbody></table>';
	  //print_r($_GET['feny']);exit;
	/*  $html.='<div style="clear:both;display:none;"></div>';
      $html.='  <tr class="m_cen">';
      $html.='    <td colspan="16"  height="30" id="feny">'.$_GET['feny'].'</td>';
      $html.=' </tr>';
	  $html.='	<tr  class="m_rig"  style="background-Color:#EBF0F1 ;display:;">';
		$html.='	<td  colspan="6"  align="right">&nbsp;小計：</td>';
		$html.='	<td  colspan="5"  align="left">&nbsp;'.$t_sum.'</td>';
		$html.='</tr>';
	$html.='<tr  class="m_rig"  style="background-Color:#EBF0F1 ;display:;">';
		$html.='	<td  colspan="6"  align="right">&nbsp;总計：</td>';
	$html.='		<td  colspan="5"  align="left">&nbsp;'.$allt_sum.'</td>';
	$html.='	</tr></tbody></table>';

*/
function gameType($gameType)
{
	$arr  = array(
		"BAC"=>"百家乐",
		"CBAC"=>"包桌百家乐",
		"LINK"=>"连环百家乐",
		"DT"=>"龙虎",
		"SHB"=>"骰宝",
		"ROU"=>"轮盘",
		"FT"=>"番攤",
		"FIFA"=>"世界盃",
		"SL1"=>"老虎機",
		"SL2"=>"水果店",
		"SL3"=>"水族館"
		);
	return $arr[$gameType];
}
function gamefw($gameType)
{
	$arr  = array(
		"BAC"=>"bjl",
		"CBAC"=>"bjl",
		"LINK"=>"bjl",
		"DT"=>"lh",
		"SHB"=>"sb",
		"ROU"=>"lunp",
		"FT"=>"lunp",
		"FIFA"=>"lunp",
		"SL1"=>"lunp",
		"SL2"=>"lunp",
		"SL3"=>"lunp"
		);
	return $arr[$gameType];
}

function flag($flag){
	$arr  = array(
		"1"=>"已结算",
		"0"=>"未结算",
		"-1"=>"重置试玩额度",
		"-2"=>"注单被篡改",
		"-8"=>"取消指定局注单",
		"-9"=>"取消注单"
		);
	return $arr[$flag];
}

function playType($playType)
{
	$arr  = array(
		"1"=>"庄",
		"2"=>"闲",
		"3"=>"和",
		"4"=>"庄对",
		"5"=>"闲对",
		"6"=>"大",
		"7"=>"小",
		"8"=>"散客区庄",
		"9"=>"散客区闲",
		"11"=>"庄免佣"
		);
	return $arr[$playType];
}
$json['html']=$html;
$json['t_sum']=$t_sum;
$json['allt_sum']=$allt_sum;
print_r(json_encode($json));
?>