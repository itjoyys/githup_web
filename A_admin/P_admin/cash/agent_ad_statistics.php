<?php
// ini_set("display_errors", "On");
// error_reporting(E_ALL);
set_time_limit(0);
include_once("../../include/config.php");
include_once("../common/login_check.php"); 
include_once("../../class/agentBet.class.php");
include("../../class/agentCenter.class.php");
include("../../lib/video/Games.class.php");

$agent = M('k_agent_ad_view',$db_config);

if (!empty($_GET['start_date']) && !empty($_GET['end_date'])) {
	$start_date = $_GET['start_date'];
	$end_date = $_GET['end_date'];
    
}else{
	$start_date = $end_date = date('Y-m-d');
}
$map =  " and reg_date >= '".$start_date.' 00:00:00'."' and reg_date <= '".$end_date.' 23:59:59'."'";
$vdate = array($start_date,$end_date);
$games = new Games();

// die();
$dat=$agent->where("is_delete = '0' and site_id = '".SITEID."'")->select();
foreach ($dat as $key => $val) {
	$userid = M('k_user',$db_config)->field("uid")->where("agent_id = '".$val['agent_id']."' and shiwan = '0'".$map)->select();
	if (!empty($userid)) {
		    $dat[$key]['user_count'] = count($userid);
			  //有效会员数量
			 //彩票体育
	          $FcSp_YX = array();
	          $FcSp_YX = agentCenter::agentUserYX($val['agent_id'],$vdate);
	               //视讯
	          $s_time = $start_date." 00:00:00";
	          $e_time = $end_date." 23:59:59";
	          $Vdata = $games->GetAllUserAvailableAmountByAgentid($val['agent_id'], $s_time, $e_time);
	          $Vdata = json_decode($Vdata);
	          $tmpd = $Vdata->data->data;
	          $video_uid_YX = array();
	          $dat[$key]['video_bet'] = $dat[$key]['video_win'] = 0;
	          foreach ($tmpd as $k => $v) {
	             $video_uid_YX[$v->username] = $v->username;
	             //视讯有效打码
	             $dat[$key]['video_bet'] += $v->BetYC+0;
	             $dat[$key]['video_win'] += $v->BetPC - $v->BetYC+0;
	          }
              //电子
	          $Gdata = $games->GetAllUserAvailableAmountByAgentid($val['agent_id'], $s_time, $e_time,1);
	          $Gdata = json_decode($Gdata);
	          $tmpg = $Gdata->data->data;
	          $game_uid_YX = array();
	          $dat[$key]['game_bet'] = $dat[$key]['game_win']= 0;
	          foreach ($tmpg as $gk => $gv) {
	             $game_uid_YX[$gv->username] = $gv->username;
	             //视讯有效打码
	             $dat[$key]['game_bet'] += $gv->BetYC+0;
	             $dat[$key]['game_win'] += $gv->BetPC - $gv->BetYC+0;
	          }
	         
	        $userYxAll = $FcSp_YX + $video_uid_YX;
            $dat[$key]['count_yx'] = count($userYxAll);
		    //体育有效投注
            $sp_bet = agentBet::spBet($val['agent_id'],$vdate);
		    $dat[$key]['sp_bet'] = $sp_bet['bet'];
		    $dat[$key]['sp_win'] = $sp_bet['win']-$sp_bet['bet'];
			// //彩票有效投注
            $fc_bet = agentBet::fcBet($val['agent_id'],$vdate);
		    $dat[$key]['cp_bet'] = $fc_bet['bet'];
		    $dat[$key]['fc_win'] = $fc_bet['win']-$fc_bet['bet'];
	      
	}
}
//分页
    $perNumber=20; //每页显示的记录数
    $count=count($dat); //获得记录总数
    $totalPage=ceil($count/$perNumber); //计算出总页数

    if (!isset($page)) {
        $page=1;
    }else{
    	 $page=$_GET['page']; //获得当前的页面值
    } //如果没有值,则赋值1

    $startCount=($page-1)*$perNumber; //分页开始,根据此方法计算出开始的记录
    $limit=$startCount.",".$perNumber;

?>
<?php require("../common_html/header.php");?>
<body>
<script>
//分页跳转
	window.onload=function(){
    document.getElementById("page").onchange=function(){
      document.getElementById('myFORM').submit();
    }
  }
</script>
<div id="con_wrap">
<form method="get" action="#" name="" id="myFORM">
<div class="input_002">代理廣告</div>
<div class="con_menu">
	<a href="agent_ad.php">代理廣告</a>
    <a href="agent_ad_statistics.php" style="color:red">廣告統計</a>
    &nbsp;&nbsp; 日期:
    <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$start_date?>" name="start_date">~
    <input class="za_text Wdate" onClick="WdatePicker()" value="<?=$end_date?>" name="end_date">
	<input type="submit" name="subbtn" class="button_a" value=" 查 詢 ">
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
  </select> <?php echo  $totalPage ;?> 頁
</div>
</form>
</div>
<div class="content">
	<table width="1024" border="0" cellspacing="0" cellpadding="0" bgcolor="#E3D46E" class="m_tab">
		<tbody><tr class="m_title_over_co">
			<td rowspan="2">代理账号</td>
			<td rowspan="2">網站</td>
			<td rowspan="2">連接網址</td>
			<td rowspan="2">會員數</td>
			<td rowspan="2">有效會員</td>
			<td colspan="4">有效投注</td>
			<td colspan="4">會員輸贏</td>
			<td rowspan="2">合計</td>
		</tr>
		<tr class="m_title_over_co">
			<td>體育</td>
			<td>彩票</td>
			<td>視訊</td>
			<td>电子</td>
			<td>體育</td>
			<td>彩票</td>
			<td>視訊</td>	
			<td>电子</td>		
		</tr>
		<?
		if(!empty($dat)){
			$totalWin = 0;
		foreach($dat as $v){   
            $totalWin += $v['fc_win'] + $v['sp_win']+$v['video_win']+$v['game_win'];
			?>
		<tr class="m_cen">
			<td><?=$v['agent_user']?></td>
			<td><?=$v['web_site']?></td>
			<td><?=$v['connection_url']?></td>
			<td><?=$v['user_count']+0?></td>
			<td><?=$v['count_yx']+0?></td>
			<td><?=$v['sp_bet']+0?></td>
			<td><?=$v['cp_bet']+0?></td>
			<td><?=$v['video_bet']+0?></td>
			<td><?=$v['game_bet']+0?></td>
			<td><?=$v['sp_win']+0?></td>
			<td><?=$v['fc_win']+0?></td>
			<td><?=$v['video_win']+0?></td>
			<td><?=$v['game_win']+0?></td>
			<td><?=$v['totalWin']?></td>
		</tr>
		<?}}else{?>
           <tr>
			<td  class="table_bg1"  colspan="15">暂无数据</td>
		</tr>
		<?php }?>
	</tbody></table>
</div>
<?php require("../common_html/footer.php");?>