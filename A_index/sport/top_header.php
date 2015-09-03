<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>welcome</title>
<link rel="stylesheet" href="public/css/standard.css" type="text/css">
<link rel="stylesheet" href="public/css/mem_header.css" type="text/css">
<script language="JavaScript" src="public/js/header.js"></script> 
<script language="JavaScript" src="public/js/jquery-1.7.2.min.js"></script> 
<script language="JavaScript" src="public/js/jquery-1.8.3.min.js"></script> 
<script language="JavaScript" src="public/js/tab.js"></script>
<script type="text/javascript">
    var mddate="<?=date('Y/m/d H:i:s')?>";
    var dd2=new Date(mddate);
    $(document).ready(function () {
        setInterval("RefTime()",1000);
    })



    function RefTime()
    {

        dd2.setSeconds(dd2.getSeconds()+1);
        var myYears = ( dd2.getYear() < 1900 ) ? ( 1900 + dd2.getYear() ) : dd2.getYear();
        $("#EST_reciprocal").html('美東時間'+'：'+myYears+'年'+fixNum(dd2.getMonth()+1)+'月'+fixNum(dd2.getDate())+'日 '+time(dd2));
    }
    function time(vtime){
        var s='';
        var d=vtime!=null?new Date(vtime):new Date();
        with(d){
            s=fixNum(getHours())+':'+fixNum(getMinutes())+':'+fixNum(getSeconds())
        }
        return(s);
    }
    function fixNum(num){
        return parseInt(num)<10?'0'+num:num;
    }

</script>

</head>
<?
	include_once("../include/private_config.php");
$user_data='';
	$user_id = @$_SESSION['uid'];
if($user_id){
    $sql="select username,money from k_user where uid='".$user_id."' and site_id='".SITEID."'";
    $query=$mysqlt->query($sql);
    $user_data = $query->fetch_array();//print_r($user_data);exit;
}

