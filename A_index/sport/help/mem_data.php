<?php
include_once("../../include/config.php");
include_once("../../include/private_config.php");
include_once("../../lib/class/model.class.php");
include_once("../../929ht/common/user_set.php");

$user = M('k_user',$db_config);
if (!empty($_SESSION['uid'])) {
   $user_id = $_SESSION['uid'];
   $user_data = $user->field('username,money,agent_id')->where("uid = '".$_SESSION['uid']."'")->find();
}

//会员设定
$member_set_data = M('k_user_sport_d_set',$db_config)->join('join k_user_sport_set_name on k_user_sport_set_name.id = k_user_sport_d_set.sport_set_name_id')->field('k_user_sport_d_set.*,k_user_sport_set_name.sport_set_name')->where("k_user_sport_d_set.uid = '".$_GET['uid']."'")->select();
$agent_id = $user_data['agent_id'];//会员所在
//会员等级详细设定

$level_set_data = M('k_user_agent_sport_set',$db_config)->join('join k_user_sport_set_name on k_user_sport_set_name.id = k_user_agent_sport_set.sport_set_name_id')->field('k_user_agent_sport_set.*,k_user_sport_set_name.sport_set_name')->where("k_user_agent_sport_set.aid = '".$agent_id."'")->select();

$set = get_mem_s_set($level_set_data,$member_set_data);

//print_r($set);exit;
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>mem_data</title>

<link rel="stylesheet" href="../public/css/mem_body_ft.css" type="text/css">
<link rel="stylesheet" href="../public/css/scroll.css" type="text/css">
</head>
<body id="MFT">
<script language="javascript">
// function Go_Chg_pass(){
// 	Real_Win=window.open("chg_passwd.php?uid=9526d3a80cf860fa09901","Chg_pass","width=490,height=300,status=no");
// }
// function getgrpdomain(){
// 	Real_Win=window.open("grpdomain.php?uid=9526d3a80cf860fa09901","grpdomain","width=450,height=600,status=no");
// }
// </script>
<table border="0" cellpadding="0" cellspacing="0" id="box" style="width:560px">
  <tbody><tr>
    <td class="top" style="text-align:center"><h1><b style="background:url('');">会员资料</b></h1></td>
  </tr>
  <tr><td class="mem"><!--
  <h2>
      <table width="100%" border="0" cellspacing="0" cellpadding="0" id="fav_bar">
        <tbody><tr>
			<td id="page_no">
			   <span id="pg_txt"><input type="button" name="Submit323" value="修改密码" onclick="Go_Chg_pass();" class="ccroll_btn">
			input type="button" name="grpdomain" value="即时资讯" onClick="getgrpdomain();" class="ccroll_btn"
