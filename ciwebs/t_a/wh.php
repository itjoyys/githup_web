<?php
    $ps = $_GET['ps'];
    $ps = str_replace(' ','+',$ps);
    $ps = json_decode(base64_decode($ps));
?>





<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="zh-cn">

<head>
    <META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no" />
    <title><?php echo $ps->copy_right;?> 维护中心</title>
<style>
body {
    font-size: 12px;
    background: #000000 url(/wh/images/whbg.jpg); margin: 0px; padding: 0px; font-family: Microsoft YaHei, "微软雅黑", sans-serif;
}

.main {
margin: 50px auto;
width: 750px;
}

.mt3 {
margin-top: 3px;
}

.clear {
clear: both;
}

.none {
display: none;
}

.show {
display: block;
}

.xs {
cursor: pointer;
}

.wbf {
width: 30px;
}

.mt3 {
margin-top: 3px;
}

.w5 {
width: 120px;
}

.info {
float: left;
width: 150px;
height: 100px;
}

.webinfo {
background: #000;
height: 200px;
}

.webinfo_content {
height: 200px;
position: relative;
top: -200px;
color: #fff
}

.info_main {
width: 150px;
height: 100px;
background: #000;
position: relative;
left: 0;
top: 0px;
z-index: 1px;
}

.info_main_ {
 
border: #ffffff solid 0px;
position: relative;
left: 0;
top: -100px;
z-index: 2px;
width: 150px;
height: 100px;
color: #fff;
text-align: center;
font-size: 14px;
line-height: 30px;
}

.info_main_ marquee {
padding: 0px 10px;
width: 100px;
}


.siteinfo {
font-size: 14px;
background: #000;
line-height: 35px;
height: 35px;
color: #fff;
border-bottom: #B00505 solid 1px;
-moz-border-radius: 10px 10px 0px 0px;
      /* Gecko browsers */
-webkit-border-radius: 10px 10px 0px 0px;
   /* Webkit browsers */
border-radius: 10px 10px 0px 0px;
            /* W3C syntax */
}

.siteinfo div {
line-height: 35px;
height: 35px;
float: left
}
.sitename {
background: #B00505;
padding: 0px 20px;
width: 260px;
overflow: hidden;
text-align: center;
-moz-border-radius: 10px 0px 0px 0px;
      /* Gecko browsers */
-webkit-border-radius: 10px 0px 0px 0px;
   /* Webkit browsers */
border-radius: 10px 0px 0px 0px;
            /* W3C syntax */
}

.whcenter {
padding-left: 20px;
}

a:link,a:visited {
color: #fff;
text-decoration: none
}

.ico {
font-size: 30px;
float: left;
margin: 30px 0px 0px 30px;
}

.notice {
padding: 0px 20px;
line-height: 30px;
font-size: 15px;
margin-top: 20px;
color: #FFAA00
}

.service {
float: right;
padding-right: 30px;
margin-top: 20px;
}

.domain {
margin-left: 300px;
}

.startname {
width: 80px;
margin: 0px auto;
background: #000;
-moz-border-radius: 15px 0px 15px 0px;
      /* Gecko browsers */
-webkit-border-radius: 15px 0px 15px 0px;
   /* Webkit browsers */
border-radius: 15px 0px 15px 0px;
            /* W3C syntax */
}
.wh {
background: #000
}

.whz {
background: #C10B0B; 
}

.close {
background: #373737;color: #999
}
/*宽度*/
@media (max-width:374px){
}
@media (min-width: 375px){
}
@media (min-width: 414px){
}
/*高度*/
@media screen and (max-height:567px){
    
}
@media screen and (min-height:568px){
}
@media screen and (min-height:627px){
}
@media screen and (min-height:736px){
}
@media screen and (max-height:736px) and (max-width: 460px){
    .main{width:90%;margin-left:5%;}
    .sitename{max-width:40%;width:auto;padding:0 15px;box-sizing:border-box;}
    .siteinfo .domain{margin-left: 0;float: right;margin-right: 15px;}
    .ico{font-size: 24px;}
    .service{position: absolute;bottom:25px;float:none;right:0px;}
}
    </style>
</head>
<body>
<div class="main">
    <div class="siteinfo">
    <div class="sitename"><?php echo $ps->copy_right;?></div> 
    <div class="whcenter">维护中心</div>
    <div class="domain"> <a href="/" >网站首页</a></div>
    <div style="clear:both;"></div>
    </div>
        <div class="webinfo">
        
    </div>
    <div class="webinfo_content">
        <div class="ico">(^_^)/ 我们的系统正在维护中，请稍后再访问！</div>
        <div class="clear"></div>
        <div class="notice"></div>
         <div class="service"> <?php echo $_SERVER['HTTP_HOST']?> | <a href="<?php echo $ps->online_service;?>" target=_blank>在线客服</a>  | <a href="/" >网站首页</a></div>
    </div>
    </div>
</body>

</html>