<?php
	include_once("../../include/config.php");
	include_once("../../common/login_check.php");
	include_once("../../common/user_set.php");

	$user = M('k_user',$db_config);
	if (!empty($_SESSION['uid'])) 
	{
	   $user_id = $_SESSION['uid'];
	   $user_data = $user->field('username,agent_id')->where("uid = '".$_SESSION['uid']."'")->find();
	}


	$leixing;
	if($_GET['style_v']=='lunp')
	{
		$style='lunp';
		$leixing='輪盤';
	}
	elseif($_GET['style_v']=='sb')
	{
		$style='sb';
		$leixing='篩寶';
	}
	elseif($_GET['style_v']=='lh')
	{
		$style='lh';
		$leixing='龍虎';
	}
	else
	{
		$style='bjl';
		$leixing='百家樂';
	}

	//会员设定
	$member_set_data = M('k_user_video_d_set',$db_config)->join('join k_user_video_set_name on k_user_video_set_name.id = k_user_video_d_set.video_set_name_id')->field('k_user_video_d_set.*,k_user_video_set_name.video_set_name,k_user_video_set_name.video_type_name as type')->where("k_user_video_d_set.uid = '".$_SESSION['uid']."'")->select();

	$aid = $user_data['agent_id'];//上级ID

	// 读取上级设定
	$level_set_data = M('k_user_agent_video_set',$db_config)->join('join k_user_video_set_name on k_user_video_set_name.id = k_user_agent_video_set.video_set_name_id')->field('k_user_agent_video_set.*,k_user_video_set_name.video_set_name')->where("k_user_agent_video_set.aid = '".$aid."'")->select();
	$set = get_mem_v_set($level_set_data,$member_set_data);
	$set = $set[$style];
?>


<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>Document</title>
		<link rel="stylesheet" href="../public/css/index_main.css" />
		<link rel="stylesheet" href="../public/css/standard.css" />
	</head>
	<body style="BACKGROUND: url(../public/images/content_bg.jpg) repeat-y left top;" >
		<div id="MAMain" style="width:767px">
			<div id="MACenter-content">
				<div id="MACenterContent">
					<div id="MNav"> 
						<a  target="k_memr" class="mbtn" href="userinfo.php">基本资讯</a>
						<div class="navSeparate"></div>
						<span class="mbtn">投注资讯</span> 
					</div>
					<div class="MNavLv2"> 
						<span class="MGameType MCurrentType" ><a target="k_memr"  href="sport.php">体育</a></span>｜ 
						<span class="MGameType " ><a target="k_memr"  href="lottery_info.php">彩票</a></span>｜ 
						<span class="MGameType "><a target="k_memr"  href="live_info.php">视讯</a></span> 
					</div>
					<div id="MMainData">
						<div class="MControlNav">
							<select name="lx" id="lx" class="MFormStyle"  onchange="document.location=this.value">
								<option value="live_info.php?style_v=bjl&stype=video&aid=<?=$_SESSION['uid']?>" <? if($style=='bjl'){ echo 'selected';}?>>百家樂</option>
								<option value="live_info.php?style_v=lunp&stype=video&aid=<?=$_SESSION['uid']?>" <? if($style=='lunp'){ echo 'selected';}?>>輪盤</option>
								<option value="live_info.php?style_v=sb&stype=video&aid=<?=$_SESSION['uid']?>" <? if($style=='sb'){ echo 'selected';}?>>篩寶</option>
								<option value="live_info.php?style_v=lh&stype=video&aid=<?=$_SESSION['uid']?>" <? if($style=='lh'){ echo 'selected';}?>>龍虎</option>
							</select>
						</div>
						<div class="MPanel"   style=" overflow-y:scroll; height:315px;">
							<table class="MMain" border="1">
								<tbody>
								<tr align="center">
									<td class="">退水设定</td>
									<td colspan="2" class="">
									<?php 
									foreach ($set as $k => $v) {
									if(!empty($v['water_break'])){
									echo $v['water_break'];
									}break;
									}
									?>
									</td>
								</tr>
								<tr>
									<th><?=$leixing; ?></th>
									<th>单注最低限额</th>
									<th>单注最高限额</th>
								</tr>
								<?php if(!empty($set))
								{
								foreach($set as $v){
								if($v['video_type_name']==$style&&$v['video_set_name']!='會員退水'){
								?>
								<tr>
									<td width="90" height="25" align="center" class="m_over_co_ed"><?=$v['video_set_name']?></td>
									<td align="center"><?=$v['single_min']?></td>
									<td align="center"><?=$v['single_max']?></td>
								</tr>
								<?}}}?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="clear"></div>
	</body>
</html>