</span> 
			   <span style="display: none;" id="t_pge"></span>
			</td>

                 </tr>
      </tbody></table>
    </h2>-->
    
			<table border="0" cellspacing="0" cellpadding="0" class="game">
               <tbody><tr class="b_rig">
					<td width="20%">会员帐号</td>
					<td colspan="6" style="text-align:left"><?=$user_data['username']?></td>
				  </tr>
				  <tr class="b_rig">
					<td>现金额度</td>
					<td colspan="6" style="text-align:left"><?=$user_data['money']?></td>
				  </tr>
				  <form method="post" onsubmit="return SubChk()"></form>
				  <tr>
					<td colspan="7" class="b_hline">单场限额</td>
				  </tr>
                  <tr>
                    <th>&nbsp;</th>
                    <th width="14%">足球</th>
                    <th width="14%">篮球</th>
                    <th width="14%">网球</th>
                    <th width="14%">排球</th>
                    <th width="14%">棒球</th>
                  </tr>
                  <tr class="b_rig">
                    <td class="">单式让球</td>
                    <td class=""><?=$set['zq']['a']['single_field_max']?></td>
                    <td class=""><?=$set['lq']['a']['single_field_max']?></td>
                    <td class=""><?=$set['wq']['a']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['a']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['a']['single_field_max']?></td>
                    
                  </tr>
                  <tr class="b_rig">
                    <td class="">单式大小</td>
                     <td class=""><?=$set['zq']['b']['single_field_max']?></td>
                    <td class=""><?=$set['lq']['b']['single_field_max']?></td>
                    <td class=""><?=$set['wq']['b']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['b']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['b']['single_field_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td class="">单式独赢</td>
                    <td class=""><?=$set['zq']['f']['single_field_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['f']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['f']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['f']['single_field_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td class="">滚球</td>
                    <td class=""><?=$set['zq']['c']['single_field_max']?></td>
                    <td class=""><?=$set['lq']['c']['single_field_max']?></td>
                    <td class=""><?=$set['wq']['c']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['c']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['c']['single_field_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td class="">滚球大小</td>
					<td class=""><?=$set['zq']['d']['single_field_max']?></td>
                    <td class=""><?=$set['lq']['d']['single_field_max']?></td>
                    <td class=""><?=$set['wq']['d']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['d']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['d']['single_field_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td class="">滚球独赢</td>
                    <td class=""><?=$set['zq']['h']['single_field_max']?></td>
                    <td class="">*</td>
                    <td class="">*</td>
                    <td class="">*</td>
                    <td class="">*</td>
                  </tr>
                  <tr class="b_rig">
                    <td>标准过关</td>
                    <td class=""><?=$set['zq']['i']['single_field_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['i']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['i']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['i']['single_field_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>让球过关</td>
                    <td class=""><?=$set['zq']['g']['single_field_max']?></td>
                    <td class=""><?=$set['lq']['g']['single_field_max']?></td>
                    <td class=""><?=$set['wq']['g']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['g']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['g']['single_field_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>综合过关</td>
                    <td class=""><?=$set['zq']['j']['single_field_max']?></td>
                    <td class=""><?=$set['lq']['j']['single_field_max']?></td>
                    <td class=""><?=$set['wq']['j']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['j']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['j']['single_field_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>波胆</td>
                    <td class=""><?=$set['zq']['k']['single_field_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['k']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['k']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['k']['single_field_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>入球数</td>
                    <td class=""><?=$set['zq']['l']['single_field_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['l']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['l']['single_field_max']?></td>
                    <td class="">*</td>
                  </tr>
                  <tr class="b_rig">
                    <td>单双</td>
                    <td class=""><?=$set['zq']['e']['single_field_max']?></td>
                    <td class=""><?=$set['lq']['e']['single_field_max']?></td>
                    <td class=""><?=$set['wq']['e']['single_field_max']?></td>
                    <td class=""><?=$set['pq']['e']['single_field_max']?></td>
                    <td class=""><?=$set['bq']['e']['single_field_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>半全场</td>
                    <td class=""><?=$set['zq']['m']['single_field_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['m']['single_field_max']?></td>
                    <td class="">*</td>
                    <td class="">*</td>
                  </tr>
                  <tr>
                    <td colspan="7" class="b_hline">单注限额</td>
                  </tr>
                  <tr>
                    <th>&nbsp;</th>
                    <th>足球</th>
                    <th>篮球</th>
                    <th>网球</th>
                    <th>排球</th>
                    <th>棒球</th>
                  </tr>
                  <tr class="b_rig">
                    <td class="">单式让球</td>
                    <td class=""><?=$set['zq']['a']['single_note_max']?></td>
                    <td class=""><?=$set['lq']['a']['single_note_max']?></td>
                    <td class=""><?=$set['wq']['a']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['a']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['a']['single_note_max']?></td>
                    
                  </tr>
                  <tr class="b_rig">
                    <td class="">单式大小</td>
                     <td class=""><?=$set['zq']['b']['single_note_max']?></td>
                    <td class=""><?=$set['lq']['b']['single_note_max']?></td>
                    <td class=""><?=$set['wq']['b']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['b']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['b']['single_note_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td class="">单式独赢</td>
                    <td class=""><?=$set['zq']['f']['single_note_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['f']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['f']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['f']['single_note_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td class="">滚球</td>
                    <td class=""><?=$set['zq']['c']['single_note_max']?></td>
                    <td class=""><?=$set['lq']['c']['single_note_max']?></td>
                    <td class=""><?=$set['wq']['c']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['c']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['c']['single_note_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td class="">滚球大小</td>
					<td class=""><?=$set['zq']['d']['single_note_max']?></td>
                    <td class=""><?=$set['lq']['d']['single_note_max']?></td>
                    <td class=""><?=$set['wq']['d']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['d']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['d']['single_note_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td class="">滚球独赢</td>
                    <td class=""><?=$set['zq']['h']['single_note_max']?></td>
                    <td class="">*</td>
                    <td class="">*</td>
                    <td class="">*</td>
                    <td class="">*</td>
                  </tr>
                  <tr class="b_rig">
                    <td>标准过关</td>
                    <td class=""><?=$set['zq']['i']['single_note_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['i']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['i']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['i']['single_note_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>让球过关</td>
                    <td class=""><?=$set['zq']['g']['single_note_max']?></td>
                    <td class=""><?=$set['lq']['g']['single_note_max']?></td>
                    <td class=""><?=$set['wq']['g']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['g']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['g']['single_note_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>综合过关</td>
                    <td class=""><?=$set['zq']['j']['single_note_max']?></td>
                    <td class=""><?=$set['lq']['j']['single_note_max']?></td>
                    <td class=""><?=$set['wq']['j']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['j']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['j']['single_note_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>波胆</td>
                    <td class=""><?=$set['zq']['k']['single_note_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['k']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['k']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['k']['single_note_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>入球数</td>
                    <td class=""><?=$set['zq']['l']['single_note_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['l']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['l']['single_note_max']?></td>
                    <td class="">*</td>
                  </tr>
                  <tr class="b_rig">
                    <td>单双</td>
                    <td class=""><?=$set['zq']['e']['single_note_max']?></td>
                    <td class=""><?=$set['lq']['e']['single_note_max']?></td>
                    <td class=""><?=$set['wq']['e']['single_note_max']?></td>
                    <td class=""><?=$set['pq']['e']['single_note_max']?></td>
                    <td class=""><?=$set['bq']['e']['single_note_max']?></td>
                  </tr>
                  <tr class="b_rig">
                    <td>半全场</td>
                    <td class=""><?=$set['zq']['m']['single_note_max']?></td>
                    <td class="">*</td>
                    <td class=""><?=$set['wq']['m']['single_note_max']?></td>
                    <td class="">*</td>
                    <td class="">*</td>
                  </tr>
				
				  
                </tbody>
              </table>


</body></html>