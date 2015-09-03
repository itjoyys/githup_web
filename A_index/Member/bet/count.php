<?php
	include_once("../../include/config.php");
	include_once("../../common/login_check.php");
	include_once("../../video/games/Games.class.php");


	if($_GET['action']=='yesterday'){
		$starttime = date("Y-m-d",strtotime("-1 day"))." 00:00:00";
		$endtime   = date("Y-m-d",strtotime("-1 day"))." 23:59:59";
	}elseif($_GET['action']=='theweek'){
		$starttime=date("Y-m-d",strtotime("last Monday"))." 00:00:00";
		$endtime=date("Y-m-d")." 23:59:59";
	}elseif($_GET['action']=='lastweek'){
		$starttime=date("Y-m-d",strtotime("last Monday")-604800)." 00:00:00";
		$endtime=date("Y-m-d",strtotime("last Monday")-86400)." 23:59:59";
	}else{
		$starttime = date("Y-m-d")." 00:00:00";
		$endtime   = date("Y-m-d")." 23:59:59";
	}


	$uid=$_SESSION['uid'];

	$map='';
	$map.="site_id='".SITEID."'";
	$map.=" and uid=$uid";
	$map.=" and addtime>'$starttime' and addtime<'$endtime'";



	$cp=M('c_bet',$db_config)->field('count(*) a,sum(money) b')->where($map)->find();//彩票

	//彩票有效的
	$valid_map="";
	$valid_map=$map." and status in(1,2)";
	$valid_cp=M('c_bet',$db_config)->field('sum(money) c,sum(win) d')->where($valid_map)->find();


	$con='';
	$con.="site_id='".SITEID."'";
	$con.=" and uid=$uid";
	$con.=" and bet_time>'$starttime' and bet_time<'$endtime'";

	$ty=M('k_bet',$db_config)->field('count(*) a,sum(bet_money) b')->where($con)->find();//体育

	//体育有效的
	$valid_con='';
	$valid_con=$con." and is_jiesuan=1";
	$valid_con.=" and status in(1,2,4,5)";
	$valid_ty=M('k_bet',$db_config)->field('sum(bet_money) c,sum(win) d')->where($valid_con)->find();


	//体育串关
	$cg_ty=M('k_bet_cg_group',$db_config)->field('count(*) a,sum(bet_money) b')->where($con)->find();

	$cg_where='';
	$cg_where=$con." and is_jiesuan=1";
	$cg_where.=" and status in(1,2,4,5)";
	$cg_valid_ty=M('k_bet_cg_group',$db_config)->field('sum(bet_money) c,sum(win) d')->where($cg_where)->find();
	$ty['a']+=$cg_ty['a'];
	$ty['b']+=$cg_ty['b'];
	$valid_ty['c']+=$cg_valid_ty['c'];
	$valid_ty['d']+=$cg_valid_ty['d'];

	//视讯

	function gamesinfo($g_type,$loginname,$starttime,$endtime,$dz){
		$games = new Games();
		$data = $games->GetAvailableAmountByUser($g_type, $loginname, $starttime, $endtime,$dz);
		return json_decode($data);
	}

	$loginname = $_SESSION['username'];

	$ag_data=gamesinfo('ag',$loginname,$starttime,$endtime);
	$og_data=gamesinfo('og',$loginname,$starttime,$endtime);
	$mg_data=gamesinfo('mg',$loginname,$starttime,$endtime);
	$ct_data=gamesinfo('ct',$loginname,$starttime,$endtime);
	$bbin_data=gamesinfo('bbin',$loginname,$starttime,$endtime);
	$lebo_data=gamesinfo('lebo',$loginname,$starttime,$endtime);
	$mgdz_data=gamesinfo('mg',$loginname,$starttime,$endtime,1);
