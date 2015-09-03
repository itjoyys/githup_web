<?php
@session_start();
//print_r($_COOKIE);
?><?php
include_once("../include/config.php");   
include_once("../include/private_config.php");
include_once("../common/logintu.php");
include_once("../class/user.php");
$uid    	=	@$_SESSION['uid'];
$loginid	=	@$_SESSION['/user_login_id'];
renovate($uid,$loginid);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>首页</title>
<link type="text/css" rel="stylesheet" href="../style/style.css">
<script language="javascript" type="text/javascript" src="../js/jquery-1.8.3.min.js"></script>
<script language="javascript" type="text/javascript" src="../js/jquery-ui.js"></script>
<script language="javascript" type="text/javascript" src="../js/common.js"></script>
<script language="javascript" type="text/javascript" src="../js/swfobject.js"></script>
<script language="javascript" type="text/javascript" src="../js/top.js"></script>
<script>
function openHelp(url) {
	var st = window.open(url,'帮助','height=630,width=1020,top=80,left=200,toolbar=no,menubar=no,scrollbars=yes,resizable=yes,location=no,status=no');

}
</script>
<script>
	if(self==top) top.location.href="/";
	var sportsTimeout = null;
	var casinoTimeout = null;
	var gameTimeout = null;
	var lotteryTimeout = null;
	
	var sportsTimeoutB = null;
	var casinoTimeoutB = null;
	var gameTimeoutB = null;
	var lotteryTimeoutB = null;
	
	var TimeoutObj = {
		"sports":sportsTimeout,
		"sportsB":sportsTimeoutB,
		"casino":casinoTimeout,
		"casinoB":casinoTimeoutB,
		"game":gameTimeout,
		"gameB":gameTimeoutB,
		"lottery":lotteryTimeout,
		"lotteryB":lotteryTimeoutB,
		"aaaa":null
	}
	$(function() {
		sMsgReset('.msg-row','||',"<br/><br/>");
		sMsgReset('.msg-col','||');
		new toggleColor( '#textGlitter' , ['#ffffff','#ffff00'] , 200 );
		//语言下拉
		var ele_lang_group = $('<div>').append($('#ele-lang-group')).html();
		$('body', document).prepend(ele_lang_group);
		var lang_group = $('#ele-lang-group');
		//設定group 的寬度
		lang_group.css("width", $('#ele-lang-wrap').width() - (lang_group.outerWidth() - lang_group.width()));
		$('#ele-lang-wrap').on("click",
		function() {
			var offset = $(this).offset();
			var obj_Left = offset.left;
			var obj_Top = offset.top + $(this).height();

			if (lang_group.is(':hidden')) {
				lang_group.stop().slideDown('fast').css({
					'left': obj_Left,
					'top': obj_Top
				}).on('mouseleave',
				function() {
					$(this).slideUp('fast');
				});
			} else {
				lang_group.stop().slideUp('fast');
			}
		});
		
		
		$(".nh-csn").attr("style","height:0;z-index:99");
		var navHoverBox = $('<div>').append($('#navHoverBox')).html();
		$('body', document).prepend(navHoverBox);
		$("#header-nav-ul").each(function(){
			main = $(this);
		
			main.children( "li").mouseenter(function(event){
				if($(this).attr('id') == "" || $(this).attr('id') == undefined) return;
				else {
					obId = $(this).attr('id').split('_')[1];
					obIdB = obId + "B";
				}
				clearTimeout(TimeoutObj[obId]);
				
				TimeoutObj[obId] = null;
				
				if(TimeoutObj[obIdB] == null){
					TimeoutObj[obIdB] = setTimeout(function(){
						showBoxFx(obId);
					},200);
				}
			});
			main.children( "li").mouseleave(function(event){
				
				if($(this).attr('id') == "" || $(this).attr('id') == undefined) return;
				else {
					obId = $(this).attr('id').split('_')[1];
					obIdB = obId + "B";
				}
				
				clearTimeout(TimeoutObj[obIdB]);
				TimeoutObj[obIdB]=null;
				
				hiddBoxFx(obId);
				
			});			
		});
		
		$(".nh-csn").mouseenter(function(){
			if($(this).attr('id') == "" || $(this).attr('id') == undefined) return;
			else {
				obId = $(this).attr('id').split('_')[1];
				obIdB = obId + "B";
			}
			clearTimeout(TimeoutObj[obId]);
			TimeoutObj[obId] = null;
			if(TimeoutObj[obIdB] == null){
				TimeoutObj[obIdB] = setTimeout(function(){
					showBoxFx(obId,"");
				},200);
			}
		});
		$(".nh-csn").mouseleave(function(){
			if($(this).attr('id') == "" || $(this).attr('id') == undefined) return;
			else {
				obId = $(this).attr('id').split('_')[1];
				obIdB = obId + "B";
			}
			
			clearTimeout(TimeoutObj[obIdB]);
			TimeoutObj[obIdB] = null;
			hiddBoxFx(obId);
		});
	});
	
	
