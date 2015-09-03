<?php

    include_once("../../include/config.php");
    include_once("../../common/login_check.php");
	$userinfo = '';

    $userinfo = M('k_user',$db_config)->field('uid,agent_id')->where("uid = '".$_SESSION['uid']."'")->find();

    $spTitle = array('ft'=>'足球','bk'=>'篮球','vb'=>'排球',
    		'bs'=>'棒球','tn'=>'网球'
    );
    //站点体育退水限额
    $spArr = array('ft','bk','vb','bs','tn');
    $Spgame = M('sp_games_view',$db_config);
    $Uagent = M('k_user_agent_sport_set',$db_config);
    $Uaset = M('k_user_sport_set',$db_config);
    
    foreach ($spArr as $key => $val) {
    	$tmpA = $tmpB = array();
    	$tmpA = $Spgame->join("join k_user_agent_sport_set on k_user_agent_sport_set.type_id = sp_games_view.id")->where("sp_games_view.type = '".$val."' and k_user_agent_sport_set.aid = '".$userinfo['agent_id']."'")->select("t_type");
    	
    	$tmpB= $Spgame->join("join k_user_sport_set on k_user_sport_set.type_id = sp_games_view.id")->where("sp_games_view.type = '".$val."' and k_user_sport_set.uid = '".$userinfo['uid']."'")->select("t_type");
    	$spType[$val] = array_merge($tmpA,$tmpB);
    }
    $typeC = 'sp';
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
				<div id="MNav"> <a target="k_memr" class="mbtn" href="userinfo.php">基本资讯</a>
					<div class="navSeparate"></div>
					<span class="mbtn">投注资讯</span>
				</div>
				<div class="MNavLv2">
					<span class="MGameType MCurrentType"><a target="k_memr" href="sport.php" style="background:#bc5a83 none repeat scroll 0 0;color:#ffffff;padding:2px">体育</a></span>｜ <span class="MGameType " ><a target="k_memr" href="lottery_info.php">彩票</a></span>
				</div>
				<div id="MMainData" style="overflow-y:auto;height:340px;">
					<div class="MControlNav"></div>
					<div class="MPanel" style="display: block;width:850px">
					<?php foreach ($spType as $key => $spval): ?>
						<table class="MMain" border="1" width="850px">
							<tbody>
							    <tr class="m_title_over_co">
							    <td height="25" align="center" style="background:#dfdfdf none repeat scroll 0 0"><?=$spTitle[$key]?></td>
							    <?php foreach ($spval as $k => $v): ?>
							      <td align="center" style="background:#dfdfdf none repeat scroll 0 0"><?=$v['t_name']?></td>
							    <?php endforeach ?>
							  </tr>
							  <tr>
							   <td nowrap="" align="center">退水設定</td>
							       <?php foreach ($spval as $k => $v): ?>
							      <td align="center"><?=$v['water_break']?></td>
							    <?php endforeach ?>
							  <tr>
							   <tr>
							   <td nowrap="" align="center">單場限額:</td>
							       <?php foreach ($spval as $k => $v): ?>
							      <td align="center"><?=$v['single_field_max']?></td>
							    <?php endforeach ?>
							  <tr>
							     <tr>
								   <td nowrap="" align="center">單注限額::</td>
								       <?php foreach ($spval as $k => $v): ?>
								      <td align="center"><?=$v['single_note_max']?></td>
								    <?php endforeach ?>
								  <tr>
								 <tr>
								   <td nowrap="" align="center">最低限额::</td>
								       <?php foreach ($spval as $k => $v): ?>
								      <td align="center"><?=$v['min']?></td>
								    <?php endforeach ?>
								  <tr>
							</tbody>
						</table>
						<br>
						    <?php endforeach ?>
					</div>
				</div>
			</div>
        </div>
    </div>
</body>
</html>