?>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
		<script>
		function go(value){
			window.location.href="record_ds.php?date="+value;
		}	
		</script>
		<link rel="stylesheet" href="../public/css/index_main.css" />
		<link rel="stylesheet" href="../public/css/standard.css" />
	</head>
	<body  style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
	    <div id="MAMain" style="width:767px">
			<div id="MACenter-content">
				<div id="MACenterContent">
					<div id="MNav"> 
						<span class="mbtn">报表统计</span>
						<div class="navSeparate"></div>
					</div>
					<div class="MNavLv2"> 
						<span class="MGameType <?php if($_GET['action']==''){echo 'hover';}?>" id="sport_today" onclick="javascript:window.location.href='count.php'">今日</span>｜ 
						<span class="MGameType <?php if($_GET['action']=='yesterday'){echo 'hover';}?>" id="lottery_today" onclick="javascript:window.location.href='count.php?action=yesterday'">昨日</span>｜
						<span class="MGameType <?php if($_GET['action']=='theweek'){echo 'hover';}?>" id="ag_today" onclick="javascript:window.location.href='count.php?action=theweek'">本周</span>｜		   
						<span class="MGameType <?php if($_GET['action']=='lastweek'){echo 'hover';}?>" id="mg_today" onclick="javascript:window.location.href='count.php?action=lastweek'">上周</span>
					</div>
					<div id="MMainData">
						<!-- 体育今日交易 -->
						<div class="MPanel" style="display: block;">
							<table class="MMain" border="1">
								<tbody>
									<tr>
										<!--th width="5%">编号</th-->
										<th>项目</th>
										<th>注单量</th>
										<th>下注总额</th>
										<th>有效下注总额</th>
										<th>赢利总额</th>
									</tr>
									<tr>
										<td>彩票</td>
										<td style='text-align:center;'><?php echo !empty($cp['a'])?$cp['a']:0;?></td>
										<td style='text-align:center;'><?php echo !empty($cp['b'])?number_format($cp['b'],2):0;?></td>
										<td style='text-align:center;'><?php echo !empty($valid_cp['c'])?number_format($valid_cp['c'],2):0;?></td>
										<td style='text-align:center;'><?php echo $valid_cp['d']-$valid_cp['c'];?></td>
									</tr>
									<tr>
										<td>体育</td>
										<td style='text-align:center;'><?php echo !empty($ty['a'])?$ty['a']:0;?></td>
										<td style='text-align:center;'><?php echo !empty($ty['b'])?number_format($ty['b'],2):0;?></td>
										<td style='text-align:center;'><?php echo !empty($valid_ty['c'])?number_format($valid_ty['c'],2):0;?></td>
										<td style='text-align:center;'><?php echo $valid_ty['d']-$valid_ty['c'];?></td>
									</tr>
									<tr>
										<td>OG</td>
										<td style='text-align:center;'><?=!empty($og_data->data->BetAllCount) ? $og_data->data->BetAllCount:0?></td>
										<td style='text-align:center;'><?=!empty($og_data->data->BetAll) ? $og_data->data->BetAll : 0?></td>
										<td style='text-align:center;'><?=!empty($og_data->data->BetYC) ? $og_data->data->BetYC : 0?></td>
										<td style='text-align:center;'><?=!empty($og_data->data->BetPC) ? number_format($og_data->data->BetPC,2): 0?></td>
									</tr>
									<tr>
										<td>MG视讯</td>
										<td style='text-align:center;'><?=!empty($mg_data->data->BetBS) ? $mg_data->data->BetBS:0?></td>
										<td style='text-align:center;'><?=!empty($mg_data->data->BetAll) ? $mg_data->data->BetAll : 0?></td>
										<td style='text-align:center;'><?=!empty($mg_data->data->BetYC) ? $mg_data->data->BetYC : 0?></td>
										<td style='text-align:center;'><?=!empty($mg_data->data->BetPC) ? number_format($mg_data->data->BetPC,2):0?></td>
									</tr>
									<tr>
										<td>MG电子</td>
										<td style='text-align:center;'><?=!empty($mgdz_data->data->BetBS) ? $mgdz_data->data->BetBS:0?></td>
										<td style='text-align:center;'><?=!empty($mgdz_data->data->BetAll) ? $mgdz_data->data->BetAll : 0?></td>
										<td style='text-align:center;'><?=!empty($mgdz_data->data->BetYC) ? $mgdz_data->data->BetYC : 0?></td>
										<td style='text-align:center;'><?=!empty($mgdz_data->data->BetPC) ? number_format($mgdz_data->data->BetPC,2):0?></td>
									</tr>
									<tr>
										<td>BBIN</td>
										<td style='text-align:center;'><?=!empty($bbin_data->data->BetAllCount) ? $bbin_data->data->BetAllCount:0?></td>
										<td style='text-align:center;'><?=!empty($bbin_data->data->BetAll) ? $bbin_data->data->BetAll : 0?></td>
										<td style='text-align:center;'><?=!empty($bbin_data->data->BetYC) ? $bbin_data->data->BetYC : 0?></td>
										<td style='text-align:center;'><?=!empty($bbin_data->data->BetPC) ? number_format($bbin_data->data->BetPC,2):0?></td>
									</tr>
									<tr>
										<td>AG </td>
										<td style='text-align:center;'><?=!empty($ag_data->data->BetAllCount) ? $ag_data->data->BetAllCount:0?></td>
										<td style='text-align:center;'><?=!empty($ag_data->data->BetAll) ? $ag_data->data->BetAll : 0?></td>
										<td style='text-align:center;'><?=!empty($ag_data->data->BetYC) ? $ag_data->data->BetYC : 0?></td>
										<td style='text-align:center;'><?=!empty($ag_data->data->BetPC) ? number_format($ag_data->data->BetPC,2):0?></td>
									</tr>
									<tr>
										<td>LEBO </td>
										<td style='text-align:center;'><?=!empty($lebo_data->data->BetAllCount) ? $lebo_data->data->BetAllCount:0?></td>
										<td style='text-align:center;'><?=!empty($lebo_data->data->BetAll) ? $lebo_data->data->BetAll : 0?></td>
										<td style='text-align:center;'><?=!empty($lebo_data->data->BetYC) ? $lebo_data->data->BetYC : 0?></td>
										<td style='text-align:center;'><?=!empty($lebo_data->data->BetPC) ? number_format($lebo_data->data->BetPC,2):0?></td>
									</tr>
									<tr>
										<td>CT</td>
										<td style='text-align:center;'><?=!empty($ct_data->data->BetAllCount) ? $ct_data->data->BetAllCount:0?></td>
										<td style='text-align:center;'><?=!empty($ct_data->data->BetAll) ? $ct_data->data->BetAll : 0?></td>
										<td style='text-align:center;'><?=!empty($ct_data->data->BetYC) ? $ct_data->data->BetYC : 0?></td>
										<td style='text-align:center;'><?=!empty($ct_data->data->BetPC) ? number_format($ct_data->data->BetPC,2):0?></td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
	    </div>
	</body>
</html>
