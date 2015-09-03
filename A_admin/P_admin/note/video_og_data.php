<?php
include_once("../../include/config.php");
include_once("../common/login_check.php");
include("../../include/private_config.php");
include("../../include/pager.class.php");



$html.='';
$html.='<table  border="0"  width="99%"  cellpadding="0"  cellspacing="0"  class="m_tab" style="margin-bottom:-1px;">';
$html.='   <tbody>';
$html.='      <tr  class="m_title">';
$html.='			<td  width="120">時間</td>';
$html.='           <td  width="70">局號</td>';
$html.='            <td  width="70">桌號</td>';
$html.='            <td  width="70">单號</td>';
$html.='            <td  width="70">所屬上級</td>';
$html.='            <td  width="70">下注帳號</td>';
$html.='			<td  width="90">視訊類別</td>';
$html.='			<td  width="90">有效投注</td>';
$html.='			<td  width="90">退水</td>';
$html.='           <td  width="90">結果</td>';
$html.='			<td  width="90">状态</td>';
$html.='	</tr>';

		
		
		
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
			$thisPage = 1;
			if(@$_GET['page']){
			  $thisPage = $_GET['page'];
			}
			$pagenum =40;
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
		
			
		
		$html.='<tr  class="m_rig"  align="left">';
		$html.='	<td  align="center">'.$rows['og_record_date'].'</td>';
        $html.='    <td  align="center"><font  color="#0000FF">'.$rows["og_stage"].'</font></td>';
        $html.='    <td  align="center">'.$rows["og_table"].'</td>';
        $html.='    <td  align="center">'.$rows["og_order"].'</td>';
        $html.='    <td  align="left"  nowrap="nowrap">'.$agent.'</td>';
        $html.='    <td  align="center">'.$rows["og_user"].'</td>';
		$html.='	<td  align="center">'.gameType($rows["og_game"]).'</td>';
		$html.='	<td>'.$rows["og_bettingamount"].'</td>';
		$html.='	<td>'.round($rows["og_bettingamount"]*$zrfsbl,2).'</td>';
		$html.='	<td  align="center">'.gameresult($rows["ResultType"]).'</td>';
		if($rows['id']){ $flag='已结算';}else{$flag='未结算';}
        $html.='    <td  align="center">'.$flag.'</td>';
		$html.='</tr>';
	 
	    }
		}
	  
	 $html.=' </tbody></table>';
	 




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
$json['html']=$html;
$json['t_sum']=$t_sum;
$json['allt_sum']=$allt_sum;
print_r(json_encode($json));
?>