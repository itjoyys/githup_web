<?php
include_once("../include/config.php");
include_once("../include/public_config.php");

//判断彩种是否未付
$is_lock = M("fc_state",$db_config)->field("name")->where("state <> 1")->select();
$lock_arr=array();
foreach ($is_lock as $key => $value) {
	$lock_arr[]=$value['name'];
}

if(empty($_SESSION['uid']))
{
	echo '<script type="text/javascript">alert("您已经离线，请重新登陆");</script>';
	echo '<script type="text/javascript">top.location.href="/";</script>';
	exit;
}
//date_default_timezone_set(PRC);
 if ($_GET['type'] == 3 || $_GET['type'] == 9|| $_GET['type'] == 10|| $_GET['type'] == 11|| $_GET['type'] == 12) {
 	 $nav_id = 'ssc';
 }elseif ($_GET['type'] ==1 || $_GET['type'] == 2 || $_GET['type'] == 3) {
 	 $nav_id ='yb';
 }elseif ($_GET['type'] == 13 || $_GET['type'] == 14) {
 	 $nav_id ='k3';
 }elseif ($_GET['type'] == 4 || $_GET['type'] == 5) {
 	 $nav_id ='gpc';
 }elseif ($_GET['type'] == 7 || $_GET['type'] == 8) {
 	 $nav_id ='sf';
 }



?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>webcom</title>
<link rel="stylesheet" href="./public/css/reset.css" type="text/css">
<link rel="stylesheet" href="./public/css/xp.css" type="text/css">
<script src="./public/js/jquery-1.8.3.min.js" type="text/javascript">
</script>
<script src="./public/js/artDialog.js" type="text/javascript">
</script>
<script>
var lx_type="<?=@$_GET['type']?>";
var nav_id = "<?=@$_GET['nav_id']?>";
var lx='0';
var oldlx=1;
top.cgTypebtn="r_class";
/*$("#main").load(function(){
	var mainheight = $(this).contents().find("body").height()+30;
	$(this).height(mainheight);
	});

$("#topFrame").load(function(){
	var mainheight = $(this).contents().find("body").height()+30;
	$(this).height(mainheight);
	});
$("#right").load(function(){
	var mainheight = $(this).contents().find("body").height()+30;
	$(this).height(mainheight);
	});
*/
function nav_go(){

 if(self.frameElement.tagName=="IFRAME"){
// 页面在iframe中时处理
}
 if (lx_type != '') {
  	$("#game_1").hide();
	$("#game_"+lx_type).show();
	oldlx = lx_type;
	 var tmp=$("#game_"+lx_type+" li").first().find("a");
	 parent.k_memr.document.location=tmp.attr('href');
  };

}
</script>
</head>
<body onload="nav_go()" id="HOP" >
<link rel="stylesheet" href="./public/css/mem_header_ssc.css" type="text/css">
<style>
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	overflow:hidden;
}

</style>
<script language="JAVASCRIPT">
var _lx=0;

var def="1";
var uid="8c31beab693ed2ab09f9a";
var langx="zh-cn";
var goto=false;

window.status="皇家博彩投注系统";

var OldMenu=null;
function ChangeMenu(o)
{
	if(OldMenu!=null)
		OldMenu.attr('class',"menu2");
	var obj=$("#"+o);
	obj.attr('class',"menu1");
	OldMenu=obj;
}
function SelGame()
{
	var DG = new J.dialog({title:"其它玩法",iconTitle:false,maxBtn:false,btnBar:false,drag:true,bgcolor:'#000000',cover:true,id:'fd_quick',page:"game.php", width:400,height:300,lockScroll:false});
	DG.ShowDialog();
}

function SGameBack(lx,t)
{
	$("#game_"+oldlx).hide();
	var obj=$("#game_"+lx);
	obj.show();
	if (lx!='') {

			parent.k_meml.document.location='main_left.php?type_y='+lx;


	};
	lx_type='';

	oldlx=lx;
	var tmp=$("#game_"+lx+" li").first().find("a");
	//var tmp_href = tmp.attr(href);
	parent.k_memr.document.location=tmp.attr('href');
	//parent.k_meml.document.location='main_left.php?type='+lx;
	tmp.removeClass("colorNumSet").addClass("colorSet");
	$("#game_"+lx+" li a").not(tmp).removeClass("colorSet").addClass("colorNumSet");
	//if(t=="黑龙江时时彩") t="黑龙江";
	//$("#menu1").html(t);
}
String.prototype.rTrim=function(){    //去右空格
	  var re_r=/[\,]*$/
	  return this.replace(re_r,"")
	}
