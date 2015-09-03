<?php if (!defined('THINK_PATH')) exit();?><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

<title>会员卡资料</title>
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="format-detection" content="telephone=no">
<link href="__PUBLICI__/Member/css/fans.css" rel="stylesheet" type="text/css">
<script src="__PUBLICI__/Member/js/jquery.min.js" type="text/javascript"></script>
<script src="__PUBLICI__/Member/js/check.js" type="text/javascript"></script>
<style>

 .themeStyle{ background-color:#E83407 !important; }  

</style>
</head>

<body id="fans">
<div class="qiandaobanner"> <a href="javascript:history.go(-1);">
  <img src="__PUBLICI__/Member/images/fans.jpg"></a> </div>
<form action="<?php echo U(GROUP_NAME .'/Member/h_add_member');?>" method="post">
<div class="cardexplain">
<!--个人头像，昵称等-->
<!--会员积分信息-->
<div class="jifen-box" style="display:none">
<ul class="zongjifen">
<li>
<div class="fengexian">
<p>会员总积分</p>
<span>0</span></div>
</li>
<li><a href="http://www2.wxokok.com/cardintegral.html">
<div class="fengexian">
<p>签到积分</p>
<span>0</span></div>
</a></li>
<li><a href="http://www2.wxokok.com/cardshopping.html">
<p>消费积分</p>
<span>0</span></a></li>
</ul>
<div class="clr"></div>
</div>
<ul class="round">
<li class="title mb"><span class="none">会员资料</span></li>
 
 
<li class="nob">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
<tbody><tr>
<th>真实姓名</th>
<td>
  <input name="name" type="text" class="px" id="truename" value="" placeholder="请输入您的真实姓名">
  <input name="mid" id="url" type="hidden" value="<?php echo ($member_card['mid']); ?>">
  <input name="openid" id="url" type="hidden" value="<?php echo ($member_card['openid']); ?>">
  <input name="siteid" id="url" type="hidden" value="<?php echo ($siteid); ?>">
</td>
</tr>
</tbody></table>
</li>
<li class="nob">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="kuang">
<tbody><tr>
<th>联系电话</th>
<td><input name="tel" class="px" id="tel" value="" onblur="check_mobile(this.value)" onchange="cgit(this.value)" type="tel" placeholder="请输入您的电话"></td>
</tr>
</tbody></table>
</li>
         
        
        <script>
function cgit(ii){
if(ii!=){
document.getElementById('dsyzm').style.display='';
}else{
document.getElementById('dsyzm').style.display='none';
}
}

$(document).ready(function(){
    $("#Submit").click(function get_mobile(){
        var mcode=Math.round(Math.random()*10000);
        $.get('='+$("#tel").val()+"&mcode="+mcode,function(data){
         alert(data);
  $.this.setAttribute("disabled",false);
       
        });
       
    });
       
var test = {
       node:null,
       count:120,
       start:function(){
          //console.log(this.count);
          if(this.count > 0){
             this.node.innerHTML = this.count--;
             var _this = this;
             setTimeout(function(){
                _this.start();
             },1000);
          }else{
             this.node.removeAttribute("disabled");
             this.node.innerHTML = "再次发送";
             this.count = 120;
          }
       },
       //初始化
       init:function(node){
          this.node = node;
          this.node.setAttribute("disabled",true);
          this.start();
       }
    };
    var btn = document.getElementById("Submit");
    btn.onclick = function(){
     //  alert("验证信息会发送到"+$("#tel").val());
       test.init(btn);
    };
  });
 
</script>
 </ul>
<div class="footReturn"> 
  <input id="showcard" style="width: 100%;" class="submit" type="submit" value="提 交"/>
<div class="window" id="windowcenter">
<div class="content">
<div id="txt"></div>
</div>
</div>
        
</div>
 
</div>
</form>

  <script src="./会员卡资料_files/index.php" type="text/javascript"></script><style type="text/css">
body { margin-bottom:60px !important; }
a, button, input { -webkit-tap-highlight-color:rgba(255, 0, 0, 0); }
ul, li { list-style:none; margin:0; padding:0 }
.top_bar { position: fixed; z-index: 900; bottom: 0; left: 0; right: 0; margin: auto; font-family: Helvetica, Tahoma, Arial, Microsoft YaHei, sans-serif; }
.top_menu { display:-webkit-box; border-top: 1px solid #3D3D46; display: block; width: 100%; background: rgba(255, 255, 255, 0.7); height: 48px; display: -webkit-box; display: box; margin:0; padding:0; -webkit-box-orient: horizontal; background: -webkit-gradient(linear, 0 0, 0 100%, from(#697077), to(#3F434E), color-stop(60%, #464A53)); box-shadow: 0 1px 0 0 rgba(255, 255, 255, 0.3) inset; }
.top_bar .top_menu>li { -webkit-box-flex:1; background: -webkit-gradient(linear, 0 0, 0 100%, from(rgba(0, 0, 0, 0.1)), color-stop(50%, rgba(0, 0, 0, 0.3)), to(rgba(0, 0, 0, 0.4))), -webkit-gradient(linear, 0 0, 0 100%, from(rgba(255, 255, 255, 0.1)), color-stop(50%, rgba(255, 255, 255, 0.1)), to(rgba(255, 255, 255, 0.15))); ; -webkit-background-size:1px 100%, 1px 100%; background-size:1px 100%, 1px 100%; background-position: 1px center, 2px center; background-repeat: no-repeat; position:relative; text-align:center; }
.top_menu li:first-child { background:none; }
.top_bar .top_menu>li>a { height:48px; display:block; text-align:center; color:#FFF; text-decoration:none; text-shadow: 0 1px rgba(0, 0, 0, 0.3); -webkit-box-flex:1; }
.top_bar .top_menu>li>a label { overflow:hidden; margin: 0 0 0 0; font-size: 12px; display: block !important; line-height: 18px; text-align: center; }
.top_bar .top_menu>li>a img { padding: 3px 0 0 0; height: 24px; width: 24px; color: #fff; line-height: 48px; vertical-align:middle; }
.top_bar li:first-child a { display: block; }
.menu_font { text-align:left; position:absolute; right:10px; z-index:500; background: -webkit-gradient(linear, 0 0, 0 100%, from(#697077), to(#3F434E), color-stop(60%, #464A53)); border-radius: 5px; width: 120px; margin-top: 10px; padding: 0; box-shadow: 0 1px 5px rgba(0, 0, 0, 0.3); }
.menu_font.hidden { display:none; }
.menu_font { top:inherit !important; bottom:60px; }
.menu_font li a { height:40px; margin-right: 1px; display:block; text-align:center; color:#FFF; text-decoration:none; text-shadow: 0 1px rgba(0, 0, 0, 0.3); -webkit-box-flex:1; }
.menu_font li a { text-align: left !important; }
.top_menu li:last-of-type a { background: none; }
.menu_font:after { top: inherit!important; bottom: -6px; border-color: #3F434E rgba(0, 0, 0, 0) rgba(0, 0, 0, 0); border-width: 6px 6px 0; position: absolute; content: ""; display: inline-block; width: 0; height: 0; border-style: solid; left: 80%; }
.menu_font li { border-top: 1px solid rgba(255, 255, 255, 0.1); border-bottom: 1px solid rgba(0, 0, 0, 0.2); }
.menu_font li:first-of-type { border-top: 0; }
.menu_font li:last-of-type { border-bottom: 0; }
.menu_font li a { height: 40px; line-height: 40px !important; position: relative; color: #fff; display: block; width: 100%; text-indent: 10px; white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
.menu_font li a img { width: 20px; height:20px; display: inline-block; margin-top:-2px; color: #fff; line-height: 40px; vertical-align:middle; }
.menu_font>li>a label { padding:3px 0 0 3px; font-size:14px; overflow:hidden; margin: 0; }
#menu_list0 { right:0; left:10px; }
#menu_list0:after { left: 20%; }
#sharemcover { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.7); display: none; z-index: 20000; }
#sharemcover img { position: fixed; right: 18px; top: 5px; width: 260px; height: 180px; z-index: 20001; border:0; }
.top_bar .top_menu>li>a:hover, .top_bar .top_menu>li>a:active { background-color:#333; }
.menu_font li a:hover, .menu_font li a:active { background-color:#333; }
.menu_font li:first-of-type a { border-radius:5px 5px 0 0; }
.menu_font li:last-of-type a { border-radius:0 0 5px 5px; }
#plug-wrap { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0); z-index:800; }
#cate18 .device {bottom: 49px;}
#cate18 #indicator {bottom: 240px;}
#cate19 .device {bottom: 49px;}
#cate19 #indicator {bottom: 330px;}
#cate19 .pagination {bottom: 60px;}
#cate60 .device {bottom: 55px;}
#cate61 .device {bottom: 55px;}
#cate74 .device {bottom: 55px;}
#cate75 .device {bottom: 55px;}
#cate63 #indicator {bottom: 80px;}
</style>
<div id="sharemcover" onclick="document.getElementById(&#39;sharemcover&#39;).style.display=&#39;&#39;;" style=" display:none"><img src="./会员卡资料_files/MgnnofmleM.png"></div>
 
<div class="top_bar" style="-webkit-transform:translate3d(0,0,0)">
  <nav>
    <ul id="top_menu" class="top_menu">
    	<li> <a onclick="javascript:displayit(0)"><img src="./会员卡资料_files/XIAD6ugwqe.png"><label>微服务</label></a>
        	<ul id="menu_list0" class="menu_font" style=" display:none">
                <li> <a href="http://www2.wxokok.com/?ac=onlinebooking&id=1194&tid=620&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/2000"><label>在线预约</label></a></li>
                <li> <a href="http://site.tg.qq.com/forwardToPhonePage?siteId=1&phone=057188136013"><img src="./会员卡资料_files/2000(1)"><label>一键拨号</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=WeddingInvitation-index&id=1194&tid=295"><img src="./会员卡资料_files/2000(2)"><label>邀请函</label></a></li>
                <li> <a href="http://apisimsim.duapp.com/?ac=heka1&hkid=898"><img src="./会员卡资料_files/QNTFCk6GqN.png"><label>节日贺卡</label></a></li>
            </ul>
        </li>
    	<li> <a onclick="javascript:displayit(1)"><img src="./会员卡资料_files/1yW0BWqD4t.png"><label>功能演示</label></a>
        	<ul id="menu_list1" class="menu_font" style=" display:none">
                <li> <a href="http://www2.wxokok.com/?ac=Groupbuying-index&id=1194&tid=47&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/kXsI9ITmWF.png"><label>微团购</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=hotels&id=1194&tid=272&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/2vlpeaC2QG.png"><label>微酒店</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=foodmenu&id=1194&shopid=190&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/X6ANFi0tf4.png"><label>微外卖</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=diancaimenu&id=1194&shopid=197&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/6fLDbriHQG.png"><label>微点餐</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=WeddingInvitation-index&id=1194&tid=30"><img src="./会员卡资料_files/VhP8W6ZSAe.png"><label>微喜帖</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=boxl&tid=1194"><img src="./会员卡资料_files/YBmgNvsll2.png"><label>音乐盒</label></a></li>
            </ul>
        </li>
    	<li> <a onclick="javascript:displayit(2)"><img src="./会员卡资料_files/9ujht5y43X.png"><label>抽奖活动</label></a>
        	<ul id="menu_list2" class="menu_font" style=" display:none">
                <li> <a href="http://www2.wxokok.com/?ac=acc&id=1194&tid=309&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/XZScjRxoQj.png"><label>优惠券</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=alw&id=1194&tid=695&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/c7kCJoE3ut.png"><label>大转盘</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=acw&id=1194&tid=487&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/zC2RX8voiU.png"><label>刮刮卡</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=survey-mobile-index&id=1194&tid=209&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/JjN1xB2ygQ.png"><label>有奖调研</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=voteimg&id=1194&tid=293&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/sqqQOqyrp4.png"><label>图片投票</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=votetext&id=1194&tid=292&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/j8spuZ9dxk.png"><label>文字投票</label></a></li>
            </ul>
        </li>
    	<li> <a onclick="javascript:displayit(3)"><img src="./会员卡资料_files/MczZiNz6a5.png"><label>其他功能</label></a>
        	<ul id="menu_list3" class="menu_font" style=" display:none">
                <li> <a href="http://www2.wxokok.com/wxapi.php?ac=photo&tid=1194"><img src="./会员卡资料_files/7bE1KgQLIF.png"><label>3G相册</label></a></li>
                <li> <a href="http://map.baidu.com/mobile/webapp/place/list/qt=s&wd=%E6%9D%AD%E5%B7%9E%E5%B8%82%E8%90%A7%E5%B1%B1%E5%8C%BA%E5%85%B4%E4%BA%94%E8%B7%AF237%E5%8F%B7/vt=map"><img src="./会员卡资料_files/9DQwTF7I7D.png"><label>一键导航</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=qj&id=1194&tid=162"><img src="./会员卡资料_files/8syOKrS1tW.png"><label>360全景</label></a></li>
                <li> <a href="http://comment.duapp.com/?openid=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com&wxid=2e6e9649f3227c5fe207bb8d25ba31d2"><img src="./会员卡资料_files/WxbqP9gdE3.png"><label>留言墙</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=cardindex&id=1194&tid=486&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/kTaX3ZnKAy.png"><label>会员卡</label></a></li>
                <li> <a href="http://www2.wxokok.com/?ac=fans&tid=1194&c=onP0rtw9X52xLMQ2ZVyYwALRxEYw&refex=mp.weixin.qq.com"><img src="./会员卡资料_files/Q6mFd6ZxNf.png"><label>会员资料</label></a></li>
            </ul>
        </li>
    </ul>
  </nav>
</div>
<div id="plug-wrap" onclick="closeall()" style="display: none;"></div>
<script>
function displayit(n){
	var count = document.getElementById("top_menu").getElementsByTagName("ul").length;	
	for(i=0;i<count;i++){
		if(i==n){
		 if(document.getElementById("top_menu").getElementsByTagName("ul").item(n).style.display=='none'){
			 document.getElementById("top_menu").getElementsByTagName("ul").item(n).style.display='';
			 document.getElementById("plug-wrap").style.display='';
			}else{
				 document.getElementById("top_menu").getElementsByTagName("ul").item(n).style.display='none';
				  document.getElementById("plug-wrap").style.display='none';
			}
		}else{
			document.getElementById("top_menu").getElementsByTagName("ul").item(i).style.display='none';
		}
	}
}
function closeall(){
	var count = document.getElementById("top_menu").getElementsByTagName("ul").length;	
	for(i=0;i<count;i++){
		 document.getElementById("top_menu").getElementsByTagName("ul").item(i).style.display='none';
	}
	 document.getElementById("plug-wrap").style.display='none';
}

document.addEventListener('WeixinJSBridgeReady', function onBridgeReady() {
WeixinJSBridge.call('hideToolbar');
});
</script> 




</body></html>