</script>
<LINK href="../css/X_style.css" rel="stylesheet" type="text/css">
<SCRIPT src="../js/jquery.min.js" type="text/javascript"></SCRIPT>
<SCRIPT src="../js/nav.js" type="text/javascript"></SCRIPT>
</head>
<body>

<div class="header">
	<div class="w1000">
	<div class="logo">
	<a href="#"><img src="../images/logo.png"></a>    
    </div>
    <div class="headright">
    	<div class="toolbar">
        <span class="phone">客服电话：0063-9994688888</span>&nbsp;&nbsp;<a href="javascript:void(0);">设为首页</a> | <a href="javascript:void(0);">加入收藏</a> <a class="flag" href="#"><img src="./../images/aomen.gif"></a> <a class="flag" href="#"><img src="./../images/china.gif"></a> <a class="flag" href="#"><img src="./../images/usa.gif"></a>
        </div>
        <div class="clear"></div>
      
		<?php if ($uid){ ?>
		<div class="login" style="width:520px;">
			帐号：<strong><?php echo $_SESSION["username"]; ?></strong>
			余额：<strong id="user_money1">0</strong>
			体育投注额：<strong id="tz_money1">---</strong>
			 <a id="su-msg" style="cursor: pointer" onclick="openHelp('/Member/notice/sms.php?1=1')" title="未读讯息">
		                未读讯息
		                (<span id="user_num1">0</span>)		            </a>
		| <a class="LogoutBtn" href="/logout.php" target="_top">登出</a>
			<br/>
			<a id="su-deposite" style="cursor: pointer" onclick="openHelp('/Member/cash/set_money.php')" title="线上存款">
		                线上存款</a>
		  | <a id="su-withdraw" style="cursor: pointer" onclick="openHelp('/Member/cash/get_money.php')" title="线上取款">
		                线上取款</a>
		  | <a id="su-switch" style="cursor: pointer" onclick="openHelp('/Member/cash/zr_money.php')" title="额度转换">
		                额度转换</a>
		  | <a id="su-macenter" style="cursor: pointer" onclick="openHelp('/Member/account/userinfo.php')" title="会员中心">
		                会员中心</a>
		  | <a id="su-switch" style="cursor: pointer" onclick="openHelp('/Member/trading_log/record_ds.php')" title="投注记录">
		                投注记录</a>
		  | <a id="su-switch" style="cursor: pointer" onclick="openHelp('/Member/bet_report/report.php')" title="投注报表">
		                投注报表</a>
				  <?php
                  $bool = $_SESSION['is_daili'];
                  if($bool){
                  ?>
		    <a title="查询下级" style="cursor: pointer" onclick="openHelp('/Member/agent/cha_xiaji.php')" class="link-set" id="su-switch">查询下级</a>
          <?php
                  }else{
                  ?>
		   | <a title="代理申请" style="cursor: pointer" onclick="openHelp('/Member/agent/daili_onshenqing.php')" class="link-set" id="su-switch">代理申请</a>
          <?php
                  }?> 

                  |
				  
					<UL id="navul" style="float:right;display:inline;margin-top:3px;">
				  <LI style="margin-left:-110px;"><A onclick="cash()">视讯余额</A>
				 <UL>
					<LI><A>AG余额：<span id="ag_cash">0</span>RMB</A></LI>
					<LI><A>MG余额：<span id="mg_cash">0</span>RMB</A></LI>
					<LI><A>OG余额：<span id="og_cash">0</span>RMB</A></LI>
					<LI><A>BBIN余额：<span id="bbin_cash">0</span>RMB</A></LI>
				 </UL>
				 </LI>
				 </UL>	
		 
	<script language="javascript">
function refresh_money(){
	$.getJSON("/get_info.php?callback=?",function(json){
		$("#user_money1").html(json.user_money);
		$("#tz_money1").html(json.tz_money);
		$("#user_num1").html(json.user_num);
	});
	window.setTimeout("refresh_money();", 10*1000); 
}
refresh_money();
</script> 																							
																							
		<?php }else { ?>
		  <div class="login" style="width:580px;">
        	<input type="txt" class="intxt" value="用户名" id="username" onfocus="if(this.value=='用户名')this.value=''" onblur="if(this.value =='') this.value ='用户名'" />
            <span class="password"><input type="password" id="password" class="pass" value="******" onfocus="if(this.value=='******')this.value=''" onblur="if(this.value =='') this.value ='******'" />忘记？</span>
            <span class="yzm"><input type="txt" class="yz" value="验证码" id="rmNum" onfocus="if(this.value=='验证码')this.value=''" onblur="if(this.value =='') this.value ='验证码'" /><img src="/yzm.php" onclick="javascript:this.src=this.src+'?_'+Math.random()"  style="float:right; cursor:pointer"></span>
            <input type="submit" value="登入"  onclick="aLeftForm1Sub()" id="submit" class="loginbtn"><a href="./zhuce.php?type=1" class="reg">注册</a><a class="reg" href="./zhuce.php">免费试玩</a>
          <?php }?>  
        </div>
    </div>
	</div>
    <div class="clear"></div>
    <div id="hd-nav" class="clearfix">
		<div class="hd-nav-bg">
			<div id="nav-inner">
				<ul id="header-nav-ul">
					<li class="nav-index">
						<a href="/" target="_top" >首页</a>
					</li>
					<li class="nav-sports it_sport ">
						<a href="../sport/sport_main.php" >皇冠体育<em class="rotate-triangle"></em></a>
					</li>
					<li class="LS-live it_csn ">
						<a href="../shixun.php" >视讯直播<em class="rotate-triangle"></em></a>
					</li>
                    <li>
						<a href="../letou.php" >香港乐透</a>
					</li>
                    <li class="nav-caipiaogm it_game"><!-- id="nav_lottery"-->
						<a href="../caipiao.php">彩票游戏<em class="rotate-triangle"></em></a>
					</li>
                    <li class="nav-promotion  ">
						<a href="../youhui.php" id="textGlitter">优惠活动</a>
					</li>
					<li class="nav-ele it_keno" id="nav_game">
						<a onClick="javascript: window.open('/mobile.html','','menubar=no,status=yes,scrollbars=yes,top=150,left=400,toolbar=no,width=705,height=520')" href="javascript://">手机投注</a>
					</li>					
					
					<li class="nav-com">
						<!--<a href="./dzyx.php">电子游戏</a>-->
						<a onclick="alert('开发中!');">电子游戏</a>
					</li>
					<li class="nav-service">
						<a onClick="javascript: window.open('http://messenger.providesupport.com/messenger/10cueo1teejwx1gl8olzhsz0ln.html','','menubar=no,status=yes,scrollbars=yes,top=150,left=400,toolbar=no,width=705,height=520')" href="javascript://">在线客服</a>
					</li>
				</ul>
				
			</div>
            
		</div>
        
	</div>


    
</div>

<script language="javascript">
function cash(){
	$.getJSON("/pk/money.php?callback=?",function(json){
		$("#ag_cash").html(json.ag);
		$("#mg_cash").html(json.mg);
		if(!json.mg||!json.mg){
			window.setTimeout("cash();", 10*1000); 
		}
		//alert(json.ag);
	})
	$.getJSON("/api/og_cha.php?callback=?",function(json){
		$("#og_cash").html(json.general);
		if(!json.general){
			window.setTimeout("cash();", 10*1000); 
		}
	})
	
}
//cash();	

</script>