//获取类别
function game_type(v)
{
	var lock_arr = document.getElementById('lock_arr').innerHTML;
	lock_arr = lock_arr.rTrim();
	lock_arr = lock_arr.split(',');
	var a=v.split("|");
	for (var i = 0; i < lock_arr.length; i++) {
		if(lock_arr[i] == a[1]){
			alert("["+lock_arr[i]+"]  正在维护！");
			return false;
		}
	};
	SGameBack(a[0],a[1]);
}


function goto()
{
	var obj=$("#game_"+oldlx);
	parent.k_memr.document.location=obj.find("a").attr('href');
}




$(document).ready(function () {


	top.currNav = $("#nav_id").text();
	setClick();

	$("#"+top.currNav).click();
	setInterval("RefTime()",1000);
	change_a_bg("#type ul li a");
	change_a_bg(".ul_s1 li a");
	//$("#"+top.currNav).find("+ ul li a").first().click();

	if(def!="")
		{
			$("#game_menu_"+def).addClass("colorSet");
			// SGameBack(def,lx_title);
		}
		else
		{
			$("#nav ul li:eq(0)").find("a").addClass("colorSet");
			//OldMenu=$("#menu1");
			// SGameBack(_lx,lx_title);
		}



});


function setClick()
{
	$("#div_s1 > a").click(function () {
		$(this).removeClass("colorNumSet").addClass("colorSet").find("+ ul").animate({ width: 'show' });
		top.currNav = $(this).attr("id");

		$("#div_s1 > a").not($(this)).removeClass("colorSet").addClass("colorNumSet").find("+ ul").animate({ width: 'hide' });
	});
}


