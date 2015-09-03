<?php if (!defined('THINK_PATH')) exit();?><!doctype html public "-//w3c//dtd html 4.0 transitional//en">
<html><head><meta content="ie=5.0000" http-equiv="x-ua-compatible">
 
<meta http-equiv="content-type" content="text/html; charset=utf-8"> 
<title><?php echo ($member['title']); ?></title> 
<meta name="viewport" content="width=device-width,height=device-height,inital-scale=1.0,maximum-scale=1.0,user-scalable=no;"> 
<meta name="apple-mobile-web-app-capable" content="yes"> 
<meta name="apple-mobile-web-app-status-bar-style" content="black"> 
<meta name="format-detection" content="telephone=no"> <link href="__PUBLICI__/Member/css/card.css" 
rel="stylesheet" type="text/css"> 
<style type="text/css">
.window {
width:267px;
position:absolute;
display:none;
margin:0px auto 0 -136px;
padding:2px;
top:0;
left:50%;
border-radius:0.6em;
-webkit-border-radius:0.6em;
-moz-border-radius:0.6em;
background-color: #f1f1f1;
-webkit-box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
-moz-box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
-o-box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
font:14px/1.5 microsoft yahei, helvitica, verdana, arial, san-serif;
z-index:9999;
bottom: auto;
}
.window .wtitle {
background-color: #585858;
line-height: 26px;
padding: 5px 5px 5px 10px;
color:#ffffff;
font-size:16px;
border-radius:0.5em 0.5em 0 0;
-webkit-border-radius:0.5em 0.5em 0 0;
-moz-border-radius:0.5em 0.5em 0 0;
}
.window .content {
/*min-height:100px;*/
overflow:auto;
padding:10px;
background: linear-gradient(#fbfbfb, #eeeeee) repeat scroll 0 0 #fff9df;
color: #222222;
text-shadow: 0 1px 0 #ffffff;
border-radius: 0 0 0.6em 0.6em;
-webkit-border-radius: 0 0 0.6em 0.6em;
-moz-border-radius: 0 0 0.6em 0.6em;
}
.window #txt {
min-height:30px;
font-size:14px;
line-height:20px;
}
.window .content p {
margin:10px 0 0 0;
}
.window .txtbtn {
color: #666666;
font-weight: bold;
text-shadow: 0 1px 0 #ffffff;
display: block;
width: 100%;
text-overflow: ellipsis;
white-space: nowrap;
cursor: pointer;
text-align: windowcenter;
font-weight: bold;
font-size: 16px;
padding:8px;
margin:10px 0 0 0;
background-color:#f4f4f4;
border:1px solid #c6c6c6;
background-image: linear-gradient(bottom, #e1e1e1 0%, #ffffff 100%);
background-image: -o-linear-gradient(bottom, #e1e1e1 0%, #ffffff 100%);
background-image: -moz-linear-gradient(bottom, #e1e1e1 0%, #ffffff 100%);
background-image: -webkit-linear-gradient(bottom, #e1e1e1 0%, #ffffff 100%);
background-image: -ms-linear-gradient(bottom, #e1e1e1 0%, #ffffff 100%);
background-image: -webkit-gradient(  linear,  left bottom,  left top,  color-stop(0, #e1e1e1),  color-stop(1, #ffffff)  );
-webkit-box-shadow: 0 1px 0 #ffffff inset, 0 1px 0 #eeeeee;
-moz-box-shadow: 0 1px 0 #ffffff inset, 0 1px 0 #eeeeee;
box-shadow: 0 1px 0 #ffffff inset, 0 1px 0 #eeeeee;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
-o-border-radius: 5px;
border-radius: 5px;
}
.window .txtbtn:visited {
background-image: linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -o-linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -moz-linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -webkit-linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -ms-linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -webkit-gradient(  linear,  left bottom,  left top,  color-stop(0, #ffffff),  color-stop(1, #e1e1e1)  );
}
.window .txtbtn:active {
background-image: linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -o-linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -moz-linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -webkit-linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -ms-linear-gradient(bottom, #ffffff 0%, #e1e1e1 100%);
background-image: -webkit-gradient(  linear,  left bottom,  left top,  color-stop(0, #ffffff),  color-stop(1, #e1e1e1)  );
}
.window .wtitle .close {
float:right;
background-image: url("data:image/png;base64,ivborw0kggoaaaansuheugaaaboaaaaacayaaacpskzoaaaaaxnsr0iars4c6qaaaarnqu1baacxjwv8yquaaaajcehzcwaadsmaaa7dacdvqgqaaactsurbvehl7dntcoagdazgb60nsgn1tplvcvnhmg76kq8e1mwv+gg27cestq4pvtz69sfocbgpwa8+zht/up+in+mhgllumnie1cpbqb2cozibfpnxhhfaizkyph0soeek/qj8o7koek84fkcwsbtfl+ny2mppckpfmh6pwehwhknciyek69vfiuuvhqjefds+ycwnbewxgqgifwyaaaaasuvork5cyii=");
width:26px;
height:26px;
display:block;
}
#overlay {
position: fixed;
top:0;
left:0;
width:100%;
height:100%;
background:#000000;
opacity:0.5;
filter:alpha(opacity=0);
display:none;
z-index: 999;
}
</style>
 
<script src="__PUBLICI__/Member/js/jquery.min.js" type="text/javascript"></script>
 
<meta name="generator" content="mshtml 10.00.9200.16750"></head> 
<body class="mode_webapp" id="card">
<?php
 $member_card=$_SESSION['member_card']; $image_url=C('image_url'); if (empty($member_card)) { $this->error('请重新访问'); }else{ $murl=$image_url.'/index.php/Index/Member/mid/'.$member_card['mid'].'?openid='.$member_card['openid']; } ?>
<div class="menu_header">
    <div class="menu_topbar">
        <strong class="head-title">会员卡首页</strong>       
        <span class="head_btn_left">
            <a href="javascript:history.go(-1);"><span>返回</span></a>
            <b></b></span>
            <a class="head_btn_right" href="<?php echo ($murl); ?>"><span><i 
class="menu_header_home"></i></span><b></b>       </a>
   </div>
</div>
<div id="overlay"></div>
<div class="cardcenter">
<div class="card"><img class="cardbg" src="__PUBLICI__/Member/images/card_bg19.png"><img 
class="logo" id="cardlogo" src="<?php echo ($image_url); echo ($member['logo']); ?>">
<h1 style="color: rgb(255, 255, 255);"><?php echo ($member['title']); ?></h1><strong class="pdo verify" 
style="color: rgb(255, 255, 255);"><span 
id="cdnb"><em>普通会员卡</em><?php echo ($member_data['card_id']); ?></span></strong> </div>
<p class="explain"><span>使用时向服务员出示此卡</span></p>
<!-- <div class="window" id="windowcenter">
<div class="wtitle" id="title">领卡信息<span class="close" 
id="alertclose"></span></div>
<div class="content">
<div id="txt"></div>
<p><input name="truename" class="px" id="truename" type="text" placeholder="请输入您的姓名" 
value=""></p>
<p><input name="tel" class="px" id="tel" type="number" placeholder="请输入您的电话" 
value=""></p><input name="确 定" class="txtbtn" id="windowclosebutton" type="button" value="确 定"></div>
</div> --></div>
<div class="cardexplain"><!--会员积分信息-->     
<div class="jifen-box">
<ul class="zongjifen">
  <li>
  <div class="fengexian">
  <p>会员总积分</p><span><?php echo ($integral['total_integral']); ?></span></div></li>
  <li><a href="#">
  <div class="fengexian">
  <p>签到积分</p><span><?php echo ($integral['in_integral']); ?></span></div></a></li>
  <li><a href="#">
  <p>消费积分</p><span><?php echo ($integral['xiaofei_integral']); ?></span></a></li></ul>
<div class="clr"></div></div>
<ul class="round" id="notice">
  <li><a href="<?php echo U(GROUP_NAME . '/Member/news_member');?>"><span>最新通知<em 
  class="ok">1</em></span></a></li>
  <li><a href="<?php echo U(GROUP_NAME . '/Member/coupon_member');?>"><span>会员优惠券<em 
  class="ok">1</em></span></a></li>
  <li><a href="<?php echo U(GROUP_NAME . '/Member/mall_member');?>"><span>积分换礼品<em
  class="ok">1</em></span></a></li></ul>
<ul class="round" id="powerandgift">
  <li><a href="<?php echo U(GROUP_NAME . '/Member/sign_member');?>"><span>签到赚积分<em
  class="ok">今日已签到</em></span></a></li>
  <li><a href="<?php echo U(GROUP_NAME . '/Member/perfect_member_data');?>"><span>个人资料</span></a></li></ul>
<ul class="round">
  <li><a href="<?php echo U(GROUP_NAME . '/Member/info_member');?>"><span>会员卡说明</span></a></li>
  <?php if($store_c != 0): ?><li><a href="<?php echo ($store_url); ?>"><span>适用门店电话及地址</span></a></li><?php endif; ?>
</ul>
<ul class="round">
  <li class="addr">
      <a href="<?php echo ($url); ?>"><span>地址:<?php echo ($member['address']); ?></span></a></li>
  <li class="tel"><a href="tel:<?php echo ($member['tel']); ?>"><span>
      电话: <?php echo ($member['tel']); ?></span></a></li></ul></div>
<script type="text/javascript"> 
 
$(document).ready(function () { 

$("#windowclosebutton").bind("click",
function() {
var btn = $(this);
var tel = $("#tel").val();
  
var truename = $("#truename").val();

var regu =/^[0-9]{7,11}$/;
var re = new regexp(regu);
if(!re.test(tel)){
 $("#tel").val("请输入正确的手机号码!");

return  ;
}
 
if (truename.length<2||truename=='请输入名字') {
 $("#truename").val("请输入名字");
return;
}
var submitdata = {
tid: 486,
truename:truename,
formhash: "292fcebb",
tel: tel,
action: "settel"
};
$.post('index.php?ac=cardindex&tid=486&c=onp0rt6f4pgciuugfs9_kfyxhjps', submitdata,
function(data) {
if (data.success == true) {
window.location.href=location.href;
//alert(data.msg);
/*	$("#windowcenter").slideup(500);
$("#overlay").css("display","none");
$("#cdnb").html(data.msg);
 $("#notice").css("display","");
 $("#powerandgift").css("display","");
settimeout($(".msk").fadeout(1000),1000);

return*/
} else {}
},
"json");



});

$("#showcard").click(function () { 
alert("填写真实的姓名以及电话号码，即可获得会员卡，享受会员特权。"); 
$("#overlay").css("display","block");
}); 
}); 

 
$("#alertclose").click(function () { 
$("#windowcenter").slideup(500);
$("#overlay").css("display","none");

}); 




function alert(title){ 
$("#windowcenter").slidetoggle("slow"); 
$("#txt").html(title);
} 

</script>
  
<?php
 $member_card=$_SESSION['member_card']; if(empty($member_card)){ $this->error('请重新访问'); }else{ $murl=$image_url.'/index.php/Index/Member/mid/'.$member_card['mid'].'?openid='.$member_card['openid']; } ?>
<div class="footermenu">
    <ul>
      <li>
            <a class="active" href="<?php echo ($murl); ?>">
            <img src="__PUBLICI__/Member/images/m_1.png">
            <p>会员卡</p>
            </a>
        </li>
        <li>
            <a href="<?php echo U(GROUP_NAME . '/Index/Member/info_member');?>">
            <img src="__PUBLICI__/Member/images/m_2.png">
            <p>特权</p>
            </a>
        </li>
        <li>
            <a href="">
            <img src="__PUBLICI__/Member/images/m_3.png">
            <p>优惠券</p>
            </a>
        </li>
        <li>
            <a href="<?php echo U(GROUP_NAME .'/Index/Member/mall_member');?>">
            <img src="__PUBLICI__/Member/images/m_4.png">
            <p>兑换</p>
            </a>
        </li>
        <li>
            <a href="<?php echo U(GROUP_NAME .'/Index/Member/sign_member');?>">
            <img src="__PUBLICI__/Member/images/m_5.png">
            <p>签到</p>
            </a>
        </li>
    </ul>
</div>
<script type="text/javascript">
 	        document.addeventlistener('weixinjsbridgeready', function onbridgeready() {
        window.sharedata = {  
           "imgurl": "http://www.139931.com/index/images/card/cardbg/card_bg19.png", 
            "timelinelink": "http://www2.wxokok.com?ac=cardindex&tid=486",
            "sendfriendlink": "http://www2.wxokok.com?ac=cardindex&tid=486",
            "weibolink": "http://www2.wxokok.com?ac=cardindex&tid=486",
            "ttitle": "纳索微营销会员卡",
            "tcontent": "纳索微营销会员卡",
            "ftitle": "纳索微营销会员卡",
            "fcontent": "纳索微营销会员卡",
            "wcontent": "纳索微营销会员卡" 
        };
        // 发送给好友
        weixinjsbridge.on('menu:share:appmessage', function (argv) {
            weixinjsbridge.invoke('sendappmessage', { 
                "img_url": window.sharedata.imgurl,
                "img_width": "640",
                "img_height": "640",
                "link": window.sharedata.sendfriendlink,
                "desc": window.sharedata.fcontent,
                "title": window.sharedata.ftitle
            }, function (res) {
                _report('send_msg', res.err_msg);
            })
        });

        // 分享到朋友圈
        weixinjsbridge.on('menu:share:timeline', function (argv) {
            weixinjsbridge.invoke('sharetimeline', {
                "img_url": window.sharedata.imgurl,
                "img_width": "640",
                "img_height": "640",
                "link": window.sharedata.timelinelink,
                "desc": window.sharedata.tcontent,
                "title": window.sharedata.ttitle
            }, function (res) {
                _report('timeline', res.err_msg);
            });
        });

        // 分享到微博
        weixinjsbridge.on('menu:share:weibo', function (argv) {
            weixinjsbridge.invoke('shareweibo', {
                "content": window.sharedata.wcontent,
                "url": window.sharedata.weibolink,
            }, function (res) {
                _report('weibo', res.err_msg);
            });
        });
        }, false)
    </script>
 </body></html>