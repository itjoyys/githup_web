<?php
	include_once("../../include/config.php");
	include_once("../../common/login_check.php");
	
	$userinfo = '';

    $userinfo = M('k_user',$db_config)->field('uid,agent_id')->where("uid = '".$_SESSION['uid']."'")->find();
    //标题
$fcTitle = array(
             'fc_3d'=>'福彩3D','pl_3'=>'排列三',
             'cq_ssc'=>'重庆时时彩','cq_ten'=>'重庆快乐十分',
             'gd_ten'=>'广东快乐十分','bj_8'=>'北京快乐8',
             'bj_10'=>'北京PK拾','tj_ssc'=>'天津时时彩',
             'xj_ssc'=>'新疆时时彩','jx_ssc'=>'江西时时彩',
             'jl_k3'=>'吉林快三','js_k3'=>'江苏快三',
             'liuhecai'=>'六合彩'
            );
//站点彩票退水限额
$fcArr = array('fc_3d','pl_3','cq_ssc','cq_ten','gd_ten','bj_8',
               'bj_10','tj_ssc','xj_ssc','jx_ssc','jl_k3','js_k3',
               'liuhecai'
               );
$Fcgame = M('fc_games_view',$db_config);
$Uagent = M('k_user_agent_fc_set',$db_config);
$Uaset = M('k_user_fc_set',$db_config);
foreach ($fcArr as $key => $val) {
     $tmpA = $tmpB = array();
     $tmpA = $Fcgame->join("join k_user_agent_fc_set on k_user_agent_fc_set.type_id = fc_games_view.id")->where("fc_games_view.fc_type = '".$val."' and k_user_agent_fc_set.aid = '".$userinfo['agent_id']."'")->select("type");
     $tmpB= $Fcgame->join("join k_user_fc_set on k_user_fc_set.type_id = fc_games_view.id")->where("fc_games_view.fc_type = '".$val."' and k_user_fc_set.uid = '".$userinfo['uid']."'")->select("type");
     $fcType[$val] = array_merge($tmpA,$tmpB);
  }
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
					<div class="MNavLv2"> <span class="MGameType MCurrentType">
						<a target="k_memr"  href="sport.php">体育</a></span>｜ <span class="MGameType "  ><a target="k_memr"  href="lottery_info.php" style="background:#bc5a83 none repeat scroll 0 0;color:#ffffff;padding:2px">彩票</a></span>
					</div>
					<div id="MMainData"style=" overflow-y:scroll; height:340px;">
						<div  class="MPanel" style="width:850px">
							<?php foreach ($fcType as $key => $fcval): ?>   
							<table border="1" class="MMain" width="850px">
								<tbody>
								<tr class="m_title_over_co">
									<td height="25" align="center" style="background:#dfdfdf none repeat scroll 0 0"><?=$fcTitle[$key]?></td>
									<?php foreach ($fcval  as $k => $v): ?>
									<td align="center" style="background:#dfdfdf none repeat scroll 0 0"><?=$v['name']?></td>
									<?php endforeach ?>
								</tr>
								<tr>
									<td nowrap="" align="center">退水設定</td>
									<?php foreach ($fcval  as $k => $v): ?>
									<td align="center"><?=$v['charges_a']?></td>
									<?php endforeach ?>
								<tr>
								<tr>
									<td nowrap="" align="center">單場限額:</td>
									<?php foreach ($fcval  as $k => $v): ?>
									<td align="center"><?=$v['single_field_max']?></td>
									<?php endforeach ?>
								<tr>
								<tr>
									<td nowrap="" align="center">單注限額::</td>
									<?php foreach ($fcval  as $k => $v): ?>
									<td align="center"><?=$v['single_note_max']?></td>
									<?php endforeach ?>
								<tr>
								<tr>
									<td nowrap="" align="center">最低限额::</td>
									<?php foreach ($fcval  as $k => $v): ?>
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