?>
<body id="HFT"  onselectstart="self.event.returnValue=false" marginwidth="0" marginheight="0">
	<div id="container">
		<input type="hidden" id="uid" name="uid" value="guest">
		<input type="hidden" id="langx" name="langx" value="zh-cn">
		<div id="userInfo">
			<div class="menu_sub_001">
				<a <? if(@$_SESSION['uid']){echo 'href="account_history.php"';}else{?>onclick='alert("请先登录！")'<?}?>" target="mainFrame">帐户历史</a>|
				<a <? if(@$_SESSION['uid']){echo 'href="note_list.php"';}else{?>onclick='alert("请先登录！")'<?}?>"  target="mainFrame">交易状况</a>
			</div>
			<div id="credit_main" style="display:<?if(@$_SESSION['uid']){echo 'block';}else{echo 'none';}?>"> <span id="credit"><span id="lbbb"><?=@$user_data['money']?> RMB</span></span>
				<input name="" type="button" class="re_credit" value="" onclick="getLeftJSON()">
			</div>
		</div>
		<!-------------------------------------------------------------------------- Today Menu Start  今日赛事 -->
		<div id="nav" class="nav">
			<ul class="level1">
			<script language="JavaScript">
			$(document).ready(function(){
				$(".colors").click(function(){//alert(22); 
					$(".colors").css('color','#fff');
					$(this).css('color','#F9C100');
				})
			})
			$(document).ready(function(){
				$(".color").click(function(){//alert(22); 
					$(".color").css('color','#fff');
					$(this).css('color','#F9C100');
				})
			})
			</script>
			
			
			
				<li class="ft" id="FT"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('ft','today');chg_index('football');">足球(<strong id="s_zq" class="game_sum">0</strong>)</a></span></li>
				<li class="bk" id="BK"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('bk','today');chg_index('bk_danshi');"><!--篮球(<strong id="s_lm" class="game_sum">32</strong>)-->
				篮球
				<span class="ball_nf"></span>
				美式足球
				<span class="ball_rl"></span>
				橄榄球(
				<strong id="s_lm" class="game_sum">0</strong>
				)
				</a></span></li>
				<li class="tn" id="TN"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('tn','today');chg_index('tennis_danshi');">网球(<strong id="s_wq" class="game_sum">0</strong>)</a></span></li>
				<li class="vb" id="VB"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('vb','today');chg_index('volleyball');"><!--排球<span id="s_pq" class="ball_bad">1</span>-->
				排球
				<span class="ball_bad"></span>
				羽毛球
				<span class="ball_tt"></span>
				乒乓球(
				<strong id="s_pq" class="ball_bad">0</strong>
				)
				</a></span></li>
				<li class="bs" id="BS"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('bs','today');chg_index('baseball');">棒球(<strong id="s_bq" class="game_sum">0</strong>)</a></span></li>
			</ul>
		</div>
		<div id="soprt_type_today" style="none"></div>

        <!-- 今日赛事运动类型 -->
		<div id="type" class="type">
			<!--足球-->
			<ul id="today_ft" style="display:block;">

			   <li class="r" id="today_1" style="display:block"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','1');chg_index('football');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
			

			   <li class="all" id="today_2"><a href="javascript:void(0)" class="type_out colors" id="all_class" onclick="chg_type('today','','2');chg_index('ft_bodan')">波胆</a></li>

			   <li class="p3" id="today_3"><a href="javascript:void(0)" class="type_out colors" id="p3_class" onclick="chg_type('today','','3');chg_index('ft_ruqiushu');">总入球</a></li>
			   
			  <li class="nfs" id="today_4"><a href="javascript:void(0)" class="type_out colors" id="all_class" onclick="chg_type('today','','4');chg_index('ft_banquanchang');">半场/全场</a></li>
			   
			
			   
			    <li class="nfs" id="today_4"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','4');chg_index('football_c');">综合过关</a></li>

			   <li class="nfs" id="today_4"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','4');chg_index('guanjun');">冠军</a></li>

			   <li class="nfs" id="today_10"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','10');chg_index('../Result/FootBall');">赛果</a></li>
			   
			   </ul>
			<!--篮球-->   
			<ul id="today_bk" style="display:none;">   
			    <li class="r" id="today_31"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','31');chg_index('bk_danshi');">独赢 & 让球 & 大小 & 单 / 双</a></li>

				 <li class="nfs" id="today_4"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','4');chg_index('bk_danshisc');">综合过关</a></li>

			   <li class="nfs" id="today_11"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','11');chg_index('../Result/BasketBall');">赛果</a></li>
			</ul>
			<!--网球--> 
			<ul id="today_tn" style="display:none;">
				<li class="nfs" id="today_12"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','12');chg_index('tennis_danshi');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
				
 	<li class="nfs" id="today_13"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','13');chg_index('../Result/Tennis');">赛果</a></li>
			</ul>
			
			<!--排球--> 
			<ul id="today_vb" style="display:none;">
				<li class="nfs" id="today_32"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','32');chg_index('volleyball');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
				
			 	<li class="nfs" id="today_14"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','14');chg_index('../Result/VolleyBall');">赛果</a></li>
			</ul>
			<!--棒球--> 
			<ul id="today_bs" style="display:none;">
				<li class="nfs" id="today_33"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','33');chg_index('baseball');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
				
			 	<li class="nfs" id="today_15"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','15');chg_index('../Result/BaseBall');">赛果</a></li>
			</ul>
			<!--其他-->
			<ul id="today_op" style="display:none;">
			
				<li class="nfs" id="today_33"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','33');chg_index('qita');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
				
				 <li class="nfs" id="today_4"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','4');chg_index('qita');">综合过关</a></li>

				<li class="nfs" id="today_4"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','4');chg_index('guanjun');">冠军</a></li>
				
				<li class="nfs" id="today_15"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','15');chg_index('qita_match');">赛果</a></li>
			</ul>
		</div>
		<!-- Today Menu end -->
		<!----- 滚球Menu Start //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
		<div id="nav_re" class="nav_re" style="display: none;">
				<ul class="level1">
				<li class="ft" id="FT"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('ft','rb');chg_indexs('grounder','ft_gunqiu');">足球(<strong id="s_zqgq" class="game_sum">0</strong>)</a></span></li>
				<li class="bk" id="BK"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('bk','rb');chg_indexs('grounder','bk_gunqiu');"><!--篮球(<strong id="s_lm" class="game_sum">32</strong>)-->
				篮球
				<span class="ball_nf"></span>
				美式足球
				<span class="ball_rl"></span>
				橄榄球(
				<strong id="s_lmgq" class="game_sum">0</strong>
				)
				</a></span></li>
				<li class="tn" id="TN"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('tn','rb');chg_indexs('today','tennis_danshi');">网球(<strong id="s_wq" class="game_sum">0</strong>)</a></span></li>
				<li class="vb" id="VB"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('vb','rb');chg_indexs('today','volleyball');"><!--排球<span id="s_pq" class="ball_bad">1</span>-->
				排球
				<span class="ball_bad"></span>
				羽毛球
				<span class="ball_tt"></span>
				乒乓球(
				<strong id="s_pq" class="ball_bad">0</strong>
				)
				</a></span></li>
				<li class="bs" id="BS"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('bs','rb');chg_indexs('today','baseball');">棒球(<strong id="s_bq" class="game_sum">0</strong>)</a></span></li>

			</ul>  
		</div>
		<div id="soprt_type_grounder" style="none"></div><!-- 滚球运动类型 -->
		<!--足球-->
		<div id="type_re" class="type_re" style="display: none;">
			<ul id="rb_ft" style="display:block;">
			   <li class="r" id="today_1"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_indexs('grounder','ft_gunqiu');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
			   <li class="nfs" id="today_10"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','10');chg_indexs('grounder','../Result/FootBall');">赛果</a></li>
			   </ul>
			<!--篮球-->
			<ul id="rb_bk" style="display:none;">
			    <li class="r" id="today_1"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_indexs('grounder','bk_gunqiu');">让球 & 大小</a></li>
			   <li class="nfs" id="today_10"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','10');chg_indexs('grounder','../Result/BasketBall');">赛果</a></li>
			</ul>
			<!--网球-->
			<ul id="rb_tn" style="display:none;">
				<li class="r" id="today_1"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','1');chg_indexs('today','tennis_danshi');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
			   <li class="nfs" id="today_10"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','10');chg_indexs('today','../Result/Tennis');">赛果</a></li>
			</ul>
			<!--排球-->
			<ul id="rb_vb" style="display:none;">
				<li class="r" id="today_1"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','1');chg_indexs('today','volleyball');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
			   <li class="nfs" id="today_10"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','10');chg_indexs('today','../Result/VolleyBall');">赛果</a></li>
			</ul>
			<!--棒球-->
			<ul id="rb_bs" style="display:none;">
				<li class="r" id="today_1"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','1');chg_indexs('today','baseball');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
			   <li class="nfs" id="today_10"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','10');chg_indexs('today','../Result/BaseBall')">赛果</a></li>
			</ul>
		</div>
		<!-- 滚球Menu End//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
		<!------- 早盘 -------->
		<div id="nav_mor"   class="nav_re" class="rbmenu" style="display: none;">
				<ul class="level1">

				<li class="ft" id="FT"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('ft','mor');chg_indexs('morning','football');">足球(<strong id="s_zqzc_ds" class="game_sum">0</strong>)</a></span></li>
				<li class="bk" id="BK"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('bk','mor');chg_indexs('morning','bk_danshi');"><!--篮球(<strong id="s_lm" class="game_sum">32</strong>)-->
				篮球
				<span class="ball_nf"></span>
				美式足球
				<span class="ball_rl"></span>
				橄榄球(
				<strong id="s_lmzc" class="game_sum">0</strong>
				)
				</a></span></li>
				<li class="tn" id="TN"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('tn','mor');chg_indexs('morning','tennis_danshi');">网球(<strong id="s_wq" class="game_sum">0</strong>)</a></span></li>
				<li class="vb" id="VB"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('vb','mor');chg_indexs('morning','volleyball');"><!--排球<span id="s_pq" class="ball_bad">1</span>-->
				排球
				<span class="ball_bad"></span>
				羽毛球
				<span class="ball_tt"></span>
				乒乓球(
				<strong id="s_pq" class="ball_bad">0</strong>
				)
				</a></span></li>
				<li class="bs" id="BS"><span class="ball"><a class="type_out color" href="javascript:void(0)" onclick="change('bs','mor');chg_indexs('morning','baseball');">棒球(<strong id="s_bq" class="game_sum">0</strong>)</a></span></li>
				

			</ul>	
		</div>
		<div id="soprt_type_morning" style="none"></div>

        <!-- 早盘运动类型 -->
		<div id="type_mor"   class="type_re" style="display: none;">
			<!--足球-->
			<ul id="mor_ft" style="display:block;">
			   <li class="r" id="today_1" style="display:block"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','1');chg_indexs('morning','football');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
			

			   <li class="all" id="today_2"><a href="javascript:void(0)" class="type_out colors" id="all_class" onclick="chg_type('today','','2');chg_indexs('morning','ft_bodan')">波胆</a></li>

			   <li class="p3" id="today_3"><a href="javascript:void(0)" class="type_out colors" id="p3_class" onclick="chg_type('today','','3');chg_indexs('morning','ft_ruqiushu');">总入球</a></li>

			   <li class="nfs" id="today_4"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','4');chg_indexs('morning','ft_banquanchang');">半场/全场</a></li>
			   
			    <li class="p3" id="today_3"><a href="javascript:void(0)" class="type_out colors" id="p3_class" onclick="chg_type('today','','3');chg_indexs('morning','football_c');">综合过关</a></li>

			   <li class="nfs" id="today_4"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','4');chg_indexs('morning','guanjun');chg_type_class('nfs_class')">冠军</a></li>

			   <li class="nfs" id="today_10"><a href="javascript:void(0)" class="type_out" id="nfs_class" onclick="chg_type('today','','10');chg_indexs('morning','../Result/FootBall');">赛果</a></li>
			   
			   </ul>
			 <!--篮球-->  
			<ul id="mor_bk" style="display:none;">   
			   <li class="r" id="today_1" style="display:block"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','1');chg_indexs('morning','bk_danshi');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
			
			    <li class="p3" id="today_3"><a href="javascript:void(0)" class="type_out colors" id="p3_class" onclick="chg_type('today','','3');chg_indexs('morning','bk_danshisc');">综合过关</a></li>

			   <li class="nfs" id="today_10"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','10');chg_indexs('morning','../Result/BasketBall');">赛果</a></li>
			</ul>
			<!--网球-->
			<ul id="mor_tn" style="display:none;">
				<li class="r" id="today_1" style="display:block"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','1');chg_indexs('morning','tennis_danshi');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>
				

				<li class="nfs" id="today_13"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','13');chg_indexs('morning','../Result/Tennis')">赛果</a></li>
			</ul>
			<!--排球-->
			<ul id="mor_vb" style="display:none;">
				<li class="r" id="today_1" style="display:block"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','1');chg_indexs('morning','volleyball');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>

				<li class="nfs" id="today_14"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','14');chg_indexs('morning','../Result/VolleyBall');">赛果</a></li>
			</ul>
			<!--棒球-->
			<ul id="mor_bs" style="display:none;">
				<li class="r" id="today_1" style="display:block"><a href="javascript:void(0)" class="type_on colors" id="r_class" onclick="chg_type('today','','1');chg_indexs('morning','baseball');">独赢 & 让球 & 大小 &amp; 单 / 双</a></li>

				<li class="nfs" id="today_15"><a href="javascript:void(0)" class="type_out colors" id="nfs_class" onclick="chg_type('today','','15');chg_indexs('morning','../Result/BaseBall');">赛果</a></li>
			</ul>


			
		</div>	

			<!------ 早餐完 -->



		<div id="topMenu">
			<ul id="back">
				<li class="help" onmouseover="OnMouseOverEvent();"><a href="javascript:void(0)" style="cursor:hand">帮助</a><span></span></li>
				
			</ul>
			<div class="info" id="informaction" onmouseover="OnMouseOverEvent()">
				<ul id="mose" onmouseout="OnMouseOutEvent();">
					<li class="help_on"><a href="javascript:void(0)">帮助</a></li>
					<li class="msg"><a href="javascript:void(0)" onclick="winOpen('help/announcement.php',650,600,null,null,'规则说明')">&nbsp; 公告栏</a></li>
					<li class="roul"><a href="javascript:void(0)" onclick="winOpen('help/1.html',805,674,null,null,'规则说明')">&nbsp; 规则说明</a></li>
					<li class="odd"><a href="javascript:void(0)" onclick="winOpen('help/way.html',620,602,null,null,'盘口使用方法')">&nbsp; 盘口使用方法</a></li>
				</ul>
			</div>
		</div>
	</div>
	<div id="welcome">
		<div class="name"> 
			<strong>您好 <span id="username"><?=@$_SESSION['username']?></span></strong><br>
			<span id="EST_reciprocal"></span>
		</div>
		<ul id="head_nav" class="level1">
			<li class="rb" id="rb_btn" style="visibility: visible;"> <a href="javascript:void(0)" onclick="chg_button_bg('FT','rb');chg_indexs('grounder','ft_gunqiu'); " id="rbyshow">滚球</a> </li>

			<li class="today_on" id="today_btn"><a href="javascript:void(0)" onclick="chg_button_bg('FT','today');chg_head('today','football.php');">今日赛事</a></li>

			<li class="early" id="early_btn"><a href="javascript:void(0)" onclick="chg_button_bg('FT','early');chg_index_head('morning','football.php'); ">早盘</a></li>
		</ul>
	</div>