//设置美东时间
var mddate="<?php echo date('Y').'/'.date('m').'/'.date('d').' '.date('H').':'.date('i').':'.date('s');?>";
var dd2=new Date(mddate);
function RefTime()
{

    dd2.setSeconds(dd2.getSeconds()+1);
	var myYears = ( dd2.getYear() < 1900 ) ? ( 1900 + dd2.getYear() ) : dd2.getYear();
	$("#vlock").html('美東時間'+'：'+myYears+'年'+fixNum(dd2.getMonth()+1)+'月'+fixNum(dd2.getDate())+'日 '+time(dd2));
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
<script>
function change_a_bg(id){
		$(id).click(function () {
			$(this).removeClass("colorNumSet").addClass("colorSet");
			$(id).not($(this)).removeClass("colorSet").addClass("colorNumSet");
		});
}
</script>
<div id="container">
	<div id="userInfo">
		<div class="menu_sub_001"><a href="account_history.php" target="k_memr">帐户历史</a>|<a href="note_list.php?type_y=<?=$_GET['type']?>" target="k_memr">交易状况</a></div>
		<ul>
			<li><h3>欢迎回来:&nbsp;&nbsp;<span id="userName"><?=$_SESSION["username"]?></span></h3></li>
			<li class="timeLi"><span id="vlock"></span></li><br>

		</ul>
	</div>
  <div id="nav">
		<div id="div_s1" class="l_float">
			<a id="yb" class="a_s1 l_float colorSet" href="javascript:void(0)">一般彩球</a>
			<ul class="ul_s1 l_float">
				<li class="conl_s1"></li>

				<li><a href="javascript:void(0)" id="game_menu_1" onclick="game_type('1|福彩3D')" class="<?php if($_GET['type']==1){echo "colorSet";}else{echo "colorNumSet";} ?>">福彩3D</a></li>
				<li><a href="javascript:void(0)" id="game_menu_2" onclick="game_type('2|排列三')" class="<?php if($_GET['type']==2){echo "colorSet";}else{echo "colorNumSet";} ?>">排列三</a></li>
				<li><a href="javascript:void(0)" id="game_menu_6" onclick="game_type('6|六合彩')" class="<?php if($_GET['type']==6){echo "colorSet";}else{echo "colorNumSet";} ?> colorSet">六合彩</a></li>
				<li class="conr_s1"></li>
			</ul>
			<span class="l_float s_s1"></span>
			<a id="gpc" class="a_s2 l_float colorNumSet" href="javascript:void(0)">北京彩</a>
			<ul class="ul_s1 l_float" style="display: none;">
				<li class="conl_s1"></li>
				<li><a href="javascript:void(0)" id="game_menu_4" onclick="game_type('4|北京快乐8')" class="<?php if($_GET['type']==4){echo "colorSet";}else{echo "colorNumSet";} ?>">北京快乐8</a></li>
				<li><a href="javascript:void(0)" id="game_menu_5" onclick="game_type('5|北京赛车pk拾')" class="<?php if($_GET['type']==5){echo "colorSet";}else{echo "colorNumSet";} ?>">北京赛车pk拾</a></li>
			</ul>
			<span class="l_float s_s1"></span>
			<a id="ssc" class="a_s2 l_float colorNumSet" href="javascript:void(0)">时时彩</a>
			<ul class="ul_s1 l_float" style="display: none;">
				<li class="conl_s1"></li>
				<li><a href="javascript:void(0)" id="game_menu_3" onclick="game_type('3|重庆时时彩')" class="<?php if($_GET['type']==3){echo "colorSet";}else{echo "colorNumSet";} ?>">重庆时时彩</a></li>
				<!-- <li><a href="javascript:void(0)" id="game_menu_3" onclick="game_type('9|上海时时彩')" class="<?php if($_GET['type']==9){echo "colorSet";}else{echo "colorNumSet";} ?>">上海时时彩</a></li> -->
				<li><a href="javascript:void(0)" id="game_menu_3" onclick="game_type('10|天津时时彩')" class="<?php if($_GET['type']==10){echo "colorSet";}else{echo "colorNumSet";} ?>">天津时时彩</a></li>
				<li><a href="javascript:void(0)" id="game_menu_3" onclick="game_type('11|江西时时彩')" class="<?php if($_GET['type']==11){echo "colorSet";}else{echo "colorNumSet";} ?>">江西时时彩</a></li>
				<li><a href="javascript:void(0)" id="game_menu_3" onclick="game_type('12|新疆时时彩')" class="<?php if($_GET['type']==12){echo "colorSet";}else{echo "colorNumSet";} ?>">新疆时时彩</a></li>
			</ul>
			<span class="l_float s_s1"></span>
			<a id="sf" class="a_s1 l_float colorNumSet" href="javascript:void(0)">快乐十分</a>
			<ul class="ul_s1 l_float" style="display: none;">
				<li class="conl_s1"></li>
				<li><a href="javascript:void(0)" id="game_menu_7" onclick="alert('广东快乐十分暂未开发！')/*game_type('7|广东快乐十分')*/" class="<?php if($_GET['type']==7){echo "colorSet";}else{echo "colorNumSet";} ?>">广东快乐十分</a></li>
				<li><a href="javascript:void(0)" id="game_menu_8" onclick="game_type('8|重庆快乐十分')" class="<?php if($_GET['type']==8){echo "colorSet";}else{echo "colorNumSet";} ?>">重庆快乐十分</a></li>
				<li class="conr_s1"></li>
			</ul>
			<span class="l_float s_s1"></span>
			<a id="k3" class="a_s2 l_float colorNumSet" href="javascript:void(0)" style="width:40px;">快三</a>
			<ul class="ul_s1 l_float" style="display: none;">
				<li class="conl_s1"></li>
				<li><a href="javascript:void(0)" id="game_menu_13" onclick="game_type('13|江苏快3')" class="<?php if($_GET['type']==13){echo "colorSet";}else{echo "colorNumSet";} ?>">江苏快3</a></li>
				<li><a href="javascript:void(0)" id="game_menu_14" onclick="game_type('14|吉林快3')" class="<?php if($_GET['type']==14){echo "colorSet";}else{echo "colorNumSet";} ?>">吉林快3</a></li>
				<li class="conr_s1"></li>
			</ul>
			<span class="l_float s_s1"></span>
		</div>
		<a onclick="parent.k_memr.document.getElementById('aui_state_focus').style.display='block';" class="a_s3 l_float">快速金额设置</a>
	</div>
  <div id="type">

    <ul id="game_1" style="">
      <li><a href="fc_3d.php?type=ball_1" target="k_memr" class="colorSet">第一球|</a></li>
      <li><a href="fc_3d.php?type=ball_2" target="k_memr" class="colorNumSet">第二球|</a></li>
      <li><a href="fc_3d.php?type=ball_3" target="k_memr" class="colorNumSet">第三球|</a></li>

      <li><a href="fc_3d.php?type=ball_4" target="k_memr" class="colorNumSet">總和,龍虎|</a></li>
      <li><a href="fc_3d.php?type=ball_5" target="k_memr" class="colorNumSet">3连|</a></li>
      <li><a href="fc_3d.php?type=ball_6" target="k_memr" class="colorNumSet">跨度|</a></li>
         <li><a href="fc_3d.php?type=ball_7" target="k_memr" class="colorNumSet">独胆|</a></li>
	  <li style="margin: 0 0 0 350px;"><a href="result.php?type=3D" target="_blank" class="colorNumSet">开奖结果|</a></li>
	  <li><a href="./rule/rule.php?gtype=bj3d" target="_blank" class="colorNumSet">玩法规则</a></li>
    </ul>
    <ul id="game_2" style="display:none">
    	   <li><a href="fc_pl3.php?type=ball_1" target="k_memr" class="colorSet">第一球|</a></li>
    	  <li><a href="fc_pl3.php?type=ball_2" target="k_memr" class="colorNumSet">第二球|</a></li>
    	  <li><a href="fc_pl3.php?type=ball_3" target="k_memr" class="colorNumSet">第三球|</a></li>
    	  <li><a href="fc_pl3.php?type=ball_4" target="k_memr" class="colorNumSet">總和,龍虎|</a></li>
    	  <li><a href="fc_pl3.php?type=ball_5" target="k_memr" class="colorNumSet">3连|</a></li>
	      <li><a href="fc_pl3.php?type=ball_6" target="k_memr" class="colorNumSet">跨度|</a></li>

	      <li><a href="fc_pl3.php?type=ball_7" target="k_memr" class="colorNumSet">独胆|</a></li>
		  <li style="margin: 0 0 0 350px;"><a href="result.php?type=pl3" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=pl3d" target="_blank" class="colorNumSet">玩法规则</a></li>
    </ul>
      <!-- 六合彩 -->
    <ul id="game_6" style="display:none">
      <li><a href="liuhecai.php?action=k_tm" target="k_memr" class="colorSet">特码|</a></li>
      <li><a href="liuhecai.php?action=k_zm" target="k_memr" class="colorNumSet">正码|</a></li>
      <li><a href="liuhecai.php?action=k_zt" target="k_memr" class="colorNumSet">正码特|</a></li>
      <li><a href="liuhecai.php?action=k_zm6" target="k_memr" class="colorNumSet">正码1-6|</a></li>
      <li><a href="liuhecai.php?action=k_gg" target="k_memr" class="colorNumSet">过关|</a></li>
      <li><a href="liuhecai.php?action=k_lm" target="k_memr" class="colorNumSet">连码|</a></li>
      <li><a href="liuhecai.php?action=k_bb" target="k_memr" class="colorNumSet">半波|</a></li>
      <li><a href="liuhecai.php?action=k_sxp" target="k_memr" class="colorNumSet">一肖/尾数|</a></li>
      <li><a href="liuhecai.php?action=k_sx" target="k_memr" class="colorNumSet">特码生肖|</a></li>
      <li><a href="liuhecai.php?action=k_sx6" target="k_memr" class="colorNumSet">合肖|</a></li>
      <li><a href="liuhecai.php?action=k_sxt2" target="k_memr" class="colorNumSet">生肖连|</a></li>
      <li><a href="liuhecai.php?action=k_wsl" target="k_memr" class="colorNumSet">尾数连|</a></li>
      <li><a href="liuhecai.php?action=k_wbz" target="k_memr" class="colorNumSet">全不中</a></li>
	  <li style="margin: 0 0 0 135px;"><a href="result.php?type=lhc" target="_blank" class="colorNumSet">开奖结果|</a></li>
	  <li><a href="./rule/rule.php?gtype=lhc" target="_blank" class="colorNumSet">玩法规则</a></li>
    </ul>
   <!--  重庆时时彩 -->
   <ul id="game_3" style="display:none">
         <li><a href="cq_ssc.php?type=ball_1" target="k_memr" class="colorSet">第一球|</a></li>
          <li><a href="cq_ssc.php?type=ball_2" target="k_memr" class="colorNumSet">第二球|</a></li>
          <li><a href="cq_ssc.php?type=ball_3" target="k_memr" class="colorNumSet">第三球|</a></li>
          <li><a href="cq_ssc.php?type=ball_4" target="k_memr" class="colorNumSet">第四球|</a></li>
          <li><a href="cq_ssc.php?type=ball_5" target="k_memr" class="colorNumSet">第五球|</a></li>
          <li><a href="cq_ssc.php?type=ball_6" target="k_memr" class="colorNumSet">總和,龍虎|</a></li>
	      <li><a href="cq_ssc.php?type=ball_7" target="k_memr" class="colorNumSet">前三球|</a></li>
	      <li><a href="cq_ssc.php?type=ball_8" target="k_memr" class="colorNumSet">中三球|</a></li>
	      <li><a href="cq_ssc.php?type=ball_9" target="k_memr" class="colorNumSet">后三球|</a></li>
	      <li><a href="cq_ssc.php?type=ball_10" target="k_memr" class="colorNumSet">斗牛|</a></li>
	      <li><a href="cq_ssc.php?type=ball_11" target="k_memr" class="colorNumSet">梭哈|</a></li>

		  <li style="margin: 0 0 0 160px;"><a href="result.php?type=Cqss" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=cqsc" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>

      <!--  天津时时彩 -->
   <ul id="game_10" style="display:none">
         <li><a href="tj_ssc.php?type=ball_1" target="k_memr" class="colorSet">第一球|</a></li>
          <li><a href="tj_ssc.php?type=ball_2" target="k_memr" class="colorNumSet">第二球|</a></li>
          <li><a href="tj_ssc.php?type=ball_3" target="k_memr" class="colorNumSet">第三球|</a></li>
          <li><a href="tj_ssc.php?type=ball_4" target="k_memr" class="colorNumSet">第四球|</a></li>
          <li><a href="tj_ssc.php?type=ball_5" target="k_memr" class="colorNumSet">第五球|</a></li>
          <li><a href="tj_ssc.php?type=ball_6" target="k_memr" class="colorNumSet">總和,龍虎|</a></li>
	      <li><a href="tj_ssc.php?type=ball_7" target="k_memr" class="colorNumSet">前三球|</a></li>
	      <li><a href="tj_ssc.php?type=ball_8" target="k_memr" class="colorNumSet">中三球|</a></li>
	      <li><a href="tj_ssc.php?type=ball_9" target="k_memr" class="colorNumSet">后三球|</a></li>
	      <li><a href="tj_ssc.php?type=ball_10" target="k_memr" class="colorNumSet">斗牛|</a></li>
	      <li><a href="tj_ssc.php?type=ball_11" target="k_memr" class="colorNumSet">梭哈|</a></li>

		  <li style="margin: 0 0 0 160px;"><a href="result.php?type=Tjss" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=tjsc" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>
      <!--  江西时时彩 -->
   <ul id="game_11" style="display:none">
         <li><a href="jx_ssc.php?type=ball_1" target="k_memr" class="colorSet">第一球|</a></li>
          <li><a href="jx_ssc.php?type=ball_2" target="k_memr" class="colorNumSet">第二球|</a></li>
          <li><a href="jx_ssc.php?type=ball_3" target="k_memr" class="colorNumSet">第三球|</a></li>
          <li><a href="jx_ssc.php?type=ball_4" target="k_memr" class="colorNumSet">第四球|</a></li>
          <li><a href="jx_ssc.php?type=ball_5" target="k_memr" class="colorNumSet">第五球|</a></li>
          <li><a href="jx_ssc.php?type=ball_6" target="k_memr" class="colorNumSet">總和,龍虎|</a></li>
	      <li><a href="jx_ssc.php?type=ball_7" target="k_memr" class="colorNumSet">前三球|</a></li>
	      <li><a href="jx_ssc.php?type=ball_8" target="k_memr" class="colorNumSet">中三球|</a></li>
	      <li><a href="jx_ssc.php?type=ball_9" target="k_memr" class="colorNumSet">后三球|</a></li>
	      <li><a href="jx_ssc.php?type=ball_10" target="k_memr" class="colorNumSet">斗牛|</a></li>
	      <li><a href="jx_ssc.php?type=ball_11" target="k_memr" class="colorNumSet">梭哈|</a></li>

		  <li style="margin: 0 0 0 160px;"><a href="result.php?type=Jxss" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=jxsc" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>
      <!--  新疆时时彩 -->
   <ul id="game_12" style="display:none">
         <li><a href="xj_ssc.php?type=ball_1" target="k_memr" class="colorSet">第一球|</a></li>
          <li><a href="xj_ssc.php?type=ball_2" target="k_memr" class="colorNumSet">第二球|</a></li>
          <li><a href="xj_ssc.php?type=ball_3" target="k_memr" class="colorNumSet">第三球|</a></li>
          <li><a href="xj_ssc.php?type=ball_4" target="k_memr" class="colorNumSet">第四球|</a></li>
          <li><a href="xj_ssc.php?type=ball_5" target="k_memr" class="colorNumSet">第五球|</a></li>
          <li><a href="xj_ssc.php?type=ball_6" target="k_memr" class="colorNumSet">總和,龍虎|</a></li>
	      <li><a href="xj_ssc.php?type=ball_7" target="k_memr" class="colorNumSet">前三球|</a></li>
	      <li><a href="xj_ssc.php?type=ball_8" target="k_memr" class="colorNumSet">中三球|</a></li>
	      <li><a href="xj_ssc.php?type=ball_9" target="k_memr" class="colorNumSet">后三球|</a></li>
	      <li><a href="xj_ssc.php?type=ball_10" target="k_memr" class="colorNumSet">斗牛|</a></li>
	      <li><a href="xj_ssc.php?type=ball_11" target="k_memr" class="colorNumSet">梭哈|</a></li>

		  <li style="margin: 0 0 0 160px;"><a href="result.php?type=Xjss" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=xjsc" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>
      <!-- 北京快乐8 -->
   <ul id="game_4" style="display:none">
            <li><a href="bj_kl8.php?type=ball_1" target="k_memr" class="colorSet">选一|</a></li>
          <li><a href="bj_kl8.php?type=ball_2" target="k_memr" class="colorNumSet">选二|</a></li>
          <li><a href="bj_kl8.php?type=ball_3" target="k_memr" class="colorNumSet">选三|</a></li>
          <li><a href="bj_kl8.php?type=ball_4" target="k_memr" class="colorNumSet">选四|</a></li>
          <li><a href="bj_kl8.php?type=ball_5" target="k_memr" class="colorNumSet">选五|</a></li>
	      <li><a href="bj_kl8.php?type=ball_6" target="k_memr" class="colorNumSet">和值|</a></li>
	      <li><a href="bj_kl8.php?type=ball_7" target="k_memr" class="colorNumSet">上中下|</a></li>
	      <li><a href="bj_kl8.php?type=ball_8" target="k_memr" class="colorNumSet">奇和偶|</a></li>
		  <li style="margin: 0 0 0 360px;"><a href="result.php?type=kl8" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=bjkn" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>
   <!-- 北京赛车PK拾 -->
                <ul id="game_5" style="display:none">
       <li><a href="bj_pk10.php?type=ball_11" target="k_memr" class="colorSet">冠、亚军和|</a></li>
      <li><a href="bj_pk10.php?type=ball_1" target="k_memr">冠军|</a></li>
      <li><a href="bj_pk10.php?type=ball_2" target="k_memr">亚军|</a></li>
      <li><a href="bj_pk10.php?type=ball_3" target="k_memr">第三名|</a></li>
      <li><a href="bj_pk10.php?type=ball_4" target="k_memr">第四名|</a></li>
      <li><a href="bj_pk10.php?type=ball_5" target="k_memr">第五名|</a></li>
      <li><a href="bj_pk10.php?type=ball_6" target="k_memr">第六名|</a></li>
      <li><a href="bj_pk10.php?type=ball_7" target="k_memr">第七名|</a></li>
      <li><a href="bj_pk10.php?type=ball_8" target="k_memr">第八名|</a></li>
      <li><a href="bj_pk10.php?type=ball_9" target="k_memr">第九名|</a></li>
      <li><a href="bj_pk10.php?type=ball_10" target="k_memr">第十名|</a></li>
      <li><a href="bj_pk10.php?type=ball_12" target="k_memr">龍虎|</a></li>
       <li style="margin: 0 0 0 120px;"><a href="result.php?type=pk10" target="_blank" class="colorNumSet">开奖结果|</a></li>
       <li><a href="./rule/rule.php?gtype=bjpk" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>
  <!--  广东快乐十分 -->
    <ul id="game_7" style="display:none">
             <li><a href="gd_kl10.php?type=ball_1" target="k_memr" class="colorSet">第一球|</a></li>
          <li><a href="gd_kl10.php?type=ball_2" target="k_memr" class="colorNumSet">第二球|</a></li>
          <li><a href="gd_kl10.php?type=ball_3" target="k_memr" class="colorNumSet">第三球|</a></li>
          <li><a href="gd_kl10.php?type=ball_4" target="k_memr" class="colorNumSet">第四球|</a></li>
          <li><a href="gd_kl10.php?type=ball_5" target="k_memr" class="colorNumSet">第五球|</a></li>
	      <li><a href="gd_kl10.php?type=ball_6" target="k_memr" class="colorNumSet">第六球|</a></li>
	      <li><a href="gd_kl10.php?type=ball_7" target="k_memr" class="colorNumSet">第七球|</a></li>
	      <li><a href="gd_kl10.php?type=ball_8" target="k_memr" class="colorNumSet">第八球|</a></li>
	      <li><a href="gd_kl10.php?type=ball_9" target="k_memr" class="colorNumSet">總和,龍虎|</a></li>
		  <li style="margin: 0 0 0 220px;"><a href="result.php?type=gdsf" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=gdsf" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>
   <!-- 重庆快乐十分 -->
   <ul id="game_8" style="display:none">
              <li><a href="cq_kl10.php?type=ball_1" target="k_memr" class="colorSet">第一球|</a></li>
          <li><a href="cq_kl10.php?type=ball_2" target="k_memr" class="colorNumSet">第二球|</a></li>
          <li><a href="cq_kl10.php?type=ball_3" target="k_memr" class="colorNumSet">第三球|</a></li>
          <li><a href="cq_kl10.php?type=ball_4" target="k_memr" class="colorNumSet">第四球|</a></li>
          <li><a href="cq_kl10.php?type=ball_5" target="k_memr" class="colorNumSet">第五球|</a></li>
	      <li><a href="cq_kl10.php?type=ball_6" target="k_memr" class="colorNumSet">第六球|</a></li>
	      <li><a href="cq_kl10.php?type=ball_7" target="k_memr" class="colorNumSet">第七球|</a></li>
	      <li><a href="cq_kl10.php?type=ball_8" target="k_memr" class="colorNumSet">第八球|</a></li>
	      <li><a href="cq_kl10.php?type=ball_9" target="k_memr" class="colorNumSet">總和,龍虎|</a></li>
		  <li style="margin: 0 0 0 220px;"><a href="result.php?type=cqsf" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=cqsf" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>
    <ul id="game_9" style="display:none">

   </ul>
   <ul id="game_13" style="display:none">
          <li><a href="js_k3.php?type=ball_1" target="k_memr" class="colorSet">和值|</a></li>
          <li><a href="js_k3.php?type=ball_2" target="k_memr" class="colorNumSet">独胆|</a></li>
          <li><a href="js_k3.php?type=ball_3" target="k_memr" class="colorNumSet">豹子|</a></li>
          <li><a href="js_k3.php?type=ball_4" target="k_memr" class="colorNumSet">两连|</a></li>
	      <li><a href="js_k3.php?type=ball_5" target="k_memr" class="colorNumSet">对子|</a></li>
		  <li style="margin: 0 0 0 480px;"><a href="result.php?type=jsk3" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=jsq3" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>

   <ul id="game_14" style="display:none">
          <li><a href="jl_k3.php?type=ball_1" target="k_memr" class="colorSet">和值|</a></li>
          <li><a href="jl_k3.php?type=ball_2" target="k_memr" class="colorNumSet">独胆|</a></li>
          <li><a href="jl_k3.php?type=ball_3" target="k_memr" class="colorNumSet">豹子|</a></li>
          <li><a href="jl_k3.php?type=ball_4" target="k_memr" class="colorNumSet">两连|</a></li>
          <li><a href="jl_k3.php?type=ball_5" target="k_memr" class="colorNumSet">对子|</a></li>
		  <li style="margin: 0 0 0 480px;"><a href="result.php?type=jlk3" target="_blank" class="colorNumSet">开奖结果|</a></li>
		  <li><a href="./rule/rule.php?gtype=ahq3" target="_blank" class="colorNumSet">玩法规则</a></li>
   </ul>
      </div>
</div>
<div id="nav_id" style="display:none"><?=$nav_id ?></div>
<div id="lock_arr" style="display:none;"><?php foreach ($lock_arr as $k => $v) {
	echo $v.',';
} ?></div>


</body></html>