<iframe id="reloadPHP" name="reloadPHP" style="display:none" width="0" height="0"></iframe>
<iframe id="reCrdit_frame" name="reCrdit_frame" width="0" height="0"></iframe>
</body></html>
<script>

function getLeftJSON(){
	     $.getJSON("leftDao.php?callback=?",function(json){

                 $("#s_zqgq").html(json.zq_gq);
                 $("#s_lmgq").html(json.lm_gq);
                 $("#s_zq").html(json.zq_ds);
                 $("#s_zq_ds").html(json.zq_ds);
                 $("#s_lm").html(json.lm_ds);


					$("#s_zq_gq").html(json.zq_gq);
					$("#s_zq_bd").html(json.zq_bd);
					$("#s_zq_rqs").html(json.zq_rqs);
					$("#s_zq_bqc").html(json.zq_bqc);
					$("#s_zq_jg").html(json.zq_jg);
					$("#s_zqzc").html(json.zqzc);
					$("#s_zqzc_ds").html(json.zqzc_ds);
					$("#s_zqzc_bd").html(json.zqzc_bd);
					$("#s_zqzc_rqs").html(json.zqzc_rqs);
					$("#s_zqzc_bqc").html(json.zqzc_bqc);

					$("#s_lm_ds").html(json.lm_ds);
					$("#s_lm_dj").html(json.lm_dj);
					$("#s_lm_gq").html(json.lm_gq);
					$("#s_lm_jg").html(json.lm_jg);
					$("#s_lmzc").html(json.lmzc);
					$("#s_lmzc_ds").html(json.lmzc_ds);
					$("#s_lmzc_dj").html(json.lmzc_dj);
					$("#s_wq").html(json.wq_ds);
					$("#s_wq_ds").html(json.wq_ds);
					$("#s_wq_bd").html(json.wq_bd);
					$("#s_wq_jg").html(json.wq_jg);
					$("#s_pq").html(json.pq);
					$("#s_pq_ds").html(json.pq_ds);
					$("#s_pq_bd").html(json.pq_bd);
					$("#s_pq_jg").html(json.pq_jg);
					$("#s_bq").html(json.bq);
					$("#s_bq_ds").html(json.bq_ds);
					$("#s_bq_zdf").html(json.bq_zdf);
					$("#s_bq_jg").html(json.bq_jg);
					$("#s_jr").html(json.jr);
					$("#s_jr_jr").html(json.jr_jr);
					$("#s_jr_jg").html(json.jr_jg);
					$("#s_gj").html(json.gj);
					$("#s_gj_gj").html(json.gj_gj);
					$("#s_gj_jg").html(json.gj_jg);
				
					$("#user_money,#lbbb").html(json.user_money);
					$("#f5").css("display","");
					$("#cg_f").html((parseInt(json.zq_ds)+parseInt(json.lm_ds)));
					$("#cg_f1").html((parseInt(json.zqzc_ds)+parseInt(json.lmzc_ds)));
					$("#cg_f_0").html(json.zq_ds);
					$("#cg_f_2").html(json.lm_ds);
					$("#cg_f1_0").html(json.zqzc_ds);
					$("#cg_f1_2").html(json.lmzc_ds);
					
			  }
		);
		setTimeout("getLeftJSON()",13000);
}
setTimeout("getLeftJSON()",1000);
</script>